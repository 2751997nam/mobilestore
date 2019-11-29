<?php

namespace App\Models;

use App\Models\Order;
use App\Models\TagRefer;
use App\Models\ProductSku;
use App\Models\ProductSkuValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
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
        'display_price', 'display_high_price', 'editUrl', 'url'
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


    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_n_category')->withPivot(['sorder'])->withTimestamps();
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function getUrlAttribute () {
        return "/" . (!empty($this->slug) ? $this->slug : "san-pham") . "-p-" . $this->id;
    }

    public function getEditUrlAttribute() {
        return  '/' . 'admin/products/' . $this->id;
    }

    public function getDisplayPriceAttribute () {
        return formatPrice($this->price);
    }

    public function getDisplayHighPriceAttribute () {
        return formatPrice($this->high_price);
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
}
