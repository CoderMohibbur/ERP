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
        'shift_date'          => 'date',
        'start_time_override' => 'datetime:H:i',
        'end_time_override'   => 'datetime:H:i',
        'is_manual_override'  => 'boolean',
    ];

    // 🔗 Relationships

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // 🧠 Scopes

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('shift_date', $date);
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('shift_date', '>', now());
    }

    public function scopePast($query)
    {
        return $query->whereDate('shift_date', '<', now());
    }

    // 🎯 Accessors

    public function getHasOverrideAttribute(): bool
    {
        return $this->is_manual_override &&
               $this->start_time_override &&
               $this->end_time_override;
    }

    public function getEffectiveStartTimeAttribute()
    {
        return $this->start_time_override ?? $this->shift?->start_time;
    }

    public function getEffectiveEndTimeAttribute()
    {
        return $this->end_time_override ?? $this->shift?->end_time;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'assigned'  => '🟢 Assigned',
            'completed' => '✅ Completed',
            'cancelled' => '❌ Cancelled',
            default     => ucfirst($this->status),
        };
    }
}
