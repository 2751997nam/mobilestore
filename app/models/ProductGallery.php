<?php

namespace App\Models;

use Megaads\Apify\Models\BaseModel;

class ProductGallery extends BaseModel
{

    use \App\Models\Multitenantable;

    protected $table = 'product_gallery';

    protected $fillable = [
        'product_id',
        'image_url',
        'description',
    ];
}
