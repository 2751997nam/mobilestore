<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{

    use \App\Models\Multitenantable;

    protected $table = 'product_gallery';

    protected $fillable = [
        'product_id',
        'image_url',
        'description',
    ];
}
