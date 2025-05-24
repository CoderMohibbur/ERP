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
                                <option value="{{ $newProject->id }}" selected>{{ $newProject->title }}</option>
                            @endif
                        @endif

                        @foreach ($projects as $id => $title)
                            <option value="{{ $id }}" {{ old('project_id') == $id ? 'selected' : '' }}>
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



            <!-- Project Drawer -->
            <div id="add-project-drawer"
                class="fixed top-0 right-0 w-96 max-w-full h-full bg-white dark:bg-gray-900 shadow-lg z-50 transform translate-x-full transition-transform duration-300 ease-in-out border-l border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">➕ Add Project</h2>

                    <form method="POST" action="{{ route('projects.store') }}">
                        @csrf
                        <input type="hidden" name="from_task_form" value="1">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Title -->
                            <div>
                                <label for="title"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                                <input type="text" name="title" id="title" required
                                    class="w-full mt-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>

                            <!-- Client -->
                            <div>
                                <label for="client_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Client</label>
                                <select name="client_id" id="client_id"
                                    class="w-full mt-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">Select Client</option>
                                    @foreach ($clients as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Deadline -->
                            <div>
                                <label for="deadline"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deadline</label>
                                <input type="date" name="deadline" id="deadline"
                                    value="{{ \Carbon\Carbon::today()->toDateString() }}"
                                    class="w-full mt-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select name="status" id="status"
                                    class="w-full mt-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>

                            <!-- Description (Full Width) -->
                            <div class="md:col-span-2">
                                <label for="description"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description /
                                    Note</label>
                                <textarea name="description" id="description" rows="5"
                                    class="w-full mt-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md resize-y text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500"
                                    placeholder="Write project details...">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end mt-6 gap-4">
                            <button type="button" onclick="closeDrawer('project')"
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded hover:bg-gray-400 dark:hover:bg-gray-700">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                Save
                            </button>
                        </div>
                    </form>
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
                                <option value="{{ $newEmployee->id }}" selected>{{ $newEmployee->name }}</option>
                            @endif
                        @endif

                        @foreach ($employees as $id => $name)
                            <option value="{{ $id }}" {{ old('assigned_to') == $id ? 'selected' : '' }}>
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
                <input type="number" name="progress" id="progress" value="{{ old('progress', 0) }}"
                    min="0" max="100"
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

    <!-- Classic Sidebar Drawer -->
    <div id="drawer-employee"
        class="fixed top-0 right-0 w-96 max-w-full h-full bg-white dark:bg-gray-900 shadow-lg z-50 transform translate-x-full transition-transform duration-300 ease-in-out border-l border-gray-200 dark:border-gray-700">
        <div class="p-6 h-full flex flex-col">

            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">➕ Add Employee</h2>
                <button type="button" onclick="closeDrawer('employee')"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('employees.store') }}" class="flex flex-col flex-1">
                @csrf
                <input type="hidden" name="from_task_form" value="1">

                <div class="space-y-5 flex-1">
                    <input type="text" name="name" placeholder="Name"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500"
                        required>
                    <input type="email" name="email" placeholder="Email"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500"
                        required>
                    <input type="text" name="phone" placeholder="Phone"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div class="flex justify-end mt-8 gap-4">
                    <button type="button" onclick="closeDrawer('employee')"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                        Save
                    </button>
                </div>
            </form>
        </div>
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
