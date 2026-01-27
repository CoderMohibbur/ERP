<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TimeLog;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TimeLogService
{
    public function start(Task $task, User $user, ?string $note = null): TimeLog
    {
        try {
            return DB::transaction(function () use ($task, $user, $note) {
                // Serialize by user row lock to avoid race condition (tab-1/tab-2 start together)
                User::query()->whereKey($user->id)->lockForUpdate()->first();

                $hasRunning = TimeLog::query()
                    ->where('user_id', $user->id)
                    ->whereNull('ended_at')
                    ->lockForUpdate()
                    ->exists();

                if ($hasRunning) {
                    throw ValidationException::withMessages([
                        'timer' => 'You already have a running timer.',
                    ]);
                }

                return TimeLog::query()->create([
                    'task_id'    => $task->id,
                    'user_id'    => $user->id,
                    'started_at' => now(),
                    'ended_at'   => null,
                    'seconds'    => 0,
                    'source'     => 'task_timer',
                    'note'       => $note,
                ]);
            }, 3);
        } catch (QueryException $e) {
            // DB-level unique guard hit (hard guarantee)
            if ($this->isOneRunningConstraintViolation($e)) {
                throw ValidationException::withMessages([
                    'timer' => 'You already have a running timer.',
                ]);
            }
            throw $e;
        }
    }

    public function stopRunning(User $user, Task $task, ?string $note = null): ?TimeLog
    {
        return DB::transaction(function () use ($user, $task, $note) {
            // Serialize stop operations for same user
            User::query()->whereKey($user->id)->lockForUpdate()->first();

            $log = TimeLog::query()
                ->where('user_id', $user->id)
                ->where('task_id', $task->id)
                ->whereNull('ended_at')
                ->lockForUpdate()
                ->first();

            if (!$log) {
                return null;
            }

            $end = now();
            $seconds = Carbon::parse($log->started_at)->diffInSeconds($end);

            $log->ended_at = $end;
            $log->seconds  = $seconds;

            if ($note !== null && $note !== '') {
                $log->note = $note;
            }

            $log->save();

            return $log;
        }, 3);
    }

    private function isOneRunningConstraintViolation(QueryException $e): bool
    {
        // MySQL duplicate unique = SQLSTATE 23000
        if ((string) $e->getCode() !== '23000') {
            return false;
        }

        $msg = $e->getMessage();

        return str_contains($msg, 'time_logs_one_running_per_user')
            || str_contains($msg, 'Duplicate entry');
    }
}
