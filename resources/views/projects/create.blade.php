<x-app-layout>
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Add Project</h2>

    <form action="{{ route('projects.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            {{-- Title --}}
            <div>
                <label for="title" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            {{-- Client --}}
            <div class="mb-4">
                <label for="client_id"
                    class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Client</label>

                <div
                    class="flex rounded-md shadow-sm overflow-hidden border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                    {{-- Select Dropdown --}}
                    <select name="client_id" id="client_id" required
                        class="flex-1 block w-full px-4 py-2 text-sm text-gray-900 dark:text-white bg-transparent border-none focus:outline-none focus:ring-0">
                        <option value="">Select Client</option>
                        @foreach ($clients as $id => $name)
                            <option value="{{ $id }}"
                                {{ old('client_id', $newClientId ?? '') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Add Button --}}
                    <button type="button" onclick="openClientDrawer()"
                        class="px-4 text-green-600 hover:text-green-700 flex items-center border-l border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </button>
                </div>
            </div>



            {{-- Deadline --}}
            <div>
                <label for="deadline" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Deadline</label>
                <input type="date" name="deadline" id="deadline"
                    value="{{ old('deadline', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            {{-- Status --}}
            <div>
                <label for="status" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" id="status"
                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in progress" {{ old('status') == 'in progress' ? 'selected' : '' }}>In Progress
                    </option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            {{-- Description --}}
            <div class="sm:col-span-2">
                <label for="description" class="block mb-1 text-sm text-gray-700 dark:text-gray-300">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md bg-white text-gray-800 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="flex justify-end items-center mt-6">
            <a href="{{ route('projects.index') }}"
                class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500">Cancel</a>
            <button type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">Save</button>
        </div>
    </form>


    {{-- Slider drawer --}}
    <div id="add-client-drawer"
        class="fixed top-0 right-0 w-96 h-full bg-white dark:bg-gray-800 shadow-lg z-50 transform translate-x-full transition-transform duration-300">
        <div class="p-6">
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">➕ Add New Client</h2>

            <form method="POST" action="{{ route('clients.store') }}">
                @csrf
                <input type="hidden" name="from_project_form" value="1">

                <div class="mb-4">
                    <label for="client_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Client
                        Name</label>
                    <input type="text" name="name" id="client_name" required
                        class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="email"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" name="email"
                        class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="phone"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                    <input type="text" name="phone"
                        class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="flex justify-between mt-6">
                    <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Save</button>
                    <button type="button" onclick="closeClientDrawer()"
                        class="text-red-500 hover:underline">Cancel</button>
                </div>
            </form>
        </div>
    </div>



</x-app-layout>

{{-- for slider --}}
<script>
    document.getElementById('client_id').addEventListener('change', function() {
        if (this.value === 'add_new_client') {
            document.getElementById('add-client-drawer').classList.remove('translate-x-full');
            this.value = '';
        }
    });

    function closeClientDrawer() {
        document.getElementById('add-client-drawer').classList.add('translate-x-full');
    }
</script>



{{--  Add Button --}}
<script>
    function openClientDrawer() {
        document.getElementById('add-client-drawer').classList.remove('translate-x-full');
    }

    function closeClientDrawer() {
        document.getElementById('add-client-drawer').classList.add('translate-x-full');
    }
</script>
