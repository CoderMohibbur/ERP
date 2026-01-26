<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    // ActivityType
    public const TYPE_CALL     = 'call';
    public const TYPE_WHATSAPP = 'whatsapp';
    public const TYPE_EMAIL    = 'email';
    public const TYPE_MEETING  = 'meeting';
    public const TYPE_NOTE     = 'note';

    public const TYPES = [
        self::TYPE_CALL,
        self::TYPE_WHATSAPP,
        self::TYPE_EMAIL,
        self::TYPE_MEETING,
        self::TYPE_NOTE,
    ];

    // Status (open/done)
    public const STATUS_OPEN = 'open';
    public const STATUS_DONE = 'done';

    public const STATUSES = [
        self::STATUS_OPEN,
        self::STATUS_DONE,
    ];

    protected $fillable = [
        'subject',
        'type',
        'body',
        'activity_at',
        'next_follow_up_at',
        'status',
        'actor_id',
        'actionable_type',
        'actionable_id',
    ];

    protected $casts = [
        'activity_at'       => 'datetime',
        'next_follow_up_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships (polymorphic)
    |--------------------------------------------------------------------------
    */

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function actionable()
    {
        return $this->morphTo();
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeFollowUpDue(Builder $query, ?\DateTimeInterface $at = null): Builder
    {
        $at = $at ?: now();
        return $query->where('status', self::STATUS_OPEN)
            ->whereNotNull('next_follow_up_at')
            ->where('next_follow_up_at', '<=', $at);
    }
}
