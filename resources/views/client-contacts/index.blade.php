<x-app-layout>
    <div class="max-w-6xl mx-auto">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-4">
            Contacts for: <span class="text-blue-600">{{ $client->name }}</span>
        </h2>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded-md">
                {{ session('success') }}
            </div>
        @endif


        <div class="mb-4 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Client Contacts</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Manage all contacts associated with this client.</p>
            </div>

            <a href="{{ route('client-contacts.create', ['client' => $client->id]) }}"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                ➕ Add Contact
            </a>
            

            <a href="{{ route('client-contacts.index', ['client' => $client->id]) }}"
                class="inline-block px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition">
                📞 Manage Contacts
            </a>


        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded shadow">
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">Type</th>
                        <th class="px-4 py-2 text-left">Value</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                        <tr class="border-t dark:border-gray-700">
                            <td class="px-4 py-2 capitalize">{{ $contact->type }}</td>
                            <td class="px-4 py-2 break-all">{{ $contact->value }}</td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <a href="{{ route('client-contacts.edit', [$client->id, $contact->id]) }}"
                                    class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('client-contacts.destroy', [$client->id, $contact->id]) }}"
                                    method="POST" class="inline-block"
                                    onsubmit="return confirm('Are you sure you want to delete this contact?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                No contacts found for this client.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($contacts instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-4">
                {{ $contacts->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
