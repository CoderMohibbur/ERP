<x-app-layout>
    <div class="max-w-2xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
            Edit Contact for <span class="text-blue-600">{{ $contact->client->name ?? 'N/A' }}</span>
        </h2>

        {{-- Validation Errors --}}
        <x-validation-errors class="mb-4" />

        {{-- Contact Edit Form --}}
        <form method="POST" action="{{ route('client-contacts.update', $contact->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5">
                {{-- Type --}}
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Type</label>
                    <select name="type" id="type" required
                            class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Select Type</option>
                        @foreach(['phone', 'email', 'whatsapp', 'facebook', 'linkedin', 'other'] as $type)
                            <option value="{{ $type }}" {{ old('type', $contact->type) === $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Value --}}
                <div>
                    <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Value</label>
                    <input type="text" name="value" id="value"
                           value="{{ old('value', $contact->value) }}" required
                           class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                           placeholder="Enter contact detail">
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end items-center mt-6">
                <a href="{{ route('client-contacts.index', $contact->client_id) }}"
                   class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500">
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
