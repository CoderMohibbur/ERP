<x-app-layout>
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Add Item Category</h2>

    <x-validation-errors class="mb-4" />

    <form action="{{ route('item-categories.store') }}" method="POST">
        @csrf
        @include('item-categories.form-fields')
        <div class="flex justify-end mt-4">
            <a href="{{ route('item-categories.index') }}" class="mr-3 text-gray-600 dark:text-gray-300">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Save</button>
        </div>
    </form>
</x-app-layout>
