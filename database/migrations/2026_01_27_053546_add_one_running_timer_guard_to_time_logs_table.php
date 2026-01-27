<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('time_logs')) {
            return;
        }

        // 1) Data safety: if any user already has multiple running timers, auto-stop extras.
        $userIds = DB::table('time_logs')
            ->whereNull('deleted_at')
            ->whereNull('ended_at')
            ->select('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('user_id');

        $hasSeconds   = Schema::hasColumn('time_logs', 'seconds');
        $hasUpdatedAt = Schema::hasColumn('time_logs', 'updated_at');

        foreach ($userIds as $userId) {
            $runningIds = DB::table('time_logs')
                ->whereNull('deleted_at')
                ->whereNull('ended_at')
                ->where('user_id', $userId)
                ->orderByDesc('started_at')
                ->pluck('id')
                ->values();

            // keep latest, stop the rest
            $keepId = $runningIds->shift();
            if (!$keepId || $runningIds->isEmpty()) {
                continue;
            }

            $now = now();

            $update = [
                'ended_at' => $now,
            ];

            if ($hasSeconds) {
                // compute seconds from started_at → now (MySQL compatible)
                $update['seconds'] = DB::raw("TIMESTAMPDIFF(SECOND, started_at, '" . $now->toDateTimeString() . "')");
            }

            if ($hasUpdatedAt) {
                $update['updated_at'] = $now;
            }

            DB::table('time_logs')
                ->whereIn('id', $runningIds->all())
                ->update($update);
        }

        // 2) Add generated guard column (NULL for non-running, 1 for running).
        Schema::table('time_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('time_logs', 'running_guard')) {
                $table->unsignedTinyInteger('running_guard')
                    ->nullable()
                    ->storedAs("CASE WHEN ended_at IS NULL AND deleted_at IS NULL THEN 1 ELSE NULL END");
            }
        });

        // 3) Unique guarantee: one running per user.
        // Idempotent-safe: try, ignore if already exists.
        try {
            Schema::table('time_logs', function (Blueprint $table) {
                $table->unique(['user_id', 'running_guard'], 'time_logs_one_running_per_user');
            });
        } catch (\Throwable $e) {
            // If it already exists (or DB doesn't support re-adding), ignore.
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('time_logs')) {
            return;
        }

        try {
            Schema::table('time_logs', function (Blueprint $table) {
                $table->dropUnique('time_logs_one_running_per_user');
            });
        } catch (\Throwable $e) {
            // ignore
        }

        Schema::table('time_logs', function (Blueprint $table) {
            if (Schema::hasColumn('time_logs', 'running_guard')) {
                $table->dropColumn('running_guard');
            }
        });
    }
};
