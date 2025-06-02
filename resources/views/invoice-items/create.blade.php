<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            ➕ Add Invoice Item
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <form method="POST" action="{{ route('invoice-items.store') }}">
                @csrf

                {{-- Include reusable form fields --}}
                @include('invoice-items.form')

                {{-- Submit Button --}}
                <div class="flex justify-end mt-6">
                    <a href="{{ route('invoice-items.index') }}"
                       class="mr-3 px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-white rounded hover:bg-gray-400 dark:hover:bg-gray-600">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-5 py-2 bg-green-600 text-white font-semibold rounded hover:bg-green-700 transition">
                        Save Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
