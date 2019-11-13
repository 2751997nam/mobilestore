<?php

namespace App\Models;

class SettingShoppingFeed extends \Megaads\Apify\Models\BaseModel {
    use \App\Models\Multitenantable;
    protected $table = "setting_shopping_feed";

    protected $fillable = [
        'name', 'category_id', 'is_only_variant', 'is_variant_title', 'google_product_taxonomy_id'
    ];

}
