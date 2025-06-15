<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">✏️ Edit Disciplinary Action</h2>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('employee-disciplinary-actions.update', $employeeDisciplinaryAction->id) }}"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Employee --}}
                <div class="mb-4">
                    <label for="employee_id" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Employee</label>
                    <select name="employee_id" id="employee_id"
                            class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        <option value="">Select Employee</option>
                        @foreach ($employees as $id => $name)
                            <option value="{{ $id }}"
                                {{ old('employee_id', $employeeDisciplinaryAction->employee_id) == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Incident Date --}}
                <div class="mb-4">
                    <label for="incident_date" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Incident Date</label>
                    <input type="date" name="incident_date" id="incident_date"
                           value="{{ old('incident_date', $employeeDisciplinaryAction->incident_date->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                </div>

                {{-- Action Date --}}
                <div class="mb-4">
                    <label for="action_date" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Action Date</label>
                    <input type="date" name="action_date" id="action_date"
                           value="{{ old('action_date', $employeeDisciplinaryAction->action_date->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                </div>

                {{-- Violation Type --}}
                <div class="mb-4">
                    <label for="violation_type" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Violation Type</label>
                    <input type="text" name="violation_type" id="violation_type"
                           value="{{ old('violation_type', $employeeDisciplinaryAction->violation_type) }}"
                           class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                </div>

                {{-- Description --}}
                <div class="mb-4 md:col-span-2">
                    <label for="description" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">{{ old('description', $employeeDisciplinaryAction->description) }}</textarea>
                </div>

                {{-- Action Taken --}}
                <div class="mb-4">
                    <label for="action_taken" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Action Taken</label>
                    <select name="action_taken" id="action_taken"
                            class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        @foreach (['verbal_warning', 'written_warning', 'suspension', 'termination', 'other'] as $type)
                            <option value="{{ $type }}"
                                {{ old('action_taken', $employeeDisciplinaryAction->action_taken) == $type ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Severity Level --}}
                <div class="mb-4">
                    <label for="severity_level" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Severity Level</label>
                    <input type="number" name="severity_level" id="severity_level" min="1" max="5"
                           value="{{ old('severity_level', $employeeDisciplinaryAction->severity_level) }}"
                           class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                </div>

                {{-- Attachment --}}
                <div class="mb-4 md:col-span-2">
                    <label for="attachment_path" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">New Attachment (optional)</label>
                    <input type="file" name="attachment_path" id="attachment_path"
                           class="w-full px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-white border rounded-md dark:border-gray-600">
                    @if ($employeeDisciplinaryAction->attachment_path)
                        <p class="mt-2 text-sm text-blue-600 dark:text-blue-400">
                            <a href="{{ asset('storage/' . $employeeDisciplinaryAction->attachment_path) }}" target="_blank" class="underline">
                                View Current Attachment
                            </a>
                        </p>
                    @endif
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end items-center mt-6">
                <a href="{{ route('employee-disciplinary-actions.index') }}"
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
