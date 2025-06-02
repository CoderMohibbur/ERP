<x-app-layout>
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Client Contacts</h2>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4 text-right">
        <a href="{{ route('client-contacts.create') }}"
           class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">➕ Add Contact</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full table-auto border border-gray-300 dark:border-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-white">
                <tr>
                    <th class="px-4 py-2 text-left">Client</th>
                    <th class="px-4 py-2 text-left">Type</th>
                    <th class="px-4 py-2 text-left">Value</th>
                    <th class="px-4 py-2 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-800 dark:text-white">
                @forelse($contacts as $contact)
                    <tr class="border-t border-gray-200 dark:border-gray-700">
                        <td class="px-4 py-2">{{ $contact->client->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2 capitalize">{{ $contact->type }}</td>
                        <td class="px-4 py-2">{{ $contact->value }}</td>
                        <td class="px-4 py-2 text-right space-x-2">
                            <a href="{{ route('client-contacts.edit', $contact->id) }}"
                               class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('client-contacts.destroy', $contact->id) }}" method="POST"
                                  class="inline-block" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-gray-500">No client contacts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $contacts->links() }}
    </div>
</x-app-layout>
