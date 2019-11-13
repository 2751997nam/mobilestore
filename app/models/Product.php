<?php

namespace App\Models;

use App\Models\Order;
use App\Models\TagRefer;
use App\Models\ProductSku;
use App\Models\ProductSkuValue;
use Illuminate\Support\Facades\DB;
use Megaads\Apify\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends BaseModel
{

    use \App\Models\Multitenantable;

    use SoftDeletes;

    protected $table = 'product';

    protected $fillable = [
        'sku',
        'name',
        'slug',
        'image_url',
        'price',
        'high_price',
        'add_shipping_fee',
        'status',
        'description',
        'content',
        'note',
        'inventory',
        'brand_id',
        'created_at',
        'updated_at'
    ];
    protected $variantDefault = null;

    protected $appends = [
        'url', 'editUrl', 'variant_default', 'is_new', 'display_price', 'display_high_price',
        'sale_percent', 'display_drop_price', 'brand', 'attributes'
    ];

    const DAY_OF_NEW = 10;

    public function galleries()
    {
        return $this->hasMany(ProductGallery::class, 'product_id', 'id');
    }

    public function meta()
    {
        return $this->hasMany(ProductMeta::class, 'product_id', 'id');
    }

    public function productSkus()
    {
        return $this->hasMany(ProductSku::class, 'product_id', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_n_category')->withPivot(['sorder'])->withTimestamps();
    }

    public function tags() {
        return $this->belongsToMany('\App\Models\Tag', 'tag_refer' ,'refer_id', 'tag_id')->where('refer_type', TagRefer::REFER_PRODUCT);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function getUrlAttribute () {
        return "/" . (!empty($this->slug) ? $this->slug : "san-pham") . "-p" . $this->id . ".html";
    }

    public function getEditUrlAttribute () {
        return "/admin/products/" . $this->id;
    }

    public function getIsNewAttribute(){
        $createdTime = date_create($this->created_at);
        $interval = date_diff($createdTime, date_create(date("Y-m-d H:i:s")));
        return ($interval->format("%a") < self::DAY_OF_NEW);
    }

    public function getImageUrlAttribute($value){
        return ($value) ? $value : 'https://s3.shopbay.vn/files/2/system/product-image-placeholder.png';
    }

    public function getVariantDefaultAttribute() {
        return $this->getDefautVariant();
    }

    public function getDisplayPriceAttribute() {
        if ($this->price > 0) {
            return number_format($this->price, 0, ',', '.').' ₫';
        } else {
            return "Liên hệ";
        }
    }

    public function getDisplayHighPriceAttribute() {
        return number_format($this->high_price, 0, ',', '.').' ₫';
    }

    private function getDefautVariant() {
        $variants = ProductSku::where('product_id', '=', $this->id)
                                ->orderBy('id', 'ASC')
                                ->get(['id', 'sku', 'price', 'high_price', 'image_url']);
        $retVal = [];
        if (count($variants) > 0) {
            $retVal = $variants[0];
        }
        return $retVal;
    }

    public function getBrandAttribute(){
        return Brand::where('id', '=', $this->brand_id)->first();
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_item')->withPivot(['quantity', 'price'])->withTimestamps();
    }

    public function getSalePercentAttribute () {
        if ($this->high_price && $this->price && $this->high_price > 0) {
            $salePercent = round(($this->high_price - $this->price) / $this->high_price * 100);
            if ($salePercent > 0) {
                return "-$salePercent%";
            }
        }

        return null;
    }

    public function getDisplayDropPriceAttribute () {
        return number_format($this->high_price - $this->price, 0, ',', '.').' ₫';
    }

    protected static function boot()
    {
        parent::boot();

        if (isset($_GET['q']) && $_GET['q']) {
            static::addGlobalScope('search', function (Builder $builder) {
                $keyword = $_GET['q'];
                $keyword = str_slug($keyword);
                $keywordSlug = $keyword;
                $keyword = str_replace('-', ' ', $keyword);
                $keyword = trim($keyword);
                if ($keyword) {
                    $builder->whereRaw("(match (`slug`) against ('$keyword') or slug like '%$keywordSlug%' or sku like '%$keyword%')");
                    $tableName = $builder->getModel()->getTable();
                    // $builder->select($tableName . ".*");
                    $builder->addSelect(DB::raw("match (`slug`) against ('$keyword') as lien_quan"));
                    $builder->orderBy("lien_quan", "desc");
                }
            });
        } else {
            static::addGlobalScope('sort', function (Builder $builder) {
                $builder->orderBy('product.id', 'desc');
            });
        }
    }

    public function filterValues()
    {
        return $this->belongsToMany(FilterValue::class, 'filter_value_n_product');
    }

    public function getAttributesAttribute () {
        $retval = [];
        $attributes = DB::table('product_meta')->where('product_id', $this->id)->get();
        foreach ($attributes as $key => $value) {
            $retval[$value->key] = $value->value;
            if($value->value){
                if ($value->value[0] == '[' || $value->value[0] == '{') {
                    $valueJson =  json_decode($value->value);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $retval[$value->key] = $valueJson;
                    }
                }
            }
        }
        return $retval;
    }
}
