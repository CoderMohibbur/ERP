<x-app-layout>
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Add Payment</h2>

    <form action="{{ route('payments.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="invoice_id" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Invoice</label>
                <select name="invoice_id" id="invoice_id" class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach($invoices as $id => $number)
                        <option value="{{ $id }}" {{ old('invoice_id') == $id ? 'selected' : '' }}>{{ $number }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="payment_method_id" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Payment Method</label>
                <select name="payment_method_id" id="payment_method_id" class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach($methods as $id => $method)
                        <option value="{{ $id }}" {{ old('payment_method_id') == $id ? 'selected' : '' }}>{{ $method }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="amount" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Amount</label>
                <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01"
                       class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
            </div>

            <div>
                <label for="paid_at" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Paid At</label>
                <input type="date" name="paid_at" id="paid_at" value="{{ old('paid_at') }}"
                       class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
            </div>
        </div>

        <div class="flex justify-end items-center mt-6">
            <a href="{{ route('payments.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500">Cancel</a>
            <button type="submit" class="ml-3 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Save</button>
        </div>
    </form>
</x-app-layout>
