<?php
namespace App\Models;

use Megaads\Apify\Models\BaseModel;
use App\Models\Order;

class Customer extends BaseModel
{
    use \App\Models\Multitenantable;

    protected $table = 'customer';
    protected $appends = [
        'total_order',
        'total_amount',
        'display_phone',
    ];

    public function getTotalOrderAttribute () {
        return Order::where('customer_id', $this->id)->count();
    }

    public function getTotalAmountAttribute () {
        return number_format(Order::where('customer_id', $this->id)->sum('amount'), 0, ',', '.').' ₫';
    }

    public function getDisplayPhoneAttribute () {
        if ($this->phone) {
            $display = '0' . number_format((int)$this->phone, 0, '-', '-');
            return $display;
        }
        return "";
        return "<a href=\"tel:$this->phone\">$display</a>";
    }

    protected $fillable = [
        'username',
        'full_name',
        'phone',
        'email',
        'gender',
        'status',
        'location_id',
        'address',
        'commune_id',
        'district_id',
        'province_id',
        'note',
    ];

    protected $hidden = [
        'username',
        'shop_uuid',
        'updated_at',
        'created_at',
        'gender',
        'status',
        'location_id'
    ];



    public function orders() {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }

    public function commune()
    {
        return $this->belongsTo(Commune::class, 'commune_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }
}
