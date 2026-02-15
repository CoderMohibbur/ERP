<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    // ✅ Status (Backward compatible + Global-friendly)
    public const STATUS_NEW = 'new';
    public const STATUS_CONTACTED = 'contacted';
    public const STATUS_QUALIFIED = 'qualified';

    // Backward compatible old status
    public const STATUS_UNQUALIFIED = 'unqualified';

    // New global-friendly statuses
    public const STATUS_LOST = 'lost';
    public const STATUS_CONVERTED = 'converted';

    /**
     * Allowed statuses.
     * Keep backward compatibility with existing data/validation.
     */
    public const STATUSES = [
        self::STATUS_NEW,
        self::STATUS_CONTACTED,
        self::STATUS_QUALIFIED,
        self::STATUS_UNQUALIFIED, // legacy
        self::STATUS_LOST,
        self::STATUS_CONVERTED,
    ];

    protected $fillable = [
        // existing fields (NO LOSS)
        'name',
        'phone',
        'email',
        'company',
        'source',
        'status',
        'owner_id',
        'next_follow_up_at',
        'last_contacted_at',
        'notes',
        'converted_client_id',

        // new fields (additive)
        'converted_at',
    ];

    protected $casts = [
        'next_follow_up_at' => 'datetime',
        'last_contacted_at' => 'datetime',
        'converted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class, 'lead_id');
    }

    public function convertedClient(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'converted_client_id');
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'actionable');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeFollowUpDue(Builder $query, ?\DateTimeInterface $at = null): Builder
    {
        $at = $at ?: now();

        return $query
            ->whereNotNull('next_follow_up_at')
            ->where('next_follow_up_at', '<=', $at);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isConverted(): bool
    {
        return (string) $this->status === self::STATUS_CONVERTED
            || !empty($this->converted_client_id);
    }
}
