<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
            🕘 Employee History
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-200 rounded transition-colors duration-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 text-right">
            <a href="{{ route('employee-histories.create') }}"
               class="px-4 py-2 bg-green-600 dark:bg-green-700 text-white rounded hover:bg-green-700 dark:hover:bg-green-800 transition-colors duration-300">
                ➕ Add History
            </a>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded transition-colors duration-300">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">#</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Employee</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Designation</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Department</th>
                        <th class="px-4 py-2 text-sm font-semibold text-center text-gray-700 dark:text-gray-200">From</th>
                        <th class="px-4 py-2 text-sm font-semibold text-center text-gray-700 dark:text-gray-200">To</th>
                        <th class="px-4 py-2 text-sm font-semibold text-center text-gray-700 dark:text-gray-200">Type</th>
                        <th class="px-4 py-2 text-sm font-semibold text-right text-gray-700 dark:text-gray-200">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($histories as $history)
                        <tr class="bg-white dark:bg-gray-800 transition-colors duration-300">
                            <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">{{ $history->employee->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">{{ $history->designation->title ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">{{ $history->department->name ?? '-' }}</td>
                            <td class="px-4 py-2 text-sm text-center text-gray-800 dark:text-gray-100">{{ $history->effective_from->format('Y-m-d') }}</td>
                            <td class="px-4 py-2 text-sm text-center text-gray-800 dark:text-gray-100">
                                {{ $history->effective_to ? $history->effective_to->format('Y-m-d') : 'Present' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-center capitalize text-gray-800 dark:text-gray-100">{{ $history->change_type }}</td>
                            <td class="px-4 py-2 text-sm text-right space-x-2">
                                <a href="{{ route('employee-histories.edit', $history->id) }}"
                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-300">
                                    Edit
                                </a>
                                <form action="{{ route('employee-histories.destroy', $history->id) }}"
                                      method="POST" class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this history?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-300">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 dark:text-gray-400 py-6 transition-colors duration-300">No employee history found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4">
                {{ $histories->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
