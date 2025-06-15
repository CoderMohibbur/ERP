<x-app-layout>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Tasks</h1>
        <a href="{{ route('tasks.create') }}"
            class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
            ➕ Add Task
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 text-green-700 bg-green-100 border border-green-300 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-200">#</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-200">Title</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-200">Project</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-200">Assignee</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-200">Status</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-200">Progress</th>
                    <th class="px-4 py-2 text-right font-medium text-gray-700 dark:text-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($tasks as $task)
                    <tr>
                        <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $task->title }}</td>
                        <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $task->project->title ?? '-' }}</td>
                        <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $task->assignee->name ?? '-' }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs font-semibold
        @class([
            'bg-yellow-100 text-yellow-800 dark:bg-yellow-300 dark:text-yellow-900' =>
                $task->status === 'pending',
            'bg-blue-100 text-blue-800 dark:bg-blue-300 dark:text-blue-900' =>
                $task->status === 'in_progress',
            'bg-green-100 text-green-800 dark:bg-green-300 dark:text-green-900' =>
                $task->status === 'completed',
            'bg-red-100 text-red-800 dark:bg-red-300 dark:text-red-900' =>
                $task->status === 'blocked',
        ])">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </td>

                        <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $task->progress }}%</td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('tasks.edit', $task->id) }}"
                                class="text-blue-500 hover:underline">Edit</a>
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline-block"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline ml-2">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">No tasks
                            found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4 px-4">
            {{ $tasks->links() }}
        </div>
    </div>
</x-app-layout>
