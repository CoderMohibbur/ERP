<x-app-layout>
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Edit Project Note</h2>

    <form action="{{ route('project-notes.update', $projectNote->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4">
            <div>
                <label for="project_id" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Project</label>
                <select name="project_id" id="project_id"
                        class="w-full px-4 py-2 text-sm bg-white border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach($projects as $id => $title)
                        <option value="{{ $id }}" {{ old('project_id', $projectNote->project_id) == $id ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="note" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Note</label>
                <textarea name="note" id="note" rows="5"
                          class="w-full px-4 py-2 text-sm border bg-white border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('note', $projectNote->note) }}</textarea>
            </div>
        </div>

        <div class="flex justify-end items-center mt-6">
            <a href="{{ route('project-notes.index') }}" class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">Update</button>
        </div>
    </form>
</x-app-layout>