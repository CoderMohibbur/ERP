<x-app-layout>
    <x-success-message />

    <div class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Edit Expense</h2>

            <div class="flex items-center gap-3">
                <a href="{{ route('expenses.show', $expense->id) }}"
                   class="text-sm text-gray-600 dark:text-gray-300 hover:text-green-600 hover:dark:text-green-400">
                    View
                </a>
                <a href="{{ route('expenses.index') }}"
                   class="text-sm text-gray-600 dark:text-gray-300 hover:text-green-600 hover:dark:text-green-400">
                    ← Back
                </a>
            </div>
        </div>

        <x-validation-errors />

        <form method="POST" action="{{ route('expenses.update', $expense->id) }}">
            @csrf
            @method('PUT')

            @include('expenses._form', ['expense' => $expense])

            <div class="flex justify-end items-center mt-6">
                <a href="{{ route('expenses.index') }}"
                   class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-400">
                    Cancel
                </a>

                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
