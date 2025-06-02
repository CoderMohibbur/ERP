<x-app-layout>
    <div>
        <x-success-message />
    </div>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Employees</h1>
        <a href="{{ route('employees.create') }}"
            class="px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
            + Add Employee
        </a>
    </div>


    <div class="flex flex-col sm:flex-row items-center justify-between mb-6">
        {{-- 🔍 Search Bar --}}
        {{-- 🔍 Filter Toggle Button --}}
        <button type="button" onclick="toggleFilter()"
            class="px-8 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600">
            Filter
        </button>
        {{-- Search Bar --}}
        <input type="text" placeholder="🔍 Search employees..."
            class="w-full sm:w-1/3 px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            oninput="filterTable(this.value)">
    </div>



    {{-- 🔽 Collapsible Filter Panel --}}
    <form method="GET" action="{{ route('employees.index') }}" id="filterPanel"
        class="hidden mb-6 bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow transition-all duration-300 ease-in-out max-h-0 overflow-hidden">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            {{-- 🔍 Search by Name or Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Search (Name /
                    Gmail)</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200"
                    placeholder="Md Tufulul / admin@example.com" />
            </div>

            {{-- 👤 Employee Dropdown --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Employee</label>
                <select name="employee_id"
                    class="w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                    <option value="">-- Select Employee --</option>
                    @foreach ($allEmployees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- 🗓️ Month Dropdown --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Month</label>
                <select name="month"
                    class="w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                    <option value="">-- Select Month --</option>
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- 📆 Join Date Picker --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Join Date</label>
                <input type="date" name="join_date" value="{{ request('join_date') }}"
                    class="w-full px-3 py-2 border rounded bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200" />
            </div>
        </div>

        <div class="flex justify-end mt-4">
            <button type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600">
                ফিল্টার প্রয়োগ করুন
            </button>
        </div>
    </form>

    <script>
        function toggleFilter() {
            const filterPanel = document.getElementById('filterPanel');
            if (filterPanel.classList.contains('hidden')) {
                filterPanel.classList.remove('hidden');
                filterPanel.style.maxHeight = filterPanel.scrollHeight + 'px';
            } else {
                filterPanel.style.maxHeight = '0';
                filterPanel.addEventListener('transitionend', function handleTransitionEnd() {
                    filterPanel.classList.add('hidden');
                    filterPanel.removeEventListener('transitionend', handleTransitionEnd);
                });
            }
        }
    </script>




    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="employee-table">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    @foreach (['#', 'Name', 'Email', 'Department', 'Designation', 'Join Date', 'Photo', 'Actions'] as $col)
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ $col }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($employees as $employee)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $employee->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">{{ $employee->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                            {{ $employee->department->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                            {{ $employee->designation->name ?? '-' }}</td>
                        {{-- model a cast kora ase string format --}}
                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                            {{ $employee->join_date->format('Y-m-d h:i A') }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                            @if ($employee->photo)
                                <img src="{{ asset('storage/' . $employee->photo) }}" alt="Photo"
                                    class="h-10 w-10 rounded-full object-cover">
                            @else
                                <span class="text-gray-400 dark:text-gray-100">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('employees.edit', $employee->id) }}"
                                class="text-blue-500 hover:text-blue-700 dark:hover:text-blue-300 font-medium">Edit</a>
                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST"
                                class="inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Are you sure?')"
                                    class="text-red-500 hover:text-red-700 dark:hover:text-red-400 font-medium">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            No employees found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4 px-4">
            {{ $employees->links() }}
        </div>
    </div>
    {{-- JS Filter (simple client-side) --}}
    <script>
        function filterTable(query) {
            const rows = document.querySelectorAll("#employee-table tbody tr");
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query.toLowerCase()) ? "" : "none";
            });
        }
    </script>
</x-app-layout>


