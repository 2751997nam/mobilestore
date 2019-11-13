<?php

namespace App\Models;

use Megaads\Apify\Models\BaseModel;

class ProductVariant extends BaseModel
{
    use \App\Models\Multitenantable;

    protected $table = 'product_variant';

    protected $fillable = [
        'slug',
        'name',
        'shop_uuid',
    ];

    public function variantOptions()
    {
        return $this->hasMany(ProductVariantOption::class, 'variant_id', 'id');
    }
}
