<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeHistory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'designation_id',
        'department_id',
        'effective_from',
        'effective_to',
        'change_type',
        'remarks',
        'changed_by',
    ];

    protected $casts = [
        'effective_from' => 'date',
        'effective_to'   => 'date',
    ];

    /**
     * 🔗 Relationship: Belongs to Employee
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * 🔗 Relationship: Belongs to Designation
     */
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    /**
     * 🔗 Relationship: Belongs to Department
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * 🔗 Relationship: Changed by (User)
     */
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * 💡 Scope: Only current (effective_to is null)
     */
    public function scopeCurrent($query)
    {
        return $query->whereNull('effective_to');
    }

    /**
     * 💡 Scope: History for employee
     */
    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }
}
