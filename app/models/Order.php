<?php

namespace App\Models;

use DB;
use Shopbay;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Location;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "order";

    protected $fillable = [
        'code',
        'customer_id',
        'status',
        'amount',
        'delivery_address',
        'commune_id',
        'district_id',
        'province_id',
        'delivery_location_id',
        'delivery_district_id',
        'delivery_note',
        'note',
    ];

    protected $appends = [
        'editUrl', 'display_amount'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function district()
    {
        return $this->belongsTo(Location::class, 'delivery_district_id', 'id');
    }

    public function getItemsAttribute() {
        return $this->buildItems();
    }

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    // private function buildItems() {
    //     $items = OrderItem::with('product', 'productSku')
    //                         ->where('order_id', '=', $this->id)
    //                         ->get();
    //     $variantNames = Shopbay::productService()->variantNameBySkuIds();
    //     $retVal = [];
    //     foreach ($items as $item) {
    //         $newItem = [
    //             'id' => $item->id,
    //             'product_id' => $item->product_id,
    //             'product_sku_id' => $item->product_sku_id,
    //             'quantity' => $item->quantity,
    //             'price' => $item->price,
    //             'sku' => is_object($item->product) ? $item->product->sku : '',
    //             'name' => is_object($item->product) ? $item->product->name : '',
    //             'image_url' => is_object($item->product) ? $item->product->image_url : '',
    //             'url' => is_object($item->product) ? $item->product->url : '',
    //             'editUrl' => is_object($item->product) ? $item->product->editUrl : ''
    //         ];
    //         if ($item->product_sku_id != '') {
    //             if (is_object($item->productSku)) {
    //                 $newItem['sku'] = $item->productSku->sku;
    //                 if (isset($variantNames[$item->productSku->id])) {
    //                     $newItem['name'] .= ', ' . $variantNames[$item->productSku->id];
    //                 }
    //                 $newItem['url'] .= '?spid=' . $item->productSku->id;
    //             }
    //         }
    //         $retVal[] = $newItem;
    //     }
    //     return $retVal;
    // }

    private function buildItems() {

    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_item')
            ->withPivot(['quantity', 'price', 'shop_uuid'])
            ->withTimestamps();
    }

    public function getEditUrlAttribute()
    {
        return "/admin/orders/" . $this->id;
    }

    public function isEditable()
    {
        return !($this->status !== 'PENDING' && $this->status !== 'PROCESSING');
    }

    public function isEditableAddress()
    {
        $retVal = $this->isEditable();
        if ($this->status === 'DELIVERING') {
            $retVal = true;
        }

        return $retVal;
    }

    public function getDisplayAmountAttribute () {
        return number_format($this->amount, 0, ',', '.').' ₫';
    }
}
