<?php

namespace App\Observers;

use App\Models\TimeLog;
use Illuminate\Validation\ValidationException;

class TimeLogObserver
{
    public function creating(TimeLog $timeLog): void
    {
        $hasRunning = TimeLog::query()
            ->where('user_id', $timeLog->user_id)
            ->whereNull('ended_at')
            ->exists();

        if ($hasRunning) {
            throw ValidationException::withMessages([
                'timer' => 'Only one running timer is allowed per user.',
            ]);
        }
    }
}
