<x-app-layout>
    <x-success-message />

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Deals</h1>
        <div class="flex gap-2">
            <a href="{{ route('deals.pipeline') }}"
               class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm font-semibold rounded-lg hover:bg-gray-50 hover:dark:bg-gray-700 transition">
                Pipeline
            </a>
            <a href="{{ route('deals.create') }}"
               class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
                + Add Deal
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="mb-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
        <form method="GET" action="{{ route('deals.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3">
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Search</label>
                <input type="text" name="q" value="{{ $q ?? request('q') }}"
                       placeholder="Title..."
                       class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Stage</label>
                <select name="stage"
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">All</option>
                    @foreach(($stages ?? ['new','contacted','quoted','negotiating','won','lost']) as $s)
                        <option value="{{ $s }}" @selected(($stage ?? request('stage')) === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Lead</label>
                <select name="lead_id"
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">All</option>
                    @foreach(($leads ?? []) as $l)
                        <option value="{{ $l->id }}" @selected((string)($leadId ?? request('lead_id')) === (string)$l->id)>{{ $l->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Client</label>
                <select name="client_id"
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">All</option>
                    @foreach(($clients ?? []) as $c)
                        <option value="{{ $c->id }}" @selected((string)($clientId ?? request('client_id')) === (string)$c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Close From</label>
                <input type="date" name="close_from" value="{{ $closeFrom ?? request('close_from') }}"
                       class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Close To</label>
                <input type="date" name="close_to" value="{{ $closeTo ?? request('close_to') }}"
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
                    <a href="{{ route('deals.index') }}"
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
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Title</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Stage</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Lead / Client</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Value</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Probability</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Expected Close</th>
                    <th class="px-6 py-3 text-right text-sm font-medium text-gray-600 dark:text-gray-300">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($deals as $deal)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ ($deals->firstItem() ?? 0) + $loop->index }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100 font-medium">
                            <a href="{{ route('deals.show', $deal) }}" class="hover:underline">{{ $deal->title }}</a>
                        </td>

                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if($deal->stage === 'won') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300
                                @elseif($deal->stage === 'lost') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300
                                @elseif(in_array($deal->stage, ['quoted','negotiating'])) bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300
                                @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200 @endif">
                                {{ ucfirst($deal->stage) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                            @if($deal->lead)
                                <div><span class="text-xs text-gray-500 dark:text-gray-400">Lead:</span> {{ $deal->lead->name }}</div>
                            @endif
                            @if($deal->client)
                                <div><span class="text-xs text-gray-500 dark:text-gray-400">Client:</span> {{ $deal->client->name }}</div>
                            @endif
                            @if(!$deal->lead && !$deal->client)
                                —
                            @endif
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ $deal->value_estimated !== null ? number_format((float)$deal->value_estimated, 2) : '—' }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ $deal->probability !== null ? ((int)$deal->probability . '%') : '—' }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ $deal->expected_close_date ? \Illuminate\Support\Carbon::parse($deal->expected_close_date)->format('Y-m-d') : '—' }}
                        </td>

                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('deals.show', $deal) }}"
                               class="text-gray-600 dark:text-gray-300 hover:text-gray-900 hover:dark:text-white font-medium">
                                View
                            </a>
                            <a href="{{ route('deals.edit', $deal) }}"
                               class="text-blue-500 hover:text-blue-700 font-medium">
                                Edit
                            </a>
                            <form action="{{ route('deals.destroy', $deal) }}" method="POST" class="inline">
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
                            No deals found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4 px-4 pb-4">
            {{ $deals->links() }}
        </div>
    </div>
</x-app-layout>
