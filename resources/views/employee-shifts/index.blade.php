<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
            👥 Employee Shift Assignments
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-100 border border-green-300 dark:border-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 text-right">
            <a href="{{ route('employee-shifts.create') }}"
               class="px-4 py-2 bg-blue-600 dark:bg-blue-500 text-white rounded hover:bg-blue-700 dark:hover:bg-blue-600">
                ➕ Assign New Shift
            </a>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-md rounded border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">#</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Employee</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Shift</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Date</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Time</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Verified</th>
                        <th class="px-4 py-2 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($shifts as $shift)
                        <tr class="bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-100">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 text-sm font-medium text-blue-600 dark:text-blue-300">
                                {{ $shift->employee->name ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">
                                {{ $shift->shift->name ?? 'N/A' }}
                                <br>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    ({{ ucfirst($shift->shift_type_cache ?? '-') }})
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">
                                {{ $shift->shift_date->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-2 text-sm">
                                <span class="text-gray-900 dark:text-gray-100">
                                    {{ $shift->effective_start_time }} - {{ $shift->effective_end_time }}
                                </span>
                                @if($shift->has_override)
                                    <span class="text-xs text-yellow-600 dark:text-yellow-400 ml-1">(override)</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm">
                                <span class="text-xs font-semibold px-2 py-1 rounded
                                    @if($shift->status === 'assigned') bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200
                                    @elseif($shift->status === 'completed') bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200
                                    @elseif($shift->status === 'cancelled') bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200
                                    @endif">
                                    {{ ucfirst($shift->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">
                                {{ $shift->verifiedBy->name ?? '-' }}
                            </td>
                            <td class="px-4 py-2 text-right text-sm space-x-2">
                                <a href="{{ route('employee-shifts.edit', $shift) }}"
                                   class="text-blue-600 hover:underline dark:text-blue-400">Edit</a>
                                <form action="{{ route('employee-shifts.destroy', $shift) }}" method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('Are you sure to delete this shift?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:underline dark:text-red-400">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 dark:text-gray-400 py-6 bg-white dark:bg-gray-900">
                                No shift assignments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4 bg-white dark:bg-gray-900">
                {{ $shifts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
