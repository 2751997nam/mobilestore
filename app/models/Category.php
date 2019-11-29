<?php
/**
 * Created by PhpStorm.
 * User: tienanhbui
 * Date: 8/8/2019
 * Time: 3:30 PM
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model {
    protected $table = "category";

    protected $fillable = [
        'name', 'type', 'is_hidden', 'image_url', 'description', 'slug', 'sorder', 'parent_id', 'is_display_home_page'
    ];

    protected $appends = [
        'url'
    ];

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
