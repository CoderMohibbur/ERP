<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'task_id',
        'user_id',
        'started_at',
        'ended_at',
        'seconds',
        'note',
        'source',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
        'seconds'    => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes / Helpers
    |--------------------------------------------------------------------------
    */

    public function scopeRunning(Builder $query): Builder
    {
        return $query->whereNull('ended_at');
    }

    public static function hasRunningTimer(int $userId): bool
    {
        return static::query()->where('user_id', $userId)->running()->exists();
    }

    public function stop(?\DateTimeInterface $endedAt = null): void
    {
        $endedAt = $endedAt ?: now();

        if ($this->ended_at) {
            return; // already stopped
        }

        $this->ended_at = $endedAt;

        $start = $this->started_at ?: now();
        $diffSeconds = max(0, $endedAt->getTimestamp() - $start->getTimestamp());

        // if controller already set seconds, keep it; otherwise compute
        if ((int) $this->seconds <= 0) {
            $this->seconds = $diffSeconds;
        }

        $this->save();
    }
}
