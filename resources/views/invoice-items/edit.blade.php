<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            ✏️ Edit Invoice Item
        </h2>
    </x-slot>

    <div class="py-10 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-8">
            <form method="POST" action="{{ route('invoice-items.update', $invoiceItem->id) }}">
                @csrf
                @method('PUT')

                {{-- Reusable Form Fields --}}
                @include('invoice-items.form', ['invoiceItem' => $invoiceItem])

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('invoice-items.index') }}"
                        class="mr-3 inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-600 text-sm text-gray-800 dark:text-white rounded hover:bg-gray-300 dark:hover:bg-gray-700 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded transition">
                        ✅ Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
