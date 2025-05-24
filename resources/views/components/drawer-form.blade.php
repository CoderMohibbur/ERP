<div id="{{ $id }}"
    class="fixed top-0 right-0 w-96 h-full bg-white dark:bg-gray-800 shadow-lg z-50 transform translate-x-full transition-transform duration-300">
    <div class="p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">{{ $title }}</h2>

        <form method="POST" action="{{ $action }}">
            @csrf
            <input type="hidden" name="from_employee_form" value="1">

            <div class="mb-4">
                <label for="{{ $inputId }}"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $inputLabel }}</label>
                <input type="text" name="name" id="{{ $inputId }}" required
                    class="w-full mt-1 px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="flex justify-between">
                <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Save</button>
                <button type="button" onclick="{{ $cancelHandler }}()"
                    class="text-red-500 hover:underline">Cancel</button>
            </div>
        </form>
    </div>
</div>
