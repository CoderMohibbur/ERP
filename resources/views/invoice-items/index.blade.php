<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            Invoice Items
        </h2>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow rounded-2xl p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 00-1-1H6zM2 9v7a2 2 0 002 2h12a2 2 0 002-2V9H2zm3 2h2v2H5v-2z" />
                    </svg>
                    Invoice Items
                </h3>

                <a href="{{ route('invoice-items.create') }}"
                   class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Item
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-3">Invoice</th>
                            <th class="px-6 py-3">Item</th>
                            <th class="px-6 py-3">Code</th>
                            <th class="px-6 py-3">Qty</th>
                            <th class="px-6 py-3">Unit Price</th>
                            <th class="px-6 py-3">Tax %</th>
                            <th class="px-6 py-3">Total</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($invoiceItems as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->invoice->invoice_number ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $item->item_name }}</td>
                                <td class="px-6 py-4">{{ $item->item_code }}</td>
                                <td class="px-6 py-4">{{ $item->quantity }}</td>
                                <td class="px-6 py-4">৳ {{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-6 py-4">{{ $item->tax_percent ?? '0' }}%</td>
                                <td class="px-6 py-4 font-bold text-green-600 dark:text-green-400">
                                    ৳ {{ number_format($item->total, 2) }}
                                </td>
                                <td class="px-6 py-4 space-x-2">
                                    <a href="{{ route('invoice-items.edit', $item->id) }}"
                                       class="text-yellow-600 hover:underline">Edit</a>
                                    <form action="{{ route('invoice-items.destroy', $item->id) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('Are you sure?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center px-6 py-6 text-gray-400 dark:text-gray-500">
                                    No invoice items found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $invoiceItems->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</x-app-layout>
