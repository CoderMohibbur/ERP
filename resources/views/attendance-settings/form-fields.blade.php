<div class="grid grid-cols-1 gap-5">
    <div>
        <label for="office_start" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Office Start Time</label>
        <input type="time" name="office_start" id="office_start"
               value="{{ old('office_start', isset($setting) ? $setting->office_start->format('H:i') : '') }}" required
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    <div>
        <label for="grace_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grace Minutes</label>
        <input type="number" name="grace_minutes" id="grace_minutes" min="0"
               value="{{ old('grace_minutes', $setting->grace_minutes ?? '') }}" required
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
