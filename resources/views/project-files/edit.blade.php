<x-app-layout>
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Edit Project File</h2>

    <form action="{{ route('project-files.update', $projectFile->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            {{-- Project Select --}}
            <div>
                <label for="project_id" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Project</label>
                <select name="project_id" id="project_id" class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach ($projects as $id => $name)
                        <option value="{{ $id }}" {{ old('project_id', $projectFile->project_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- File Upload --}}
            <div>
                <label for="file" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Replace File</label>
                <input type="file" name="file" id="file" class="w-full text-sm border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <small class="text-gray-500 dark:text-gray-400">Leave empty to keep existing file.</small>
            </div>

            {{-- File Type --}}
            <div>
                <label for="file_type" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">File Type</label>
                <input type="text" name="file_type" id="file_type" value="{{ old('file_type', $projectFile->file_type) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
        </div>

        <div class="flex justify-end items-center mt-6">
            <a href="{{ route('project-files.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500 mr-4">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Update</button>
        </div>
    </form>
</x-app-layout>
