<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
            🧠 Employee Skill Assignments
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 border border-green-300 dark:border-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 text-right">
            <a href="{{ route('employee-skills.create') }}"
               class="px-4 py-2 bg-blue-600 dark:bg-blue-700 text-white rounded hover:bg-blue-700 dark:hover:bg-blue-800 transition">
                ➕ Assign New Skill
            </a>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-md rounded">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">#</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Employee</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Skill</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Proficiency</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Assigned By</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Notes</th>
                        <th class="px-4 py-2 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($skills as $skill)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-100">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 text-sm text-blue-700 dark:text-blue-300">
                                {{ $skill->employee->name ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-200">
                                {{ $skill->skill->name ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-center">
                                @if($skill->proficiency_level)
                                    <span class="text-sm px-2 py-1 rounded bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200">
                                        {{ $skill->proficiency_level }}/10
                                    </span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">N/A</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">
                                {{ $skill->assigner->name ?? 'System' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ $skill->notes ? Str::limit($skill->notes, 40) : '-' }}
                            </td>
                            <td class="px-4 py-2 text-right text-sm space-x-2">
                                <a href="{{ route('employee-skills.edit', $skill) }}"
                                   class="text-blue-600 hover:underline dark:text-blue-400 dark:hover:text-blue-300 transition">Edit</a>
                                <form action="{{ route('employee-skills.destroy', $skill) }}"
                                      method="POST" class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to remove this skill?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline dark:text-red-400 dark:hover:text-red-300 transition">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 dark:text-gray-400 py-6">
                                No skill assignments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4">
                {{ $skills->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
