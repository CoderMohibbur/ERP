            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $employee->name ?? '') }}"
                        required
                        class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" name="email" id="email"
                        value="{{ old('email', $employee->email ?? '') }}" required
                        class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div>
                    <label for="phone"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                    <input type="text" name="phone" id="phone"
                        value="{{ old('phone', $employee->phone ?? '') }}"
                        class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div>
                    <label for="join_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Join
                        Date</label>
                    <input type="date" name="join_date" id="join_date"
                        value="{{ old('join_date', isset($employee) ? $employee->join_date->format('Y-m-d') : '') }}"
                        required
                        class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div>
                    <label for="photo"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Photo</label>
                    <input type="file" name="photo" id="photo"
                        class="w-full mt-1 text-sm text-gray-500 dark:text-gray-300">
                    @if (isset($employee) && $employee->photo)
                        <img src="{{ asset('storage/' . $employee->photo) }}" alt="Preview"
                            class="mt-2 h-16 rounded shadow">
                    @endif
                </div>

                <div>
                    <label for="department_id"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Department</label>
                    <select name="department_id" id="department_id" required
                        class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Select Department</option>
                        @foreach ($departments as $id => $name)
                            <option value="{{ $id }}"
                                {{ old('department_id', $employee->department_id ?? '') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="designation_id"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Designation</label>
                    <select name="designation_id" id="designation_id" required
                        class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Select Designation</option>
                        @foreach ($designations as $id => $name)
                            <option value="{{ $id }}"
                                {{ old('designation_id', $employee->designation_id ?? '') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
