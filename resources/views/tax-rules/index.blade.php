<x-app-layout>
    <div>
        <x-success-message />
    </div>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Tax Rules</h1>
        <a href="{{ route('tax-rules.create') }}"
           class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
            ➕ New Tax Rule
        </a>
    </div>

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">#</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Rate (%)</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Scope</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Region</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Active</th>
                    <th class="px-6 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($taxRules as $taxRule)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $taxRule->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ number_format($taxRule->rate_percent, 3) }}</td>
                        <td class="px-6 py-4 text-sm capitalize text-gray-800 dark:text-gray-100">{{ $taxRule->scope }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                            {{ $taxRule->country_code ?? '-' }}{{ $taxRule->region ? ', ' . $taxRule->region : '' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                            <span class="px-2 py-1 rounded {{ $taxRule->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $taxRule->is_active ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('tax-rules.edit', $taxRule->id) }}"
                               class="text-blue-500 hover:text-blue-700 dark:hover:text-blue-300 font-medium">Edit</a>
                            <form action="{{ route('tax-rules.destroy', $taxRule->id) }}"
                                  method="POST" class="inline-block"
                                  onsubmit="return confirm('Are you sure you want to delete this tax rule?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-500 hover:text-red-700 dark:hover:text-red-400 font-medium">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            No tax rules found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4 px-4">
            {{ $taxRules->links() }}
        </div>
    </div>
</x-app-layout>
