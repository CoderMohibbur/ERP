<x-app-layout>
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Edit Task</h2>

    <x-validation-errors class="mb-4" />

    <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Title -->
            <div>
                <label for="title" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" required
                       class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600" />
            </div>

            <!-- Project -->
            <div>
                <label for="project_id" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Project</label>
                <select name="project_id" id="project_id" required
                        class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    @foreach($projects as $id => $title)
                        <option value="{{ $id }}" {{ (old('project_id', $task->project_id) == $id) ? 'selected' : '' }}>
                            {{ $title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Assignee -->
            <div>
                <label for="assigned_to" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Assign To</label>
                <select name="assigned_to" id="assigned_to"
                        class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    <option value="">None</option>
                    @foreach($employees as $id => $name)
                        <option value="{{ $id }}" {{ (old('assigned_to', $task->assigned_to) == $id) ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Priority -->
            <div>
                <label for="priority" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Priority</label>
                <select name="priority" id="priority"
                        class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    @foreach(['low', 'normal', 'high', 'urgent'] as $priority)
                        <option value="{{ $priority }}" {{ old('priority', $task->priority) == $priority ? 'selected' : '' }}>
                            {{ ucfirst($priority) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Status</label>
                <select name="status" id="status"
                        class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    @foreach(['pending', 'in_progress', 'completed', 'blocked'] as $status)
                        <option value="{{ $status }}" {{ old('status', $task->status) == $status ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Progress -->
            <div>
                <label for="progress" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Progress (%)</label>
                <input type="number" name="progress" id="progress" min="0" max="100"
                       value="{{ old('progress', $task->progress) }}"
                       class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600" />
            </div>

            <!-- Dates -->
            <div>
                <label for="start_date" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Start Date</label>
                <input type="date" name="start_date" id="start_date"
                       value="{{ old('start_date', $task->start_date?->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600" />
            </div>
            <div>
                <label for="due_date" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Due Date</label>
                <input type="date" name="due_date" id="due_date"
                       value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600" />
            </div>

            <!-- Hours -->
            <div>
                <label for="estimated_hours" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Estimated Hours</label>
                <input type="number" step="0.25" name="estimated_hours" id="estimated_hours"
                       value="{{ old('estimated_hours', $task->estimated_hours) }}"
                       class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600" />
            </div>

            <!-- Note -->
            <div class="md:col-span-2">
                <label for="note" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Note</label>
                <textarea name="note" id="note" rows="4"
                          class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">{{ old('note', $task->note) }}</textarea>
            </div>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('tasks.index') }}"
               class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Update Task
            </button>
        </div>
    </form>
</x-app-layout>
