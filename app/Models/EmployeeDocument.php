<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeDocument extends Model
{
    use SoftDeletes;

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

    protected $casts = [
        'file_size'    => 'integer',
        'is_verified'  => 'boolean',
        'expires_at'   => 'date',
    ];

    // 🔗 Relationships

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // 🔎 Scope

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeVisibleTo($query, $role = 'employee')
    {
        return $query->where('visibility', $role);
    }

    // 📁 Accessors

    public function getFileNameAttribute()
    {
        return basename($this->file_path);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
