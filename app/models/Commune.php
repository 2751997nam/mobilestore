<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Commune extends Model
{
    protected $table = 'commune';

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function province()
    {
        return $this->district()->with('province');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('sort', function (Builder $builder) {
            $builder->orderBy('commune.name', 'asc');
        });
    }
}
