<?php
/**
 * Created by PhpStorm.
 * User: DiemND
 * Date: 08/26/2019
 * Time: 10:40
 */

namespace App\Models;


use Megaads\Apify\Models\BaseModel;

class ShippingFee extends BaseModel
{
    use \App\Models\Multitenantable;
    protected $table = "shipping_fee";
    protected $fillable = [
        'location_id',
        'shop_uuid'
    ];

    protected $appends = ['location_name', 'items'];

    public function getLocationNameAttribute()
    {
        $name = '';
        if ($this->location_id) {
            $location = Province::find($this->location_id);
            if (!empty($location->name)) {
                $name = mb_strtoupper($location->name);
            }
        } else if ($this->location_id ===  0) {
            $name = 'CÁC TỈNH/THÀNH KHÁC';
        }
        return $name;
    }

    public function getItemsAttribute()
    {
        $items = null;
        if ($this->id) {
            $items = ShippingFeeItem::where('shipping_fee_id', '=', $this->id)->get();
        }
        return $items;
    }


}