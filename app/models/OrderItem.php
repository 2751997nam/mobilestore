<?php

namespace App\Models;

use App\Models\Multitenantable;
use Megaads\Apify\Models\BaseModel;

class OrderItem extends BaseModel
{
    use Multitenantable;

    protected $table = 'order_item';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_sku_id',
        'quantity',
        'price',
        'shop_uuid',
    ];

    public function product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id')->withTrashed();
    }

    public function productSku()
    {
        return $this->hasOne('App\Models\ProductSku', 'id', 'product_sku_id');
    }
}
