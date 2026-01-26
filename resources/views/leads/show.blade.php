<x-app-layout>
    <x-success-message />

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Lead Details</h1>
        <div class="flex gap-2">
            <a href="{{ route('leads.edit', $lead) }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                Edit
            </a>
            <a href="{{ route('leads.index') }}"
               class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm font-semibold rounded-lg hover:bg-gray-50 hover:dark:bg-gray-700 transition">
                Back
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Name</div>
                <div class="text-gray-900 dark:text-white font-semibold">{{ $lead->name }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Phone</div>
                <div class="text-gray-900 dark:text-white">{{ $lead->phone }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Email</div>
                <div class="text-gray-900 dark:text-white">{{ $lead->email ?? '—' }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Source</div>
                <div class="text-gray-900 dark:text-white">{{ $lead->source ?? '—' }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Status</div>
                <div class="text-gray-900 dark:text-white font-semibold">{{ ucfirst($lead->status) }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Owner</div>
                <div class="text-gray-900 dark:text-white">{{ $lead->owner?->name ?? '—' }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Next Follow-up</div>
                <div class="text-gray-900 dark:text-white">
                    {{ $lead->next_follow_up_at ? \Illuminate\Support\Carbon::parse($lead->next_follow_up_at)->format('Y-m-d') : '—' }}
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Created</div>
                <div class="text-gray-900 dark:text-white">
                    {{ $lead->created_at ? $lead->created_at->format('Y-m-d H:i') : '—' }}
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <form action="{{ route('leads.destroy', $lead) }}" method="POST">
                @csrf @method('DELETE')
                <button onclick="return confirm('Are you sure?')"
                        class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition">
                    Delete Lead
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
