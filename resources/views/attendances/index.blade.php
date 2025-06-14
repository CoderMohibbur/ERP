<x-app-layout>
    <div>
        <x-success-message />
    </div>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Attendances</h1>
        <a href="{{ route('attendances.create') }}"
           class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
            + Add Attendance
        </a>
    </div>

    {{-- Optionally: Filters --}}
    {{-- <x-attendance-filters :employees="$employees" /> --}}

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    @foreach (['#', 'Employee', 'Date', 'Status', 'Note', 'Actions'] as $label)
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ $label }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($attendances as $attendance)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                            {{ $attendance->employee->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                            {{ $attendance->date->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @php
                                $statusColor = match($attendance->status) {
                                    'present' => 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-white',
                                    'late' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-white',
                                    'absent' => 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-white',
                                    'leave' => 'bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-white',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-white',
                                };
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded {{ $statusColor }}">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                            {{ $attendance->note ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('attendances.edit', $attendance->id) }}"
                               class="text-blue-500 hover:text-blue-700 dark:hover:text-blue-300 font-medium">Edit</a>
                            <form action="{{ route('attendances.destroy', $attendance->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Are you sure?')"
                                        class="text-red-500 hover:text-red-700 dark:hover:text-red-400 font-medium">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            No attendance records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4 px-4">
            {{ $attendances->links() }}
        </div>
    </div>
</x-app-layout>
