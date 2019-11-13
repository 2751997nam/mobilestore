<?php

namespace App\Models;

use Megaads\Apify\Models\BaseModel;

class ProductVariantOption extends BaseModel
{
    use \App\Models\Multitenantable;

    protected $table = 'product_variant_option';

    protected $fillable = [
        'variant_id',
        'name',
        'slug',
        'shop_uuid'
    ];
}
