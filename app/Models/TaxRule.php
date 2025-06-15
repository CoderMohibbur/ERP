<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxRule extends Model
{
    use SoftDeletes;

    /**
     * Mass assignable attributes.
     */
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

    /**
     * Casts for automatic type conversion.
     */
    protected $casts = [
        'rate_percent'    => 'float',
        'is_active'       => 'boolean',
        'applicable_from' => 'date',
        'applicable_to'   => 'date',
    ];

    /**
     * 🔍 Scopes for reusable query filters.
     */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeGlobal($query)
    {
        return $query->where('scope', 'global');
    }

    public function scopeEffectiveToday($query)
    {
        $today = now()->toDateString();

        return $query->where(function ($q) use ($today) {
            $q->whereNull('applicable_from')
              ->orWhere('applicable_from', '<=', $today);
        })->where(function ($q) use ($today) {
            $q->whereNull('applicable_to')
              ->orWhere('applicable_to', '>=', $today);
        });
    }

    public function scopeForRegion($query, $country = null, $region = null)
    {
        return $query->where(function ($q) use ($country, $region) {
            if ($country) {
                $q->where('country_code', $country);
            }
            if ($region) {
                $q->where('region', $region);
            }
        });
    }

    /**
     * 🧠 Relationships (future ready, if using user tracking).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
