<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    /**
     * Fillable attributes (safe for mass-assignment)
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'company_name',
        'industry_type',
        'website',
        'tax_id',
        'status',
        'custom_fields',
        'created_by',
        'updated_by',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'custom_fields' => 'array',
    ];

    /**
     * 🔗 Belongs to: Created by user
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * 🔗 Belongs to: Updated by user
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * 🔍 Scope: Active clients only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * 🔍 Scope: Filter by industry
     */
    public function scopeIndustry($query, $industry)
    {
        return $query->where('industry_type', $industry);
    }
}
