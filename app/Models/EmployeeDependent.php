<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDependent extends Model
{
    /**
     * 🔐 Fillable attributes
     */
    protected $fillable = [
        'employee_id',
        'name',
        'relation',
        'dob',
        'phone',
        'nid_number',
        'is_emergency_contact',
        'notes',
        'created_by',
        'updated_by',
    ];

    /**
     * 🧠 Cast attributes to proper types
     */
    protected $casts = [
        'dob'                  => 'date',
        'is_emergency_contact' => 'boolean',
    ];

    // 🔗 Relationships

    /**
     * Employee to whom this dependent belongs
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * User who created the record
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User who last updated the record
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // 🎯 Accessors (Optional)

    /**
     * Get formatted relation label (for UI display)
     */
    public function getRelationLabelAttribute(): string
    {
        return ucfirst($this->relation);
    }

    /**
     * Show emergency badge (for UI)
     */
    public function getIsEmergencyBadgeAttribute(): string
    {
        return $this->is_emergency_contact ? '✅ Yes' : '—';
    }
}
