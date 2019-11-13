<?php

namespace App\Models;

use Megaads\Apify\Models\BaseModel;

class Brand extends BaseModel
{

    use \App\Models\Multitenantable;

    protected $table = 'brand';

    protected $fillable = [
        'name',
        'slug',
        'image_url',
        'description',
        'sorder',
        'shop_uuid'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
