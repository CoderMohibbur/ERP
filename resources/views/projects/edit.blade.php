<x-app-layout>
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Edit Project</h2>

    <form action="{{ route('projects.update', $project->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            {{-- Title --}}
            <div>
                <label for="title" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $project->title) }}"
                       class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            {{-- Client --}}
            <div>
                <label for="client_id" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Client</label>
                <select name="client_id" id="client_id"
                        class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($clients as $id => $name)
                        <option value="{{ $id }}" {{ old('client_id', $project->client_id) == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Deadline --}}
            <div>
                <label for="deadline" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Deadline</label>
                <input type="date" name="deadline" id="deadline" value="{{ old('deadline', $project->deadline->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            {{-- Status --}}
            <div>
                <label for="status" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" id="status"
                        class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="pending" {{ old('status', $project->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in progress" {{ old('status', $project->status) == 'in progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            {{-- Description --}}
            <div class="sm:col-span-2">
                <label for="description" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $project->description) }}</textarea>
            </div>
        </div>

        <div class="flex justify-end items-center mt-6">
            <a href="{{ route('projects.index') }}"
               class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Update
            </button>
        </div>
    </form>
</x-app-layout>
