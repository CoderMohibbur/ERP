<x-app-layout>
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Edit Invoice</h2>

    <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="client_id" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Client</label>
                <select name="client_id" id="client_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach ($clients as $id => $name)
                        <option value="{{ $id }}" {{ old('client_id', $invoice->client_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="project_id" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Project (Optional)</label>
                <select name="project_id" id="project_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">None</option>
                    @foreach ($projects as $id => $name)
                        <option value="{{ $id }}" {{ old('project_id', $invoice->project_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="total_amount" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Total Amount</label>
                <input type="number" step="0.01" name="total_amount" id="total_amount" value="{{ old('total_amount', $invoice->total_amount) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            <div>
                <label for="due_amount" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Due Amount</label>
                <input type="number" step="0.01" name="due_amount" id="due_amount" value="{{ old('due_amount', $invoice->due_amount) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            <div>
                <label for="status" class="block mb-1 text-sm text-gray-600 dark:text-gray-300">Status</label>
                <select name="status" id="status"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="unpaid" {{ old('status', $invoice->status) == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="paid" {{ old('status', $invoice->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="partial" {{ old('status', $invoice->status) == 'partial' ? 'selected' : '' }}>Partial</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end items-center mt-6">
            <a href="{{ route('invoices.index') }}" class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Update</button>
        </div>
    </form>
</x-app-layout>