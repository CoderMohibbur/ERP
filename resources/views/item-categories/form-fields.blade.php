<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    {{-- Category Name --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category Name</label>
        <input type="text" name="name" id="name"
               value="{{ old('name', $itemCategory->name ?? '') }}"
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600"
               required>
    </div>

    {{-- Parent Category --}}
    <div>
        <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Parent Category</label>
        <select name="parent_id" id="parent_id"
                class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600">
            <option value="">None</option>
            @foreach($categories as $id => $name)
                <option value="{{ $id }}"
                    {{ old('parent_id', $itemCategory->parent_id ?? '') == $id ? 'selected' : '' }}>
                    {{ $name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Description --}}
    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
        <textarea name="description" id="description" rows="4"
                  class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600">{{ old('description', $itemCategory->description ?? '') }}</textarea>
    </div>
</div>
