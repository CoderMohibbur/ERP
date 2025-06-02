<div id="item-list">
    <div class="item-row grid grid-cols-1 md:grid-cols-6 gap-4 mb-4">
        <input type="text" name="items[0][item_name]" placeholder="Item Name"
               class="col-span-1 md:col-span-2 px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">

        <input type="number" name="items[0][quantity]" placeholder="Qty" min="1"
               class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">

        <input type="number" name="items[0][unit_price]" placeholder="Unit Price" step="0.01"
               class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">

        <input type="number" name="items[0][tax_percent]" placeholder="Tax %" step="0.01"
               class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">

        <input type="text" name="items[0][description]" placeholder="Description"
               class="col-span-1 md:col-span-1 px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">

        <button type="button" class="remove-item px-3 py-2 bg-red-600 text-white rounded">×</button>
    </div>
</div>

<div class="mb-4">
    <button type="button" id="add-item"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        + Add Item
    </button>
</div>

@push('scripts')
<script>
    let index = 1;
    document.getElementById('add-item').addEventListener('click', function () {
        const itemList = document.getElementById('item-list');
        const newRow = document.createElement('div');
        newRow.classList.add('item-row', 'grid', 'grid-cols-1', 'md:grid-cols-6', 'gap-4', 'mb-4');
        newRow.innerHTML = `
            <input type="text" name="items[${index}][item_name]" placeholder="Item Name"
                   class="col-span-1 md:col-span-2 px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">
            <input type="number" name="items[${index}][quantity]" placeholder="Qty" min="1"
                   class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">
            <input type="number" name="items[${index}][unit_price]" placeholder="Unit Price" step="0.01"
                   class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">
            <input type="number" name="items[${index}][tax_percent]" placeholder="Tax %" step="0.01"
                   class="px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">
            <input type="text" name="items[${index}][description]" placeholder="Description"
                   class="col-span-1 md:col-span-1 px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">
            <button type="button" class="remove-item px-3 py-2 bg-red-600 text-white rounded">×</button>
        `;
        itemList.appendChild(newRow);
        index++;
    });

    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-item')) {
            e.target.closest('.item-row').remove();
        }
    });
</script>
@endpush
