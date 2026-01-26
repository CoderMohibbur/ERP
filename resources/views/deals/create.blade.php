<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Add Lead</h2>

        <x-validation-errors />

        <form method="POST" action="{{ route('leads.store') }}">
            @csrf

            @include('leads._form', [
                'lead' => null,
                'statuses' => $statuses ?? ['new','contacted','qualified','unqualified'],
                'sources' => $sources ?? ['whatsapp','facebook','website','referral'],
                'owners' => $owners ?? [],
            ])

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
