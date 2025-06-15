<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
            ➕ Upload Employee Document
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 text-red-800 border border-red-300 rounded dark:bg-red-900 dark:text-red-200 dark:border-red-700">
                <ul class="list-disc ml-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('employee-documents.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Employee -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Employee</label>
                    <select name="employee_id" required class="w-full mt-1 px-3 py-2 border rounded bg-white text-gray-900 border-gray-300 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400">
                        <option value="">-- Select Employee --</option>
                        @foreach ($employees as $id => $name)
                            <option value="{{ $id }}" {{ old('employee_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Document Type</label>
                    <input type="text" name="type" value="{{ old('type') }}"
                           class="w-full mt-1 px-3 py-2 border rounded bg-white text-gray-900 border-gray-300 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400" required placeholder="e.g. NID, Contract">
                </div>

                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Title (Optional)</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="w-full mt-1 px-3 py-2 border rounded bg-white text-gray-900 border-gray-300 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400" placeholder="e.g. National ID Card">
                </div>

                <!-- Visibility -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Visibility</label>
                    <select name="visibility" class="w-full mt-1 px-3 py-2 border rounded bg-white text-gray-900 border-gray-300 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400" required>
                        @php $options = ['admin', 'employee', 'private']; @endphp
                        @foreach ($options as $option)
                            <option value="{{ $option }}" {{ old('visibility') == $option ? 'selected' : '' }}>
                                {{ ucfirst($option) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Expires At -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Expires At (optional)</label>
                    <input type="date" name="expires_at" value="{{ old('expires_at') }}"
                           class="w-full mt-1 px-3 py-2 border rounded bg-white text-gray-900 border-gray-300 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400">
                </div>

                <!-- File -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Upload File</label>
                    <input type="file" name="file"
                           class="w-full mt-1 px-3 py-2 border rounded bg-white text-gray-900 border-gray-300 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400" required>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Notes (Optional)</label>
                    <textarea name="notes" rows="3"
                              class="w-full mt-1 px-3 py-2 border rounded bg-white text-gray-900 border-gray-300 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('employee-documents.index') }}"
                   class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600">Cancel</a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">Upload</button>
            </div>
        </form>
    </div>
</x-app-layout>
