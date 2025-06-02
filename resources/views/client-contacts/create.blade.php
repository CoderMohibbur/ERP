<x-app-layout>
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Add Client Contact</h2>

    <form action="{{ route('client-contacts.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            {{-- Client --}}
            <div>
                <label for="client_id" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Client</label>
                <select name="client_id" id="client_id"
                        class="w-full px-4 py-2 text-sm border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    @foreach ($clients as $id => $name)
                        <option value="{{ $id }}" {{ old('client_id') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Type --}}
            <div>
                <label for="type" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Type</label>
                <select name="type" id="type"
                        class="w-full px-4 py-2 text-sm border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    <option value="name" {{ old('type') == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="email" {{ old('type') == 'email' ? 'selected' : '' }}>Email</option>
                    <option value="phone" {{ old('type') == 'phone' ? 'selected' : '' }}>Phone</option>
                    <option value="designation" {{ old('type') == 'designation' ? 'selected' : '' }}>Designation</option>
                </select>
            </div>

            {{-- Value --}}
            <div class="sm:col-span-2">
                <label for="value" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Value</label>
                <input type="text" name="value" id="value" value="{{ old('value') }}"
                       class="w-full px-4 py-2 text-sm border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600" />
            </div>
        </div>

        <div class="flex justify-end items-center mt-6">
            <a href="{{ route('client-contacts.index') }}"
               class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500">Cancel</a>
            <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Save</button>
        </div>
    </form>
</x-app-layout>
