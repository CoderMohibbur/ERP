<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
            📁 Employee Documents
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-200 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 text-right">
            <a href="{{ route('employee-documents.create') }}"
               class="px-4 py-2 bg-green-600 dark:bg-green-700 text-white rounded hover:bg-green-700 dark:hover:bg-green-800">
                ➕ Upload Document
            </a>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">#</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Employee</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Type</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Title</th>
                        <th class="px-4 py-2 text-sm font-semibold text-center text-gray-700 dark:text-gray-200">File</th>
                        <th class="px-4 py-2 text-sm font-semibold text-center text-gray-700 dark:text-gray-200">Expires</th>
                        <th class="px-4 py-2 text-sm font-semibold text-center text-gray-700 dark:text-gray-200">Verified</th>
                        <th class="px-4 py-2 text-sm font-semibold text-center text-gray-700 dark:text-gray-200">Visibility</th>
                        <th class="px-4 py-2 text-sm font-semibold text-right text-gray-700 dark:text-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($documents as $doc)
                        <tr class="bg-white dark:bg-gray-900">
                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-100">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-100">{{ $doc->employee->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-100">{{ ucfirst($doc->type) }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-100">{{ $doc->title ?? '-' }}</td>
                            <td class="px-4 py-2 text-sm text-center">
                                <a href="{{ Storage::url($doc->file_path) }}" target="_blank"
                                   class="text-blue-600 dark:text-blue-400 hover:underline">View</a>
                            </td>
                            <td class="px-4 py-2 text-sm text-center text-gray-700 dark:text-gray-100">
                                {{ $doc->expires_at ? $doc->expires_at->format('Y-m-d') : '-' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-center">
                                @if($doc->is_verified)
                                    <span class="text-green-600 dark:text-green-400 font-semibold">✔</span>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">✘</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-center text-gray-700 dark:text-gray-100">{{ ucfirst($doc->visibility) }}</td>
                            <td class="px-4 py-2 text-sm text-right space-x-2">
                                <a href="{{ route('employee-documents.edit', $doc->id) }}"
                                   class="text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
                                <form action="{{ route('employee-documents.destroy', $doc->id) }}"
                                      method="POST" class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this document?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-gray-500 dark:text-gray-400 py-6">No documents found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4 bg-white dark:bg-gray-800">
                {{ $documents->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
