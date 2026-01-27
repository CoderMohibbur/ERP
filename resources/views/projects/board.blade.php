<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="text-xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $project->title ?? $project->name ?? ('Project #' . $project->id) }} — Board
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-300">
                    Kanban: Backlog / Doing / Review / Done
                </div>
            </div>

            <div class="flex items-center gap-2">
                @if(\Illuminate\Support\Facades\Route::has('projects.show'))
                    <a href="{{ route('projects.show', $project) }}"
                       class="px-3 py-2 rounded-md bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-sm">
                        ← Project
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="rounded-md bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-3 text-green-800 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-md bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-3 text-red-800 dark:text-red-200">
                    <div class="font-semibold mb-1">Please fix the following:</div>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Search --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-100 dark:border-gray-700 p-4">
                <form method="GET" action="">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input name="q" value="{{ $q ?? '' }}"
                               class="w-full sm:flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                               placeholder="Search tasks (title/note)..." />
                        <button class="px-4 py-2 rounded-md bg-gray-900 text-white dark:bg-gray-100 dark:text-gray-900">
                            Search
                        </button>
                        @if(!empty($q))
                            <a href="{{ request()->url() }}"
                               class="px-4 py-2 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-center">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @php
                $columns = [
                    'backlog' => 'Backlog',
                    'doing'   => 'Doing',
                    'review'  => 'Review',
                    'done'    => 'Done',
                ];

                $taskShowRoute = \Illuminate\Support\Facades\Route::has('tasks.show') ? 'tasks.show' : null;
                $taskStatusRoute = \Illuminate\Support\Facades\Route::has('tasks.status') ? 'tasks.status' : null;

                $legacyToBoard = function ($legacy) {
                    return match ($legacy) {
                        'pending'     => 'backlog',
                        'in_progress' => 'doing',
                        'completed'   => 'done',
                        'blocked'     => 'blocked',
                        default       => 'backlog',
                    };
                };
            @endphp

            {{-- Board --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @foreach($columns as $key => $label)
                    <div class="bg-gray-50 dark:bg-gray-900/30 rounded-lg border border-gray-200 dark:border-gray-700 p-3">
                        <div class="flex items-center justify-between mb-3">
                            <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $label }}</div>
                            <div class="text-xs px-2 py-1 rounded bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200">
                                {{ $counts[$key] ?? 0 }}
                            </div>
                        </div>

                        <div class="space-y-3">
                            @forelse(($groups[$key] ?? collect()) as $task)
                                @php
                                    $title = $task->title ?? $task->name ?? ('Task #' . $task->id);
                                    $due   = $task->due_date ?? $task->deadline ?? null;

                                    $boardStatus = $usesErpStatus
                                        ? ($task->erp_status ?? 'backlog')
                                        : $legacyToBoard($task->status ?? 'pending');

                                    if (!empty($task->is_blocked)) {
                                        $boardStatus = 'blocked';
                                    }
                                @endphp

                                <div class="rounded-lg bg-white dark:bg-gray-800 shadow border {{ !empty($task->is_blocked) ? 'border-red-300 dark:border-red-700' : 'border-gray-100 dark:border-gray-700' }} p-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <div class="font-semibold text-gray-900 dark:text-gray-100 truncate">
                                                @if($taskShowRoute)
                                                    <a href="{{ route($taskShowRoute, $task) }}" class="hover:underline">
                                                        {{ $title }}
                                                    </a>
                                                @else
                                                    {{ $title }}
                                                @endif
                                            </div>

                                            <div class="mt-1 text-xs text-gray-600 dark:text-gray-300 space-y-1">
                                                <div>
                                                    Assignee:
                                                    <span class="font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $task->assignee?->name ?? '—' }}
                                                    </span>
                                                </div>
                                                <div>
                                                    Due:
                                                    <span class="font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $due ? \Illuminate\Support\Carbon::parse($due)->toFormattedDateString() : '—' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="shrink-0">
                                            <span class="text-[11px] px-2 py-1 rounded border
                                                {{ $boardStatus === 'done' ? 'bg-green-50 text-green-800 border-green-200 dark:bg-green-900/20 dark:text-green-200 dark:border-green-800' : '' }}
                                                {{ $boardStatus === 'review' ? 'bg-yellow-50 text-yellow-800 border-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-200 dark:border-yellow-800' : '' }}
                                                {{ $boardStatus === 'doing' ? 'bg-blue-50 text-blue-800 border-blue-200 dark:bg-blue-900/20 dark:text-blue-200 dark:border-blue-800' : '' }}
                                                {{ $boardStatus === 'backlog' ? 'bg-gray-50 text-gray-800 border-gray-200 dark:bg-gray-900/20 dark:text-gray-200 dark:border-gray-700' : '' }}
                                                {{ $boardStatus === 'blocked' ? 'bg-red-50 text-red-800 border-red-200 dark:bg-red-900/20 dark:text-red-200 dark:border-red-800' : '' }}
                                            ">
                                                {{ strtoupper($boardStatus) }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Status update --}}
                                    @if($taskStatusRoute)
                                        <form method="POST" action="{{ route($taskStatusRoute, $task) }}" class="mt-3 space-y-2">
                                            @csrf

                                            <div class="flex gap-2">
                                                <select name="status"
                                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 text-sm">
                                                    @foreach(['backlog'=>'Backlog','doing'=>'Doing','review'=>'Review','done'=>'Done','blocked'=>'Blocked'] as $sv => $sl)
                                                        <option value="{{ $sv }}" @selected($boardStatus === $sv)>{{ $sl }}</option>
                                                    @endforeach
                                                </select>

                                                <button type="submit"
                                                        class="px-3 py-2 rounded-md bg-gray-900 text-white dark:bg-gray-100 dark:text-gray-900 text-sm">
                                                    Update
                                                </button>
                                            </div>

                                            <input type="text" name="blocked_reason"
                                                   value="{{ $task->blocked_reason ?? '' }}"
                                                   class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 text-sm"
                                                   placeholder="Blocked reason (required if status=Blocked)" />
                                        </form>
                                    @endif
                                </div>
                            @empty
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    No tasks.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-xs text-gray-500 dark:text-gray-400">
                Note: Spec অনুযায়ী board 4 columns (Backlog/Doing/Review/Done)। “Blocked” টাস্কগুলো Doing কলামে লাল হাইলাইট হিসেবে দেখানো হচ্ছে।
            </div>

        </div>
    </div>
</x-app-layout>
