<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
            ➕ Create New Shift
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

        <form method="POST" action="{{ route('shifts.store') }}">
            @csrf

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                <!-- Shift Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Shift Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                </div>

                <!-- Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}" required
                           class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                </div>

                <!-- Slug (optional) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug') }}"
                           placeholder="Auto-generated if blank"
                           class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Type</label>
                    <select name="type"
                            class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        <option value="">-- Select --</option>
                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                        <option value="flexible" {{ old('type') == 'flexible' ? 'selected' : '' }}>Flexible</option>
                        <option value="split" {{ old('type') == 'split' ? 'selected' : '' }}>Split</option>
                    </select>
                </div>

                <!-- Start Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Start Time <span class="text-red-500">*</span></label>
                    <input type="time" name="start_time" value="{{ old('start_time') }}" required
                           class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                </div>

                <!-- End Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">End Time <span class="text-red-500">*</span></label>
                    <input type="time" name="end_time" value="{{ old('end_time') }}" required
                           class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                </div>

                <!-- Color -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Color (optional)</label>
                    <input type="color" name="color" value="{{ old('color', '#3b82f6') }}"
                           class="mt-1 w-16 h-10 rounded border border-gray-300 dark:border-gray-600">
                </div>

                <!-- Week Days -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Week Days</label>
                    <select name="week_days[]" multiple
                            class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        @foreach (['sun','mon','tue','wed','thu','fri','sat'] as $day)
                            <option value="{{ $day }}" {{ collect(old('week_days'))->contains($day) ? 'selected' : '' }}>
                                {{ ucfirst($day) }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-sm text-gray-500 dark:text-gray-400">Hold Ctrl (Windows) or ⌘ (Mac) to select multiple.</small>
                </div>

                <!-- Crosses Midnight -->
                <div class="flex items-center space-x-2 mt-4">
                    <input type="checkbox" name="crosses_midnight" value="1"
                           {{ old('crosses_midnight') ? 'checked' : '' }}
                           class="text-blue-600 border-gray-300 focus:ring-blue-500 rounded">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Crosses Midnight</label>
                </div>

                <!-- Is Active -->
                <div class="flex items-center space-x-2 mt-4">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="text-green-600 border-gray-300 focus:ring-green-500 rounded">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Is Active</label>
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Notes</label>
                <textarea name="notes" rows="3"
                          class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                          placeholder="Optional description or rules...">{{ old('notes') }}</textarea>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('shifts.index') }}"
                   class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save Shift</button>
            </div>
        </form>
    </div>
</x-app-layout>
