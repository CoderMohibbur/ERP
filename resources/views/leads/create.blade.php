<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Add Lead</h2>

            <a href="{{ route('leads.index') }}"
               class="text-sm text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500">
                ← Back
            </a>
        </div>

        <x-validation-errors />

        @php
            /** @var \App\Models\Lead $lead */
            $lead = $lead ?? new \App\Models\Lead();
        @endphp

        <form method="POST" action="{{ route('leads.store') }}">
            @csrf

            @include('leads._form', ['lead' => $lead])

            <div class="flex justify-end items-center mt-6">
                <a href="{{ route('leads.index') }}"
                   class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500">
                    Cancel
                </a>

                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Save
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
