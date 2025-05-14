<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}

    <!-- 🔹 Top 3 Colorful Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div class="p-4 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white rounded-xl shadow">
            <p class="text-sm font-medium">Total Users</p>
            <h2 class="text-3xl font-bold mt-2">2,345</h2>
        </div>
        <div class="p-4 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-xl shadow">
            <p class="text-sm font-medium">Orders Today</p>
            <h2 class="text-3xl font-bold mt-2">87</h2>
        </div>
        <div class="p-4 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl shadow">
            <p class="text-sm font-medium">Total Revenue</p>
            <h2 class="text-3xl font-bold mt-2">$12,450</h2>
        </div>
    </div>

    <!-- 🔥 2 Column Responsive Bar Chart Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        <!-- 📊 Monthly Sales Report -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">📊 Monthly Sales Report</h2>
                <select
                    class="text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option>This Year</option>
                    <option>Last Year</option>
                    <option>Last 6 Months</option>
                </select>
            </div>

            <div class="h-72">
                <canvas id="salesBarChart"></canvas>
            </div>

            <div class="mt-4 text-sm text-right text-gray-600 dark:text-gray-300">
                Total: <span class="font-semibold text-indigo-600 dark:text-indigo-400">$13,600</span>
            </div>
        </div>

        <!-- 🧑‍💼 New Users vs Orders -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">🧑‍💼 New Users vs Orders</h2>
                <select
                    class="text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option>This Month</option>
                    <option>Last Month</option>
                    <option>Last 3 Months</option>
                </select>
            </div>

            <div class="h-72">
                <canvas id="usersOrdersBarChart"></canvas>
            </div>

            <div class="mt-4 text-sm text-right text-gray-600 dark:text-gray-300">
                Users: <span class="font-semibold text-blue-600 dark:text-blue-400">+324</span>,
                Orders: <span class="font-semibold text-green-600 dark:text-green-400">+287</span>
            </div>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sales Chart
        new Chart(document.getElementById('salesBarChart'), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Sales',
                    data: [1500, 2100, 1800, 2400, 3000, 2700],
                    backgroundColor: '#4f46e5',
                    borderRadius: 6,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleColor: '#fff',
                        bodyColor: '#e5e7eb',
                        cornerRadius: 6
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#6b7280'
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        ticks: {
                            color: '#6b7280'
                        },
                        grid: {
                            color: '#e5e7eb',
                            borderDash: [4, 4]
                        },
                        beginAtZero: true
                    }
                }
            }
        });

        // Users vs Orders Chart
        new Chart(document.getElementById('usersOrdersBarChart'), {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                datasets: [{
                        label: 'Users',
                        data: [30, 45, 60, 40, 80, 90],
                        backgroundColor: '#3b82f6',
                        borderRadius: 6,
                        barThickness: 30
                    },
                    {
                        label: 'Orders',
                        data: [20, 35, 50, 30, 60, 70],
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                        barThickness: 30
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleColor: '#fff',
                        bodyColor: '#e5e7eb',
                        cornerRadius: 6
                    }
                },
                scales: {
                    x: {
                        stacked: false,
                        ticks: {
                            color: '#6b7280'
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        stacked: false,
                        ticks: {
                            color: '#6b7280'
                        },
                        grid: {
                            color: '#e5e7eb',
                            borderDash: [4, 4]
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    </script>


    <!-- 🟪 Middle Section - Modern Stat Cards -->
    <!-- 🌈 Extended Middle Section Cards - Bright & Stylish -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

        <!-- New Signups -->
        <div class="flex items-center justify-between p-5 bg-yellow-200 dark:bg-yellow-800 rounded-xl shadow-lg">
            <div>
                <p class="text-sm font-medium text-yellow-900 dark:text-yellow-200 mb-1">New Signups</p>
                <h2 class="text-3xl font-extrabold text-yellow-950 dark:text-yellow-100">56</h2>
            </div>
            <div class="p-3 bg-yellow-300 dark:bg-yellow-700 rounded-full">
                <svg class="w-6 h-6 text-yellow-900 dark:text-yellow-200" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="flex items-center justify-between p-5 bg-rose-200 dark:bg-rose-800 rounded-xl shadow-lg">
            <div>
                <p class="text-sm font-medium text-rose-900 dark:text-rose-200 mb-1">Pending Orders</p>
                <h2 class="text-3xl font-extrabold text-rose-950 dark:text-rose-100">23</h2>
            </div>
            <div class="p-3 bg-rose-300 dark:bg-rose-700 rounded-full">
                <svg class="w-6 h-6 text-rose-900 dark:text-rose-200" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        </div>

        <!-- Product Views -->
        <div class="flex items-center justify-between p-5 bg-sky-200 dark:bg-sky-800 rounded-xl shadow-lg">
            <div>
                <p class="text-sm font-medium text-sky-900 dark:text-sky-200 mb-1">Product Views</p>
                <h2 class="text-3xl font-extrabold text-sky-950 dark:text-sky-100">7,890</h2>
            </div>
            <div class="p-3 bg-sky-300 dark:bg-sky-700 rounded-full">
                <svg class="w-6 h-6 text-sky-900 dark:text-sky-200" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 10l4.553-2.276A2 2 0 0122 9.618v4.764a2 2 0 01-2.447 1.894L15 14M4 6h16" />
                </svg>
            </div>
        </div>

        <!-- Support Tickets -->
        <div class="flex items-center justify-between p-5 bg-teal-200 dark:bg-teal-800 rounded-xl shadow-lg">
            <div>
                <p class="text-sm font-medium text-teal-900 dark:text-teal-200 mb-1">Support Tickets</p>
                <h2 class="text-3xl font-extrabold text-teal-950 dark:text-teal-100">12</h2>
            </div>
            <div class="p-3 bg-teal-300 dark:bg-teal-700 rounded-full">
                <svg class="w-6 h-6 text-teal-900 dark:text-teal-200" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>
        </div>

        <!-- Delivered Orders -->
        <div class="flex items-center justify-between p-5 bg-green-200 dark:bg-green-800 rounded-xl shadow-lg">
            <div>
                <p class="text-sm font-medium text-green-900 dark:text-green-200 mb-1">Delivered Orders</p>
                <h2 class="text-3xl font-extrabold text-green-950 dark:text-green-100">198</h2>
            </div>
            <div class="p-3 bg-green-300 dark:bg-green-700 rounded-full">
                <svg class="w-6 h-6 text-green-900 dark:text-green-200" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <!-- Refund Requests -->
        <div class="flex items-center justify-between p-5 bg-purple-200 dark:bg-purple-800 rounded-xl shadow-lg">
            <div>
                <p class="text-sm font-medium text-purple-900 dark:text-purple-200 mb-1">Refund Requests</p>
                <h2 class="text-3xl font-extrabold text-purple-950 dark:text-purple-100">5</h2>
            </div>
            <div class="p-3 bg-purple-300 dark:bg-purple-700 rounded-full">
                <svg class="w-6 h-6 text-purple-900 dark:text-purple-200" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                </svg>
            </div>
        </div>


        <!-- Total Users -->
        <div class="flex items-center justify-between p-5 bg-blue-200 dark:bg-blue-800 rounded-xl shadow-lg">
            <div>
                <p class="text-sm font-medium text-blue-900 dark:text-blue-200 mb-1">Total Users</p>
                <h2 class="text-3xl font-extrabold text-blue-950 dark:text-blue-100">3,456</h2>
            </div>
            <div class="p-3 bg-blue-300 dark:bg-blue-700 rounded-full">
                <svg class="w-6 h-6 text-blue-900 dark:text-blue-200" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 20h5v-2a4 4 0 00-4-4h-1m-6 6h6M9 20H4v-2a4 4 0 014-4h1" />
                </svg>
            </div>
        </div>

        <!-- Total Products -->
        <div class="flex items-center justify-between p-5 bg-orange-200 dark:bg-orange-800 rounded-xl shadow-lg">
            <div>
                <p class="text-sm font-medium text-orange-900 dark:text-orange-200 mb-1">Total Products</p>
                <h2 class="text-3xl font-extrabold text-orange-950 dark:text-orange-100">1,200</h2>
            </div>
            <div class="p-3 bg-orange-300 dark:bg-orange-700 rounded-full">
                <svg class="w-6 h-6 text-orange-900 dark:text-orange-200" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6M16 21v-6H8v6" />
                </svg>
            </div>
        </div>

        <!-- Active Products -->
        <div class="flex items-center justify-between p-5 bg-emerald-200 dark:bg-emerald-800 rounded-xl shadow-lg">
            <div>
                <p class="text-sm font-medium text-emerald-900 dark:text-emerald-200 mb-1">Active Products</p>
                <h2 class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-100">890</h2>
            </div>
            <div class="p-3 bg-emerald-300 dark:bg-emerald-700 rounded-full">
                <svg class="w-6 h-6 text-emerald-900 dark:text-emerald-200" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>
        {{--
      <!-- Average Order Time -->
      <div class="flex items-center justify-between p-5 bg-gray-200 dark:bg-gray-800 rounded-xl shadow-lg">
        <div>
          <p class="text-sm font-medium text-gray-900 dark:text-gray-300 mb-1">Avg. Order Time</p>
          <h2 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100">1h 24m</h2>
        </div>
        <div class="p-3 bg-gray-300 dark:bg-gray-700 rounded-full">
          <svg class="w-6 h-6 text-gray-900 dark:text-gray-300" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>

      <!-- Today's Revenue -->
      <div class="flex items-center justify-between p-5 bg-lime-200 dark:bg-lime-800 rounded-xl shadow-lg">
        <div>
          <p class="text-sm font-medium text-lime-900 dark:text-lime-200 mb-1">Revenue (Today)</p>
          <h2 class="text-3xl font-extrabold text-lime-950 dark:text-lime-100">$2,350</h2>
        </div>
        <div class="p-3 bg-lime-300 dark:bg-lime-700 rounded-full">
          <svg class="w-6 h-6 text-lime-900 dark:text-lime-200" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3" />
          </svg>
        </div>
      </div>

      <!-- New Reviews -->
      <div class="flex items-center justify-between p-5 bg-pink-200 dark:bg-pink-800 rounded-xl shadow-lg">
        <div>
          <p class="text-sm font-medium text-pink-900 dark:text-pink-200 mb-1">New Reviews</p>
          <h2 class="text-3xl font-extrabold text-pink-950 dark:text-pink-100">32</h2>
        </div>
        <div class="p-3 bg-pink-300 dark:bg-pink-700 rounded-full">
          <svg class="w-6 h-6 text-pink-900 dark:text-pink-200" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.973a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.39 2.462a1 1 0 00-.364 1.118l1.286 3.973c.3.921-.755 1.688-1.54 1.118l-3.39-2.462a1 1 0 00-1.176 0l-3.39 2.462c-.785.57-1.84-.197-1.54-1.118l1.286-3.973a1 1 0 00-.364-1.118L2.455 9.4c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.973z" />
          </svg>
        </div>
      </div> --}}


    </div>



    <!-- 🟦 Stylish Notifications Box -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">📬 Latest Messages</h3>
            <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View All</a>
        </div>

        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            <li class="py-3">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <span
                            class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 text-blue-600 rounded-full">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            <strong>John Doe</strong> sent you a message.
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">2 minutes ago</p>
                    </div>
                </div>
            </li>

            <li class="py-3">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <span
                            class="inline-flex items-center justify-center w-10 h-10 bg-green-100 text-green-600 rounded-full">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13 16h-1v-4h-1m2-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                            </svg>
                        </span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            <strong>System</strong> updated successfully.
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">10 minutes ago</p>
                    </div>
                </div>
            </li>

            <li class="py-3">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <span
                            class="inline-flex items-center justify-center w-10 h-10 bg-red-100 text-red-600 rounded-full">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            <strong>Alert:</strong> Server CPU usage is high!
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">1 hour ago</p>
                    </div>
                </div>
            </li>
        </ul>
    </div>

    <!-- 🟩 Bottom Cards -->
    <!-- 🌟 Modern Dashboard Cards (4 Stats) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Total Products -->
        <div class="flex items-center justify-between p-5 bg-blue-50 dark:bg-blue-900 rounded-xl shadow-lg">
            <div>
                <p class="text-sm font-semibold text-blue-900 dark:text-blue-200 mb-1">Total Products</p>
                <h2 class="text-3xl font-extrabold text-blue-950 dark:text-blue-100">1,220</h2>
            </div>
            <div class="p-3 bg-blue-200 dark:bg-blue-700 rounded-full">
                <svg class="w-6 h-6 text-blue-900 dark:text-blue-200" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6M16 21v-6H8v6" />
                </svg>
            </div>
        </div>

        <!-- Low Stock Items -->
        <div class="flex items-center justify-between p-5 bg-yellow-50 dark:bg-yellow-900 rounded-xl shadow-lg">
            <div>
                <p class="text-sm font-semibold text-yellow-900 dark:text-yellow-200 mb-1">Low Stock Items</p>
                <h2 class="text-3xl font-extrabold text-yellow-950 dark:text-yellow-100">18</h2>
            </div>
            <div class="p-3 bg-yellow-200 dark:bg-yellow-700 rounded-full">
                <svg class="w-6 h-6 text-yellow-900 dark:text-yellow-200" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                </svg>
            </div>
        </div>

        <!-- Refund Requests -->
        <div class="flex items-center justify-between p-5 bg-purple-50 dark:bg-purple-900 rounded-xl shadow-lg">
            <div>
                <p class="text-sm font-semibold text-purple-900 dark:text-purple-200 mb-1">Refund Requests</p>
                <h2 class="text-3xl font-extrabold text-purple-950 dark:text-purple-100">4</h2>
            </div>
            <div class="p-3 bg-purple-200 dark:bg-purple-700 rounded-full">
                <svg class="w-6 h-6 text-purple-900 dark:text-purple-200" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                </svg>
            </div>
        </div>

        <!-- Newsletter Subscribers -->
        <div class="flex items-center justify-between p-5 bg-green-50 dark:bg-green-900 rounded-xl shadow-lg">
            <div>
                <p class="text-sm font-semibold text-green-900 dark:text-green-200 mb-1">Newsletter Subs</p>
                <h2 class="text-3xl font-extrabold text-green-950 dark:text-green-100">3,015</h2>
            </div>
            <div class="p-3 bg-green-200 dark:bg-green-700 rounded-full">
                <svg class="w-6 h-6 text-green-900 dark:text-green-200" fill="none" stroke="currentColor"
                    stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 12a4 4 0 01-8 0m8 0a4 4 0 00-8 0m8 0V8m-8 4v4m4-4v2" />
                </svg>
            </div>
        </div>
    </div>
    @include('layouts.admin-footer')
</x-app-layout>
