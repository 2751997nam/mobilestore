<?php 
namespace App\Models;

use Megaads\Apify\Models\BaseModel;

class PostMeta extends BaseModel 
{
    use \App\Models\Multitenantable;
    
    protected $table = "post_meta";
}