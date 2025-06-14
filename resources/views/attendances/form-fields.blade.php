{{-- resources/views/attendances/form-fields.blade.php --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    {{-- Employee --}}
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

    {{-- Date --}}
    <div>
        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
        <input type="date" name="date" id="date"
               value="{{ old('date', isset($attendance) ? $attendance->date->format('Y-m-d') : now()->format('Y-m-d') ) }}"
               required
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    {{-- Status --}}
    <div>
        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
        <select name="status" id="status" required
                class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">Select Status</option>
            @foreach(['present' => 'Present', 'late' => 'Late', 'absent' => 'Absent', 'leave' => 'Leave'] as $value => $label)
                <option value="{{ $value }}" {{ old('status', $attendance->status ?? '') == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Note --}}
    <div>
        <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Note</label>
        <input type="text" name="note" id="note"
               value="{{ old('note', $attendance->note ?? '') }}"
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    {{-- In Time --}}
    <div>
        <label for="in_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">In Time</label>
        <input type="time" name="in_time" id="in_time"
               value="{{ old('in_time', isset($attendance) ? $attendance->in_time->format('H:i') : '') }}"
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    {{-- Out Time --}}
    <div>
        <label for="out_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Out Time</label>
        <input type="time" name="out_time" id="out_time"
               value="{{ old('out_time', isset($attendance) ? $attendance->out_time->format('H:i') : '') }}"
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    {{-- Worked Hours --}}
    <div>
        <label for="worked_hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Worked Hours</label>
        <input type="number" name="worked_hours" id="worked_hours" step="0.25" min="0"
               value="{{ old('worked_hours', $attendance->worked_hours ?? '') }}"
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    {{-- Late By Minutes --}}
    <div>
        <label for="late_by_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Late (minutes)</label>
        <input type="number" name="late_by_minutes" id="late_by_minutes" min="0"
               value="{{ old('late_by_minutes', $attendance->late_by_minutes ?? '') }}"
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    {{-- Early Leave Minutes --}}
    <div>
        <label for="early_leave_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Left Early (minutes)</label>
        <input type="number" name="early_leave_minutes" id="early_leave_minutes" min="0"
               value="{{ old('early_leave_minutes', $attendance->early_leave_minutes ?? '') }}"
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    {{-- Location --}}
    <div>
        <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
        <input type="text" name="location" id="location"
               value="{{ old('location', $attendance->location ?? '') }}"
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    {{-- Device Type --}}
    <div>
        <label for="device_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Device</label>
        <select name="device_type" id="device_type"
                class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">Select Device</option>
            @foreach(['web' => 'Web', 'mobile' => 'Mobile', 'kiosk' => 'Kiosk'] as $key => $value)
                <option value="{{ $key }}" {{ old('device_type', $attendance->device_type ?? '') == $key ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>
</div>
