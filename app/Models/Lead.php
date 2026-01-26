<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    // Status (LeadStatus)
    public const STATUS_NEW         = 'new';
    public const STATUS_CONTACTED   = 'contacted';
    public const STATUS_QUALIFIED   = 'qualified';
    public const STATUS_UNQUALIFIED = 'unqualified';

    public const STATUSES = [
        self::STATUS_NEW,
        self::STATUS_CONTACTED,
        self::STATUS_QUALIFIED,
        self::STATUS_UNQUALIFIED,
    ];

    protected $fillable = [
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
    ];

    protected $casts = [
        'next_follow_up_at' => 'datetime',
        'last_contacted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function deals()
    {
        return $this->hasMany(Deal::class, 'lead_id');
    }

    public function convertedClient()
    {
        return $this->belongsTo(Client::class, 'converted_client_id');
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'actionable');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes (helpful for dashboard/filters)
    |--------------------------------------------------------------------------
    */

    public function scopeFollowUpDue(Builder $query, ?\DateTimeInterface $at = null): Builder
    {
        $at = $at ?: now();
        return $query->whereNotNull('next_follow_up_at')
            ->where('next_follow_up_at', '<=', $at);
    }
}
