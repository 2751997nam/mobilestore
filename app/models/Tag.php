<?php
/**
 * Created by PhpStorm.
 * User: KimTung
 * Date: 8/2/2019
 * Time: 2:37 PM
 */

namespace App\Models;


class Tag extends \Megaads\Apify\Models\BaseModel {
    use \App\Models\Multitenantable;

    protected $table = "tag";

    public function tagRefers()
    {
        return $this->hasMany(TagRefer::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'tag_refer', 'tag_id', 'refer_id')->where('refer_type', TagRefer::REFER_PRODUCT);
    }
}
