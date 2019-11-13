<?php

namespace App\Models;

use Megaads\Apify\Models\BaseModel;

class Domain extends BaseModel
{
    use \App\Models\Multitenantable;

    protected $table = 'domain';
    protected $connection = 'mysql_shobay_home';

}
