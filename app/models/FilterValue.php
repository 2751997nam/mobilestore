<?php

namespace App\Models;

use Megaads\Apify\Models\BaseModel;

class FilterValue extends BaseModel
{
    use \App\Models\Multitenantable;

    protected $table = 'filter_value';

    protected $fillable = [
        'filter_id',
        'name',
        'slug',
        'shop_uuid'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'filter_value_n_product');
    }

    public function countProduct()
    {
        return $this->products()->selectRaw('count(sb_product.id) as aggregate')
        ->groupBy('pivot_product_id');
    }
}
