<x-app-layout>
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-6">Create Invoice</h2>

    <form action="{{ route('invoices.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Client --}}
            <div>
                <label for="client_id" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Client</label>
                <select name="client_id" id="client_id" required
                    class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white">
                    <option value="">Select Client</option>
                    @foreach($clients as $id => $name)
                        <option value="{{ $id }}" {{ old('client_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Project --}}
            <div>
                <label for="project_id" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Project</label>
                <select name="project_id" id="project_id"
                    class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white">
                    <option value="">Select Project</option>
                    @foreach($projects as $id => $title)
                        <option value="{{ $id }}" {{ old('project_id') == $id ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Issue Date --}}
            <div>
                <label for="issue_date" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Issue Date</label>
                <input type="date" name="issue_date" id="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}"
                    class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white" required>
            </div>

            {{-- Due Date --}}
            <div>
                <label for="due_date" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Due Date</label>
                <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}"
                    class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white" required>
            </div>

            {{-- Currency --}}
            <div>
                <label for="currency" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Currency</label>
                <select name="currency" id="currency" class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white">
                    <option value="BDT" {{ old('currency') == 'BDT' ? 'selected' : '' }}>BDT</option>
                    <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                </select>
            </div>

            {{-- Status --}}
            <div>
                <label for="status" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Status</label>
                <select name="status" id="status" class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white">
                    <option value="draft">Draft</option>
                    <option value="sent">Sent</option>
                    <option value="paid">Paid</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>

            {{-- Sub Total --}}
            <div>
                <label for="sub_total" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Sub Total</label>
                <input type="number" step="0.01" name="sub_total" id="sub_total" value="{{ old('sub_total') }}"
                    class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white" required>
            </div>

            {{-- Discount Type --}}
            <div>
                <label for="discount_type" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Discount Type</label>
                <select name="discount_type" id="discount_type"
                    class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white">
                    <option value="">None</option>
                    <option value="flat">Flat</option>
                    <option value="percentage">Percentage</option>
                </select>
            </div>

            {{-- Discount Value --}}
            <div>
                <label for="discount_value" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Discount Value</label>
                <input type="number" step="0.01" name="discount_value" id="discount_value" value="{{ old('discount_value') }}"
                    class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white">
            </div>

            {{-- Tax Rate --}}
            <div>
                <label for="tax_rate" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Tax Rate (%)</label>
                <input type="number" step="0.01" name="tax_rate" id="tax_rate" value="{{ old('tax_rate') }}"
                    class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white">
            </div>

            {{-- Total Amount --}}
            <div>
                <label for="total_amount" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Total Amount</label>
                <input type="number" step="0.01" name="total_amount" id="total_amount" value="{{ old('total_amount') }}"
                    class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white" required>
            </div>

            {{-- Paid Amount --}}
            <div>
                <label for="paid_amount" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Paid Amount</label>
                <input type="number" step="0.01" name="paid_amount" id="paid_amount" value="{{ old('paid_amount') }}"
                    class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white">
            </div>

            {{-- Due Amount --}}
            <div>
                <label for="due_amount" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Due Amount</label>
                <input type="number" step="0.01" name="due_amount" id="due_amount" value="{{ old('due_amount') }}"
                    class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white">
            </div>

            {{-- Notes --}}
            <div class="md:col-span-2">
                <label for="notes" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Notes</label>
                <textarea name="notes" id="notes" rows="4"
                    class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-white">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <a href="{{ route('invoices.index') }}" class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Save</button>
        </div>
    </form>
</x-app-layout>
