<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
            👨‍👩‍👧‍👦 Employee Dependents
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 border border-green-300 dark:border-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 text-right">
            <a href="{{ route('employee-dependents.create') }}"
               class="px-4 py-2 bg-blue-600 dark:bg-blue-700 text-white rounded hover:bg-blue-700 dark:hover:bg-blue-800">
                ➕ Add New Dependent
            </a>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-md rounded border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">#</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Name</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Employee</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Relation</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">DOB</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">NID</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Emergency</th>
                        <th class="px-4 py-2 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($dependents as $dependent)
                        <tr class="bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 text-sm font-medium text-blue-700 dark:text-blue-300">
                                {{ $dependent->name }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200">
                                {{ $dependent->employee->name ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ ucfirst($dependent->relation) }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200">
                                {{ optional($dependent->dob)->format('d M, Y') ?? '-' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-300">
                                {{ $dependent->nid_number ?? '-' }}
                            </td>
                            <td class="px-4 py-2 text-sm">
                                @if ($dependent->is_emergency_contact)
                                    <span class="text-green-700 dark:text-green-300 text-xs font-semibold">✅ Yes</span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-right text-sm space-x-2">
                                <a href="{{ route('employee-dependents.edit', $dependent) }}"
                                   class="text-blue-600 hover:underline dark:text-blue-400 dark:hover:text-blue-300">Edit</a>
                                <form action="{{ route('employee-dependents.destroy', $dependent) }}" method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('Are you sure to delete this dependent?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:underline dark:text-red-400 dark:hover:text-red-300">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 dark:text-gray-400 py-6 bg-white dark:bg-gray-900">
                                No dependents found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4 bg-white dark:bg-gray-900">
                {{ $dependents->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
