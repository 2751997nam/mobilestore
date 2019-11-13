<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Megaads\Apify\Models\BaseModel;

class Province extends BaseModel
{
    protected $table = 'province';

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('sort', function (Builder $builder) {
            $builder->orderBy('province.sorder', 'desc');
        });
    }
}
