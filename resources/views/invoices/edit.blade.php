<x-app-layout>
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Edit Invoice</h2>

    <x-validation-errors class="mb-4" />

    <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Invoice Number --}}
            <div>
                <label for="invoice_number" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Invoice Number</label>
                <input type="text" name="invoice_number" id="invoice_number"
                       value="{{ old('invoice_number', $invoice->invoice_number) }}" required
                       class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            {{-- Invoice Type --}}
            <div>
                <label for="invoice_type" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Invoice Type</label>
                <select name="invoice_type" id="invoice_type"
                        class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="final" {{ old('invoice_type', $invoice->invoice_type) === 'final' ? 'selected' : '' }}>Final</option>
                    <option value="proforma" {{ old('invoice_type', $invoice->invoice_type) === 'proforma' ? 'selected' : '' }}>Proforma</option>
                </select>
            </div>

            {{-- Client --}}
            <div>
                <label for="client_id" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Client</label>
                <select name="client_id" id="client_id" required
                        class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach($clients as $id => $name)
                        <option value="{{ $id }}" {{ old('client_id', $invoice->client_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Project --}}
            <div>
                <label for="project_id" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Project (optional)</label>
                <select name="project_id" id="project_id"
                        class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">None</option>
                    @foreach($projects as $id => $title)
                        <option value="{{ $id }}" {{ old('project_id', $invoice->project_id) == $id ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Issue Date --}}
            <div>
                <label for="issue_date" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Issue Date</label>
                <input type="date" name="issue_date" id="issue_date"
                       value="{{ old('issue_date', $invoice->issue_date->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            {{-- Due Date --}}
            <div>
                <label for="due_date" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Due Date</label>
                <input type="date" name="due_date" id="due_date"
                       value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            {{-- Currency --}}
            <div>
                <label for="currency" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Currency</label>
                <input type="text" name="currency" id="currency"
                       value="{{ old('currency', $invoice->currency) }}" maxlength="10"
                       class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            {{-- Sub Total --}}
            <div>
                <label for="sub_total" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Sub Total</label>
                <input type="number" name="sub_total" id="sub_total" step="0.01" min="0"
                       value="{{ old('sub_total', $invoice->sub_total) }}"
                       class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>
        </div>

        {{-- Notes --}}
        <div class="mt-4">
            <label for="notes" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Notes</label>
            <textarea name="notes" id="notes" rows="3"
                      class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('notes', $invoice->notes) }}</textarea>
        </div>

        {{-- Submit Buttons --}}
        <div class="flex justify-end items-center mt-6">
            <a href="{{ route('invoices.index') }}"
               class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500 dark:hover:text-red-500">
                Cancel
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Update Invoice
            </button>
        </div>
    </form>
</x-app-layout>
