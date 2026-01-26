<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Edit Deal</h2>

        <x-validation-errors />

        <form method="POST" action="{{ route('deals.update', $deal) }}">
            @csrf
            @method('PUT')

            @include('deals._form', [
                'deal' => $deal,
                'stages' => $stages ?? ['new','contacted','quoted','negotiating','won','lost'],
                'leads' => $leads ?? [],
                'clients' => $clients ?? [],
            ])

            <div class="flex justify-end mt-6">
                <a href="{{ route('deals.index') }}"
                   class="mr-3 text-gray-600 dark:text-gray-300 hover:underline">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
