<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Edit Project</h2>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('projects.update', $project->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $project->title) }}" required
                           class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600">
                </div>

                {{-- Project Code --}}
                <div>
                    <label for="project_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Project Code</label>
                    <input type="text" name="project_code" id="project_code" value="{{ old('project_code', $project->project_code) }}" required
                           class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600">
                </div>

                {{-- Client --}}
                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Client</label>
                    <select name="client_id" id="client_id" required
                            class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        @foreach($clients as $id => $name)
                            <option value="{{ $id }}" {{ old('client_id', $project->client_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Deadline --}}
                <div>
                    <label for="deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deadline</label>
                    <input type="date" name="deadline" id="deadline" value="{{ old('deadline', $project->deadline?->format('Y-m-d')) }}"
                           class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600">
                </div>

                {{-- Budget --}}
                <div>
                    <label for="budget" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Budget</label>
                    <input type="number" name="budget" id="budget" step="0.01" min="0" value="{{ old('budget', $project->budget) }}"
                           class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600">
                </div>

                {{-- Actual Cost --}}
                <div>
                    <label for="actual_cost" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Actual Cost</label>
                    <input type="number" name="actual_cost" id="actual_cost" step="0.01" min="0" value="{{ old('actual_cost', $project->actual_cost) }}"
                           class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600">
                </div>

                {{-- Priority --}}
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Priority</label>
                    <select name="priority" id="priority"
                            class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        @foreach(['low', 'medium', 'high', 'urgent'] as $level)
                            <option value="{{ $level }}" {{ old('priority', $project->priority) === $level ? 'selected' : '' }}>
                                {{ ucfirst($level) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select name="status" id="status"
                            class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        @foreach(['pending', 'in_progress', 'completed', 'cancelled'] as $state)
                            <option value="{{ $state }}" {{ old('status', $project->status) === $state ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $state)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Description --}}
            <div class="mt-4">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600">{{ old('description', $project->description) }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end items-center mt-6">
                <a href="{{ route('projects.index') }}"
                   class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500 dark:hover:text-red-400">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
