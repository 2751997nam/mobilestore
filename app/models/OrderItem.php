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
        'name'
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function getNameAttribute()
    {
        return $this->product_name;
    }
}
