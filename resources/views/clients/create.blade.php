<x-app-layout>
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Add Client</h2>

    <form action="{{ route('clients.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       class="w-full px-4 py-2 text-sm bg-white border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="email" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                       class="w-full px-4 py-2 text-sm bg-white border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            <div>
                <label for="phone" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                       class="w-full px-4 py-2 text-sm bg-white border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            <div>
                <label for="address" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Address</label>
                <input type="text" name="address" id="address" value="{{ old('address') }}"
                       class="w-full px-4 py-2 text-sm bg-white border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>
        </div>

        <div class="flex justify-end items-center mt-6">
            <a href="{{ route('clients.index') }}"
               class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Save
            </button>
        </div>
    </form>
</x-app-layout>
