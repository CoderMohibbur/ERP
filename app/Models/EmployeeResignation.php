<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeResignation extends Model
{
    use SoftDeletes;

    /**
     * 🔐 Fillable attributes
     */
    protected $fillable = [
        'employee_id',
        'resignation_date',
        'effective_date',
        'reason',
        'details',
        'status',
        'approved_at',
        'approved_by',
        'created_by',
        'updated_by',
    ];

    /**
     * 🧠 Attribute casting
     */
    protected $casts = [
        'resignation_date' => 'date',
        'effective_date' => 'date',
        'approved_at' => 'datetime',
    ];

    /**
     * 🔗 Relationship: Employee
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * 🔗 Relationship: Approved by user
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * 🔗 Relationship: Created by user
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * 🔗 Relationship: Updated by user
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * 🔍 Scope: Approved resignations
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * 🔍 Scope: Pending resignations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * 🔍 Scope: Filter by month/year
     */
    public function scopeInMonth($query, $month)
    {
        return $query->whereMonth('resignation_date', $month);
    }

    public function scopeInYear($query, $year)
    {
        return $query->whereYear('resignation_date', $year);
    }
}
