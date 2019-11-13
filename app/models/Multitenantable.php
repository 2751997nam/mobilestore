<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

trait Multitenantable
{
    protected static function bootMultitenantable()
    {
        $shopUuid = app('request')->header('shopUuid', -1);
        static::creating(function ($model) use ($shopUuid) {
            $model->shop_uuid = $shopUuid;
        });
        static::addGlobalScope('shopUuid', function (Builder $builder) use ($shopUuid) {
            $tableName = $builder->getModel()->getTable();
            $builder->where($tableName . '.shop_uuid', $shopUuid);
        });
    }

}
