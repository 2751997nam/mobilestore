<?php

namespace App\Models;

class Location extends \Megaads\Apify\Models\BaseModel
{
    protected $table = "location";

    public function districts()
    {
        return $this->hasMany(Location::class, 'parent_id', 'id');
    }
}
