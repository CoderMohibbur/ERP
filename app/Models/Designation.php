<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Designation extends Model
{
    use SoftDeletes;

    /**
     * Fillable fields from migration
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'level',
        'created_by',
        'updated_by',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'name'        => 'string',
        'code'        => 'string',
        'description' => 'string',
        'level'       => 'integer',
    ];

    /**
     * 🔗 Created By (User)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * 🔗 Updated By (User)
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * 🔍 Scope: Filter by level (optional use)
     */
    public function scopeLevel($query, $level)
    {
        return $query->where('level', $level);
    }
}
