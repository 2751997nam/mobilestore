<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{

    protected $table = 'order_item';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'image_url',
        'product_name'
    ];

    protected $appends = [
        'name', 'display_image_url'
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function getNameAttribute()
    {
        return $this->product_name;
    }

    public function getDisplayImageUrlAttribute() {
        return file_exists(public_path() . $this->image_url) ? $this->image_url : '/images/default.jpg';
    }
}
