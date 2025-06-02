<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'assigned_to',
        'parent_task_id',
        'dependency_task_id',
        'title',
        'priority',
        'status',
        'progress',
        'start_date',
        'due_date',
        'end_date',
        'estimated_hours',
        'actual_hours',
        'note',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'end_date' => 'date',
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2',
        'progress' => 'integer',
    ];

    // 🔗 Relationships

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function parentTask()
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }

    public function dependency()
    {
        return $this->belongsTo(Task::class, 'dependency_task_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // 🔍 Scopes

    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', now()->toDateString());
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }
}
