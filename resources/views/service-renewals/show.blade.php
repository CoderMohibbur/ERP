<x-app-layout>
    <x-success-message />

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            Renewal Details #{{ $serviceRenewal->id }}
        </h1>

        <a href="{{ route('service-renewals.index') }}"
            class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 text-sm font-semibold rounded-lg hover:bg-gray-200 hover:dark:bg-gray-700 transition">
            ← Back
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Renewal Date</div>
                <div class="font-semibold text-gray-800 dark:text-white">{{ $serviceRenewal->renewal_date }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Status</div>
                <div class="font-semibold text-gray-800 dark:text-white">{{ $serviceRenewal->status }}</div>
            </div>

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Amount</div>
                <div class="font-semibold text-gray-800 dark:text-white">
                    {{ number_format((float) $serviceRenewal->amount, 2) }}
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Invoice</div>
                <div class="font-semibold">
                    @if($serviceRenewal->invoice_id)
                        <a class="text-blue-600 hover:underline"
                           href="{{ route('invoices.show', $serviceRenewal->invoice_id) }}">
                            Invoice #{{ $serviceRenewal->invoice_id }}
                        </a>
                    @else
                        <span class="text-gray-600 dark:text-gray-300">—</span>
                    @endif
                </div>
            </div>

            <div class="sm:col-span-2">
                <div class="text-xs text-gray-500 dark:text-gray-400">Service</div>
                <div class="font-semibold">
                    @if($serviceRenewal->service_id)
                        <a class="text-blue-600 hover:underline"
                           href="{{ route('services.show', $serviceRenewal->service_id) }}">
                            {{ $serviceRenewal->service->name ?? ('Service#'.$serviceRenewal->service_id) }}
                        </a>
                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            Client: {{ $serviceRenewal->service?->client?->name ?? '—' }}
                        </div>
                    @else
                        <span class="text-gray-600 dark:text-gray-300">—</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
