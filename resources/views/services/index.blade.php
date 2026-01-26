<x-app-layout>
    <x-success-message />

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Services</h1>
        <a href="{{ route('services.create') }}"
           class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
            + Add Service
        </a>
    </div>

    {{-- Filters --}}
    <div class="mb-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
        <form method="GET" action="{{ route('services.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3">
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Search</label>
                <input type="text" name="q" value="{{ $q ?? request('q') }}"
                       placeholder="Name/Type..."
                       class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Status</label>
                <select name="status"
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">All</option>
                    @foreach(($statuses ?? ['active','suspended','cancelled','expired']) as $s)
                        <option value="{{ $s }}" @selected(($status ?? request('status')) === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Type</label>
                <select name="type"
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">All</option>
                    @foreach(($types ?? ['shared_hosting','dedicated','domain','ssl','maintenance']) as $t)
                        <option value="{{ $t }}" @selected(($type ?? request('type')) === $t)>{{ str_replace('_',' ', ucfirst($t)) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Client</label>
                <select name="client_id"
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">All</option>
                    @foreach(($clients ?? []) as $c)
                        <option value="{{ $c->id }}" @selected((string)($clientId ?? request('client_id')) === (string)$c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Renewal From</label>
                <input type="date" name="renewal_from" value="{{ $renewalFrom ?? request('renewal_from') }}"
                       class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Renewal To</label>
                <input type="date" name="renewal_to" value="{{ $renewalTo ?? request('renewal_to') }}"
                       class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="md:col-span-6 flex flex-col sm:flex-row gap-3 sm:items-end sm:justify-between mt-2">
                <div class="w-full sm:w-48">
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Per Page</label>
                    <select name="per_page"
                            class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @foreach([10,15,25,50,100] as $pp)
                            <option value="{{ $pp }}" @selected((int)($perPage ?? request('per_page', 15)) === $pp)>{{ $pp }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2 justify-end">
                    <a href="{{ route('services.index') }}"
                       class="px-4 py-2 text-sm font-semibold rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 hover:dark:bg-gray-700 transition">
                        Reset
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
                        Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">#</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Name / Type</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Client</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Billing</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Amount</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Next Renewal</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Auto Invoice</th>
                    <th class="px-6 py-3 text-right text-sm font-medium text-gray-600 dark:text-gray-300">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($services as $service)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ ($services->firstItem() ?? 0) + $loop->index }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100 font-medium">
                            <a href="{{ route('services.show', $service) }}" class="hover:underline">
                                {{ $service->name ?? 'Service' }}
                            </a>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $service->type }}</div>
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">{{ $service->client?->name ?? '—' }}</td>

                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">{{ $service->billing_cycle }}</td>

                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ number_format((float)$service->amount, 2) }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ $service->next_renewal_at ? \Illuminate\Support\Carbon::parse($service->next_renewal_at)->format('Y-m-d') : '—' }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">{{ ucfirst($service->status) }}</td>

                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                            {{ $service->auto_invoice ? 'Yes' : 'No' }}
                        </td>

                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('services.show', $service) }}"
                               class="text-gray-600 dark:text-gray-300 hover:text-gray-900 hover:dark:text-white font-medium">
                                View
                            </a>
                            <a href="{{ route('services.edit', $service) }}"
                               class="text-blue-500 hover:text-blue-700 font-medium">
                                Edit
                            </a>
                            <form action="{{ route('services.destroy', $service) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Are you sure?')"
                                        class="text-red-500 hover:text-red-700 font-medium">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            No services found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4 px-4 pb-4">
            {{ $services->links() }}
        </div>
    </div>
</x-app-layout>
