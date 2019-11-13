<?php

namespace App\Models;

use Megaads\Apify\Models\BaseModel;

class Filter extends BaseModel
{

    use \App\Models\Multitenantable;

    protected $table = 'filter';

    protected $fillable = [
        'name',
        'display_name',
        'slug',
        'shop_uuid'
    ];

    public function values()
    {
        return $this->hasMany(FilterValue::class, 'filter_id', 'id')->orderBy('id');
    }

    public function productCount()
    {
        return $this->values()->countProduct();
    }

    public function categories(){
        return $this->belongsToMany(Category::class, 'filter_n_category');
    }

}
