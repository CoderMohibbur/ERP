<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">➕ Create Tax Rule</h2>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('tax-rules.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Name --}}
                <div class="mb-4">
                    <label for="name" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Tax Name</label>
                    <input type="text" name="name" id="name"
                           value="{{ old('name') }}"
                           class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600"
                           placeholder="e.g., VAT 15%">
                </div>

                {{-- Rate Percent --}}
                <div class="mb-4">
                    <label for="rate_percent" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Rate (%)</label>
                    <input type="number" step="0.001" name="rate_percent" id="rate_percent"
                           value="{{ old('rate_percent') }}"
                           class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600"
                           placeholder="e.g., 15.000">
                </div>

                {{-- Scope --}}
                <div class="mb-4">
                    <label for="scope" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Scope</label>
                    <select name="scope" id="scope"
                            class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        @foreach (['global', 'category', 'item', 'project'] as $option)
                            <option value="{{ $option }}" {{ old('scope') == $option ? 'selected' : '' }}>
                                {{ ucfirst($option) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Is Active --}}
                <div class="mb-4">
                    <label for="is_active" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Status</label>
                    <select name="is_active" id="is_active"
                            class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                {{-- Applicable From --}}
                <div class="mb-4">
                    <label for="applicable_from" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Applicable From</label>
                    <input type="date" name="applicable_from" id="applicable_from"
                           value="{{ old('applicable_from') }}"
                           class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                </div>

                {{-- Applicable To --}}
                <div class="mb-4">
                    <label for="applicable_to" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Applicable To</label>
                    <input type="date" name="applicable_to" id="applicable_to"
                           value="{{ old('applicable_to') }}"
                           class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600">
                </div>

                {{-- Country Code --}}
                <div class="mb-4">
                    <label for="country_code" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Country Code</label>
                    <input type="text" name="country_code" id="country_code" maxlength="5"
                           value="{{ old('country_code') }}"
                           class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600"
                           placeholder="e.g., BD, US">
                </div>

                {{-- Region --}}
                <div class="mb-4">
                    <label for="region" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Region</label>
                    <input type="text" name="region" id="region"
                           value="{{ old('region') }}"
                           class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600"
                           placeholder="e.g., Dhaka, California">
                </div>

                {{-- Description (span both columns) --}}
                <div class="mb-4 md:col-span-2">
                    <label for="description" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 dark:text-white dark:border-gray-600"
                              placeholder="Optional additional information">{{ old('description') }}</textarea>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end items-center mt-6">
                <a href="{{ route('tax-rules.index') }}"
                   class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Save
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
