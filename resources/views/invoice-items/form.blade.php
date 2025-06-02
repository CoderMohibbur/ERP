<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    {{-- Invoice --}}
    <div>
        <label for="invoice_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Invoice</label>
        <select name="invoice_id" id="invoice_id"
            class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500">
            @foreach ($invoices as $id => $number)
                <option value="{{ $id }}"
                    {{ old('invoice_id', $invoiceItem->invoice_id ?? '') == $id ? 'selected' : '' }}>
                    {{ $number }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Item Name --}}
    <div>
        <label for="item_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Item Name</label>
        <input type="text" name="item_name" id="item_name"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500"
            value="{{ old('item_name', $invoiceItem->item_name ?? '') }}">
    </div>

    {{-- Item Code --}}
    <div>
        <label for="item_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Item Code</label>
        <input type="text" name="item_code" id="item_code"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500"
            value="{{ old('item_code', $invoiceItem->item_code ?? '') }}">
    </div>

    {{-- Quantity --}}
    <div>
        <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity</label>
        <input type="number" name="quantity" id="quantity" min="1"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500"
            value="{{ old('quantity', $invoiceItem->quantity ?? 1) }}">
    </div>

    {{-- Unit Price --}}
    <div>
        <label for="unit_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit Price</label>
        <input type="number" step="0.01" name="unit_price" id="unit_price"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500"
            value="{{ old('unit_price', $invoiceItem->unit_price ?? '') }}">
    </div>

    {{-- Tax Percent --}}
    <div>
        <label for="tax_percent" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tax Percent (%)</label>
        <input type="number" step="0.01" name="tax_percent" id="tax_percent"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500"
            value="{{ old('tax_percent', $invoiceItem->tax_percent ?? '') }}">
    </div>

    {{-- Description (Full width) --}}
    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
        <textarea name="description" id="description" rows="4"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('description', $invoiceItem->description ?? '') }}</textarea>
    </div>
</div>
