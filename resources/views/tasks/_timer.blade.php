<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Timer</h3>

            @if($runningTimeLog)
                @if($runningTimeLog->task_id === $task->id)
                    <p class="text-sm text-green-700 dark:text-green-300 mt-1">
                        ✅ Running on this task since
                        <span class="font-medium">{{ optional($runningTimeLog->started_at)->format('d M Y, h:i A') }}</span>
                    </p>
                @else
                    <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">
                        ⚠️ Another task timer is running (Task ID: {{ $runningTimeLog->task_id }}). Stop it first.
                    </p>
                @endif
            @else
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">No running timer.</p>
            @endif

            <div class="mt-3 text-sm text-gray-700 dark:text-gray-200">
                <div>
                    <span class="font-medium">Today on this task:</span>
                    {{ gmdate('H:i:s', (int) $todayTaskSeconds) }}
                </div>

                @if($runningTimeLog && $runningTimeLog->task_id === $task->id)
                    <div class="mt-1">
                        <span class="font-medium">Current session:</span>
                        <span id="timer-elapsed">00:00:00</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-2">
            {{-- Start --}}
            <form method="POST" action="{{ route('timer.start', $task) }}">
                @csrf
                <button type="submit"
                    @if($runningTimeLog && $runningTimeLog->task_id !== $task->id) disabled @endif
                    class="px-4 py-2 rounded-lg text-sm font-semibold
                           bg-green-600 text-white hover:bg-green-700 transition
                           disabled:opacity-50 disabled:cursor-not-allowed">
                    ▶ Start
                </button>
            </form>

            {{-- Stop (only if running on this task) --}}
            @if($runningTimeLog && $runningTimeLog->task_id === $task->id)
                <form method="POST" action="{{ route('timer.stop', $task) }}" class="flex items-center gap-2">
                    @csrf
                    <input type="text" name="note" placeholder="note (optional)"
                        class="px-3 py-2 rounded-lg text-sm border
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    />
                    <button type="submit"
                        class="px-4 py-2 rounded-lg text-sm font-semibold
                               bg-red-600 text-white hover:bg-red-700 transition">
                        ⏹ Stop
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if($runningTimeLog && $runningTimeLog->task_id === $task->id)
        <script>
            (function () {
                const startedAt = new Date(@json(optional($runningTimeLog->started_at)->toIso8601String()));
                const el = document.getElementById('timer-elapsed');

                function pad(n){ return String(n).padStart(2,'0'); }

                function tick() {
                    const now = new Date();
                    let diff = Math.max(0, Math.floor((now - startedAt) / 1000));
                    const h = Math.floor(diff / 3600);
                    diff = diff % 3600;
                    const m = Math.floor(diff / 60);
                    const s = diff % 60;
                    el.textContent = `${pad(h)}:${pad(m)}:${pad(s)}`;
                }

                tick();
                setInterval(tick, 1000);
            })();
        </script>
    @endif
</div>
