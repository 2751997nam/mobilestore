<?php
namespace App\Models;

use Megaads\Apify\Models\BaseModel;

class Post extends BaseModel
{
    use \App\Models\Multitenantable;

    protected $table = "post";

    protected $appends = ['url', 'editUrl'];

    // protected $guarded = ['shop_uuid', 'category'];
    protected $fillable = [
        'id', 'name', 'description', 'content', 'sorder',
        'category_id', 'image_url', 'status', 'slug'
    ];

    public function postMeta() {
        return $this->hasMany(PostMeta::class, 'post_id','id');
    }
    public function postTag() {
        return $this->belongsToMany(Tag::class, 'tag_refer', 'refer_id')->where('refer_type', 'POST');
    }
    public function category() {
        return $this->hasOne(Category::class, 'id', 'category_id')->where('type', 'POST');
    }

    public function getUrlAttribute () {
        return "/" . $this->slug . "-n" . $this->id . ".html";
    }

    public function getSlugAttribute () {
        return str_slug($this->name);
    }

    public function getEditUrlAttribute () {
        return "/admin/posts/$this->id";
    }

    public function getImageUrlAttribute($value){
        return ($value) ? $value : 'https://s3.shopbay.vn/files/2/system/product-image-placeholder.png';
    }
}
