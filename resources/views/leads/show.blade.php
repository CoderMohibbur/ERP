<x-app-layout>
    <x-success-message />
    <x-validation-errors />

    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Lead: {{ $lead->name }}
                </h1>

                <div class="mt-2 flex flex-wrap gap-2 text-sm">
                    {{-- Status badge --}}
                    <span class="px-2.5 py-1 rounded-md
                        {{ ($lead->status ?? '') === 'converted'
                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200'
                            : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200' }}">
                        {{ ucfirst((string) ($lead->status ?? 'unknown')) }}
                    </span>

                    {{-- Source badge --}}
                    @if(!empty($lead->source))
                        <span class="px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-200">
                            Source: {{ $lead->source }}
                        </span>
                    @endif

                    {{-- Follow-up badge --}}
                    @if(!empty($lead->next_follow_up_at))
                        <span class="px-2.5 py-1 rounded-md bg-amber-50 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200">
                            Next Follow-up:
                            {{ \Illuminate\Support\Carbon::parse($lead->next_follow_up_at)->format('d M Y') }}
                        </span>
                    @endif
                </div>

                {{-- Converted badge --}}
                @if(!empty($lead->converted_client_id))
                    <div class="mt-3">
                        <div class="inline-flex flex-wrap items-center gap-2 px-3 py-1.5 rounded-md
                                    bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">
                            <span class="font-semibold">Converted ✅</span>

                            {{-- route safe --}}
                            @if(\Illuminate\Support\Facades\Route::has('clients.show'))
                                <a class="underline font-semibold"
                                   href="{{ route('clients.show', $lead->converted_client_id) }}">
                                    View Client #{{ $lead->converted_client_id }}
                                </a>
                            @else
                                <span class="opacity-80">Client #{{ $lead->converted_client_id }}</span>
                            @endif

                            <span class="opacity-80">
                                ({{ !empty($lead->converted_at)
                                    ? \Illuminate\Support\Carbon::parse($lead->converted_at)->format('d M Y, h:i A')
                                    : '—' }})
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex flex-wrap items-center justify-end gap-2">
                @if(\Illuminate\Support\Facades\Route::has('leads.edit'))
                    <a href="{{ route('leads.edit', $lead) }}"
                       class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                        Edit
                    </a>
                @endif

                @if(\Illuminate\Support\Facades\Route::has('leads.index'))
                    <a href="{{ route('leads.index') }}"
                       class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-sm font-semibold rounded-lg hover:bg-gray-200 hover:dark:bg-gray-600 transition">
                        ← Back
                    </a>
                @endif

                @if(\Illuminate\Support\Facades\Route::has('leads.destroy'))
                    <form action="{{ route('leads.destroy', $lead) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('Are you sure you want to delete this lead?')"
                                class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition">
                            Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Details Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Name</div>
                    <div class="text-gray-900 dark:text-white font-semibold">
                        {{ $lead->name ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Phone</div>
                    <div class="text-gray-900 dark:text-white">
                        {{ $lead->phone ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Email</div>
                    <div class="text-gray-900 dark:text-white">
                        {{ $lead->email ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Source</div>
                    <div class="text-gray-900 dark:text-white">
                        {{ $lead->source ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Status</div>
                    <div class="text-gray-900 dark:text-white font-semibold">
                        {{ ucfirst((string) ($lead->status ?? '—')) }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Owner</div>
                    <div class="text-gray-900 dark:text-white">
                        {{ $lead->owner?->name ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Next Follow-up</div>
                    <div class="text-gray-900 dark:text-white">
                        {{ !empty($lead->next_follow_up_at)
                            ? \Illuminate\Support\Carbon::parse($lead->next_follow_up_at)->format('Y-m-d')
                            : '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Created</div>
                    <div class="text-gray-900 dark:text-white">
                        {{ !empty($lead->created_at) ? $lead->created_at->format('Y-m-d H:i') : '—' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Convert to Client (only if not converted) --}}
        @can('lead.convert')
            @if(empty($lead->converted_client_id) && \Illuminate\Support\Facades\Route::has('leads.convert'))
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                            Convert to Client
                        </h3>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            Choose create or link
                        </span>
                    </div>

                    <form method="POST" action="{{ route('leads.convert', $lead) }}" class="mt-4 space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">Mode</label>
                            <select name="mode" id="mode"
                                    class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700">
                                <option value="create" @selected(old('mode', 'create') === 'create')>Create New Client</option>
                                <option value="link" @selected(old('mode') === 'link')>Link Existing Client</option>
                            </select>
                        </div>

                        <div id="linkFields" class="hidden">
                            <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">Existing Client ID</label>
                            <input type="number" name="existing_client_id" value="{{ old('existing_client_id') }}"
                                   class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700"
                                   placeholder="e.g. 12">
                            <p class="text-xs text-gray-500 mt-1">
                                Use this when the lead is a duplicate of an existing client.
                            </p>
                        </div>

                        <div id="createFields" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">Client Name</label>
                                <input type="text" name="name" value="{{ old('name', $lead->name) }}"
                                       class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">Company</label>
                                <input type="text" name="company_name" value="{{ old('company_name') }}"
                                       class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" name="email" value="{{ old('email', $lead->email) }}"
                                       class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700">
                            </div>

                            <div>
                                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone', $lead->phone) }}"
                                       class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">Notes (optional)</label>
                                <textarea name="notes" rows="3"
                                          class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700"
                                          placeholder="Any notes to carry into client activity...">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                                Convert
                            </button>
                        </div>
                    </form>

                    <script>
                        (function () {
                            const mode = document.getElementById('mode');
                            const linkFields = document.getElementById('linkFields');
                            const createFields = document.getElementById('createFields');

                            function sync() {
                                const v = mode ? mode.value : 'create';
                                if (v === 'link') {
                                    linkFields && linkFields.classList.remove('hidden');
                                    createFields && createFields.classList.add('hidden');
                                } else {
                                    linkFields && linkFields.classList.add('hidden');
                                    createFields && createFields.classList.remove('hidden');
                                }
                            }

                            if (mode) {
                                mode.addEventListener('change', sync);
                            }
                            sync();
                        })();
                    </script>
                </div>
            @endif
        @endcan

        {{-- Activities Panel --}}
        @include('activities._panel', ['actionable' => $lead])

    </div>
</x-app-layout>
