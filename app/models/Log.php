<?php

namespace App\Models;

use App\Models\Multitenantable;

class Log extends \Megaads\Apify\Models\BaseModel
{
    use \App\Models\Multitenantable;
    protected $table = "log";
    protected $fillable = [
        'actor_type',
        'actor_email',
        'target_type',
        'target_id',
        'event_type',
        'data',
        'created_at'
    ];
    const UPDATED_AT = null;

    const EVENT_TYPE_CREATE = 'CREATE';
    const EVENT_TYPE_UPDATE = 'UPDATE';
    const EVENT_TYPE_STATUS = 'UPDATE_STATUS';
}
