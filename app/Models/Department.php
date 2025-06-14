<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    /**
     * Fillable attributes — only those that exist in the migration
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'name' => 'string',
    ];

    /**
     * 🔍 Scope: Active only (optional but safe)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }
}
