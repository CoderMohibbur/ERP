<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
            ➕ Assign Shift to Employee
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-100 border border-red-300 dark:border-red-600 rounded">
                <ul class="list-disc ml-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('employee-shifts.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Employee -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Employee</label>
                    <select name="employee_id" required
                            class="mt-1 w-full border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        <option value="">-- Select --</option>
                        @foreach($employees as $id => $name)
                            <option value="{{ $id }}" {{ old('employee_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Shift -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Shift</label>
                    <select name="shift_id" required
                            class="mt-1 w-full border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        <option value="">-- Select --</option>
                        @foreach($shifts as $id => $name)
                            <option value="{{ $id }}" {{ old('shift_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Shift Date</label>
                    <input type="date" name="shift_date" value="{{ old('shift_date') }}" required
                           class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                </div>

                <!-- Override Times -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Start Time Override (optional)</label>
                    <input type="time" name="start_time_override" value="{{ old('start_time_override') }}"
                           class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">End Time Override (optional)</label>
                    <input type="time" name="end_time_override" value="{{ old('end_time_override') }}"
                           class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                </div>

                <!-- Override Flag -->
                <div class="flex items-center space-x-2 mt-1">
                    <input type="checkbox" name="is_manual_override" value="1"
                           {{ old('is_manual_override') ? 'checked' : '' }}
                           class="text-blue-600 border-gray-300 focus:ring-blue-500 rounded">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Mark as Manual Override</label>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Status</label>
                    <select name="status" required
                            class="mt-1 w-full border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        <option value="assigned" {{ old('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Verified By (optional) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Verified By (User ID)</label>
                    <input type="number" name="verified_by" value="{{ old('verified_by') }}"
                           class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                           placeholder="Optional">
                </div>
            </div>

            <!-- Remarks -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Remarks</label>
                <textarea name="remarks" rows="3"
                          class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                          placeholder="Optional notes...">{{ old('remarks') }}</textarea>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('employee-shifts.index') }}"
                   class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Assign Shift</button>
            </div>
        </form>
    </div>
</x-app-layout>
