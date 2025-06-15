<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
            ➕ Add Invoice Items
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8">
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 text-red-800 border border-red-300 rounded">
                <ul class="list-disc ml-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('invoice-items.store') }}">
            @csrf

            <!-- Invoice Dropdown -->
            <div class="mb-6">
                <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Select Invoice</label>
                <select name="invoice_id"
                        class="w-full border rounded px-3 py-2 bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100"
                        required>
                    <option value="" disabled {{ old('invoice_id') ? '' : 'selected' }}
                        class="text-gray-400 dark:text-gray-500 bg-white dark:bg-gray-800">
                        -- Choose Invoice --
                    </option>
                    @foreach ($invoices as $id => $number)
                        <option value="{{ $id }}" {{ old('invoice_id') == $id ? 'selected' : '' }}>
                            {{ $number }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Dynamic Item Fields -->
            <div id="items-wrapper">
                @include('invoice-items.form', ['index' => 0, 'categories' => $categories])
            </div>

            <div class="mt-4 text-right">
                <button type="button" onclick="addItemField()"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    ➕ Add More Item
                </button>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('invoice-items.index') }}"
                   class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save Items</button>
            </div>
        </form>
    </div>

    <script>
        let index = 1;

        function addItemField() {
            fetch(`/invoice-items/row-template/${index}`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('items-wrapper').insertAdjacentHTML('beforeend', html);
                    index++;
                });
        }
    </script>
</x-app-layout>
