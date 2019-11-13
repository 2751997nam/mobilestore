<?php
/**
 * Created by PhpStorm.
 * User: DiemND
 * Date: 08/26/2019
 * Time: 10:40
 */

namespace App\Models;


use Megaads\Apify\Models\BaseModel;

class ShippingFeeItem extends BaseModel
{
    use \App\Models\Multitenantable;
    protected $table = "shipping_fee_item";
    protected $fillable = [
        'shipping_fee_id',
        'min_amount',
        'max_amount',
        'fee',
        'shop_uuid'
    ];


}