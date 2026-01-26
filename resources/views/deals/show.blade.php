<x-app-layout>
    <x-success-message />

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Deal Details</h1>
        <div class="flex gap-2">
            <a href="{{ route('deals.edit', $deal) }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                Edit
            </a>
            <a href="{{ route('deals.pipeline') }}"
               class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm font-semibold rounded-lg hover:bg-gray-50 hover:dark:bg-gray-700 transition">
                Pipeline
            </a>
            <a href="{{ route('deals.index') }}"
               class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm font-semibold rounded-lg hover:bg-gray-50 hover:dark:bg-gray-700 transition">
                Back
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <div class="text-xs text-gray-500 dark:text-gray-400">Title</div>
                <div class="text-gray-900 dark:text-white font-semibold">{{ $deal->title }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Stage</div>
                <div class="text-gray-900 dark:text-white font-semibold">{{ ucfirst($deal->stage) }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Estimated Value</div>
                <div class="text-gray-900 dark:text-white">
                    {{ $deal->value_estimated !== null ? number_format((float)$deal->value_estimated, 2) : '—' }}
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Probability</div>
                <div class="text-gray-900 dark:text-white">{{ $deal->probability !== null ? ((int)$deal->probability . '%') : '—' }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Expected Close</div>
                <div class="text-gray-900 dark:text-white">
                    {{ $deal->expected_close_date ? \Illuminate\Support\Carbon::parse($deal->expected_close_date)->format('Y-m-d') : '—' }}
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Lead</div>
                <div class="text-gray-900 dark:text-white">{{ $deal->lead?->name ?? '—' }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Client</div>
                <div class="text-gray-900 dark:text-white">{{ $deal->client?->name ?? '—' }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Won At</div>
                <div class="text-gray-900 dark:text-white">{{ $deal->won_at ? \Illuminate\Support\Carbon::parse($deal->won_at)->format('Y-m-d H:i') : '—' }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Lost At</div>
                <div class="text-gray-900 dark:text-white">{{ $deal->lost_at ? \Illuminate\Support\Carbon::parse($deal->lost_at)->format('Y-m-d H:i') : '—' }}</div>
            </div>

            <div class="md:col-span-2">
                <div class="text-xs text-gray-500 dark:text-gray-400">Lost Reason</div>
                <div class="text-gray-900 dark:text-white">{{ $deal->lost_reason ?? '—' }}</div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <form action="{{ route('deals.destroy', $deal) }}" method="POST">
                @csrf @method('DELETE')
                <button onclick="return confirm('Are you sure?')"
                        class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition">
                    Delete Deal
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
