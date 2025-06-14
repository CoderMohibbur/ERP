<x-app-layout>
    <div class="w-full max-w-4xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Add New Client</h2>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('clients.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- 🔹 Personal Info --}}
                <div>
                    <label for="name" class="block text-sm text-gray-700 dark:text-gray-300">Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div>
                    <label for="email" class="block text-sm text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div>
                    <label for="phone" class="block text-sm text-gray-700 dark:text-gray-300">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                        class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div>
                    <label for="address" class="block text-sm text-gray-700 dark:text-gray-300">Address</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}"
                        class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                {{-- 🏢 Company Info --}}
                <div>
                    <label for="company_name" class="block text-sm text-gray-700 dark:text-gray-300">Company Name</label>
                    <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}"
                        class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div>
                    <label for="industry_type" class="block text-sm text-gray-700 dark:text-gray-300">Industry Type</label>
                    <input type="text" name="industry_type" id="industry_type" value="{{ old('industry_type') }}"
                        class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div>
                    <label for="website" class="block text-sm text-gray-700 dark:text-gray-300">Website</label>
                    <input type="url" name="website" id="website" value="{{ old('website') }}"
                        class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div>
                    <label for="tax_id" class="block text-sm text-gray-700 dark:text-gray-300">Tax ID</label>
                    <input type="text" name="tax_id" id="tax_id" value="{{ old('tax_id') }}"
                        class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                {{-- 🔘 Status --}}
                <div>
                    <label for="status" class="block text-sm text-gray-700 dark:text-gray-300">Status</label>
                    <select name="status" id="status"
                        class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                {{-- 🧩 Custom Fields (optional) --}}
                <div class="md:col-span-2">
                    <label for="custom_fields" class="block text-sm text-gray-700 dark:text-gray-300">Custom Fields (JSON)</label>
                    <textarea name="custom_fields" id="custom_fields" rows="3"
                        class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder='{"LinkedIn": "https://...", "Notes": "Priority client"}'>{{ old('custom_fields') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('clients.index') }}"
                    class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500">
                    Cancel
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Save
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
