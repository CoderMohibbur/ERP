<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use SoftDeletes;

    /**
     * 🔐 Fillable attributes for mass assignment
     */
    protected $fillable = [
        'name',
        'slug',
        'code',
        'start_time',
        'end_time',
        'crosses_midnight',
        'type',
        'color',
        'notes',
        'week_days',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * 🧠 Attribute casting
     */
    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'crosses_midnight' => 'boolean',
        'is_active' => 'boolean',
        'week_days' => 'array',
    ];

    /**
     * 🔗 Relationship: created by user
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * 🔗 Relationship: updated by user
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * 🔍 Scope: Only active shifts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * 🔍 Scope: Filter by type
     */
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * 🔍 Scope: Filter by day (for roster planning)
     */
    public function scopeForDay($query, string $day)
    {
        return $query->whereJsonContains('week_days', $day);
    }

    /**
     * 🎨 Accessor: Human-readable time range
     */
    public function getTimeRangeAttribute()
    {
        return $this->start_time . ' - ' . $this->end_time . ($this->crosses_midnight ? ' (Overnight)' : '');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_shifts')->withTimestamps();
    }


    public function scopeBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }


    protected static function booted()
    {
        static::saving(function ($shift) {
            if (empty($shift->slug) && !empty($shift->name)) {
                $shift->slug = \Str::slug($shift->name);
            }
        });
    }
}
