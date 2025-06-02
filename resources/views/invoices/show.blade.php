<x-app-layout>
    <div class="bg-white shadow rounded-lg p-6 dark:bg-gray-800">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Invoice #{{ $invoice->invoice_number }}</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Issued: {{ $invoice->issue_date->format('d M Y') }} | Due: {{ $invoice->due_date->format('d M Y') }}</p>
            </div>
            <div class="space-x-2">
                <a href="{{ route('invoices.print', $invoice->id) }}" target="_blank"
                   class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Print</a>
                <a href="{{ route('invoices.download', $invoice->id) }}"
                   class="inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Download PDF</a>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <h3 class="font-semibold text-gray-700 dark:text-gray-300">Client</h3>
                <p>{{ $invoice->client->name }}</p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-700 dark:text-gray-300">Project</h3>
                <p>{{ $invoice->project->title ?? 'N/A' }}</p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-700 dark:text-gray-300">Currency</h3>
                <p>{{ $invoice->currency }}</p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-700 dark:text-gray-300">Status</h3>
                <p class="capitalize">{{ $invoice->status }}</p>
            </div>
        </div>

        <div class="border-t pt-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Sub Total:</span>
                        <span class="font-semibold">{{ number_format($invoice->sub_total, 2) }} {{ $invoice->currency }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Discount ({{ $invoice->discount_type ?? 'None' }}):</span>
                        <span class="font-semibold">{{ $invoice->discount_value ?? '0.00' }} {{ $invoice->currency }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Tax Rate:</span>
                        <span class="font-semibold">{{ $invoice->tax_rate ?? '0' }}%</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Total Amount:</span>
                        <span class="font-semibold">{{ number_format($invoice->total_amount, 2) }} {{ $invoice->currency }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Paid:</span>
                        <span class="font-semibold">{{ number_format($invoice->paid_amount, 2) }} {{ $invoice->currency }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Due:</span>
                        <span class="font-semibold">{{ number_format($invoice->due_amount, 2) }} {{ $invoice->currency }}</span>
                    </div>
                </div>
            </div>

            @if ($invoice->notes)
                <div class="mt-6">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Notes</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $invoice->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
