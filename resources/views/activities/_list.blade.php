@php
    $activities = $activities ?? collect();
@endphp

<div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-100 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">When</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Subject</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Type</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Next</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Status</th>
                <th class="px-4 py-3 text-right text-sm font-medium text-gray-600 dark:text-gray-300">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($activities as $activity)
                @php
                    $isDone = ($activity->status === 'done');
                @endphp

                <tr>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                        {{ optional($activity->activity_at)->format('d M Y, h:i A') }}
                    </td>

                    <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-100">
                        <div class="font-medium">{{ $activity->subject }}</div>
                        @if(!empty($activity->body))
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                                {{ $activity->body }}
                            </div>
                        @endif
                        <div class="text-xs text-gray-400 mt-1">
                            Actor: {{ optional($activity->actor)->name ?? ('User#'.$activity->actor_id) }}
                        </div>
                    </td>

                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                        {{ ucfirst($activity->type) }}
                    </td>

                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                        @if($activity->next_follow_up_at)
                            {{ $activity->next_follow_up_at->format('d M Y, h:i A') }}
                        @else
                            —
                        @endif
                    </td>

                    <td class="px-4 py-3 text-sm">
                        @if($isDone)
                            <span class="px-2 py-1 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 text-xs">Done</span>
                        @else
                            <span class="px-2 py-1 rounded bg-yellow-200 dark:bg-yellow-700 text-gray-900 dark:text-white text-xs">Open</span>
                        @endif
                    </td>

                    <td class="px-4 py-3 text-right space-x-2">
                        <form action="{{ route('activities.update', $activity) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="{{ $isDone ? 'open' : 'done' }}">
                            <button class="text-blue-500 hover:text-blue-700 font-medium text-sm">
                                {{ $isDone ? 'Reopen' : 'Mark Done' }}
                            </button>
                        </form>

                        <form action="{{ route('activities.destroy', $activity) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Are you sure?')"
                                    class="text-red-500 hover:text-red-700 font-medium text-sm">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                        No activities found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
