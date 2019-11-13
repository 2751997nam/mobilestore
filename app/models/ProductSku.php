<?php

namespace App\Models;

use Megaads\Apify\Models\BaseModel;

class ProductSku extends BaseModel
{
    use \App\Models\Multitenantable;

    protected $table = 'product_sku';

    protected $fillable = [
        'sku',
        'price',
        'high_price',
        'image_url',
        'product_id',
        'shop_uuid',
        'is_default',
        'sale_percent',
        'display_drop_price',
    ];
    protected $appends = ['product_name'];

    public function skuValues()
    {
        return $this->hasMany(ProductSkuValue::class, 'sku_id', 'id');
    }

    public function getProductNameAttribute()
    {
        $name = '';
        if ($this->product_id && $this->id) {
            $productOrigin = Product::find($this->product_id);
            $name = $productOrigin->name;
            $skuValues = ProductSkuValue::where('sku_id', '=', $this->id)->get();
            foreach ($skuValues as $skuValue) {
                if ($skuValue->variant_id && $skuValue->variant_option_id) {
                    $variant = ProductVariant::find($skuValue->variant_id);
                    $variantOption = ProductVariantOption::find($skuValue->variant_option_id);
                    if ($variant->slug == 'size') {
                        $attrName = $variant->name . ': ' . $variantOption->name;
                    } else {
                        $attrName = $variantOption->name;
                    }
                    $name .= ', ' . $attrName;
                }
            }
        }
        return $name;
    }

    public function getSalePercentAttribute () {
        if ($this->high_price && $this->price && $this->high_price > 0) {
            $salePercent = round(($this->high_price - $this->price) / $this->high_price * 100);
            if ($salePercent > 0) {
                return "-$salePercent%";
            }
        }

        return null;
    }

    public function getDisplayDropPriceAttribute () {
        return number_format($this->high_price - $this->price, 0, ',', '.').' ₫';
    }

}
