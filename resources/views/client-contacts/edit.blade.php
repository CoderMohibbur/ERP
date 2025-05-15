<x-app-layout>
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Edit Client Contact</h2>

    <form action="{{ route('client-contacts.update', $clientContact->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            {{-- Client --}}
            <div>
                <label for="client_id" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Client</label>
                <select name="client_id" id="client_id"
                        class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500">
                    @foreach($clients as $id => $name)
                        <option value="{{ $id }}" {{ old('client_id', $clientContact->client_id) == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Name --}}
            <div>
                <label for="name" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $clientContact->name) }}"
                       class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500" />
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $clientContact->email) }}"
                       class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500" />
            </div>

            {{-- Phone --}}
            <div>
                <label for="phone" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $clientContact->phone) }}"
                       class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500" />
            </div>

            {{-- Designation --}}
            <div>
                <label for="designation" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Designation</label>
                <input type="text" name="designation" id="designation" value="{{ old('designation', $clientContact->designation) }}"
                       class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500" />
            </div>
        </div>

        <div class="flex justify-end items-center mt-6">
            <a href="{{ route('client-contacts.index') }}"
               class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Update
            </button>
        </div>
    </form>
</x-app-layout>
