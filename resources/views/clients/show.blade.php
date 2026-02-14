{{-- resources/views/clients/show.blade.php --}}
<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- Header --}}
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    Client: <span class="text-blue-500">{{ $client->name }}</span>
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $client->company_name ?? '—' }} • {{ $client->email ?? '—' }} • {{ $client->phone ?? '—' }}
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('client-contacts.index', $client->id) }}"
                    class="px-4 py-2 rounded bg-slate-700 text-white hover:bg-slate-600">
                    Contacts
                </a>

                <a href="{{ route('client-notes.index', $client->id) }}"
                    class="px-4 py-2 rounded bg-slate-700 text-white hover:bg-slate-600">
                    Notes
                </a>

                <a href="{{ route('clients.edit', $client->id) }}"
                    class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-500">
                    Edit
                </a>
            </div>
        </div>

        {{-- Quick info --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 rounded-lg bg-white/70 dark:bg-gray-900/60 border border-gray-200/60 dark:border-gray-800">
                <div class="text-xs uppercase text-gray-500">Status</div>
                <div class="mt-1 text-lg text-gray-900 dark:text-gray-100">
                    {{ $client->status ?? '—' }}
                </div>
            </div>

            <div class="p-4 rounded-lg bg-white/70 dark:bg-gray-900/60 border border-gray-200/60 dark:border-gray-800">
                <div class="text-xs uppercase text-gray-500">Website</div>
                <div class="mt-1 text-lg text-gray-900 dark:text-gray-100 break-all">
                    {{ $client->website ?? '—' }}
                </div>
            </div>

            <div class="p-4 rounded-lg bg-white/70 dark:bg-gray-900/60 border border-gray-200/60 dark:border-gray-800">
                <div class="text-xs uppercase text-gray-500">Industry</div>
                <div class="mt-1 text-lg text-gray-900 dark:text-gray-100">
                    {{ $client->industry_type ?? '—' }}
                </div>
            </div>
        </div>

        {{-- ✅ Activities Panel (Meetings/Calls/Follow-ups) --}}
        <div class="rounded-lg bg-white/70 dark:bg-gray-900/60 border border-gray-200/60 dark:border-gray-800 p-4">
            @include('activities._panel', [
                'actionable' => $client,
                'activities' => $activities,
            ])
        </div>

    </div>
</x-app-layout>
