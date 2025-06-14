<x-app-layout>
    <div class="max-w-xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Edit Department</h2>

        <x-validation-errors />

        <form method="POST" action="{{ route('departments.update', $department->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label for="name" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Department Name
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $department->name) }}" required
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('departments.index') }}"
                    class="mr-3 text-gray-600 dark:text-gray-300 hover:underline">
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
