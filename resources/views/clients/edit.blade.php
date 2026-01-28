<x-app-layout>
    <div class="w-full max-w-5xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="flex items-start justify-between gap-4 mb-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Edit Client</h2>
                <p class="text-sm text-gray-500 dark:text-gray-300 mt-1">
                    Update basic/company details. Custom fields accept JSON (object/array).
                </p>
            </div>
            <a href="{{ route('clients.index') }}"
               class="text-sm text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500">
                Back
            </a>
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('clients.update', $client->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- ✅ Section: Basic --}}
            <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50/40 dark:bg-gray-900/20">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-3">Basic Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm text-gray-700 dark:text-gray-300">Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $client->name) }}" required
                               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm text-gray-700 dark:text-gray-300">Phone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $client->phone) }}"
                               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label for="email" class="block text-sm text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $client->email) }}"
                               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label for="address" class="block text-sm text-gray-700 dark:text-gray-300">Address</label>
                        <input type="text" name="address" id="address" value="{{ old('address', $client->address) }}"
                               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>
            </div>

            {{-- ✅ Section: Company --}}
            <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50/40 dark:bg-gray-900/20">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-3">Company Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="company_name" class="block text-sm text-gray-700 dark:text-gray-300">Company Name</label>
                        <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $client->company_name) }}"
                               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label for="industry_type" class="block text-sm text-gray-700 dark:text-gray-300">Industry Type</label>
                        <input type="text" name="industry_type" id="industry_type" value="{{ old('industry_type', $client->industry_type) }}"
                               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label for="website" class="block text-sm text-gray-700 dark:text-gray-300">Website</label>
                        <input type="url" name="website" id="website" value="{{ old('website', $client->website) }}"
                               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label for="tax_id" class="block text-sm text-gray-700 dark:text-gray-300">Tax ID</label>
                        <input type="text" name="tax_id" id="tax_id" value="{{ old('tax_id', $client->tax_id) }}"
                               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label for="status" class="block text-sm text-gray-700 dark:text-gray-300">Status</label>
                        <select name="status" id="status"
                                class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="active" {{ old('status', $client->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $client->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- ✅ Section: Advanced (Custom Fields) --}}
            @php
                $defaultJson = '';
                if (!empty($client->custom_fields) && is_array($client->custom_fields)) {
                    $defaultJson = json_encode($client->custom_fields, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                }
            @endphp

            <details class="p-4 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50/40 dark:bg-gray-900/20">
                <summary class="cursor-pointer select-none text-sm font-semibold text-gray-800 dark:text-white">
                    Advanced (Custom Fields JSON)
                </summary>

                <div class="mt-3" x-data="{ formatJson() {
                        const el = this.$refs.cf;
                        const v = (el.value || '').trim();
                        if (!v || v.toLowerCase() === 'null') { el.value = ''; return; }
                        try { el.value = JSON.stringify(JSON.parse(v), null, 2); }
                        catch(e) { /* let server validate */ }
                    } }">
                    <div class="flex items-center justify-between gap-3 mb-2">
                        <p class="text-xs text-gray-500 dark:text-gray-300">
                            Leave blank if none. Must be valid JSON object/array.
                        </p>
                        <button type="button" @click="formatJson()"
                                class="text-xs px-3 py-1 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Format JSON
                        </button>
                    </div>

                    <textarea name="custom_fields" id="custom_fields" rows="6" x-ref="cf"
                              class="w-full mt-1 px-4 py-2 font-mono text-sm border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                              placeholder='{"LinkedIn":"https://...","Notes":"..."}'>{{ old('custom_fields', $defaultJson) }}</textarea>
                </div>
            </details>

            <div class="flex justify-end gap-3">
                <a href="{{ route('clients.index') }}"
                   class="text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
