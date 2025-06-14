<x-app-layout>
    <div class="max-w-xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Add Designation</h2>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('designations.store') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Designation Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       required
                       class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
            </div>

            <div class="mb-4">
                <label for="code" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Code <span class="text-red-500">*</span>
                </label>
                <input type="text" name="code" id="code" maxlength="10" value="{{ old('code') }}"
                       required
                       class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
            </div>

            <div class="mb-4">
                <label for="level" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Level (1 = CEO, 5 = Staff)
                </label>
                <input type="number" name="level" id="level" value="{{ old('level') }}"
                       min="1" max="10"
                       class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
            </div>

            <div class="mb-5">
                <label for="description" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Description
                </label>
                <textarea name="description" id="description" rows="3"
                          class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">{{ old('description') }}</textarea>
            </div>

            <div class="flex justify-end items-center mt-6">
                <a href="{{ route('designations.index') }}"
                   class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Save
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
