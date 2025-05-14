<div class="grid grid-cols-2 gap-4">
    <div>
        <label for="office_start" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Office Start Time</label>
        <input type="time" name="office_start" id="office_start"
               value="{{ old('office_start', isset($setting) ? $setting->office_start->format('H:i') : '') }}" required
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    <div>
        <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Time</label>
        <input type="time" name="start_time" id="start_time"
               value="{{ old('start_time', $setting->start_time ?? '') }}" required
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    <div>
        <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Time</label>
        <input type="time" name="end_time" id="end_time"
               value="{{ old('end_time', $setting->end_time ?? '') }}" required
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    <div>
        <label for="grace_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grace Minutes</label>
        <input type="number" name="grace_minutes" id="grace_minutes" min="0"
               value="{{ old('grace_minutes', $setting->grace_minutes ?? '') }}" required
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    <div>
        <label for="half_day_after" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Half Day After (Minutes)</label>
        <input type="number" name="half_day_after" id="half_day_after" min="0"
               value="{{ old('half_day_after', $setting->half_day_after ?? '') }}"
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    <div>
        <label for="working_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Working Days (Numeric)</label>
        <input type="number" name="working_days" id="working_days" min="0" max="7"
               value="{{ old('working_days', $setting->working_days ?? '') }}" required
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    <div>
        <label for="weekend_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Weekend Days</label>
        <select name="weekend_days[]" id="weekend_days" multiple
                class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            @foreach(['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                <option value="{{ $day }}" @if(collect(old('weekend_days', $setting->weekend_days ?? []))->contains($day)) selected @endif>
                    {{ $day }}
                </option>
            @endforeach
        </select>
        <p class="text-xs text-gray-500 mt-1">Hold Ctrl (Cmd on Mac) to select multiple</p>
    </div>
</div>
