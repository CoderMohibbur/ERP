<x-app-layout>
    <div>
        <x-success-message />
    </div>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Employee Disciplinary Actions</h1>
        <a href="{{ route('employee-disciplinary-actions.create') }}"
           class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
            ➕ Record New Action
        </a>
    </div>

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">#</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Employee</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Violation</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Incident Date</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Action Taken</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Severity</th>
                    <th class="px-6 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($actions as $action)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $action->employee->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ ucfirst($action->violation_type) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $action->incident_date->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 text-sm capitalize text-gray-800 dark:text-gray-100">{{ str_replace('_', ' ', $action->action_taken) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $action->severity_level ?? '-' }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('employee-disciplinary-actions.edit', $action->id) }}"
                               class="text-blue-500 hover:text-blue-700 dark:hover:text-blue-300 font-medium">Edit</a>
                            <form action="{{ route('employee-disciplinary-actions.destroy', $action->id) }}"
                                  method="POST" class="inline-block"
                                  onsubmit="return confirm('Are you sure you want to delete this record?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-500 hover:text-red-700 dark:hover:text-red-400 font-medium">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            No disciplinary actions recorded.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4 px-4">
            {{ $actions->links() }}
        </div>
    </div>
</x-app-layout>
