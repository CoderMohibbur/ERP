<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">✏️ Edit Resignation</h2>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('employee-resignations.update', $employeeResignation->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                {{-- Employee --}}
                <div class="mb-4">
                    <label for="employee_id" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Employee</label>
                    <select name="employee_id" id="employee_id"
                            class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        <option value="">Select Employee</option>
                        @foreach ($employees as $id => $name)
                            <option value="{{ $id }}"
                                {{ old('employee_id', $employeeResignation->employee_id) == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Resignation Date --}}
                <div class="mb-4">
                    <label for="resignation_date" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Resignation Date</label>
                    <input type="date" name="resignation_date" id="resignation_date"
                           value="{{ old('resignation_date', $employeeResignation->resignation_date->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                </div>

                {{-- Effective Date --}}
                <div class="mb-4">
                    <label for="effective_date" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Effective Date</label>
                    <input type="date" name="effective_date" id="effective_date"
                           value="{{ old('effective_date', $employeeResignation->effective_date->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                </div>

                {{-- Reason --}}
                <div class="mb-4">
                    <label for="reason" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Short Reason</label>
                    <input type="text" name="reason" id="reason"
                           value="{{ old('reason', $employeeResignation->reason) }}"
                           class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                </div>

                {{-- Details --}}
                <div class="mb-4 md:col-span-2 lg:col-span-2">
                    <label for="details" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Details</label>
                    <textarea name="details" id="details" rows="4"
                              class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">{{ old('details', $employeeResignation->details) }}</textarea>
                </div>

                {{-- Status --}}
                <div class="mb-4">
                    <label for="status" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Status</label>
                    <select name="status" id="status"
                            class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        @foreach (['pending', 'approved', 'rejected'] as $status)
                            <option value="{{ $status }}"
                                {{ old('status', $employeeResignation->status) == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end items-center mt-6">
                <a href="{{ route('employee-resignations.index') }}"
                   class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
