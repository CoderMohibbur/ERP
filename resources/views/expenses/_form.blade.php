@php
    $expense = $expense ?? null;

    $categories = $categories ?? [
        'server' => 'Server',
        'tools' => 'Tools',
        'salary' => 'Salary',
        'office' => 'Office',
        'marketing' => 'Marketing',
        'other' => 'Other',
    ];
@endphp

<div class="grid grid-cols-1 gap-5">
    <div>
        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Title <span class="text-red-500">*</span></label>
        <input type="text" name="title" value="{{ old('title', $expense?->title) }}" required
               class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Category <span class="text-red-500">*</span></label>
            <select name="category" required
                    class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
                @foreach($categories as $k => $label)
                    <option value="{{ $k }}" @selected(old('category', $expense?->category ?? 'other') === $k)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Amount <span class="text-red-500">*</span></label>
            <input type="number" step="0.01" name="amount" value="{{ old('amount', $expense?->amount ?? 0) }}" required
                   class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Currency</label>
            <input type="text" name="currency" value="{{ old('currency', $expense?->currency ?? 'BDT') }}"
                   class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Expense Date <span class="text-red-500">*</span></label>
            <input type="date" name="expense_date" value="{{ old('expense_date', $expense?->expense_date) }}" required
                   class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
        </div>
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Vendor</label>
            <input type="text" name="vendor" value="{{ old('vendor', $expense?->vendor) }}"
                   class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Reference</label>
            <input type="text" name="reference" value="{{ old('reference', $expense?->reference) }}"
                   class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">
        </div>
        <div></div>
    </div>

    <div>
        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
        <textarea name="notes" rows="3"
                  class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-green-500 focus:border-green-500">{{ old('notes', $expense?->notes) }}</textarea>
    </div>
</div>
