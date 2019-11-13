<?php

namespace App\Models;

use Megaads\Apify\Models\BaseModel;

class FilterValueProduct extends BaseModel
{

    use \App\Models\Multitenantable;

    protected $table = 'filter_value_n_product';

    protected $fillable = [
        'filter_value_id',
        'product_id',
        'shop_uuid'
    ];

    public function filterValue() {
        return $this->hasOne('App\Models\FilterValue', 'id', 'filter_value_id');
    }
}
