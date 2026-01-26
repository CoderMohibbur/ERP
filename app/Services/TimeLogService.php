<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TimeLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TimeLogService
{
    public function start(Task $task, User $user, ?string $note = null): TimeLog
    {
        return DB::transaction(function () use ($task, $user, $note) {
            // Lock running rows to prevent race condition (2 tabs)
            $hasRunning = TimeLog::query()
                ->where('user_id', $user->id)
                ->whereNull('ended_at')
                ->lockForUpdate()
                ->exists();

            if ($hasRunning) {
                throw ValidationException::withMessages([
                    'timer' => 'You already have a running timer. Stop it first.',
                ]);
            }

            $log = new TimeLog();
            $log->task_id = $task->id;
            $log->user_id = $user->id;
            $log->started_at = now();
            $log->ended_at = null;
            $log->seconds = 0;
            $log->note = $note;
            $log->source = $log->source ?? 'manual';

            $log->save();

            return $log;
        });
    }

    public function stopRunning(User $user, ?Task $task = null, ?string $note = null): ?TimeLog
    {
        return DB::transaction(function () use ($user, $task, $note) {
            $q = TimeLog::query()
                ->where('user_id', $user->id)
                ->whereNull('ended_at')
                ->lockForUpdate();

            if ($task) {
                $q->where('task_id', $task->id);
            }

            $log = $q->first();
            if (!$log) {
                return null;
            }

            $endedAt = now();
            $seconds = max(0, $endedAt->diffInSeconds($log->started_at));

            $log->ended_at = $endedAt;
            $log->seconds = $seconds;
            if ($note !== null) {
                $log->note = $note;
            }

            $log->save();

            return $log;
        });
    }
}
