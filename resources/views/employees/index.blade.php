<x-app-layout>
    <div>
        <x-success-message />
    </div>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Employees</h1>
        <a href="{{ route('employees.create') }}"
           class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
            + Add Employee
        </a>
    </div>

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    @foreach(['#', 'Name', 'Email', 'Department', 'Designation', 'Join Date', 'Photo', 'Actions'] as $col)
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ $col }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($employees as $employee)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $employee->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $employee->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $employee->department->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $employee->designation->name ?? '-' }}</td>
                        {{-- model a cast kora ase string format --}}
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $employee->join_date->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                            @if ($employee->photo)
                                <img src="{{ asset('storage/' . $employee->photo) }}" alt="Photo"
                                     class="h-10 w-10 rounded-full object-cover">
                            @else
                                <span class="text-gray-400 dark:text-gray-100">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('employees.edit', $employee->id) }}"
                               class="text-blue-500 hover:text-blue-700 dark:hover:text-blue-300 font-medium">Edit</a>
                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="inline">
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
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            No employees found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4 px-4">
            {{ $employees->links() }}
        </div>
    </div>
</x-app-layout>
