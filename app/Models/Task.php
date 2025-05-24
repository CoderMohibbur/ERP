<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'title',
        'priority',
        'assigned_to',
        'progress',
        'due_date',
        'note', // ✅ newly added
    ];

    protected $dates = ['due_date'];

    protected $casts = [
        'due_date' => 'date',
    ];

    // 🔁 Relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedEmployee()
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'task_employee');
    }
}
