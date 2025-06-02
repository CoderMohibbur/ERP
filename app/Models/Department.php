<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    /**
     * Fillable attributes
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'department_head_id',
        'created_by',
        'updated_by',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'name' => 'string',
        'code' => 'string',
        'description' => 'string',
    ];

    /**
     * 🔗 Relationship: Head of Department
     */
    public function head()
    {
        return $this->belongsTo(Employee::class, 'department_head_id');
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
     * 🔍 Scope: Active only (optional)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }
}
