<?php
/**
 * Created by PhpStorm.
 * User: KimTung
 * Date: 8/2/2019
 * Time: 2:37 PM
 */

namespace App\Models;


class TagRefer extends \Megaads\Apify\Models\BaseModel {
    use \App\Models\Multitenantable;

    protected $table = "tag_refer";
    const REFER_PRODUCT = 'PRODUCT';
    const REFER_POST = 'POST';
}
