<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    {{-- Name --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
        <input type="text" name="name" id="name" value="{{ old('name', $employee->name ?? '') }}" required
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    {{-- Email --}}
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $employee->email ?? '') }}" required
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    {{-- Phone --}}
    <div>
        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
        <input type="text" name="phone" id="phone" value="{{ old('phone', $employee->phone ?? '') }}"
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    {{-- Join Date --}}
    <div>
        <label for="join_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Join Date & Time</label>
        <input type="datetime-local" name="join_date" id="join_date"
               value="{{ old('join_date', isset($employee) ? $employee->join_date->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
               required
               class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
    </div>

    {{-- Photo Upload --}}
    <div>
        <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Photo</label>
        <input type="file" name="photo" id="photo"
               class="block w-full text-sm text-gray-900 bg-white border border-gray-300 rounded-lg cursor-pointer
                      dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600
                      file:border-0 file:bg-gray-100 file:dark:bg-gray-600 file:text-gray-700
                      file:px-4 file:py-2 file:rounded-md hover:file:bg-gray-200 dark:hover:file:bg-gray-700">

        @if (isset($employee) && $employee->photo)
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Current:</p>
            <img src="{{ asset('storage/' . $employee->photo) }}" alt="Photo"
                 class="mt-1 h-14 w-14 rounded border border-gray-300 dark:border-gray-600 object-cover">
        @endif
    </div>

    {{-- Department --}}
    <div>
        <label for="department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Department</label>
        <select name="department_id" id="department_id" required
                class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">Select Department</option>
            <option value="add_new" class="text-purple-500 font-semibold">➕ Add Department</option>
            @foreach ($departments as $id => $name)
                <option value="{{ $id }}"
                    {{ old('department_id', $employee->department_id ?? '') == $id ? 'selected' : '' }}>
                    {{ $name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Designation --}}
    <div>
        <label for="designation_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Designation</label>
        <select name="designation_id" id="designation_id" required
                class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="">Select Designation</option>
            <option value="add_new_designation" class="text-purple-500 font-semibold">➕ Add Designation</option>

            @if (isset($newDesignation))
                <option value="{{ $newDesignation->id }}" selected>{{ $newDesignation->name }}</option>
            @endif

            @foreach ($designations as $id => $name)
                <option value="{{ $id }}"
                    {{ old('designation_id', $employee->designation_id ?? '') == $id ? 'selected' : '' }}>
                    {{ $name }}
                </option>
            @endforeach
        </select>
    </div>
</div>
