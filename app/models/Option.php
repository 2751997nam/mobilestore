<?php

namespace App\Models;

use Megaads\Apify\Models\BaseModel;

class Option extends BaseModel
{
    use \App\Models\Multitenantable;
    public $timestamps = false;

    protected $table = 'option';

}
