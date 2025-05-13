<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSetting extends Model
{
    protected $fillable = ['office_start', 'grace_minutes', 'weekend_days'];
    protected $casts = [
        'office_start' => 'datetime:H:i',
        'weekend_days' => 'array'
    ];
}