<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
            ➕ Add Employee Dependent
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8">
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-100 border border-red-300 dark:border-red-600 rounded">
                <ul class="list-disc ml-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('employee-dependents.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Employee -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Employee</label>
                    <select name="employee_id" required
                            class="mt-1 w-full border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        <option value="">-- Select --</option>
                        @foreach ($employees as $id => $name)
                            <option value="{{ $id }}" {{ old('employee_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Dependent Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                </div>

                <!-- Relation -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Relation</label>
                    <select name="relation" required
                            class="mt-1 w-full border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        @foreach(['spouse','child','father','mother','sibling','other'] as $rel)
                            <option value="{{ $rel }}" {{ old('relation') == $rel ? 'selected' : '' }}>
                                {{ ucfirst($rel) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- DOB -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Date of Birth</label>
                    <input type="date" name="dob" value="{{ old('dob') }}"
                           class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                           placeholder="Optional">
                </div>

                <!-- NID -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">NID Number</label>
                    <input type="text" name="nid_number" value="{{ old('nid_number') }}"
                           class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                           placeholder="Optional">
                </div>

                <!-- Emergency Contact -->
                <div class="flex items-center mt-2 space-x-2">
                    <input type="checkbox" name="is_emergency_contact" value="1"
                           {{ old('is_emergency_contact') ? 'checked' : '' }}
                           class="text-blue-600 border-gray-300 focus:ring-blue-500 rounded">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Emergency Contact</label>
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Notes</label>
                <textarea name="notes" rows="3"
                          class="mt-1 w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                          placeholder="Optional note">{{ old('notes') }}</textarea>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('employee-dependents.index') }}"
                   class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save Dependent</button>
            </div>
        </form>
    </div>
</x-app-layout>
