<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
            🕒 <span class="text-gray-800 dark:text-white">Shift List</span>
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100 border border-green-300 dark:border-green-600 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 text-right">
            <a href="{{ route('shifts.create') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 dark:text-white">
                ➕ <span class="text-white dark:text-white">Add New Shift</span>
            </a>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-900 shadow-md rounded">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">#</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Name</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Time</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Type</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Active</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Days</th>
                        <th class="px-4 py-2 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($shifts as $shift)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-100">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 text-sm text-blue-700 dark:text-blue-300 font-medium">
                                {{ $shift->name }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">
                                {{ $shift->start_time }} - {{ $shift->end_time }}
                                @if ($shift->crosses_midnight)
                                    <span class="text-xs text-orange-500 dark:text-orange-300 ml-1">(crosses midnight)</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ ucfirst($shift->type ?? '-') }}
                            </td>
                            <td class="px-4 py-2 text-sm">
                                @if ($shift->is_active)
                                    <span class="text-green-600 bg-green-100 dark:bg-green-800 dark:text-green-300 px-2 py-1 text-xs rounded">Active</span>
                                @else
                                    <span class="text-red-600 bg-red-100 dark:bg-red-800 dark:text-red-300 px-2 py-1 text-xs rounded">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                @if ($shift->week_days)
                                    <span class="text-gray-500 dark:text-gray-400">{{ implode(', ', $shift->week_days) }}</span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-right text-sm space-x-2">
                                <a href="{{ route('shifts.edit', $shift) }}"
                                   class="text-blue-600 hover:underline dark:text-blue-400 dark:hover:text-blue-300">Edit</a>
                                <form action="{{ route('shifts.destroy', $shift) }}" method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('Are you sure to delete this shift?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:underline dark:text-red-400 dark:hover:text-red-300">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 dark:text-gray-400 py-6">No shifts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4">
                <span class="text-gray-700 dark:text-gray-200">
                    {{ $shifts->links() }}
                </span>
            </div>
        </div>
    </div>
</x-app-layout>
