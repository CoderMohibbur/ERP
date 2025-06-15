<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeDisciplinaryAction extends Model
{
    use SoftDeletes;

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

    protected $casts = [
        'incident_date' => 'date',
        'action_date' => 'date',
        'approved_at' => 'datetime',
        'severity_level' => 'integer',
    ];

    // 🔗 Relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // 🔍 Query Scopes
    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeBySeverity($query, int $level)
    {
        return $query->where('severity_level', $level);
    }

    public function scopeByActionTaken($query, string $action)
    {
        return $query->where('action_taken', $action);
    }

    public function scopePendingApproval($query)
    {
        return $query->whereNull('approved_at');
    }

    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_at');
    }
}
