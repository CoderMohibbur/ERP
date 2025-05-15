<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSetting extends Model
{
    protected $fillable = [
        'office_start',
        'start_time',
        'end_time',
        'grace_minutes',
        'half_day_after',
        'working_days',
        'weekend_days',
        'note',
    ];
    protected $casts = [
        'office_start' => 'datetime:H:i',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'weekend_days' => 'array'
    ];
}
