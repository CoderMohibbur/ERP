<x-app-layout>
    <x-success-message />

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Leads</h1>
        <a href="{{ route('leads.create') }}"
           class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
            + Add Lead
        </a>
    </div>

    {{-- Filters --}}
    <div class="mb-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
        <form method="GET" action="{{ route('leads.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3">
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Search</label>
                <input type="text" name="q" value="{{ $q ?? request('q') }}"
                       placeholder="Name/Phone/Email..."
                       class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Status</label>
                <select name="status"
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">All</option>
                    @foreach(($statuses ?? ['new','contacted','qualified','unqualified']) as $s)
                        <option value="{{ $s }}" @selected(($status ?? request('status')) === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Source</label>
                <select name="source"
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">All</option>
                    @foreach(($sources ?? ['whatsapp','facebook','website','referral']) as $src)
                        <option value="{{ $src }}" @selected(($source ?? request('source')) === $src)>{{ ucfirst($src) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Owner</label>
                <select name="owner_id"
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">All</option>
                    @foreach(($owners ?? []) as $u)
                        <option value="{{ $u->id }}" @selected((string)($ownerId ?? request('owner_id')) === (string)$u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Follow-up From</label>
                <input type="date" name="follow_up_from" value="{{ $followUpFrom ?? request('follow_up_from') }}"
                       class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Follow-up To</label>
                <input type="date" name="follow_up_to" value="{{ $followUpTo ?? request('follow_up_to') }}"
                       class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="md:col-span-6 flex flex-col sm:flex-row gap-3 sm:items-end sm:justify-between mt-2">
                <div class="w-full sm:w-48">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Per Page</label>
                    <select name="per_page"
                            class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @foreach([10,15,25,50,100] as $pp)
                            <option value="{{ $pp }}" @selected((int)($perPage ?? request('per_page', 15)) === $pp)>{{ $pp }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2 justify-end">
                    <a href="{{ route('leads.index') }}"
                       class="px-4 py-2 text-sm font-semibold rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 hover:dark:bg-gray-700 transition">
                        Reset
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
                        Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">#</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Phone</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Owner</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Next Follow-up</th>
                    <th class="px-6 py-3 text-right text-sm font-medium text-gray-600 dark:text-gray-300">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($leads as $lead)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ ($leads->firstItem() ?? 0) + $loop->index }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100 font-medium">
                            <a href="{{ route('leads.show', $lead) }}" class="hover:underline">
                                {{ $lead->name }}
                            </a>
                            @if($lead->source)
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Source: {{ $lead->source }}</div>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">{{ $lead->phone }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">{{ $lead->email ?? '—' }}</td>

                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if($lead->status === 'qualified') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300
                                @elseif($lead->status === 'unqualified') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300
                                @elseif($lead->status === 'contacted') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300
                                @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200 @endif">
                                {{ ucfirst($lead->status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ $lead->owner?->name ?? '—' }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ $lead->next_follow_up_at ? \Illuminate\Support\Carbon::parse($lead->next_follow_up_at)->format('Y-m-d') : '—' }}
                        </td>

                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('leads.show', $lead) }}"
                               class="text-gray-600 dark:text-gray-300 hover:text-gray-900 hover:dark:text-white font-medium">
                                View
                            </a>
                            <a href="{{ route('leads.edit', $lead) }}"
                               class="text-blue-500 hover:text-blue-700 font-medium">
                                Edit
                            </a>
                            <form action="{{ route('leads.destroy', $lead) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Are you sure?')"
                                        class="text-red-500 hover:text-red-700 font-medium">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            No leads found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4 px-4 pb-4">
            {{ $leads->links() }}
        </div>
    </div>
</x-app-layout>
