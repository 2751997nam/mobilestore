<?php
/**
 * Created by PhpStorm.
 * User: DiemND
 * Date: 08/26/2019
 * Time: 10:42
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = "cart_item";
    protected $appends = ['display_price', 'url', 'total'];
    protected $fillable = [
        'cart_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
        'image_url',
    ];

    public function getDisplayPriceAttribute() {
        return number_format($this->price, 0, ',', '.').' ₫';
    }

    public function getTotalAttribute() {
        return number_format($this->price * $this->quantity, 0, ',', '.').' ₫';
    }

    public function getUrlAttribute() {
        return "/" . str_slug($this->product_name) . "-p" . $this->product_id . ".html";
    }

}
