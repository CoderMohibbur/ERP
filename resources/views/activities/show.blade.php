<x-app-layout>
    <x-success-message />
    <x-validation-errors />

    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow p-6">

        {{-- Header --}}
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    {{ $activity->subject }}
                </h1>

                <div class="mt-2 flex flex-wrap gap-2 text-sm">
                    <span class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-700">
                        {{ ucfirst($activity->type) }}
                    </span>

                    <span
                        class="px-2 py-1 rounded
                        {{ $activity->status === 'done'
                            ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200'
                            : 'bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-200' }}">
                        {{ ucfirst($activity->status) }}
                    </span>
                </div>

                <div class="mt-3 text-sm text-gray-600 dark:text-gray-300 space-y-1">
                    <div>
                        Actor:
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ $activity->actor?->name ?? '—' }}
                        </span>
                    </div>

                    <div>
                        Activity At:
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ optional($activity->activity_at)->format('d M Y, h:i A') }}
                        </span>
                    </div>

                    <div>
                        Next Follow-up:
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ $activity->next_follow_up_at
                                ? optional($activity->next_follow_up_at)->format('d M Y, h:i A')
                                : '—' }}
                        </span>
                    </div>
                </div>
            </div>

            <a href="{{ route('activities.index') }}"
               class="px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg text-sm">
                ← Back
            </a>
        </div>

        {{-- Body --}}
        @if($activity->body)
            <div class="mt-6">
                <div class="text-sm font-semibold mb-1 text-gray-800 dark:text-gray-100">
                    Details
                </div>
                <div class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line">
                    {{ $activity->body }}
                </div>
            </div>
        @endif

        {{-- Actions --}}
        <div class="mt-6 flex flex-wrap gap-2 border-t pt-4">

            {{-- Mark Done --}}
            @if($activity->status !== \App\Models\Activity::STATUS_DONE)
                <form method="POST" action="{{ route('activities.update', $activity) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="done">
                    <button
                        class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm hover:bg-emerald-700">
                        Mark Done
                    </button>
                </form>
            @endif

            {{-- Edit --}}
            <a href="{{ route('activities.index', ['edit' => $activity->id]) }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">
                Edit
            </a>

            {{-- Delete --}}
            <form method="POST"
                  action="{{ route('activities.destroy', $activity) }}"
                  onsubmit="return confirm('Are you sure?');">
                @csrf
                @method('DELETE')
                <button
                    class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700">
                    Delete
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <div class="mt-6 text-xs text-gray-500 dark:text-gray-400">
            Related:
            {{ class_basename($activity->actionable_type) }}
            #{{ $activity->actionable_id }}
        </div>
    </div>
</x-app-layout>
w