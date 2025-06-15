<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
            ✏️ Edit Employee History
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 text-red-800 border border-red-300 rounded dark:bg-red-900 dark:text-red-200 dark:border-red-700">
                <ul class="list-disc ml-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('employee-histories.update', $employeeHistory->id) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Employee -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Employee</label>
                    <select name="employee_id" class="w-full mt-1 px-3 py-2 border rounded bg-white dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700" required>
                        <option value="">-- Select Employee --</option>
                        @foreach ($employees as $id => $name)
                            <option value="{{ $id }}" {{ old('employee_id', $employeeHistory->employee_id) == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Designation -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Designation</label>
                    <select name="designation_id" class="w-full mt-1 px-3 py-2 border rounded bg-white dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700" required>
                        <option value="">-- Select Designation --</option>
                        @foreach ($designations as $id => $name)
                            <option value="{{ $id }}" {{ old('designation_id', $employeeHistory->designation_id) == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Department -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Department (Optional)</label>
                    <select name="department_id" class="w-full mt-1 px-3 py-2 border rounded bg-white dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700">
                        <option value="">-- Select Department --</option>
                        @foreach ($departments as $id => $name)
                            <option value="{{ $id }}" {{ old('department_id', $employeeHistory->department_id) == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Effective From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Effective From</label>
                    <input type="date" name="effective_from"
                           value="{{ old('effective_from', $employeeHistory->effective_from->format('Y-m-d')) }}"
                           class="w-full mt-1 px-3 py-2 border rounded bg-white dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700" required>
                </div>

                <!-- Effective To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Effective To (optional)</label>
                    <input type="date" name="effective_to"
                           value="{{ old('effective_to', optional($employeeHistory->effective_to)->format('Y-m-d')) }}"
                           class="w-full mt-1 px-3 py-2 border rounded bg-white dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700">
                </div>

                <!-- Change Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Change Type</label>
                    <select name="change_type" class="w-full mt-1 px-3 py-2 border rounded bg-white dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700" required>
                        @php
                            $types = ['promotion', 'transfer', 'reinstatement', 'demotion', 'joining'];
                        @endphp
                        @foreach ($types as $type)
                            <option value="{{ $type }}" {{ old('change_type', $employeeHistory->change_type) == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Remarks -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Remarks (Optional)</label>
                    <textarea name="remarks"
                              class="w-full mt-1 px-3 py-2 border rounded bg-white dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700"
                              rows="3">{{ old('remarks', $employeeHistory->remarks) }}</textarea>
                </div>

            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('employee-histories.index') }}"
                   class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600">Cancel</a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">Update</button>
            </div>
        </form>
    </div>
</x-app-layout>
