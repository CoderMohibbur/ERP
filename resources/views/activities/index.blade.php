<x-app-layout>
    <x-success-message />

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Activities</h1>
            <p class="text-sm text-gray-500 dark:text-gray-300 mt-1">All follow-ups & logs in one place</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
        <form method="GET" action="{{ route('activities.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3">
            <input
                type="text"
                name="q"
                value="{{ $filters['q'] }}"
                placeholder="Search subject/body..."
                class="md:col-span-2 w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500"
            />

            <select name="type"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
                <option value="">All Types</option>
                @foreach($types as $t)
                    <option value="{{ $t }}" @selected($filters['type']===$t)>{{ ucfirst($t) }}</option>
                @endforeach
            </select>

            <select name="status"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
                <option value="">All Status</option>
                @foreach($statuses as $s)
                    <option value="{{ $s }}" @selected($filters['status']===$s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>

            <input type="date" name="from" value="{{ $filters['from'] }}"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500" />

            <input type="date" name="to" value="{{ $filters['to'] }}"
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500" />

            <label class="md:col-span-6 flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200 mt-1">
                <input type="checkbox" name="followup_due" value="1" @checked($filters['followup_due'])
                    class="rounded border-gray-300 text-green-600 focus:ring-green-500" />
                Only follow-up due (open + next_follow_up_at <= now)
            </label>

            <div class="md:col-span-6 flex items-center gap-2 mt-2">
                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
                    Filter
                </button>

                <a href="{{ route('activities.index') }}"
                    class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-100 text-sm font-semibold rounded-lg hover:opacity-90 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Subject</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Type</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Activity At</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Next Follow-up</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Actor</th>
                    <th class="px-6 py-3 text-right text-sm font-medium text-gray-600 dark:text-gray-300">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($activities as $activity)
                    @php
                        $typeBadge = 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-100';
                        if ($activity->type === 'call') $typeBadge = 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-200';
                        if ($activity->type === 'whatsapp') $typeBadge = 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-200';
                        if ($activity->type === 'meeting') $typeBadge = 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-200';
                        if ($activity->type === 'email') $typeBadge = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-200';

                        $statusBadge = $activity->status === 'done'
                            ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200'
                            : 'bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-200';
                    @endphp

                    <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                                <a href="{{ route('activities.show', $activity) }}" class="hover:underline">
                                    {{ $activity->subject }}
                                </a>
                            </div>
                            @if($activity->body)
                                <div class="text-xs text-gray-500 dark:text-gray-300 mt-1 line-clamp-1">
                                    {{ $activity->body }}
                                </div>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <span class="text-xs px-2 py-1 rounded {{ $typeBadge }}">
                                {{ ucfirst($activity->type) }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <span class="text-xs px-2 py-1 rounded {{ $statusBadge }}">
                                {{ ucfirst($activity->status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ optional($activity->activity_at)->format('d M Y, h:i A') }}
                        </td>

                        <td class="px-6 py-4 text-sm">
                            @if($activity->next_follow_up_at)
                                <span class="text-gray-800 dark:text-gray-100">
                                    {{ optional($activity->next_follow_up_at)->format('d M Y, h:i A') }}
                                </span>
                                @if($activity->status === 'open' && $activity->next_follow_up_at <= now())
                                    <span class="ml-2 text-xs px-2 py-1 rounded bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-200">
                                        Due
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ $activity->actor?->name ?? '—' }}
                        </td>

                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('activities.show', $activity) }}"
                                class="text-blue-500 hover:text-blue-700 font-medium">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                            No activities found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4 px-4 pb-4">
            {{ $activities->links() }}
        </div>
    </div>
</x-app-layout>
