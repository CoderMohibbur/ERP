<x-app-layout>
    <div class="max-w-2xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Edit Service</h2>

        <x-validation-errors />

        <form method="POST" action="{{ route('services.update', $service->id) }}">
            @csrf
            @method('PUT')

            @include('services._form', ['service' => $service])

            <div class="flex justify-end items-center mt-6">
                <a href="{{ route('services.index') }}"
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
