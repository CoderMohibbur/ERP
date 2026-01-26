<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReminderLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'entity_type',
        'entity_id',
        'remind_on',
        'sent_at',
        'meta',
    ];

    protected $casts = [
        'remind_on' => 'date',
        'sent_at'   => 'datetime',
        'meta'      => 'array',
    ];
}
