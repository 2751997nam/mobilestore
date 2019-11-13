<?php 
namespace App\Models;

use Megaads\Apify\Models\BaseModel;

class Subscribe extends BaseModel {
    use \App\Models\Multitenantable;
    
    protected $table = 'subscribe';
}