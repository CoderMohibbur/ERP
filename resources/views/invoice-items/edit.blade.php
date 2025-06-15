<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
            ✏️ Edit Invoice Item
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 text-red-800 border border-red-300 rounded">
                <ul class="list-disc ml-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('invoice-items.update', $invoiceItem->id) }}">
            @csrf
            @method('PUT')

            @include('invoice-items.form', [
                'invoices' => $invoices,
                'categories' => $categories,
                'item' => $invoiceItem,
                'mode' => 'edit'
            ])

            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('invoice-items.index') }}"
                   class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>
</x-app-layout>
