<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Designation extends Model
{
    use SoftDeletes;

    /**
     * Fillable attributes for mass assignment
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'level',
        'created_by',
        'updated_by',
    ];

    /**
     * Casts for clean type handling
     */
    protected $casts = [
        'level' => 'integer',
    ];

    /**
     * 🔗 Created by admin/user
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * 🔗 Updated by admin/user
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * 🔍 Scope: Executive Level Designations
     */
    public function scopeExecutive($query)
    {
        return $query->where('level', '<=', 2);
    }

    /**
     * 🔍 Scope: Ordered by hierarchy
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('level');
    }

    /**
     * 🔗 Optional: All employees under this designation
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
