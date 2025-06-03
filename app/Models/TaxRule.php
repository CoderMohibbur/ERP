<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxRule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'rate_percent',
        'scope',
        'is_active',
        'applicable_from',
        'applicable_to',
        'country_code',
        'region',
        'description',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'rate_percent'    => 'float',
        'is_active'       => 'boolean',
        'applicable_from' => 'date',
        'applicable_to'   => 'date',
        'created_by'      => 'integer',
        'updated_by'      => 'integer',
    ];

    // 🔍 Optional scope helper
    public function isGlobal(): bool
    {
        return $this->scope === 'global';
    }

    // 🧑 Created By
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // 🧑 Updated By
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // InvoiceItem.php
    public function taxRule()
    {
        return $this->belongsTo(TaxRule::class);
    }


    // TaxRule.php
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
