<x-app-layout>
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-6">Edit Invoice</h2>

    <form method="POST" action="{{ route('invoices.update', $invoice->id) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Client -->
            <div>
                <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Client</label>
                <select name="client_id" id="client_id" class="form-select">
                    @foreach($clients as $id => $name)
                        <option value="{{ $id }}" {{ $invoice->client_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Project -->
            <div>
                <label for="project_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Project</label>
                <select name="project_id" id="project_id" class="form-select">
                    @foreach($projects as $id => $title)
                        <option value="{{ $id }}" {{ $invoice->project_id == $id ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Invoice Number -->
            <div>
                <label for="invoice_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Invoice Number</label>
                <input type="text" name="invoice_number" id="invoice_number" value="{{ $invoice->invoice_number }}" class="form-input" readonly>
            </div>

            <!-- Currency -->
            <div>
                <label for="currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Currency</label>
                <select name="currency" id="currency" class="form-select">
                    <option value="BDT" {{ $invoice->currency == 'BDT' ? 'selected' : '' }}>BDT</option>
                    <option value="USD" {{ $invoice->currency == 'USD' ? 'selected' : '' }}>USD</option>
                </select>
            </div>

            <!-- Issue Date -->
            <div>
                <label for="issue_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Issue Date</label>
                <input type="date" name="issue_date" id="issue_date" value="{{ $invoice->issue_date->format('Y-m-d') }}" class="form-input">
            </div>

            <!-- Due Date -->
            <div>
                <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Due Date</label>
                <input type="date" name="due_date" id="due_date" value="{{ $invoice->due_date->format('Y-m-d') }}" class="form-input">
            </div>

            <!-- Sub Total -->
            <div>
                <label for="sub_total" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sub Total</label>
                <input type="number" name="sub_total" id="sub_total" step="0.01" value="{{ $invoice->sub_total }}" class="form-input">
            </div>

            <!-- Discount -->
            <div>
                <label for="discount_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Discount Type</label>
                <select name="discount_type" id="discount_type" class="form-select">
                    <option value="flat" {{ $invoice->discount_type == 'flat' ? 'selected' : '' }}>Flat</option>
                    <option value="percentage" {{ $invoice->discount_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                </select>
                <input type="number" name="discount_value" id="discount_value" step="0.01" value="{{ $invoice->discount_value }}" class="form-input mt-2">
            </div>

            <!-- Tax Rate -->
            <div>
                <label for="tax_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tax Rate (%)</label>
                <input type="number" name="tax_rate" id="tax_rate" step="0.01" value="{{ $invoice->tax_rate }}" class="form-input">
            </div>

            <!-- Total Amount -->
            <div>
                <label for="total_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Amount</label>
                <input type="number" name="total_amount" id="total_amount" step="0.01" value="{{ $invoice->total_amount }}" class="form-input">
            </div>

            <!-- Paid & Due -->
            <div>
                <label for="paid_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Paid Amount</label>
                <input type="number" name="paid_amount" id="paid_amount" step="0.01" value="{{ $invoice->paid_amount }}" class="form-input">
            </div>

            <div>
                <label for="due_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Due Amount</label>
                <input type="number" name="due_amount" id="due_amount" step="0.01" value="{{ $invoice->due_amount }}" class="form-input">
            </div>

            <!-- Notes -->
            <div class="md:col-span-2">
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                <textarea name="notes" id="notes" rows="4" class="form-textarea">{{ $invoice->notes }}</textarea>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <a href="{{ route('invoices.index') }}" class="btn-secondary mr-3">Cancel</a>
            <button type="submit" class="btn-primary">Update Invoice</button>
        </div>
    </form>
</x-app-layout>
