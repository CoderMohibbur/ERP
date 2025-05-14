<x-app-layout>
    <div class="w-full mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Edit Attendance Setting</h2>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('attendance-settings.update', $attendanceSetting->id) }}">
            @csrf
            @method('PUT')

            @include('attendance-settings.form-fields', ['setting' => $attendanceSetting])

            <div class="flex justify-end items-center mt-6">
                <a href="{{ route('attendance-settings.index') }}"
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
</x-app-layout>