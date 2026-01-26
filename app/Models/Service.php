<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    // ServiceStatus
    public const STATUS_ACTIVE    = 'active';
    public const STATUS_SUSPENDED = 'suspended';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_EXPIRED   = 'expired';

    public const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_SUSPENDED,
        self::STATUS_CANCELLED,
        self::STATUS_EXPIRED,
    ];

    protected $fillable = [
        'client_id',
        'type',
        'name',
        'billing_cycle',
        'amount',
        'currency',
        'started_at',
        'expires_at',
        'next_renewal_at',
        'status',
        'auto_invoice',
        'notes',
    ];

    protected $casts = [
        'amount'         => 'decimal:2',
        'auto_invoice'   => 'boolean',
        'started_at'     => 'date',
        'expires_at'     => 'date',
        'next_renewal_at'=> 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function renewals()
    {
        return $this->hasMany(ServiceRenewal::class, 'service_id');
    }

    public function activities()
    {
        // optional usage: activities.actionable_type = Service::class
        return $this->morphMany(Activity::class, 'actionable');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeRenewalDueWithin(Builder $query, int $days): Builder
    {
        $to = now()->addDays($days)->toDateString();

        return $query->whereNotNull('next_renewal_at')
            ->where('next_renewal_at', '<=', $to)
            ->whereIn('status', [self::STATUS_ACTIVE, self::STATUS_SUSPENDED]);
    }
}
