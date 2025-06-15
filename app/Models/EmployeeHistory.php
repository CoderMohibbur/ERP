<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeHistory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'designation_id',
        'department_id',
        'effective_from',
        'effective_to',
        'change_type',
        'remarks',
        'changed_by',
    ];

    protected $casts = [
        'effective_from' => 'date',
        'effective_to'   => 'date',
    ];

    // 🔗 Relationships

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    // 📌 Accessors (Optional)

    public function getStatusLabelAttribute()
    {
        return ucfirst($this->change_type);
    }

    public function isCurrent(): bool
    {
        return is_null($this->effective_to);
    }

    // 🧠 Scope for current history
    public function scopeCurrent($query)
    {
        return $query->whereNull('effective_to');
    }
}
