<x-app-layout>
    <x-success-message />
    <x-validation-errors />

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Deals Pipeline</h1>
        <div class="flex gap-2">
            <a href="{{ route('deals.index') }}"
               class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm font-semibold rounded-lg hover:bg-gray-50 hover:dark:bg-gray-700 transition">
                Deals List
            </a>
            <a href="{{ route('deals.create') }}"
               class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
                + Add Deal
            </a>
        </div>
    </div>

    {{-- Pipeline Filters --}}
    <div class="mb-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
        <form method="GET" action="{{ route('deals.pipeline') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3">
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Search</label>
                <input type="text" name="q" value="{{ $q ?? request('q') }}"
                       placeholder="Title..."
                       class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
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

            <div class="md:col-span-2 flex gap-2 items-end justify-end">
                <a href="{{ route('deals.pipeline') }}"
                   class="px-4 py-2 text-sm font-semibold rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 hover:dark:bg-gray-700 transition">
                    Reset
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
                    Apply
                </button>
            </div>
        </form>
    </div>

    {{-- 6 columns --}}
    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-4">
        @foreach(($stages ?? ['new','contacted','quoted','negotiating','won','lost']) as $stage)
            @php
                $items = $pipeline instanceof \Illuminate\Support\Collection
                    ? $pipeline->get($stage, collect())
                    : (is_array($pipeline ?? null) ? ($pipeline[$stage] ?? collect()) : collect());
            @endphp

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-bold text-gray-800 dark:text-white">{{ strtoupper($stage) }}</h2>
                    <span class="text-xs px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        {{ $items->count() }}
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse($items as $deal)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                <a href="{{ route('deals.show', $deal) }}" class="hover:underline">{{ $deal->title }}</a>
                            </div>

                            <div class="mt-1 text-xs text-gray-600 dark:text-gray-300 space-y-1">
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Value:</span>
                                    {{ $deal->value_estimated !== null ? number_format((float)$deal->value_estimated, 2) : '—' }}
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Lead/Client:</span>
                                    @if($deal->lead)
                                        <span class="px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700">{{ $deal->lead->name }}</span>
                                    @elseif($deal->client)
                                        <span class="px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700">{{ $deal->client->name }}</span>
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>

                            {{-- Stage change --}}
                            <form method="POST" action="{{ route('deals.stage', $deal) }}" class="mt-3 space-y-2">
                                @csrf

                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Move to</label>
                                    <select name="stage"
                                            class="w-full px-3 py-2 border rounded-lg text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        @foreach(($stages ?? ['new','contacted','quoted','negotiating','won','lost']) as $s)
                                            <option value="{{ $s }}" @selected($deal->stage === $s)>{{ ucfirst($s) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Lost reason (optional)</label>
                                    <input type="text" name="lost_reason" value=""
                                           placeholder="Only for lost"
                                           class="w-full px-3 py-2 border rounded-lg text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>

                                <button type="submit"
                                        class="w-full px-3 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                                    Update Stage
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            No deals in this stage.
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
