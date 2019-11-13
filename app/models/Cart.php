<?php
/**
 * Created by PhpStorm.
 * User: DiemND
 * Date: 08/26/2019
 * Time: 10:40
 */

namespace App\Models;


use Megaads\Apify\Models\BaseModel;

class Cart extends BaseModel
{
    use \App\Models\Multitenantable;
    protected $table = "cart";
    protected $fillable = [
        'token',
        'status',
        'shop_uuid'
    ];
}