<x-app-layout>
    <x-success-message />
    <x-validation-errors />

    @php
        use Illuminate\Support\Carbon;

        // Robust title/desc fallbacks
        $taskTitle = $task->title ?? $task->name ?? $task->task_name ?? ('Task #' . $task->id);
        $taskDesc  = $task->description ?? $task->details ?? $task->note ?? null;

        // Project & assignee (based on your existing relations used in index: project, assignee)
        $projectTitle = optional($task->project)->title ?? optional($task->project)->name ?? null;
        $assigneeName = optional($task->assignee)->name ?? null;

        $status   = $task->status ?? null;
        $priority = $task->priority ?? null;

        // Due date fallback
        $dueDate = $task->due_date ?? $task->deadline ?? $task->end_date ?? null;

        $fmtSeconds = function ($seconds) {
            $seconds = max(0, (int) $seconds);
            $h = intdiv($seconds, 3600);
            $m = intdiv($seconds % 3600, 60);
            $s = $seconds % 60;
            return sprintf('%02d:%02d:%02d', $h, $m, $s);
        };

        $isRunningThis   = !empty($runningForThis);
        $hasRunningOther = !empty($runningOther);

        // Freeze day window once (for consistent overlap calculation in table)
        $now = now();
        $todayStart = $now->copy()->startOfDay();
        $todayEnd   = $now->copy()->endOfDay();
    @endphp

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                {{ $taskTitle }}
            </h1>

            <div class="mt-1 text-sm text-gray-600 dark:text-gray-300 flex flex-wrap gap-x-3 gap-y-1">
                <span class="inline-flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        #{{ $task->id }}
                    </span>
                </span>

                @if($projectTitle)
                    <span class="inline-flex items-center gap-2">
                        <span class="text-gray-400">•</span>
                        <span class="px-2 py-0.5 rounded bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-200">
                            Project: {{ $projectTitle }}
                        </span>
                    </span>
                @endif

                @if($assigneeName)
                    <span class="inline-flex items-center gap-2">
                        <span class="text-gray-400">•</span>
                        <span class="px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-200">
                            Assignee: {{ $assigneeName }}
                        </span>
                    </span>
                @endif

                @if($status)
                    <span class="inline-flex items-center gap-2">
                        <span class="text-gray-400">•</span>
                        <span class="px-2 py-0.5 rounded bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-200">
                            Status: {{ $status }}
                        </span>
                    </span>
                @endif

                @if($dueDate)
                    <span class="inline-flex items-center gap-2">
                        <span class="text-gray-400">•</span>
                        <span class="px-2 py-0.5 rounded bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-200">
                            Due: {{ Carbon::parse($dueDate)->format('d M Y') }}
                        </span>
                    </span>
                @endif

                @if(!is_null($priority) && $priority !== '')
                    <span class="inline-flex items-center gap-2">
                        <span class="text-gray-400">•</span>
                        <span class="px-2 py-0.5 rounded bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-200">
                            Priority: {{ $priority }}
                        </span>
                    </span>
                @endif
            </div>

            @if($taskDesc)
                <p class="mt-3 text-sm text-gray-700 dark:text-gray-200 leading-relaxed max-w-3xl">
                    {{ $taskDesc }}
                </p>
            @endif
        </div>

        <div class="flex gap-2">
            <a href="{{ route('tasks.index') }}"
               class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 hover:dark:bg-gray-700 transition">
                ← Back
            </a>

            @if(Route::has('tasks.edit'))
                <a href="{{ route('tasks.edit', $task->id) }}"
                   class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-semibold hover:bg-green-700 transition">
                    Edit
                </a>
            @endif
        </div>
    </div>

    {{-- Running other warning --}}
    @if($hasRunningOther)
        <div class="mb-6 p-4 rounded-lg border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/20">
            <div class="font-semibold text-amber-800 dark:text-amber-200">
                ⚠️ আপনার আরেকটি Task এ Timer চলছে (একসাথে ১টা running timer allowed)।
            </div>
            <div class="mt-1 text-sm text-amber-800/90 dark:text-amber-200/90">
                Running Task ID: <span class="font-semibold">#{{ $runningOther->task_id }}</span>
                @if(Route::has('tasks.show'))
                    — <a class="underline font-semibold" href="{{ route('tasks.show', $runningOther->task_id) }}">Open</a>
                @endif
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Today Total --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-100 dark:border-gray-700">
            <div class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                Today Total (এই Task এ)
            </div>

            <div class="mt-3 text-3xl font-extrabold text-gray-900 dark:text-white tabular-nums"
                 id="today-total"
                 data-base-seconds="{{ (int) $todayTotalSeconds }}"
                 @if($isRunningThis)
                    data-running="1"
                    data-started-at="{{ Carbon::parse($runningForThis->started_at)->toIso8601String() }}"
                 @else
                    data-running="0"
                 @endif
            >
                {{ $fmtSeconds($todayTotalSeconds) }}
            </div>

            @if($isRunningThis)
                <div class="mt-2 text-sm text-green-700 dark:text-green-300">
                    ⏱ Running since:
                    <span class="font-semibold">
                        {{ Carbon::parse($runningForThis->started_at)->format('d M Y, h:i A') }}
                    </span>
                </div>
            @else
                <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    এখন কোনো running session নেই।
                </div>
            @endif
        </div>

        {{-- Timer Controls --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-100 dark:border-gray-700 lg:col-span-2">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="text-lg font-bold text-gray-800 dark:text-white">Time Tracker</div>
                    <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                        Start/Stop দিয়ে এই Task এর কাজের সময় ট্র্যাক করুন।
                    </div>
                </div>

                <div class="flex gap-2">
                    @if(!$isRunningThis)
                        <form method="POST" action="{{ route('timer.start', $task->id) }}">
                            @csrf
                            <button type="submit"
                                @if($hasRunningOther) disabled @endif
                                class="px-4 py-2 rounded-lg text-sm font-semibold transition
                                    @if($hasRunningOther)
                                        bg-gray-200 text-gray-500 dark:bg-gray-700 dark:text-gray-400 cursor-not-allowed
                                    @else
                                        bg-green-600 text-white hover:bg-green-700
                                    @endif
                                ">
                                ▶ Start
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('timer.stop', $task->id) }}"
                              class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">
                            @csrf

                            <input type="text"
                                   name="note"
                                   placeholder="(optional) Work note..."
                                   class="w-full sm:w-64 px-3 py-2 border rounded-lg text-sm
                                          bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                          focus:ring-green-500 focus:border-green-500">

                            <button type="submit"
                                class="px-4 py-2 rounded-lg text-sm font-semibold bg-red-600 text-white hover:bg-red-700 transition">
                                ■ Stop
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-700">
                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400">Today Sessions</div>
                    <div class="mt-1 text-xl font-bold text-gray-800 dark:text-white">
                        {{ $todayLogs->count() }}
                    </div>
                </div>

                <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-700">
                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400">Running</div>
                    <div class="mt-1 text-xl font-bold {{ $isRunningThis ? 'text-green-700 dark:text-green-300' : 'text-gray-800 dark:text-white' }}">
                        {{ $isRunningThis ? 'Yes' : 'No' }}
                    </div>
                </div>

                <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-700">
                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400">Last Activity</div>
                    <div class="mt-1 text-sm font-semibold text-gray-800 dark:text-white">
                        @if($todayLogs->first())
                            {{ Carbon::parse($todayLogs->first()->started_at)->format('h:i A') }}
                        @else
                            —
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Today logs list --}}
    <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div class="text-lg font-bold text-gray-800 dark:text-white">Today Time Logs</div>
            <div class="text-sm text-gray-600 dark:text-gray-300">
                {{ now()->format('d M Y') }}
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Start</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">End</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Today Duration</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Note</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($todayLogs as $log)
                        @php
                            $rawStart = Carbon::parse($log->started_at);
                            $rawEnd   = $log->ended_at ? Carbon::parse($log->ended_at) : now();

                            // Show "today portion" so it matches todayTotalSeconds
                            $effectiveStart = $rawStart->greaterThan($todayStart) ? $rawStart : $todayStart;
                            $effectiveEnd   = $rawEnd->lessThan($todayEnd) ? $rawEnd : $todayEnd;

                            $durationSeconds = 0;
                            if ($effectiveEnd->greaterThan($effectiveStart)) {
                                $durationSeconds = $effectiveEnd->diffInSeconds($effectiveStart);
                            }

                            $startTitle = 'Actual: ' . $rawStart->format('d M Y, h:i A');
                            $endTitle   = 'Actual: ' . ($log->ended_at ? $rawEnd->format('d M Y, h:i A') : 'Running');
                        @endphp

                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200" title="{{ $startTitle }}">
                                {{ $effectiveStart->format('h:i A') }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200" title="{{ $endTitle }}">
                                {{ $log->ended_at ? $effectiveEnd->format('h:i A') : 'Running…' }}
                            </td>

                            <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white tabular-nums">
                                {{ $fmtSeconds($durationSeconds) }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                {{ $log->note ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                আজকের কোনো Time Log নেই।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Live update total when running --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const el = document.getElementById('today-total');
            if (!el) return;

            const baseSeconds = parseInt(el.dataset.baseSeconds || '0', 10);
            const running = (el.dataset.running === '1');
            const startedAt = el.dataset.startedAt ? new Date(el.dataset.startedAt) : null;

            function fmt(sec) {
                sec = Math.max(0, Math.floor(sec));
                const h = Math.floor(sec / 3600);
                const m = Math.floor((sec % 3600) / 60);
                const s = sec % 60;
                return String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
            }

            if (!running || !startedAt) return;

            const tick = () => {
                const now = new Date();
                const diff = Math.max(0, Math.floor((now - startedAt) / 1000));
                el.textContent = fmt(baseSeconds + diff);
            };

            tick();
            setInterval(tick, 1000);
        });
    </script>
</x-app-layout>
