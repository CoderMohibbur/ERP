<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    /**
     * Fillable fields
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'join_date',
        'photo',
        'department_id',
        'designation_id',
        'created_by',
    ];

    /**
     * Cast attributes
     */
    protected $casts = [
        'join_date' => 'datetime',
    ];

    /**
     * 🔗 Department relation
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * 🔗 Designation relation
     */
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    /**
     * 🔗 Created By (User)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * 🔗 Attendance records
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * 🔗 Tasks directly assigned
     */
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /**
     * 🔗 Belongs to many projects (pivot: project_employee)
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_employee');
    }

    /**
     * 🔗 Belongs to many tasks (pivot: task_employee)
     */
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_employee');
    }

    /**
     * 🔍 Scope: active employees (soft-deleted excluded)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }
}
