<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
            🏷️ Skill List
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100 border border-green-300 dark:border-green-600 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 text-right">
            <a href="{{ route('skills.create') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                ➕ Add New Skill
            </a>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-900 shadow-md rounded">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">#</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Name</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Category</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Created</th>
                        <th class="px-4 py-2 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($skills as $skill)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 text-sm text-blue-700 dark:text-blue-300 font-medium">
                                {{ $skill->name }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ $skill->category ?? '-' }}
                            </td>
                            <td class="px-4 py-2 text-sm">
                                @if ($skill->is_active)
                                    <span class="text-green-600 bg-green-100 dark:bg-green-800 dark:text-green-300 px-2 py-1 text-xs rounded">Active</span>
                                @else
                                    <span class="text-red-600 bg-red-100 dark:bg-red-800 dark:text-red-300 px-2 py-1 text-xs rounded">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ $skill->created_at->format('Y-m-d') }}
                            </td>
                            <td class="px-4 py-2 text-right text-sm space-x-2">
                                <a href="{{ route('skills.edit', $skill) }}"
                                   class="text-blue-600 hover:underline dark:text-blue-400">Edit</a>
                                <form action="{{ route('skills.destroy', $skill) }}"
                                      method="POST" class="inline-block"
                                      onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline dark:text-red-400">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 dark:text-gray-400 py-6">No skills found.</td>
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
