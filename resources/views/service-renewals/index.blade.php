<x-app-layout>
    <x-success-message />

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Service Renewals</h1>

        <a href="{{ route('services.index') }}"
            class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 text-sm font-semibold rounded-lg hover:bg-gray-200 hover:dark:bg-gray-700 transition">
            ← Back to Services
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
        <form method="GET" action="{{ route('service-renewals.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status"
                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">All</option>
                    <option value="pending" @selected(request('status') === 'pending')>pending</option>
                    <option value="invoiced" @selected(request('status') === 'invoiced')>invoiced</option>
                    <option value="paid" @selected(request('status') === 'paid')>paid</option>
                    <option value="skipped" @selected(request('status') === 'skipped')>skipped</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From</label>
                <input type="date" name="from" value="{{ request('from') }}"
                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To</label>
                <input type="date" name="to" value="{{ request('to') }}"
                    class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
                    Filter
                </button>

                <a href="{{ route('service-renewals.index') }}"
                    class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-sm font-semibold rounded-lg hover:bg-gray-200 hover:dark:bg-gray-600 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">#</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Renewal Date</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Service</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Client</th>
                    <th class="px-6 py-3 text-right text-sm font-medium text-gray-600 dark:text-gray-300">Amount</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Invoice</th>
                    <th class="px-6 py-3 text-right text-sm font-medium text-gray-600 dark:text-gray-300">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($renewals as $renewal)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ $loop->iteration + ($renewals->currentPage() - 1) * $renewals->perPage() }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                            {{ $renewal->renewal_date }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                            @if($renewal->service_id)
                                <a class="text-blue-600 hover:underline"
                                   href="{{ route('services.show', $renewal->service_id) }}">
                                    {{ $renewal->service->name ?? ('Service#'.$renewal->service_id) }}
                                </a>
                            @else
                                —
                            @endif
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                            {{ $renewal->service?->client?->name ?? '—' }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100 text-right">
                            {{ number_format((float) $renewal->amount, 2) }}
                        </td>

                        <td class="px-6 py-4 text-sm">
                            @php
                                $status = (string) $renewal->status;
                                $badge = match($status) {
                                    'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200',
                                    'invoiced' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200',
                                    'skipped' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/40 dark:text-gray-200',
                                    default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-200',
                                };
                            @endphp
                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $badge }}">{{ $status }}</span>
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                            @if($renewal->invoice_id)
                                <a class="text-blue-600 hover:underline"
                                   href="{{ route('invoices.show', $renewal->invoice_id) }}">
                                    Invoice #{{ $renewal->invoice_id }}
                                </a>
                            @else
                                —
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('service-renewals.show', $renewal->id) }}"
                               class="text-blue-500 hover:text-blue-700 font-medium">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                            No renewals found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4 px-4 pb-4">
            {{ $renewals->links() }}
        </div>
    </div>
</x-app-layout>
