<x-app-layout>
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Edit Payment</h2>

    <form action="{{ route('payments.update', $payment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            {{-- Invoice --}}
            <div>
                <label for="invoice_id" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Invoice</label>
                <select name="invoice_id" id="invoice_id"
                        class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach($invoices as $id => $number)
                        <option value="{{ $id }}" {{ old('invoice_id', $payment->invoice_id) == $id ? 'selected' : '' }}>
                            {{ $number }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Payment Method --}}
            <div>
                <label for="payment_method_id" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Payment Method</label>
                <select name="payment_method_id" id="payment_method_id"
                        class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach($methods as $id => $name)
                        <option value="{{ $id }}" {{ old('payment_method_id', $payment->payment_method_id) == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Amount --}}
            <div>
                <label for="amount" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Amount</label>
                <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount', $payment->amount) }}"
                       class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            {{-- Paid At --}}
            <div>
                <label for="paid_at" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Paid At</label>
                <input type="date" name="paid_at" id="paid_at" value="{{ old('paid_at', $payment->paid_at->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>
        </div>

        <div class="flex justify-end items-center mt-6">
            <a href="{{ route('payments.index') }}"
               class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Update
            </button>
        </div>
    </form>
</x-app-layout>
