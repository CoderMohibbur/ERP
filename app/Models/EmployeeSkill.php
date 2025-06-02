<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeSkill extends Model
{
    use SoftDeletes;

    /**
     * 🔐 Mass assignable attributes
     */
    protected $fillable = [
        'employee_id',
        'skill_id',
        'proficiency_level',
        'notes',
        'assigned_by',
    ];

    /**
     * 🧠 Type casting
     */
    protected $casts = [
        'proficiency_level' => 'integer',
    ];

    /**
     * 🔗 Relationship: Belongs to Employee
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * 🔗 Relationship: Belongs to Skill
     */
    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    /**
     * 🔗 Relationship: Assigned by User
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * 🔍 Scope: Filter by proficiency level
     */
    public function scopeProficient($query, int $level)
    {
        return $query->where('proficiency_level', '>=', $level);
    }

    /**
     * 🔍 Scope: Filter by specific skill
     */
    public function scopeWithSkill($query, $skillId)
    {
        return $query->where('skill_id', $skillId);
    }


    public function getProficiencyLabelAttribute(): string
    {
        return match (true) {
            $this->proficiency_level >= 9 => 'Expert',
            $this->proficiency_level >= 6 => 'Advanced',
            $this->proficiency_level >= 3 => 'Intermediate',
            default => 'Beginner',
        };
    }
}
