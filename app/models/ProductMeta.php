<?php

namespace App\Models;

use Megaads\Apify\Models\BaseModel;

class ProductMeta extends BaseModel
{
    use \App\Models\Multitenantable;

    protected $table = 'product_meta';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'key',
        'value',
        'shop_uuid'
    ];

}
