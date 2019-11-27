<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
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
