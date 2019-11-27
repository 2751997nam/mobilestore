<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class District extends Model
{
    protected $table = 'district';

    public function communes()
    {
        return $this->hasMany(Commune::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('sort', function (Builder $builder) {
            $builder->orderBy('district.name', 'asc');
        });
    }
}
