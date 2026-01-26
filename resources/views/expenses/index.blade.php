<x-app-layout>
    <x-success-message />

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Expenses</h1>
        <a href="{{ route('expenses.create') }}"
           class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
            + Add Expense
        </a>
    </div>

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">#</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Title</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Category</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Date</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Amount</th>
                    <th class="px-6 py-3 text-right text-sm font-medium text-gray-600 dark:text-gray-300">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse(($expenses ?? []) as $expense)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $expense->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $expense->category }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                            {{ $expense->expense_date ? \Illuminate\Support\Carbon::parse($expense->expense_date)->format('d M, Y') : '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                            {{ $expense->currency ?? 'BDT' }} {{ number_format((float)($expense->amount ?? 0), 2) }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('expenses.show', $expense->id) }}"
                               class="text-indigo-500 hover:text-indigo-700 font-medium">View</a>

                            <a href="{{ route('expenses.edit', $expense->id) }}"
                               class="text-blue-500 hover:text-blue-700 font-medium">Edit</a>

                            <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="inline">
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
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            No expenses found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4 px-4 pb-4">
            {{ method_exists(($expenses ?? null), 'links') ? $expenses->links() : '' }}
        </div>
    </div>
</x-app-layout>
