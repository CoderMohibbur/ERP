<?php

namespace App\Models;


use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skill extends Model
{
    use SoftDeletes;

    /**
     * 🔐 Mass assignable attributes
     */
    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * 🧠 Cast types
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * 🔗 Belongs to the user who created this skill
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * 🔗 Belongs to the user who last updated this skill
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * 🔗 Many-to-Many: Skill assigned to multiple employees
     */
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_skills')
                    ->withTimestamps();
    }

    /**
     * 🔍 Scope: Only active skills
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * 🔍 Scope: Filter by category (e.g., 'Technical')
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * 🔍 Scope: Find by slug
     */
    public function scopeSlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * 🔁 Auto lowercase slug (if you want to keep it synced)
     */
    public static function booted()
    {
        static::saving(function ($skill) {
            if (!empty($skill->name) && empty($skill->slug)) {
                $skill->slug = Str::slug($skill->name);
            }
        });
    }
}
