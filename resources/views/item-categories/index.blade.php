<x-app-layout>
    <div>
        <x-success-message />
    </div>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Item Categories</h1>
        <a href="{{ route('item-categories.create') }}"
           class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
            + Add Category
        </a>
    </div>

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">#</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Slug</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Parent</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Status</th>
                    <th class="px-6 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($categories as $category)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $category->slug ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $category->parent->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @class([
                                    'bg-green-100 text-green-800 dark:bg-green-700 dark:text-white' => $category->is_active,
                                    'bg-red-100 text-red-800 dark:bg-red-700 dark:text-white' => !$category->is_active,
                                ])">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('item-categories.edit', $category->id) }}"
                               class="text-blue-500 hover:text-blue-700 dark:hover:text-blue-300 font-medium">Edit</a>
                            <form action="{{ route('item-categories.destroy', $category->id) }}" method="POST"
                                  class="inline-block" onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="text-red-500 hover:text-red-700 dark:hover:text-red-400 font-medium">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            No categories found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4 px-4">
            {{ $categories->links() }}
        </div>
    </div>
</x-app-layout>
