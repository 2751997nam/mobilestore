<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{
    protected $table = 'product_gallery';

    protected $fillable = [
        'product_id',
        'image_url',
        'description',
    ];

    protected $appends = [
        'display_image_url'
    ];

    public function getDisplayImageUrlAttribute() {
        return file_exists(public_path() . $this->image_url) ? $this->image_url : '/images/default.jpg';
    }
}
