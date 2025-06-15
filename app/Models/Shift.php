<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'start_time',
        'end_time',
        'crosses_midnight',
        'type',
        'color',
        'notes',
        'week_days',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_time'       => 'datetime:H:i',
        'end_time'         => 'datetime:H:i',
        'crosses_midnight' => 'boolean',
        'week_days'        => 'array',
        'is_active'        => 'boolean',
        'created_by'       => 'integer',
        'updated_by'       => 'integer',
    ];

    // 🔗 Relationships

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // 🧠 Scopes

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // 💡 Accessors

    public function getTimeRangeAttribute(): string
    {
        return $this->start_time . ' - ' . $this->end_time . ($this->crosses_midnight ? ' (+1d)' : '');
    }

    public function getFormattedNameAttribute(): string
    {
        return ucfirst(str_replace('-', ' ', $this->name));
    }
}
