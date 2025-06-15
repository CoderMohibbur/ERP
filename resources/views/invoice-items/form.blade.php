<div class="grid grid-cols-6 gap-4 mb-4 item-group">
       <input type="text" name="items[{{ $index }}][item_name]" placeholder="Item Name"
                 class="col-span-2 border px-3 py-2 rounded bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100" required>

       <input type="text" name="items[{{ $index }}][item_code]" placeholder="Item Code (optional)"
                 class="border px-3 py-2 rounded bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100">

       <input type="number" name="items[{{ $index }}][quantity]" placeholder="Qty" min="1"
                 class="border px-3 py-2 rounded bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100" required>

       <input type="text" name="items[{{ $index }}][unit]" placeholder="Unit" value="pcs"
                 class="border px-3 py-2 rounded bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100" required>

       <input type="number" step="0.01" name="items[{{ $index }}][unit_price]" placeholder="Unit Price"
                 class="border px-3 py-2 rounded bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100" required>

       <input type="number" step="0.01" name="items[{{ $index }}][total]" placeholder="Total"
                 class="border px-3 py-2 rounded bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100" required>
</div>
