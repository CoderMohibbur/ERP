{{-- resources/views/attendances/form-fields.blade.php --}}
<div class="grid grid-cols-1 gap-5">
    <div>
        <label for="employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Employee</label>
        <select name="employee_id" id="employee_id" required
                class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">Select Employee</option>
            @foreach($employees as $id => $name)
                <option value="{{ $id }}" {{ old('employee_id', $attendance->employee_id ?? '') == $id ? 'selected' : '' }}>
                    {{ $name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
        <input type="date" name="date" id="date"
               value="{{ old('date', isset($attendance) ? $attendance->date->format('Y-m-d') : '') }}" required
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    <div>
        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
        <select name="status" id="status" required
                class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">Select Status</option>
            @foreach(['present' => 'Present', 'late' => 'Late', 'absent' => 'Absent'] as $value => $label)
                <option value="{{ $value }}" {{ old('status', $attendance->status ?? '') == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Note</label>
        <input type="text" name="note" id="note"
               value="{{ old('note', $attendance->note ?? '') }}"
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>
</div>
