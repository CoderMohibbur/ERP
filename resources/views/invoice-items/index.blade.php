<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
            📦 Invoice Items
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 text-right">
            <a href="{{ route('invoice-items.create') }}"
               class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                ➕ Add Items
            </a>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded shadow">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">#</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Invoice</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Item</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Category</th>
                        <th class="px-4 py-2 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">Qty</th>
                        <th class="px-4 py-2 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">Unit Price</th>
                        <th class="px-4 py-2 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">Tax %</th>
                        <th class="px-4 py-2 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">Total</th>
                        <th class="px-4 py-2 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($items as $item)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ $item->invoice->invoice_number ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">
                                {{ $item->item_name }}
                                @if($item->item_code)
                                    <div class="text-xs text-gray-500">({{ $item->item_code }})</div>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ $item->category->name ?? '-' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-right text-gray-800 dark:text-gray-100">{{ $item->quantity }}</td>
                            <td class="px-4 py-2 text-sm text-right text-gray-800 dark:text-gray-100">
                                {{ number_format($item->unit_price, 2) }}
                            </td>
                            <td class="px-4 py-2 text-sm text-right text-gray-800 dark:text-gray-100">
                                {{ $item->tax_percent ?? 0 }}%
                            </td>
                            <td class="px-4 py-2 text-sm text-right font-semibold text-green-700 dark:text-green-400">
                                {{ number_format($item->total, 2) }}
                            </td>
                            <td class="px-4 py-2 text-sm text-right space-x-2">
                                <a href="{{ route('invoice-items.edit', $item->id) }}"
                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    Edit
                                </a>
                                <form action="{{ route('invoice-items.destroy', $item->id) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this item?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-gray-500 py-6">No invoice items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
