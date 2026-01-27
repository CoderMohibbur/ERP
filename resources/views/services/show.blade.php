<x-app-layout>
    <x-success-message />

    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $service->name }}</h1>
                <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                    Client:
                    <span class="font-medium text-gray-900 dark:text-white">
                        {{ $service->client?->name ?? '—' }}
                    </span>
                </div>
                <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                    Type: <span class="font-medium text-gray-900 dark:text-white">{{ $service->type }}</span> •
                    Status: <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst($service->status) }}</span>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-end gap-2">
                {{-- Renewal Actions --}}
                @php
                    $hasClient = !empty($service->client_id);
                @endphp

                <form method="POST" action="{{ route('services.renewals.invoice', $service) }}" class="inline">
                    @csrf
                    <button
                        type="submit"
                        @disabled(!$hasClient)
                        onclick="return confirm('Generate renewal invoice?')"
                        class="px-3 py-2 rounded-lg text-sm font-semibold transition
                               {{ $hasClient
                                    ? 'bg-green-600 text-white hover:bg-green-700'
                                    : 'bg-gray-200 text-gray-500 cursor-not-allowed dark:bg-gray-700 dark:text-gray-400' }}">
                        Generate Invoice
                    </button>
                </form>

                <a href="{{ route('service-renewals.index') }}"
                   class="px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-sm font-semibold hover:bg-gray-200 hover:dark:bg-gray-600 transition">
                    View Renewals
                </a>

                {{-- Existing Actions --}}
                <a href="{{ route('services.edit', $service->id) }}"
                   class="px-3 py-2 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700 transition">
                    Edit
                </a>

                <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Are you sure?')"
                            class="px-3 py-2 rounded-lg bg-red-600 text-white text-sm hover:bg-red-700 transition">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        {{-- Optional helper note --}}
        @if(empty($service->client_id))
            <div class="mt-4 p-3 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 text-sm">
                ⚠️ এই সার্ভিসে Client সেট করা নেই। Client সেট না করলে Renewal invoice generate করা যাবে না।
            </div>
        @endif

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900">
                <div class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Amount</div>
                <div class="mt-1 text-sm font-medium text-gray-800 dark:text-gray-100">
                    {{ $service->currency ?? 'BDT' }} {{ number_format((float)($service->amount ?? 0), 2) }}
                </div>
            </div>

            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900">
                <div class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Billing Cycle</div>
                <div class="mt-1 text-sm font-medium text-gray-800 dark:text-gray-100">
                    {{ $service->billing_cycle }}
                </div>
            </div>

            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900">
                <div class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Next Renewal</div>
                <div class="mt-1 text-sm font-medium text-gray-800 dark:text-gray-100">
                    {{ $service->next_renewal_at ? \Illuminate\Support\Carbon::parse($service->next_renewal_at)->format('d M, Y') : '—' }}
                </div>

                {{-- Inline renewal actions (extra usability) --}}
                <div class="mt-3 flex flex-wrap gap-2">
                    <form method="POST" action="{{ route('services.renewals.invoice', $service) }}" class="inline">
                        @csrf
                        <button
                            type="submit"
                            @disabled(!$hasClient)
                            onclick="return confirm('Generate renewal invoice?')"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition
                                   {{ $hasClient
                                        ? 'bg-green-600 text-white hover:bg-green-700'
                                        : 'bg-gray-200 text-gray-500 cursor-not-allowed dark:bg-gray-700 dark:text-gray-400' }}">
                            Generate Invoice
                        </button>
                    </form>

                    <a href="{{ route('service-renewals.index') }}"
                       class="px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-semibold hover:bg-gray-200 hover:dark:bg-gray-600 transition">
                        View Renewals
                    </a>
                </div>
            </div>

            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900">
                <div class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Auto Invoice</div>
                <div class="mt-1 text-sm font-medium text-gray-800 dark:text-gray-100">
                    {{ $service->auto_invoice ? 'Yes' : 'No' }}
                </div>
            </div>
        </div>

        <div class="mt-6">
            <div class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Notes</div>
            <div class="mt-2 text-sm text-gray-800 dark:text-gray-100 whitespace-pre-line">
                {{ $service->notes ?: '—' }}
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('services.index') }}"
               class="text-gray-600 dark:text-gray-300 hover:underline">← Back to Services</a>
        </div>
    </div>
</x-app-layout>
