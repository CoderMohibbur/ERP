<x-app-layout>
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Edit Item Category</h2>

    <x-validation-errors class="mb-4" />

    <form action="{{ route('item-categories.update', $itemCategory->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('item-categories.form-fields')
        <div class="flex justify-end mt-4">
            <a href="{{ route('item-categories.index') }}" class="mr-3 text-gray-600 dark:text-gray-300">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update</button>
        </div>
    </form>
</x-app-layout>
