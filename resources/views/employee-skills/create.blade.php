<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
            ➕ Assign Skill to Employee
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

        <form method="POST" action="{{ route('employee-skills.store') }}">
            @csrf

            <div class="grid grid-cols-1 gap-6">

                <!-- Employee -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Select Employee <span class="text-red-500">*</span></label>
                    <select name="employee_id" required
                            class="mt-1 block w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        <option value="">-- Choose --</option>
                        @foreach ($employees as $id => $name)
                            <option value="{{ $id }}" {{ old('employee_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Skill -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Select Skill <span class="text-red-500">*</span></label>
                    <select name="skill_id" required
                            class="mt-1 block w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        <option value="">-- Choose --</option>
                        @foreach ($skills as $id => $name)
                            <option value="{{ $id }}" {{ old('skill_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Proficiency Level -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Proficiency Level (1-10)</label>
                    <input type="number" name="proficiency_level" min="1" max="10"
                           value="{{ old('proficiency_level') }}"
                           class="mt-1 block w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                           placeholder="Optional">
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Notes (Optional)</label>
                    <textarea name="notes" rows="3"
                              class="mt-1 block w-full px-3 py-2 border rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                              placeholder="Any additional comments...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('employee-skills.index') }}"
                   class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Assign Skill</button>
            </div>
        </form>
    </div>
</x-app-layout>
