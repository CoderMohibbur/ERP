<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
            ➕ Add New Skill
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8">
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-100 border border-red-300 dark:border-red-600 rounded">
                <ul class="list-disc ml-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('skills.store') }}">
            @csrf

            <div class="grid grid-cols-1 gap-6">

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Skill Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           required class="mt-1 block w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                </div>

                <!-- Slug (optional) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Slug (optional)</label>
                    <input type="text" name="slug" value="{{ old('slug') }}"
                           class="mt-1 block w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                           placeholder="Auto-generated if left blank">
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Category (optional)</label>
                    <input type="text" name="category" value="{{ old('category') }}"
                           class="mt-1 block w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                           placeholder="e.g. Technical, Soft Skill, Language">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Description (optional)</label>
                    <textarea name="description" rows="3"
                              class="mt-1 block w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                              placeholder="Additional details or usage note...">{{ old('description') }}</textarea>
                </div>

                <!-- Is Active -->
                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="text-blue-600 border-gray-300 focus:ring-blue-500 rounded">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Is Active</label>
                </div>

            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('skills.index') }}"
                   class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</x-app-layout>
