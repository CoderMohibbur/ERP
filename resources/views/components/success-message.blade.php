@if (session('success'))
    <div class="mb-4 p-4 text-green-800 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-900">
        {{ session('success') }}
    </div>
@endif
