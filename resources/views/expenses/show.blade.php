<x-app-layout>
    <x-success-message />

    <div class="max-w-4xl mx-auto">
        <div class="flex items-start sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    {{ $expense->title }}
                </h1>
                <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                    Date:
                    <span class="font-medium text-gray-800 dark:text-gray-100">
                        {{ optional($expense->expense_date)->format('Y-m-d') ?? $expense->expense_date }}
                    </span>
                    <span class="mx-2">•</span>
                    Category:
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        {{ \Illuminate\Support\Str::headline($expense->category) }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('expenses.edit', $expense->id) }}"
                   class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                    Edit
                </a>

                <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to delete this expense?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition">
                        Delete
                    </button>
                </form>

                <a href="{{ route('expenses.index') }}"
                   class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-sm font-semibold rounded-lg hover:bg-gray-200 hover:dark:bg-gray-600 transition">
                    Back
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="text-sm text-gray-600 dark:text-gray-300">Amount</div>
                <div class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ $expense->currency ?? 'BDT' }}
                    {{ number_format((float) $expense->amount, 2) }}
                </div>
            </div>

            <div class="p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Vendor</dt>
                        <dd class="mt-1 text-sm text-gray-800 dark:text-gray-100">
                            {{ $expense->vendor ?: '—' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Reference</dt>
                        <dd class="mt-1 text-sm text-gray-800 dark:text-gray-100">
                            {{ $expense->reference ?: '—' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Created By</dt>
                        <dd class="mt-1 text-sm text-gray-800 dark:text-gray-100">
                            {{ optional($expense->createdBy)->name ?? optional($expense->creator)->name ?? '—' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Created At</dt>
                        <dd class="mt-1 text-sm text-gray-800 dark:text-gray-100">
                            {{ optional($expense->created_at)->format('Y-m-d H:i') ?? '—' }}
                        </dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Notes</dt>
                        <dd class="mt-2 text-sm text-gray-800 dark:text-gray-100 whitespace-pre-line">
                            {{ $expense->notes ?: '—' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>
