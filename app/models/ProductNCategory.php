<?php
/**
 * Created by PhpStorm.
 * User: KimTung
 * Date: 8/2/2019
 * Time: 2:37 PM
 */

namespace App\Models;


class ProductNCategory extends \Megaads\Apify\Models\BaseModel {
    use \App\Models\Multitenantable;

    protected $table = "product_n_category";
    public $timestamps = true;

}
