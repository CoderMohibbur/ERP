<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">📄 Invoices</h2>
        <a href="{{ route('invoices.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">➕
            New Invoice</a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-300">Invoice #</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-300">Client</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-300">Project</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-300">Issue Date</th>
                    <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-300">Due Date</th>
                    <th class="px-4 py-2 text-right font-medium text-gray-700 dark:text-gray-300">Total</th>
                    <th class="px-4 py-2 text-right font-medium text-gray-700 dark:text-gray-300">Paid</th>
                    <th class="px-4 py-2 text-right font-medium text-gray-700 dark:text-gray-300">Due</th>
                    <th class="px-4 py-2 text-center font-medium text-gray-700 dark:text-gray-300">Status</th>
                    <th class="px-4 py-2 text-center font-medium text-gray-700 dark:text-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($invoices as $invoice)
                    <tr>
                        <td class="px-4 py-2 text-gray-800 dark:text-white">{{ $invoice->invoice_number }}</td>
                        <td class="px-4 py-2 text-gray-800 dark:text-white">{{ $invoice->client->name ?? '-' }}</td>
                        <td class="px-4 py-2 text-gray-800 dark:text-white">{{ $invoice->project->title ?? '-' }}</td>
                        <td class="px-4 py-2 text-gray-600 dark:text-gray-300">
                            {{ $invoice->issue_date->format('d M Y h:i A') }}
                        </td>

                        <td class="px-4 py-2 text-gray-600 dark:text-gray-300">{{ $invoice->due_date->format('d M Y') }}
                        </td>
                        <td class="px-4 py-2 text-right text-gray-800 dark:text-white">
                            {{ number_format($invoice->total_amount, 2) }} {{ $invoice->currency }}</td>
                        <td class="px-4 py-2 text-right text-green-600 font-semibold">
                            {{ number_format($invoice->paid_amount, 2) }}</td>
                        <td class="px-4 py-2 text-right text-red-500 font-semibold">
                            {{ number_format($invoice->due_amount, 2) }}</td>
                        <td class="px-4 py-2 text-center">
                            <span
                                class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $invoice->status === 'paid'
                                    ? 'bg-green-100 text-green-700'
                                    : ($invoice->status === 'overdue'
                                        ? 'bg-red-100 text-red-700'
                                        : ($invoice->status === 'sent'
                                            ? 'bg-blue-100 text-blue-700'
                                            : 'bg-gray-100 text-gray-700')) }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-center space-x-2">
                            <a href="{{ route('invoices.show', $invoice) }}"
                                class="text-blue-600 hover:underline">View</a>
                            <a href="{{ route('invoices.edit', $invoice) }}"
                                class="text-yellow-600 hover:underline">Edit</a>
                            <form action="{{ route('invoices.destroy', $invoice) }}" method="POST"
                                class="inline-block" onsubmit="return confirm('Are you sure to delete this invoice?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-4 py-6 text-center text-gray-600 dark:text-gray-300">No invoices
                            found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
