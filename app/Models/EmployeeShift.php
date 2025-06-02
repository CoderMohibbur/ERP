<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeShift extends Model
{
    use SoftDeletes;

    /**
     * 🔐 Fillable attributes
     */
    protected $fillable = [
        'employee_id',
        'shift_id',
        'shift_date',
        'start_time_override',
        'end_time_override',
        'is_manual_override',
        'status',
        'remarks',
        'verified_by',
        'assigned_by',
        'shift_type_cache',
    ];

    /**
     * 🧠 Casts for type handling
     */
    protected $casts = [
        'shift_date' => 'date',
        'start_time_override' => 'datetime:H:i',
        'end_time_override' => 'datetime:H:i',
        'is_manual_override' => 'boolean',
    ];

    /**
     * 🔗 Employee who is assigned to the shift
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * 🔗 Assigned shift
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * 🔗 User who assigned the shift
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * 🔗 User who verified the shift
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * 🔍 Scope: Only completed shifts
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * 🔍 Scope: Filter by date
     */
    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('shift_date', $date);
    }

    /**
     * 🔍 Scope: For specific employee
     */
    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * 🎯 Accessor: Was shift manually overridden?
     */
    public function getHasOverrideAttribute()
    {
        return $this->is_manual_override &&
            $this->start_time_override &&
            $this->end_time_override;
    }


    public function scopeUpcoming($query)
    {
        return $query->whereDate('shift_date', '>', now());
    }


    public function scopePast($query)
    {
        return $query->whereDate('shift_date', '<', now());
    }


    public function getStartTimeAttribute()
    {
        return $this->start_time_override ?? $this->shift?->start_time;
    }

    
    public function getEndTimeAttribute()
    {
        return $this->end_time_override ?? $this->shift?->end_time;
    }
}
