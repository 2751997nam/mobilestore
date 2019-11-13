<?php
namespace App\Models;
class CategoryMeta extends \Megaads\Apify\Models\BaseModel {

    use \App\Models\Multitenantable;

    protected $table = "category_meta";
    protected $fillable = ['category_id', 'key', 'value'];
    public $timestamps = false;

    public function category() {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
}
