<x-app-layout>
    <x-success-message />

    @php
        use Illuminate\Support\Facades\Route;

        $statusMeta = [
            'backlog' => ['label' => 'Backlog', 'hint' => 'Not started'],
            'doing'   => ['label' => 'Doing',   'hint' => 'In progress'],
            'review'  => ['label' => 'Review',  'hint' => 'Needs review'],
            'done'    => ['label' => 'Done',    'hint' => 'Completed'],
            'blocked' => ['label' => 'Blocked', 'hint' => 'Stuck / Waiting'],
        ];

        $boardUrl = Route::has('projects.board') ? route('projects.board', $project) : url()->current();
        $projectsIndexUrl = Route::has('projects.index') ? route('projects.index') : '#';
        $projectShowUrl = Route::has('projects.show') ? route('projects.show', $project) : null;

        $tasksCreateUrl = Route::has('tasks.create')
            ? route('tasks.create', ['project_id' => $project->id])
            : null;
    @endphp

    <div class="mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <div class="text-2xl font-bold text-gray-800 dark:text-white">
                    Project Board
                </div>
                <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                    <span class="font-medium">Project:</span>
                    {{ $project->name ?? $project->title ?? ('#'.$project->id) }}
                    @if($projectShowUrl)
                        <a href="{{ $projectShowUrl }}" class="ml-2 text-blue-600 dark:text-blue-400 hover:underline">
                            (Details)
                        </a>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ $projectsIndexUrl }}"
                   class="px-4 py-2 bg-gray-200 text-gray-800 text-sm font-semibold rounded-lg hover:bg-gray-300 transition dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                    ← Projects
                </a>

                @if($tasksCreateUrl)
                    <a href="{{ $tasksCreateUrl }}"
                       class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
                        + Add Task
                    </a>
                @endif
            </div>
        </div>

        <div class="mt-4 bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <form method="GET" action="{{ $boardUrl }}" class="flex flex-col sm:flex-row sm:items-center gap-3">
                <input
                    type="text"
                    name="q"
                    value="{{ $q ?? '' }}"
                    placeholder="Search task by title or #id..."
                    class="w-full sm:flex-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500"
                />

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                    Search
                </button>

                @if(!empty($q))
                    <a href="{{ $boardUrl }}"
                       class="px-4 py-2 bg-gray-200 text-gray-800 text-sm font-semibold rounded-lg hover:bg-gray-300 transition dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                        Clear
                    </a>
                @endif
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <div class="flex gap-4 min-w-max pb-4">
            @foreach($statusMeta as $key => $meta)
                @php
                    $items = $grouped[$key] ?? collect();
                @endphp

                <div class="w-[320px] shrink-0">
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg shadow">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div class="font-semibold text-gray-800 dark:text-white">
                                    {{ $meta['label'] }}
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                                    {{ $items->count() }}
                                </span>
                            </div>
                            <div class="mt-1 text-xs text-gray-600 dark:text-gray-300">
                                {{ $meta['hint'] }}
                            </div>
                        </div>

                        <div class="p-3 space-y-3 max-h-[70vh] overflow-y-auto">
                            @forelse($items as $task)
                                @php
                                    $taskTitle = $task->title ?? $task->name ?? $task->task_name ?? ('Task #'.$task->id);
                                    $taskShowUrl = Route::has('tasks.show') ? route('tasks.show', $task) : null;
                                    $currentStatus = $task->status ?? 'backlog';
                                @endphp

                                <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 p-3 hover:shadow transition">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="min-w-0">
                                            @if($taskShowUrl)
                                                <a href="{{ $taskShowUrl }}"
                                                   class="font-semibold text-gray-900 dark:text-white hover:underline break-words">
                                                    {{ $taskTitle }}
                                                </a>
                                            @else
                                                <div class="font-semibold text-gray-900 dark:text-white break-words">
                                                    {{ $taskTitle }}
                                                </div>
                                            @endif

                                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                #{{ $task->id }}
                                            </div>
                                        </div>

                                        @if(!empty($task->priority))
                                            <span class="text-[11px] px-2 py-1 rounded bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-200">
                                                P{{ $task->priority }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-2 flex flex-wrap gap-2 text-xs">
                                        @if(!empty($task->due_date))
                                            <span class="px-2 py-1 rounded bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200">
                                                Due: {{ \Illuminate\Support\Carbon::parse($task->due_date)->format('d M, Y') }}
                                            </span>
                                        @endif

                                        @if(!empty($task->assigned_to) || !empty(optional($task->user)->name) || !empty(optional($task->assignee)->name))
                                            <span class="px-2 py-1 rounded bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200">
                                                @php
                                                    $assigneeName = optional($task->user)->name
                                                        ?? optional($task->assignee)->name
                                                        ?? ($task->assigned_to ?? null);
                                                @endphp
                                                {{ $assigneeName }}
                                            </span>
                                        @endif
                                    </div>

                                    <form method="POST" action="{{ route('tasks.status', $task) }}" class="mt-3">
                                        @csrf
                                        @method('PATCH')

                                        <select name="status"
                                            onchange="this.form.submit()"
                                            class="w-full px-3 py-2 text-sm border rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                                            @foreach($statusMeta as $sKey => $sMeta)
                                                <option value="{{ $sKey }}" @selected($currentStatus === $sKey)>
                                                    {{ $sMeta['label'] }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @if(($currentStatus === 'blocked') && !empty($task->blocked_reason))
                                            <div class="mt-2 text-xs text-red-600 dark:text-red-400">
                                                {{ $task->blocked_reason }}
                                            </div>
                                        @endif
                                    </form>
                                </div>
                            @empty
                                <div class="text-sm text-gray-600 dark:text-gray-300 p-3">
                                    No tasks
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
