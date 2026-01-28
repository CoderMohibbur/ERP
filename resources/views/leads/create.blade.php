<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-0">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Add Lead</h1>
                <p class="text-sm text-gray-500 dark:text-gray-300 mt-1">
                    Create a new lead with status, source and owner.
                </p>
            </div>

            <a href="{{ route('leads.index') }}"
               class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:underline">
                ← Back
            </a>
        </div>

        {{-- Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            {{-- Flash message (optional component exists in your project) --}}
            <x-success-message />

            {{-- Validation errors --}}
            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('leads.store') }}">
                @csrf

                @include('leads._form', [
                    'lead' => null,
                    'statuses' => $statuses ?? ['new', 'contacted', 'qualified', 'unqualified'],
                    'sources' => $sources ?? ['whatsapp', 'facebook', 'website', 'referral'],
                    'owners' => $owners ?? [],
                ])

                <div class="mt-6 flex items-center justify-end gap-3">
                    <a href="{{ route('leads.index') }}"
                       class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                              text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Cancel
                    </a>

                    <button type="submit"
                            class="px-5 py-2 rounded-lg bg-green-600 text-white font-semibold
                                   hover:bg-green-700 transition">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
