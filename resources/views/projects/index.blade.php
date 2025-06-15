<x-app-layout>
    <div class="mb-6">
        <x-success-message />
    </div>

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Projects</h1>

        <a href="{{ route('projects.create') }}"
           class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
            ➕ Add Project
        </a>
    </div>

    {{-- 🔍 Search --}}
    <form method="GET" action="{{ route('projects.index') }}" class="mb-4">
        <div class="flex flex-wrap gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by title or code..."
                   class="w-full sm:w-64 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600" />

            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Search
            </button>
        </div>
    </form>

    {{-- 📋 Table --}}
    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full table-auto divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">#</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Title</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Code</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Client</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Deadline</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Status</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Priority</th>
                    <th class="px-4 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                @forelse($projects as $project)
                    <tr>
                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $project->title }}</td>
                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $project->project_code }}</td>
                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $project->client->name ?? '—' }}</td>
                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $project->deadline?->format('d M Y') ?? '-' }}</td>
                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100 capitalize">{{ str_replace('_', ' ', $project->status) }}</td>
                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100 capitalize">{{ $project->priority }}</td>
                        <td class="px-4 py-2 text-right space-x-2">
                            <a href="{{ route('projects.edit', $project->id) }}"
                               class="text-blue-600 hover:underline">Edit</a>

                            <form method="POST" action="{{ route('projects.destroy', $project->id) }}"
                                  class="inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                            No projects found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- 📄 Pagination --}}
    <div class="mt-4">
        {{ $projects->links() }}
    </div>
</x-app-layout>
