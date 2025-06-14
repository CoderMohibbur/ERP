<x-app-layout>
    <div class="w-full mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Update Employee</h2>

        {{-- Validation Errors --}}
        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('employees.update', $employee->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Reusable Fields --}}
            @include('employees.form-fields')

            {{-- Actions --}}
            <div class="flex justify-end items-center mt-6">
                <a href="{{ route('employees.index') }}"
                    class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500">
                    Cancel
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Update
                </button>
            </div>
        </form>
    </div>

    {{-- JS (Only if drawers are reusable here too) --}}
    <script>
        function setupDrawer(selectId, triggerValue, drawerId) {
            const selectEl = document.getElementById(selectId);
            if (!selectEl) return;
            selectEl.addEventListener('change', function() {
                if (this.value === triggerValue) {
                    document.getElementById(drawerId).classList.remove('translate-x-full');
                    this.value = '';
                }
            });
        }

        setupDrawer('department_id', 'add_new', 'add-department-drawer');
        setupDrawer('designation_id', 'add_new_designation', 'add-designation-drawer');

        function closeDrawer() {
            document.getElementById('add-department-drawer').classList.add('translate-x-full');
        }

        function closeDesignationDrawer() {
            document.getElementById('add-designation-drawer').classList.add('translate-x-full');
        }
    </script>
</x-app-layout>
