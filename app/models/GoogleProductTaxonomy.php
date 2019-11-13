<?php

namespace App\Models;

class GoogleProductTaxonomy extends \Megaads\Apify\Models\BaseModel {
    protected $table = "google_product_taxonomy";

    protected $fillable = [
        'product_taxonomy_id', 'product_taxonomy_name'
    ];

}
