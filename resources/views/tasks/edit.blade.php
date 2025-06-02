<x-app-layout>
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Edit Task</h2>

    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            {{-- Project --}}
            <div>
                <label for="project_id" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Project</label>
                <select name="project_id" id="project_id"
                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach ($projects as $id => $title)
                        <option value="{{ $id }}"
                            {{ old('project_id', $task->project_id) == $id ? 'selected' : '' }}>{{ $title }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Title --}}
            <div>
                <label for="title" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}"
                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            {{-- Priority --}}
            <div>
                <label for="priority" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Priority</label>
                <select name="priority" id="priority"
                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low
                    </option>
                    <option value="normal" {{ old('priority', $task->priority) == 'normal' ? 'selected' : '' }}>Normal
                    </option>
                    <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High
                    </option>
                </select>
            </div>

            {{-- Assigned To --}}
            <div>
                <label for="assigned_to" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Assigned To</label>
                <select name="assigned_to" id="assigned_to"
                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">-- Select Employee --</option>
                    @foreach ($employees as $id => $name)
                        <option value="{{ $id }}"
                            {{ old('assigned_to', $task->assigned_to) == $id ? 'selected' : '' }}>{{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Progress --}}
            <div>
                <label for="progress" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Progress (%)</label>
                <input type="number" name="progress" id="progress" value="{{ old('progress', $task->progress) }}"
                    min="0" max="100"
                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            {{-- Due Date --}}
            <div>
                <label for="due_date" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Due Date</label>
                <input type="date" name="due_date" id="due_date"
                    value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            {{-- ✅ Insert here --}}
            <div class="sm:col-span-2">
                <label for="note" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Note</label>
                <textarea name="note" id="note" rows="4"
                    class="w-full mt-1 px-4 py-2 border rounded-md text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    placeholder="Enter additional notes or details...">{{ old('note', $task->note) }}</textarea>
            </div>
        </div>

        <div class="flex justify-end items-center mt-6">
            <a href="{{ route('tasks.index') }}"
                class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500">Cancel</a>
            <button type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">Update</button>
        </div>
    </form>
</x-app-layout>
