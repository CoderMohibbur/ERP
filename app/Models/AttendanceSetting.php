<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceSetting extends Model
{
    use SoftDeletes;

    /**
     * Fillable attributes
     */
    protected $fillable = [
        'office_start',
        'start_time',
        'end_time',
        'grace_minutes',
        'half_day_after',
        'working_days',
        'weekend_days',
        'timezone',
        'allow_remote_attendance',
        'note',
        'created_by',
        'updated_by',
    ];

    /**
     * Cast attributes to appropriate types
     */
    protected $casts = [
        'office_start' => 'datetime:H:i',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'grace_minutes' => 'integer',
        'half_day_after' => 'integer',
        'working_days' => 'integer',
        'weekend_days' => 'array',
        'allow_remote_attendance' => 'boolean',
    ];

    /**
     * 🔗 Relationship: created by
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * 🔗 Relationship: updated by
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * 🔍 Scope: active remote attendance enabled
     */
    public function scopeRemoteEnabled($query)
    {
        return $query->where('allow_remote_attendance', true);
    }
}
