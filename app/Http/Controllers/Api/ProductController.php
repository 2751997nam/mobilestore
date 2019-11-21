<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductGallery;
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
}
