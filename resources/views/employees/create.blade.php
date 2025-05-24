<x-app-layout>
    <div class="w-full mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Add Employee</h2>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">

            @csrf

            @include('employees.form-fields')


            <div class="flex justify-end items-center mt-6">
                <a href="{{ route('employees.index') }}"
                    class="mr-3 text-gray-600 dark:text-gray-300 hover:text-red-500 hover:dark:text-red-500">
                    Cancel
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Save
                </button>
            </div>
        </form>


        {{-- Department Drawer --}}
        <x-drawer-form id="add-department-drawer" title="➕ Add Department" action="{{ route('departments.store') }}"
            inputId="new_department_name" inputLabel="Department Name" cancelHandler="closeDrawer" />

        {{-- Designation Drawer --}}
        <x-drawer-form id="add-designation-drawer" title="➕ Add Designation" action="{{ route('designations.store') }}"
            inputId="new_designation_name" inputLabel="Designation Name" cancelHandler="closeDesignationDrawer" />




    </div>

</x-app-layout>

<script>
    // Drawer Toggle Handler (Reusable)
    function setupDrawer(selectId, triggerValue, drawerId) {
        const selectEl = document.getElementById(selectId);
        selectEl.addEventListener('change', function() {
            if (this.value === triggerValue) {
                document.getElementById(drawerId).classList.remove('translate-x-full');
                this.value = ''; // Reset dropdown
            }
        });
    }

    // Call for both dropdowns
    setupDrawer('department_id', 'add_new', 'add-department-drawer');
    setupDrawer('designation_id', 'add_new_designation', 'add-designation-drawer');

    // Close Handlers
    function closeDrawer() {
        document.getElementById('add-department-drawer').classList.add('translate-x-full');
    }

    function closeDesignationDrawer() {
        document.getElementById('add-designation-drawer').classList.add('translate-x-full');
    }
</script>
