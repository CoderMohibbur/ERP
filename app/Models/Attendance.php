<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use SoftDeletes;

    /**
     * Fillable attributes for safe mass-assignment
     */
    protected $fillable = [
        'employee_id',
        'date',
        'status',
        'note',
        'in_time',
        'out_time',
        'worked_hours',
        'late_by_minutes',
        'early_leave_minutes',
        'location',
        'device_type',
        'verified_by',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'date'                => 'date',
        'in_time'             => 'datetime:H:i',
        'out_time'            => 'datetime:H:i',
        'worked_hours'        => 'decimal:2',
        'late_by_minutes'     => 'integer',
        'early_leave_minutes' => 'integer',
    ];

    /**
     * 🔗 Employee Relationship
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * 🔗 Verified By (User / Admin)
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * 🔍 Scope: Filter by single status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * 🔍 Scope: Filter by exact date
     */
    public function scopeOnDate($query, $date)
    {
        return $query->where('date', $date);
    }

    /**
     * 🔍 Scope: Present or Late
     */
    public function scopeActiveAttendances($query)
    {
        return $query->whereIn('status', ['present', 'late']);
    }

    /**
     * 🔍 Scope: For a specific month
     */
    public function scopeInMonth($query, $month)
    {
        return $query->whereMonth('date', $month);
    }

    /**
     * 🔍 Scope: Filter by device type
     */
    public function scopeDevice($query, $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }
}
