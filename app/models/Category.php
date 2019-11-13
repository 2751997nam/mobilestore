<?php
/**
 * Created by PhpStorm.
 * User: tienanhbui
 * Date: 8/8/2019
 * Time: 3:30 PM
 */

namespace App\Models;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Builder;

class Category extends \Megaads\Apify\Models\BaseModel {
    use NodeTrait;
    protected $table = "category";

    protected $fillable = [
        'name', 'type', 'is_hidden', 'image_url', 'description', 'slug', 'sorder', 'parent_id', 'is_display_home_page'
    ];

    protected $appends = [
        'url'
    ];

    use \App\Models\Multitenantable;

    public function meta() {
        return $this->hasMany('App\Models\CategoryMeta', 'category_id', 'id');
    }

    public function getLftName()
    {
        return '_lft';
    }

    public function getRgtName()
    {
        return '_rgt';
    }

    public function getParentIdName()
    {
        return 'parent_id';
    }

    // Specify parent id attribute mutator
    public function setParentAttribute($value)
    {
        $this->setParentIdAttribute($value);
    }

    public function checkDescendants($id){
        $descendants = Category::descendantsOf($this->id);
        foreach ($descendants as $cate){
            if($cate->id == $id) return true;
        }
        return false;
    }

    public function hasChild(){
        if(!$this->id) return false;
        if(!Category::descendantsOf($this->id)->count())
            return false;
        return true;
    }

    public function fullDelete()
    {
        if(!$this->id) return false;

        $status = false;
        if(!Category::descendantsOf($this->id)->count()){
            ProductNCategory::where('category_id', '=', $this->id)->delete();
            CategoryMeta::where('category_id', '=', $this->id)->delete();
            $this->delete();
            $status = true;
        }

        return $status;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_n_category')->withPivot(['sorder'])->withTimestamps();
    }

    public function posts() {
        return $this->hasMany(Post::class, 'category_id', 'id');
    }

    public function getSlugAttribute ($value) {
        if ($value != '') {
            return $value;
        } else {
            return str_slug($this->name);
        }
    }

    public function getUrlAttribute () {
        if ($this->type == 'PRODUCT') {
            return "/" . $this->slug . "-c" . $this->id . ".html";
        } else {
            return "/" . $this->slug . "-a" . $this->id . ".html";
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('sort', function (Builder $builder) {
            $builder->orderBy('category.sorder', 'desc')
                    ->orderBy('category.id', 'asc');
        });
    }
}
