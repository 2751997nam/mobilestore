<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductGallery;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller 
{
    const VALIDATION_RULES = [
        'name' => 'bail|required|max:255',
        'description' => 'max:2000',
        'sku' => 'max:255',
        'inventory' => 'numeric',
        'images.*' => 'string',
        'brand_id' => 'numeric',
        'categories.*' => 'numeric',
        'price' => 'bail|required|numeric',
        'high_price' => 'numeric'
    ];

    const NICE_NAMES = [
        'name' => 'tên sản phẩm',
        'description' => 'mô tả',
        'sku' => 'mã sản phẩm',
        'inventory' => 'tồn kho',
        'images.*' => 'ảnh',
        'brand_id' => 'thương hiệu',
        'price' => 'giá',
        'high_price' => 'giá thị trường'
    ]; 

    const VALIDATION_IMAGE_MESSAGES = [
        'required' => 'Vui lòng chọn ảnh để tải lên',
        'mimes' => 'Chỉ chấp nhận file có định dạng jpeg, bmp, png, webp, jpg',
        'max' => 'Kích thước ảnh tối đa là 5MB',
    ];

    public function index(Request $request)
    {
        $query = Product::with(['categories', 'brand'])->orderBy('created_at', 'desc');
        $meta = $this->getMetaData($query, $request);
        $products = $this->paginate($query, $meta['page_size'], $meta['page_id']);

        return $this->responseWithMeta($products, $meta);
    }

    public function uploadImage(Request $request)
    {
        $retval = [
            'status' => 'successful',
            'result' => []
        ];
        $validator = Validator::make($request->all(), [
            'images.*' => 'bail|required|file|mimes:jpeg,bmp,png,webp,jpg|max:5120'
        ], self::VALIDATION_IMAGE_MESSAGES);

        if ($validator->fails()) {
            $retval = $this->responseFail($validator->errors()->first());

            return $retval;
        }

        $result = [];
        if ($request->hasFile('images')) {
            $images = $request->file('images');
        }

        foreach ($images as $image) {
            $filename = $image->store('images');
            $result[] = Storage::url($filename);
        }

        $retval['result'] = $result;

        return $retval;
    }

    public function store(Request $request)
    {
        $retval = $this->responseSuccess('Thêm sản phẩm thành công!');
        $validator = Validator::make($request->all(), self::VALIDATION_RULES, [], self::NICE_NAMES);

        if ($validator->fails()) {
            $retval = $this->responseFail($validator->errors()->first());
        } else {
            $data = $request->all();
            if (!empty($data['images'])) {
                $data['image_url'] = $data['images'][0];
            }

            $product = Product::create($data);

            if (!empty($product)) {
                if (!empty($data['categories'])) {
                    $product->categories()->sync($data['categories']);
                }

                if (!empty($data['images'])) {
                    array_shift($data['images']); 
                    $saveImages = [];
                    foreach ($data['images'] as $value) {
                        $saveImages[] = [
                            'product_id' => $product->id,
                            'image_url' => $value
                        ];
                    }

                    ProductGallery::insert($saveImages);
                }
            }
        }

        return $retval;
    }

    public function toSqlWithBinding($query)
    {
        $sql = $query->toSql();
        foreach ($query->getBindings() as $binding) {
            $value = is_numeric($binding) ? $binding : "'".$binding."'";
            $sql = preg_replace('/\?/', $value, $sql, 1);
        }

        return $sql;
    }

    public function getRelationWithCount($data, $productIds, $table, $pivotTable, $column = 'product_id')
    {
        $subQuery = DB::table($pivotTable)
            ->select($column, $table . '_id')
            ->whereIn($column, $productIds);
        $subQuery = $this->toSqlWithBinding($subQuery);

        $query = DB::table($table)->select($table . '.*', DB::raw('count(sb_relation' . '.' . $column .') as count'))
            ->join(DB::raw('(' . $subQuery . ') as sb_relation'), function ($query) use ($table) {
                $query->on($table . '.id', 'sb_relation.' . $table . '_id');
            })
            ->groupBy($table . '.id');
        if ($table == 'category') {
            $query->where('is_hidden', '0');
        }

        // return $this->toSqlWithBinding($result);

        $result = $query->get()->keyBy('id')->toArray();
        if (count($result) == 0 && isset($data[$table])) {
            $query = DB::table($table)->select($table . '.*', DB::raw('0 as count'))
                ->whereIn('id', $data[$table]);
            $result = $query->get();
        }

        return $result;
    }

    public function getBrandWithCount($data, $productIds)
    {
        $subQuery = Product::select('id', 'brand_id')
            ->whereIn('id', $productIds);
        $subQuery = $this->toSqlWithBinding($subQuery);

        $query = Brand::select('brand.*', DB::raw('count(sb_relation.id) as count'))
            ->join(DB::raw('(' . $subQuery . ') as sb_relation'), function ($query) {
                $query->on('brand.id', 'sb_relation.brand_id');
            })
            ->groupBy('brand.id');

        $result = $query->get()->keyBy('id')->toArray();
        if (count($result) == 0 && isset($data['brand'])) {
            $query = Brand::select('*', DB::raw('0 as count'))->where('slug', $data['brand']);
            $result = $query->get();
        }

        return $result;
    }

    public function getChildCategories($parentId)
    {
        return Category::where('parent_id', $parentId)->where('is_hidden', '0')->get();
    }

    public function search(Request $request)
    {
        $data = $request->all();
        $query = Product::select('product.*')->where('status', 'ACTIVE');
        $this->addFilterToQuery($data, $query);
        if (!empty($data['order'])) {
            $this->addSortToQuery($data['order'], $query);
        }
        $products = $query->get();
        $productIds = $products->pluck('id')
            ->toArray();
        $priceRange = $this->getPriceRange($data);
        if (isset($data['category_id'])) {
            $filterCategory = Category::find($data['category_id']);
            $categories = $this->getChildCategories($data['category_id']);
        } else {
            $categories = $this->getRelationWithCount($data, $productIds, 'category', 'product_n_category');
        }
        $brands = $this->getBrandWithCount($data, $productIds);
        $meta = $this->getMetaData($query, $request);
        $result = $this->paginate($query, $meta['page_size'], $meta['page_id']);

        // $sql = $this->toSqlWithBinding($query);
        // dd($sql);
        // dd($meta);

        unset($data['q']);
        $displayedFilters = $this->getDisplayedFilters($data, [
            'brand' => $brands,
            'category' => $categories,
        ]);

        $retVal = [
            'result' => $result,
            'categories' => $categories,
            'displayedFilters' => $displayedFilters,
            'brands' => $brands,
            'priceRange' => $priceRange,
            'meta' => $meta,
            'filterCategory' => []
        ];

        if (isset($filterCategory)) {
            $retVal = array_merge($retVal, ['filterCategory' => $filterCategory]);
        }

        return $retVal;
    }

    public function getDisplayedFilters($filters, array $objects = [])
    {
        $result = [];
        if (isset($filters['minPrice']) || isset($filters['maxPrice'])) {
            $result['price'] = '';
        }
        if (isset($filters['minPrice']) && isset($filters['maxPrice'])) {
            $result['price'] = 'Từ ' . formatPrice($filters['minPrice']) . ' ';
            $result['price'] .= 'đến ' . formatPrice($filters['maxPrice']);
            unset($filters['minPrice']);
            unset($filters['maxPrice']);
        } elseif (isset($filters['minPrice']) && !isset($filters['maxPrice'])) {
            $result['price'] = 'Trên ' . formatPrice($filters['minPrice']) . ' ';
            unset($filters['minPrice']);
        } elseif (!isset($filters['minPrice']) && isset($filters['maxPrice'])) {
            $result['price'] .= 'Dưới ' . formatPrice($filters['maxPrice']);
            unset($filters['maxPrice']);
        }
        foreach ($filters as $key => $value) {
            if (is_array($value)) {
                $result[$key] = [];
                if (isset($objects[$key])) {
                    foreach ($value as $val) {
                        $table = $key;
                        if ($key == 'filter') {
                            $table = 'filter_value';
                        }
                        $obj = DB::table($table)
                            ->where('id', $val)->first();
                        if (!empty($obj)) {
                            $display_name = isset($obj->name) ? $obj->name : (isset($obj->title) ? $obj->title : '');
                            array_push($result[$key], $display_name);
                        }
                    }
                }
            } else {
                if (isset($objects[$key])) {
                    foreach ($objects[$key] as $obj) {
                        if ($obj['slug'] == $value) {
                            $result[$key] = $obj['name'];
                            break;
                        }
                    }
                }
            }
        }

        return $result;
    }

    public function addFilterToQuery($filters, &$query, $isIncludePrice = true)
    {
        $query->select('product.*');
        if (!empty($filters['tag'])) {
            foreach ($filters['tag'] as $key => $value) {
                $query->join('tag_refer as tag_pivot' . $key, function ($query) use ($key, $value) {
                    $query->on('tag_pivot'. $key . '.refer_id', 'product.id')
                        ->where('tag_pivot'. $key . '.tag_id', $value);
                });
            }
        }
        if (!empty($filters['brand'])) {
            $brand = Brand::where('slug', $filters['brand'])->first();
            if (!empty($brand)) {
                $query->where('brand_id', $brand->id);
            } else {
                $query->where('brand_id', 0);
            }
        }
        if ($isIncludePrice) {
            if (!empty($filters['maxPrice']) && !empty($filters['minPrice'])) {
                $query->whereBetween('price', [$filters['minPrice'], $filters['maxPrice']]);
            } elseif (!empty($filters['minPrice'])) {
                $query->where('price', '>=', $filters['minPrice']);
            } elseif (!empty($filters['maxPrice'])) {
                $query->where('price', '<=', $filters['maxPrice']);
            }
        }
        if (!empty($filters['category'])) {
            foreach ($filters['category'] as $key => $value) {
                $query->join('product_n_category as cate_pivot' . $key, function ($query) use ($key, $value) {
                    $query->on('cate_pivot'. $key . '.product_id', 'product.id')
                        ->where('cate_pivot'. $key . '.category_id', $value);
                });
            }
        }
        if (!empty($filters['category_id'])) {
            $children = Category::where('parent_id', $filters['category_id'])->get()->pluck('id')->toArray();
            $query->whereHas('categories', function ($query) use ($filters, $children) {
                $query->where('category_id', $filters['category_id'])
                    ->orWhereIn('category_id', $children);
            });
        }
        if (!empty($filters['filter'])) {
            foreach ($filters['filter'] as $key => $value) {
                $query->join('filter_value_n_product as filter_pivot' . $key, function ($query) use ($key, $value) {
                    $query->on('filter_pivot'. $key . '.product_id', 'product.id')
                        ->where('filter_pivot'. $key . '.filter_value_id', $value);
                });
            }
        }
    }

    public function addSortToQuery($order, &$query)
    {
        switch ($order) {
            case 'lastest':
                $query->orderBy('created_at', 'DESC');
                break;
            case 'sold':
                $query->orderBy('sold', 'DESC');
                break;
            case 'view':
                $query->orderBy('view_count', 'DESC');
                break;
            case 'low_price':
                $query->orderBy('price', 'ASC');
                break;
            case 'high_price':
                $query->orderBy('price', 'DESC');
                break;
            case 'sale':
                $query->addSelect(DB::raw('price/high_price as percent'));
                $query->where('price', '>', 0);
                $query->where('high_price', '>', 0);
                $query->orderBy('percent', 'ASC');
                break;
            default:
                break;
        }
    }

    public function getPriceRange($data)
    {
        $query = Product::select('product.*')->where('status', 'ACTIVE');
        $filters = [];
        if (!empty($data['category_id'])) {
            $filters['category_id'] = $data['category_id'];
        }
        $this->addFilterToQuery($filters, $query);
        $products = $query->get();
        $productPriceAboveZeros = $products->filter(function ($value, $key) {
            return $value->price > 0;
        });
        $maxPrice = $productPriceAboveZeros->max('price');
        $minPrice = $productPriceAboveZeros->min('price');
        $priceRange = [];
        $unit = 5;
        $range = (int)(($maxPrice - $minPrice) / $unit);
        $length = strlen((int) $range) - 1;
        $range = round($range, -$length, PHP_ROUND_HALF_DOWN);
        if ($maxPrice > 0 && $range > 0 && $maxPrice != $minPrice) {
            $priceRange = [
                [
                    'from' => 0,
                    'to' => 0,
                ]
            ];

            $max = 0;

            while ($max < $maxPrice) {
                if ($max <= $minPrice) {
                    $max += $range;
                    $priceRange[0]['to'] = $max;
                } else {
                    $priceRange[] = [
                        'from' => $max,
                        'to' => $max + $range
                    ];
                    $max += $range;
                }
            }
        }

        foreach ($priceRange as $key => $value) {
            if ($value['to'] == 0) {
                unset($priceRange[$key]);
            }
        }

        return $priceRange;
    }

    public function increaseView($id)
    {
        return Product::where('id', $id)->increment('view_count', 1);
    }

    public function deleteProduct($id)
    {
        $retval = ['status' => 'success', 'message' => 'Successfull!'];
        $code = 200;
        try {
            Product::destroy($id);
        } catch (\Exception $ex) {
            $code = 500;
            $retval = ['status' => 'failed', 'message' => 'Không thể xoá sản phẩm đã có đơn hàng'];
        }

        return response()->json($retval, $code);
    }
}
