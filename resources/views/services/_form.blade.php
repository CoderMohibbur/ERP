@php
    $service = $service ?? null;

    $types = $types ?? [
        'shared_hosting' => 'Shared Hosting',
        'dedicated' => 'Dedicated',
        'domain' => 'Domain',
        'ssl' => 'SSL',
        'maintenance' => 'Maintenance',
    ];

    $billingCycles = $billingCycles ?? [
        'monthly' => 'Monthly',
        'quarterly' => 'Quarterly',
        'half_yearly' => 'Half Yearly',
        'yearly' => 'Yearly',
        'custom' => 'Custom',
    ];

    $statuses = $statuses ?? [
        'active' => 'Active',
        'suspended' => 'Suspended',
        'cancelled' => 'Cancelled',
        'expired' => 'Expired',
    ];

    $clients = $clients ?? [];
@endphp

<div class="grid grid-cols-1 gap-5">
    <div>
        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Client <span class="text-red-500">*</span></label>
        <select name="client_id" required
                class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
            <option value="">—</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" @selected((string)old('client_id', $service?->client_id) === (string)$client->id)>
                    {{ $client->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Type <span class="text-red-500">*</span></label>
            <select name="type" required
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
                @foreach($types as $k => $label)
                    <option value="{{ $k }}" @selected(old('type', $service?->type ?? 'shared_hosting') === $k)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Billing Cycle <span class="text-red-500">*</span></label>
            <select name="billing_cycle" required
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
                @foreach($billingCycles as $k => $label)
                    <option value="{{ $k }}" @selected(old('billing_cycle', $service?->billing_cycle ?? 'yearly') === $k)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Name / Plan <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $service?->name) }}" required
               class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Amount <span class="text-red-500">*</span></label>
            <input type="number" step="0.01" name="amount" value="{{ old('amount', $service?->amount ?? 0) }}" required
                   class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Currency</label>
            <input type="text" name="currency" value="{{ old('currency', $service?->currency ?? 'BDT') }}"
                   class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></label>
            <select name="status" required
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
                @foreach($statuses as $k => $label)
                    <option value="{{ $k }}" @selected(old('status', $service?->status ?? 'active') === $k)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Started At</label>
            <input type="date" name="started_at" value="{{ old('started_at', $service?->started_at) }}"
                   class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Expires At</label>
            <input type="date" name="expires_at" value="{{ old('expires_at', $service?->expires_at) }}"
                   class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Next Renewal At</label>
            <input type="date" name="next_renewal_at" value="{{ old('next_renewal_at', $service?->next_renewal_at) }}"
                   class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
        </div>
    </div>

    <div class="flex items-center gap-2">
        <input id="auto_invoice" type="checkbox" name="auto_invoice" value="1"
               @checked((bool)old('auto_invoice', $service?->auto_invoice ?? false))
               class="rounded border-gray-300 text-green-600 focus:ring-green-500">
        <label for="auto_invoice" class="text-sm text-gray-700 dark:text-gray-300">Auto Invoice</label>
    </div>

    <div>
        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
        <textarea name="notes" rows="3"
                  class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">{{ old('notes', $service?->notes) }}</textarea>
    </div>
</div>