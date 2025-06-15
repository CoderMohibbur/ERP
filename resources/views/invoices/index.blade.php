<x-app-layout>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Invoices</h1>
        <a href="{{ route('invoices.create') }}"
           class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
            + New Invoice
        </a>
    </div>

    {{-- Success Message --}}
    <x-success-message />

    {{-- Filter Bar --}}
    <form method="GET" class="mb-4 flex flex-wrap gap-3 items-center">
        <select name="status" class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">
            <option value="">All Status</option>
            @foreach(['draft', 'sent', 'paid', 'overdue'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>

        <select name="invoice_type" class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">
            <option value="">All Types</option>
            @foreach(['proforma', 'final'] as $type)
                <option value="{{ $type }}" @selected(request('invoice_type') === $type)>
                    {{ ucfirst($type) }}
                </option>
            @endforeach
        </select>

        <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            Filter
        </button>
    </form>

    {{-- Table --}}
    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left">#</th>
                    <th class="px-4 py-3 text-left">Invoice</th>
                    <th class="px-4 py-3 text-left">Client</th>
                    <th class="px-4 py-3 text-left">Amount</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Due</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($invoices as $invoice)
                    <tr>
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-semibold text-blue-600 dark:text-blue-300">
                            {{ $invoice->invoice_number }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $invoice->client->name ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $invoice->currency }} {{ number_format($invoice->total_amount, 2) }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-1 rounded font-medium
                                @class([
                                    'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200' => $invoice->status === 'draft',
                                    'bg-blue-200 text-blue-800 dark:bg-blue-700 dark:text-white' => $invoice->status === 'sent',
                                    'bg-green-200 text-green-800 dark:bg-green-700 dark:text-white' => $invoice->status === 'paid',
                                    'bg-red-200 text-red-800 dark:bg-red-700 dark:text-white' => $invoice->status === 'overdue',
                                ])">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $invoice->due_date->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('invoices.edit', $invoice->id) }}"
                               class="text-blue-600 hover:underline dark:text-blue-400">Edit</a>
                            <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST"
                                  class="inline-block" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline dark:text-red-400">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                            No invoices found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $invoices->links() }}
    </div>
</x-app-layout>
