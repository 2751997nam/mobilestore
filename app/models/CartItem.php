<?php
/**
 * Created by PhpStorm.
 * User: DiemND
 * Date: 08/26/2019
 * Time: 10:42
 */

namespace App\Models;


use Megaads\Apify\Models\BaseModel;

class CartItem extends BaseModel
{
    use \App\Models\Multitenantable;
    protected $table = "cart_item";
    protected $appends = ['display_price', 'url'];
    protected $fillable = [
        'cart_id',
        'product_id',
        'product_sku_id',
        'product_name',
        'quantity',
        'price',
        'discount',
        'image_url',
        'shop_uuid',
    ];

    public function getDisplayPriceAttribute() {
        return number_format($this->price, 0, ',', '.').' ₫';
    }

    public function getUrlAttribute() {
        return "/" . str_slug($this->product_name) . "-p" . $this->product_id . ".html";
    }

    public function getPriceAttribute($value) {

        $price = $value;
        if ($this->product_sku_id) {
            $productSku = ProductSku::find($this->product_sku_id);
            if (isset($productSku->price)) {
                $price = $productSku->price;
            }
        } else if ($this->product_id) {
            $product = Product::find($this->product_id);
            if (isset($product->price)) {
                $price = $product->price;
            }
        }
        return $price;
    }

}
