<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    /**
     * Fillable attributes for mass assignment
     */
    protected $fillable = [
        'title',
        'client_id',
        'description',
        'deadline',
        'started_at',
        'completed_at',
        'budget',
        'actual_cost',
        'project_code',
        'priority',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'deadline' => 'date',
        'started_at' => 'date',
        'completed_at' => 'date',
        'budget' => 'decimal:2',
        'actual_cost' => 'decimal:2',
    ];

    /**
     * 🔗 Belongs to: Client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
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
     * 🔍 Scope: active projects
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'in_progress']);
    }

    /**
     * 🔍 Scope: by priority
     */
    public function scopePriority($query, $level)
    {
        return $query->where('priority', $level);
    }

    /**
     * 🔍 Scope: deadline within days
     */
    public function scopeDueInDays($query, $days = 7)
    {
        return $query->whereBetween('deadline', [now(), now()->addDays($days)]);
    }
}
