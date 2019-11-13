<?php

namespace App\Models;

use Megaads\Apify\Models\BaseModel;

class ProductSkuValue extends BaseModel
{
    use \App\Models\Multitenantable;

    protected $table = 'product_sku_value';

    protected $fillable = [
        'sku_id',
        'variant_id',
        'variant_option_id',
        'product_id',
        'shop_uuid'
    ];
}
