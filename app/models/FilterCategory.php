<?php

namespace App\Models;

use Megaads\Apify\Models\BaseModel;

class FilterCategory extends BaseModel
{

    use \App\Models\Multitenantable;

    protected $table = 'filter_n_category';

    protected $fillable = [
        'filter_id',
        'category_id',
        'shop_uuid'
    ];
}
