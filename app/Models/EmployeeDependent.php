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
     * 🧠 Attribute casting
     */
    protected $casts = [
        'dob' => 'date',
        'is_emergency_contact' => 'boolean',
    ];

    /**
     * 🔗 Relationship: Parent employee
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

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
     * 🔍 Scope: Emergency contacts only
     */
    public function scopeEmergency($query)
    {
        return $query->where('is_emergency_contact', true);
    }

    /**
     * 🔍 Scope: Filter by relation
     */
    public function scopeRelationType($query, $relation)
    {
        return $query->where('relation', $relation);
    }
}
