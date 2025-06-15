<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeSkill extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'skill_id',
        'proficiency_level',
        'notes',
        'assigned_by',
    ];

    protected $casts = [
        'proficiency_level' => 'integer',
        'assigned_by'       => 'integer',
    ];

    // 🔗 Relationships

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // 🔍 Scope

    public function scopeWithProficiency($query, $level)
    {
        return $query->where('proficiency_level', '>=', $level);
    }

    // 💡 Accessor (Optional)

    public function getProficiencyDescriptionAttribute(): string
    {
        return match ($this->proficiency_level) {
            1, 2 => 'Beginner',
            3, 4, 5 => 'Intermediate',
            6, 7, 8 => 'Advanced',
            9, 10 => 'Expert',
            default => 'Not Set',
        };
    }
}
