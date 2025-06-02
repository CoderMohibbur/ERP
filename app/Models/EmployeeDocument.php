<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeDocument extends Model
{
    use SoftDeletes;

    /**
     * 🔐 Mass assignable attributes
     */
    protected $fillable = [
        'employee_id',
        'type',
        'title',
        'file_path',
        'file_type',
        'file_size',
        'file_hash',
        'visibility',
        'expires_at',
        'is_verified',
        'notes',
        'uploaded_by',
        'verified_by',
    ];

    /**
     * 🧠 Type casting
     */
    protected $casts = [
        'is_verified' => 'boolean',
        'expires_at' => 'date',
    ];

    /**
     * 🔗 Relationship: Employee
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * 🔗 Relationship: Uploaded by user
     */
    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * 🔗 Relationship: Verified by user
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * 🔍 Scope: Verified documents only
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * 🔍 Scope: Expired documents
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')
                     ->where('expires_at', '<', now());
    }

    /**
     * 🔍 Scope: Filter by visibility level
     */
    public function scopeVisibleTo($query, string $role)
    {
        return $query->where('visibility', $role);
    }
}
