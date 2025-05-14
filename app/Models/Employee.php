<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'join_date',
        'photo',
        'department_id',
        'designation_id',
        'created_by'
    ];
    protected $dates = ['join_date'];
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_employee');
    }
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_employee');
    }

    // Accessor for formatted join date 
    protected $casts = [
    'join_date' => 'date',
];

}