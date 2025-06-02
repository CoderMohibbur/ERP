<x-app-layout>
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Create Task</h2>



    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-md">
            <strong>🚫 Validation Error:</strong>
            <ul class="mt-2 list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif



    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @php
                $newProjectId = session('new_project_id');
            @endphp

            {{-- Project --}}
            <div class="mb-4">
                <label for="project_id"
                    class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Project</label>

                <div
                    class="flex rounded-md shadow-sm overflow-hidden border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                    {{-- Select Dropdown --}}
                    <select name="project_id" id="project_id"
                        class="flex-1 block w-full px-4 py-2 text-sm text-gray-900 dark:text-white bg-transparent border-none focus:outline-none focus:ring-0">
                        <option value="">Select Project</option>

                        @if ($newProjectId)
                            @php
                                $newProject = \App\Models\Project::find($newProjectId);
                            @endphp
                            @if ($newProject)
                                <option value="{{ $newProject->id }}"
                                    {{ (old('project_id') ?? $newProjectId) == $newProject->id ? 'selected' : '' }}>
                                    {{ $newProject->title }}
                                </option>
                            @endif
                        @endif

                        @foreach ($projects as $id => $title)
                            <option value="{{ $id }}"
                                {{ (old('project_id') ?? $newProjectId) == $id ? 'selected' : '' }}>
                                {{ $title }}
                            </option>
                        @endforeach
                    </select>


                    {{-- Add Project Button --}}
                    <button type="button" onclick="openDrawer('project')"
                        class="px-4 text-green-600 hover:text-green-700 flex items-center border-l border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                </div>
            </div>


            {{-- Title --}}
            <div>
                <label for="title" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            {{-- Priority --}}
            <div>
                <label for="priority" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Priority</label>
                <select name="priority" id="priority"
                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                </select>
            </div>

            <div class="relative">
                <label for="assigned_to" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Assigned To</label>
                <div class="flex items-center">
                    <select name="assigned_to" id="assigned_to"
                        class="w-full px-4 py-2 text-sm border border-gray-300 rounded-l-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">-- Select Employee --</option>

                        @if ($newEmployeeId)
                            @php
                                $newEmployee = \App\Models\Employee::find($newEmployeeId);
                            @endphp
                            @if ($newEmployee)
                                <option value="{{ $newEmployee->id }}"
                                    {{ (old('assigned_to') ?? $newEmployeeId) == $newEmployee->id ? 'selected' : '' }}>
                                    {{ $newEmployee->name }}
                                </option>
                            @endif
                        @endif

                        @foreach ($employees as $id => $name)
                            <option value="{{ $id }}"
                                {{ (old('assigned_to') ?? $newEmployeeId) == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>


                    <button type="button" onclick="openDrawer('employee')"
                        class="flex items-center justify-center px-3 py-2 bg-white border border-l-0 border-gray-300 rounded-r-md shadow-sm hover:bg-gray-100 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-5 h-5 text-green-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                </div>
            </div>


            {{-- Progress --}}
            <div>
                <label for="progress" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Progress (%)</label>
                <input type="number" name="progress" id="progress" value="{{ old('progress', 0) }}" min="0"
                    max="100"
                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            {{-- Due Date --}}
            <div>
                <label for="due_date" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Due Date</label>
                <input type="date" name="due_date" id="due_date"
                    value="{{ old('due_date', \Carbon\Carbon::today()->toDateString()) }}"
                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            {{-- Note --}}
            <div class="sm:col-span-2">
                <label for="note" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Note</label>
                <textarea name="note" id="note" rows="4"
                    class="w-full mt-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-700 dark:text-white"
                    placeholder="Enter additional notes or details...">{{ old('note') }}</textarea>
            </div>
        </div>

        <div class="flex justify-end items-center mt-6">
            <a href="{{ route('tasks.index') }}"
                class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500">Cancel</a>
            <button type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">Save</button>
        </div>
    </form>



    <!-- Project Drawer -->
    <div id="drawer-project"
        class="fixed top-0 right-0 w-full max-w-lg h-full bg-gradient-to-br from-green-50 via-white to-green-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out border-l-2 border-green-200 dark:border-green-700 flex flex-col">
        <div
            class="flex items-center justify-between px-8 py-6 border-b border-green-100 dark:border-green-800 bg-gradient-to-r from-green-100 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
            <h2 class="text-2xl font-extrabold text-green-700 dark:text-green-300 flex items-center gap-2">
                <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Project
            </h2>
            <button type="button" onclick="closeDrawer('project')"
                class="text-green-400 hover:text-green-700 dark:text-green-300 dark:hover:text-green-100 transition rounded-full p-1 focus:outline-none focus:ring-2 focus:ring-green-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('projects.store') }}" class="flex-1 flex flex-col justify-between">
            @csrf
            <input type="hidden" name="from_task_form" value="1">
            <div class="p-8 space-y-6 overflow-y-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div>
                        <label for="title"
                            class="block text-sm font-semibold text-green-800 dark:text-green-200 mb-1">Project Name
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" required
                            class="w-full px-4 py-2 border-2 border-green-200 dark:border-green-700 rounded-lg bg-white dark:bg-gray-900 text-green-900 dark:text-green-100 focus:outline-none focus:ring-2 focus:ring-green-400 transition shadow-sm"
                            placeholder="Enter project name">
                    </div>
                    <!-- Client -->
                    <div>
                        <label for="client_id"
                            class="block text-sm font-semibold text-green-800 dark:text-green-200 mb-1">Client</label>
                        <select name="client_id" id="client_id"
                            class="w-full px-4 py-2 border-2 border-green-200 dark:border-green-700 rounded-lg bg-white dark:bg-gray-900 text-green-900 dark:text-green-100 focus:outline-none focus:ring-2 focus:ring-green-400 transition shadow-sm">
                            <option value="">Select Client</option>
                            @foreach ($clients as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Deadline -->
                    <div>
                        <label for="deadline"
                            class="block text-sm font-semibold text-green-800 dark:text-green-200 mb-1">Deadline</label>
                        <input type="date" name="deadline" id="deadline"
                            value="{{ \Carbon\Carbon::today()->toDateString() }}"
                            class="w-full px-4 py-2 border-2 border-green-200 dark:border-green-700 rounded-lg bg-white dark:bg-gray-900 text-green-900 dark:text-green-100 focus:outline-none focus:ring-2 focus:ring-green-400 transition shadow-sm">
                    </div>
                    <!-- Status -->
                    <div>
                        <label for="status"
                            class="block text-sm font-semibold text-green-800 dark:text-green-200 mb-1">Status</label>
                        <select name="status" id="status"
                            class="w-full px-4 py-2 border-2 border-green-200 dark:border-green-700 rounded-lg bg-white dark:bg-gray-900 text-green-900 dark:text-green-100 focus:outline-none focus:ring-2 focus:ring-green-400 transition shadow-sm">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <!-- Description (Full Width) -->
                    <div class="md:col-span-2">
                        <label for="description"
                            class="block text-sm font-semibold text-green-800 dark:text-green-200 mb-1">Description /
                            Note</label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full px-4 py-2 border-2 border-green-200 dark:border-green-700 rounded-lg bg-white dark:bg-gray-900 text-green-900 dark:text-green-100 focus:outline-none focus:ring-2 focus:ring-green-400 transition shadow-sm resize-y"
                            placeholder="Write project details...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
            <div
                class="flex justify-end gap-4 px-8 py-6 border-t border-green-100 dark:border-green-800 bg-gradient-to-r from-green-50 via-white to-green-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
                <button type="button" onclick="closeDrawer('project')"
                    class="px-5 py-2 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 rounded-lg hover:bg-green-200 dark:hover:bg-green-700 transition font-semibold shadow">
                    Cancel
                </button>
                <button type="submit"
                    class="px-6 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition font-bold shadow-lg">
                    Save
                </button>
            </div>
        </form>
    </div>


    <!-- Classic Sidebar Drawer -->
    <div id="drawer-employee"
        class="fixed top-0 right-0 w-full max-w-lg h-full bg-gradient-to-br from-green-50 via-white to-green-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out border-l-2 border-green-200 dark:border-green-700 flex flex-col">
        <div
            class="flex items-center justify-between px-8 py-6 border-b border-green-100 dark:border-green-800 bg-gradient-to-r from-green-100 via-white to-green-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
            <h2 class="text-2xl font-extrabold text-green-700 dark:text-green-300 flex items-center gap-2">
                <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Employee
            </h2>
            <button type="button" onclick="closeDrawer('employee')"
                class="text-green-400 hover:text-green-700 dark:text-green-300 dark:hover:text-green-100 transition rounded-full p-1 focus:outline-none focus:ring-2 focus:ring-green-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data"
            class="flex-1 flex flex-col justify-between">
            @csrf
            <input type="hidden" name="from_task_form" value="1">
            <div class="p-8 space-y-6 overflow-y-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Name --}}
                    <div>
                        <label for="name"
                            class="block text-sm font-semibold text-green-800 dark:text-green-200 mb-1">Full Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" placeholder="Full Name" required
                            class="w-full px-4 py-2 border-2 border-green-200 dark:border-green-700 rounded-lg bg-white dark:bg-gray-900 text-green-900 dark:text-green-100 focus:outline-none focus:ring-2 focus:ring-green-400 transition shadow-sm">
                    </div>
                    {{-- Email --}}
                    <div>
                        <label for="email"
                            class="block text-sm font-semibold text-green-800 dark:text-green-200 mb-1">Email <span
                                class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" placeholder="Email" required
                            class="w-full px-4 py-2 border-2 border-green-200 dark:border-green-700 rounded-lg bg-white dark:bg-gray-900 text-green-900 dark:text-green-100 focus:outline-none focus:ring-2 focus:ring-green-400 transition shadow-sm">
                    </div>
                    {{-- Phone --}}
                    <div>
                        <label for="phone"
                            class="block text-sm font-semibold text-green-800 dark:text-green-200 mb-1">Phone</label>
                        <input type="text" name="phone" id="phone" placeholder="Phone"
                            class="w-full px-4 py-2 border-2 border-green-200 dark:border-green-700 rounded-lg bg-white dark:bg-gray-900 text-green-900 dark:text-green-100 focus:outline-none focus:ring-2 focus:ring-green-400 transition shadow-sm">
                    </div>
                    {{-- Join Date --}}
                    <div>
                        <label for="join_date"
                            class="block text-sm font-semibold text-green-800 dark:text-green-200 mb-1">Join Date &
                            Time</label>
                        <input type="datetime-local" name="join_date" id="join_date"
                            value="{{ now()->format('Y-m-d\TH:i') }}"
                            class="w-full px-4 py-2 border-2 border-green-200 dark:border-green-700 rounded-lg bg-white dark:bg-gray-900 text-green-900 dark:text-green-100 focus:outline-none focus:ring-2 focus:ring-green-400 transition shadow-sm">
                    </div>
                    {{-- Photo --}}
                    <div>
                        <label for="photo"
                            class="block text-sm font-semibold text-green-800 dark:text-green-200 mb-1">Photo</label>
                        <input type="file" name="photo" id="photo"
                            class="w-full px-4 py-2 border-2 border-green-200 dark:border-green-700 rounded-lg bg-white dark:bg-gray-900 text-green-900 dark:text-green-100 file:bg-green-100 file:border-0 file:px-3 file:py-1 file:rounded file:mr-4 focus:outline-none focus:ring-2 focus:ring-green-400 transition shadow-sm">
                    </div>
                    {{-- Department --}}
                    <div>
                        <label for="department_id"
                            class="block text-sm font-semibold text-green-800 dark:text-green-200 mb-1">Department</label>
                        <select name="department_id" id="department_id"
                            class="w-full px-4 py-2 border-2 border-green-200 dark:border-green-700 rounded-lg bg-white dark:bg-gray-900 text-green-900 dark:text-green-100 focus:outline-none focus:ring-2 focus:ring-green-400 transition shadow-sm">
                            <option value="">Select Department</option>
                            @foreach ($departments as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Designation --}}
                    <div class="md:col-span-2">
                        <label for="designation_id"
                            class="block text-sm font-semibold text-green-800 dark:text-green-200 mb-1">Designation</label>
                        <select name="designation_id" id="designation_id"
                            class="w-full px-4 py-2 border-2 border-green-200 dark:border-green-700 rounded-lg bg-white dark:bg-gray-900 text-green-900 dark:text-green-100 focus:outline-none focus:ring-2 focus:ring-green-400 transition shadow-sm">
                            <option value="">Select Designation</option>
                            @foreach ($designations as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div
                class="flex justify-end gap-4 px-8 py-6 border-t border-green-100 dark:border-green-800 bg-gradient-to-r from-green-50 via-white to-green-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
                <button type="button" onclick="closeDrawer('employee')"
                    class="px-5 py-2 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 rounded-lg hover:bg-green-200 dark:hover:bg-green-700 transition font-semibold shadow">
                    Cancel
                </button>
                <button type="submit"
                    class="px-6 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition font-bold shadow-lg">
                    Save
                </button>
            </div>
        </form>
    </div>


</x-app-layout>

<script>
    function openDrawer(type) {
        const drawer = document.getElementById(`drawer-${type}`) ?? document.getElementById(`add-${type}-drawer`);
        if (drawer) {
            drawer.classList.remove('translate-x-full');
            drawer.classList.add('translate-x-0');
        }
    }

    function closeDrawer(type) {
        const drawer = document.getElementById(`drawer-${type}`) ?? document.getElementById(`add-${type}-drawer`);
        if (drawer) {
            drawer.classList.remove('translate-x-0');
            drawer.classList.add('translate-x-full');
        }
    }
</script>
