<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
            ✏️ Edit Shift
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

        <form method="POST" action="{{ route('shifts.update', $shift) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Shift Name</label>
                    <input type="text" name="name" value="{{ old('name', $shift->name) }}" required
                           class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                </div>

                <!-- Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Code</label>
                    <input type="text" name="code" value="{{ old('code', $shift->code) }}" required
                           class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                </div>

                <!-- Slug -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $shift->slug) }}"
                           class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                           placeholder="Auto-generated if blank">
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Type</label>
                    <select name="type"
                            class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        <option value="">-- Select --</option>
                        @foreach (['fixed', 'flexible', 'split'] as $type)
                            <option value="{{ $type }}" {{ old('type', $shift->type) == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Start & End Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Start Time</label>
                    <input type="time" name="start_time" value="{{ old('start_time', $shift->start_time) }}" required
                           class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">End Time</label>
                    <input type="time" name="end_time" value="{{ old('end_time', $shift->end_time) }}" required
                           class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                </div>

                <!-- Color -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Color</label>
                    <input type="color" name="color" value="{{ old('color', $shift->color ?? '#3b82f6') }}"
                           class="mt-1 w-16 h-10 rounded border border-gray-300 dark:border-gray-600">
                </div>

                <!-- Week Days -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Week Days</label>
                    <select name="week_days[]" multiple
                            class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        @foreach (['sun','mon','tue','wed','thu','fri','sat'] as $day)
                            <option value="{{ $day }}"
                                {{ in_array($day, old('week_days', $shift->week_days ?? [])) ? 'selected' : '' }}>
                                {{ ucfirst($day) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Crosses Midnight -->
                <div class="flex items-center space-x-2 mt-4">
                    <input type="checkbox" name="crosses_midnight" value="1"
                           {{ old('crosses_midnight', $shift->crosses_midnight) ? 'checked' : '' }}
                           class="text-blue-600 border-gray-300 focus:ring-blue-500 rounded">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Crosses Midnight</label>
                </div>

                <!-- Is Active -->
                <div class="flex items-center space-x-2 mt-4">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $shift->is_active) ? 'checked' : '' }}
                           class="text-green-600 border-gray-300 focus:ring-green-500 rounded">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Is Active</label>
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Notes</label>
                <textarea name="notes" rows="3"
                          class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                          placeholder="Optional description">{{ old('notes', $shift->notes) }}</textarea>
            </div>

            <!-- Submit -->
            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('shifts.index') }}"
                   class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update Shift</button>
            </div>
        </form>
    </div>
</x-app-layout>
