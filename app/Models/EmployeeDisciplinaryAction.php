<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeDisciplinaryAction extends Model
{
    use SoftDeletes;

    /**
     * 🔐 Fillable attributes
     */
    protected $fillable = [
        'employee_id',
        'incident_date',
        'action_date',
        'violation_type',
        'description',
        'action_taken',
        'severity_level',
        'attachment_path',
        'created_by',
        'updated_by',
        'approved_by',
        'approved_at',
    ];

    /**
     * 🧠 Type casting
     */
    protected $casts = [
        'incident_date' => 'date',
        'action_date' => 'date',
        'approved_at' => 'datetime',
    ];

    /**
     * 🔗 Employee associated with the action
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * 🔗 Created by user
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * 🔗 Updated by user
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * 🔗 Approved by user
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * 🔍 Scope: Approved disciplinary actions
     */
    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_at');
    }

    /**
     * 🔍 Scope: By violation type
     */
    public function scopeViolation($query, string $type)
    {
        return $query->where('violation_type', $type);
    }

    /**
     * 🔍 Scope: Filter by severity level
     */
    public function scopeSeverity($query, int $level)
    {
        return $query->where('severity_level', $level);
    }

    /**
     * 🔍 Scope: Incidents within a date range
     */
    public function scopeBetweenDates($query, $from, $to)
    {
        return $query->whereBetween('incident_date', [$from, $to]);
    }
}
