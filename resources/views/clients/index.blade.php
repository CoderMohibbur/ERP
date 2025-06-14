<x-app-layout>
    <div>
        <x-success-message />
    </div>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Clients</h1>
        <a href="{{ route('clients.create') }}"
           class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
            + Add Client
        </a>
    </div>

    {{-- 🔍 Search --}}
    <form method="GET" action="{{ route('clients.index') }}" class="mb-4">
        <input type="text" name="search" placeholder="Search by name, email, or company..."
               value="{{ request('search') }}"
               class="w-full sm:w-1/3 px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        />
    </form>

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    @foreach (['#', 'Name', 'Email', 'Phone', 'Company', 'Status', 'Actions'] as $head)
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ $head }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($clients as $client)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $client->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $client->email ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $client->phone ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $client->company_name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-block px-2 py-1 text-xs rounded-full
                                {{ $client->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($client->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('clients.edit', $client->id) }}"
                               class="text-blue-500 hover:text-blue-700 dark:hover:text-blue-300 font-medium">Edit</a>
                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Are you sure?')"
                                        class="text-red-500 hover:text-red-700 dark:hover:text-red-400 font-medium">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            No clients found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4 px-4">
            {{ $clients->links() }}
        </div>
    </div>
</x-app-layout>
