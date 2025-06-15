<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'deadline',
        'started_at',
        'completed_at',
        'budget',
        'actual_cost',
        'project_code',
        'priority',
        'status',
        'client_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'deadline'      => 'date',
        'started_at'    => 'date',
        'completed_at'  => 'date',
        'budget'        => 'decimal:2',
        'actual_cost'   => 'decimal:2',
    ];

    // 🔗 Relationships
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // 🧠 Scope (Optional)
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'cancelled');
    }
}
