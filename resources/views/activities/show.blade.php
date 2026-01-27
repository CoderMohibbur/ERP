<x-app-layout>
    <x-success-message />
    <x-validation-errors />

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Activity Details</h1>
            <p class="text-sm text-gray-500 dark:text-gray-300 mt-1">View / update / mark done</p>
        </div>

        <a href="{{ route('activities.index') }}"
            class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 text-sm font-semibold rounded-lg hover:opacity-90 transition">
            ← Back
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Details --}}
        <div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-lg shadow p-5">
            <div class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $activity->subject }}</div>

            <div class="mt-3 text-sm text-gray-700 dark:text-gray-200 space-y-2">
                <div><span class="font-semibold">Type:</span> {{ ucfirst($activity->type) }}</div>
                <div><span class="font-semibold">Status:</span> {{ ucfirst($activity->status) }}</div>
                <div><span class="font-semibold">Activity At:</span> {{ optional($activity->activity_at)->format('d M Y, h:i A') }}</div>
                <div>
                    <span class="font-semibold">Next Follow-up:</span>
                    {{ $activity->next_follow_up_at ? optional($activity->next_follow_up_at)->format('d M Y, h:i A') : '—' }}
                </div>
                <div><span class="font-semibold">Actor:</span> {{ $activity->actor?->name ?? '—' }}</div>
            </div>

            @if($activity->body)
                <div class="mt-4 text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line border-t dark:border-gray-700 pt-4">
                    {{ $activity->body }}
                </div>
            @endif

            <div class="mt-5 flex gap-2">
                {{-- Mark Done --}}
                <form method="POST" action="{{ route('activities.update', $activity) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="subject" value="{{ $activity->subject }}">
                    <input type="hidden" name="type" value="{{ $activity->type }}">
                    <input type="hidden" name="activity_at" value="{{ optional($activity->activity_at)->format('Y-m-d\TH:i') }}">
                    <input type="hidden" name="next_follow_up_at" value="{{ $activity->next_follow_up_at ? optional($activity->next_follow_up_at)->format('Y-m-d\TH:i') : '' }}">
                    <input type="hidden" name="body" value="{{ $activity->body }}">
                    <input type="hidden" name="status" value="done">

                    <button type="submit"
                        class="px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition">
                        Mark Done
                    </button>
                </form>

                {{-- Delete --}}
                <form method="POST" action="{{ route('activities.destroy', $activity) }}"
                    onsubmit="return confirm('Are you sure you want to delete this activity?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        {{-- Right: Edit/Update --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow p-5">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Update Activity</h2>

            <form method="POST" action="{{ route('activities.update', $activity) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Subject</label>
                    <input type="text" name="subject" value="{{ old('subject', $activity->subject) }}" required
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                        <select name="type" required
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
                            @foreach($types as $t)
                                <option value="{{ $t }}" @selected(old('type', $activity->type)===$t)>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select name="status" required
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
                            @foreach($statuses as $s)
                                <option value="{{ $s }}" @selected(old('status', $activity->status)===$s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Activity At</label>
                        <input type="datetime-local" name="activity_at"
                            value="{{ old('activity_at', optional($activity->activity_at)->format('Y-m-d\TH:i')) }}" required
                            class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Next Follow-up At</label>
                    <input type="datetime-local" name="next_follow_up_at"
                        value="{{ old('next_follow_up_at', $activity->next_follow_up_at ? optional($activity->next_follow_up_at)->format('Y-m-d\TH:i') : '') }}"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
                    <p class="text-xs text-gray-500 dark:text-gray-300 mt-1">Leave empty if no follow-up required.</p>
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Body / Notes</label>
                    <textarea name="body" rows="5"
                        class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">{{ old('body', $activity->body) }}</textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
