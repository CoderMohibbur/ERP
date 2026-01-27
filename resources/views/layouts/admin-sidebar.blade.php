<aside id="sidebar"
    class="fixed top-0 left-0 z-40 w-20 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    aria-label="Sidebar">

    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <ul class="space-y-2 font-medium">

            {{-- =======================
                DASHBOARD
            ======================== --}}
            <li>
                <a href="{{ \Illuminate\Support\Facades\Route::has('dashboard') ? route('dashboard') : url('/dashboard') }}"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                        <path
                            d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path
                            d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                    </svg>
                    <span class="menu-text hidden ms-3">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ \Illuminate\Support\Facades\Route::has('owner-dashboard') ? route('owner-dashboard') : url('/owner-dashboard') }}"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-500 transition duration-300 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                        viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M3 3h18v2H3V3Zm0 16h18v2H3v-2Zm2-7h6v2H5v-2Zm0-4h10v2H5V8Zm0 8h10v2H5v-2Zm12-4h2v2h-2v-2Z" />
                    </svg>
                    <span class="menu-text hidden ms-3">Owner Dashboard</span>
                </a>
            </li>

            {{-- =======================
                CRM (Leads / Deals / Activities)
            ======================== --}}
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                    aria-controls="crm-dropdown" data-collapse-toggle="crm-dropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-300 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                        viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M6 2h12v2H6V2Zm-2 6h16v2H4V8Zm2 4h12v10H6V12Z" />
                    </svg>

                    <span class="menu-text hidden flex-1 ml-3 text-left whitespace-nowrap">CRM</span>
                    <svg sidebar-toggle-item="" class="menu-text hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>

                <ul id="crm-dropdown" class="space-y-2 hidden">
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('leads.index') ? route('leads.index') : url('/leads') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Leads
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('deals.index') ? route('deals.index') : url('/deals') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Deals
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('deals.pipeline') ? route('deals.pipeline') : url('/deals/pipeline') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Deals Pipeline
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('activities.index') ? route('activities.index') : url('/activities') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Activities
                        </a>
                    </li>
                </ul>
            </li>

            {{-- =======================
                DELIVERY (Projects / Tasks / Board)
            ======================== --}}
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                    aria-controls="delivery-dropdown" data-collapse-toggle="delivery-dropdown" aria-expanded="false">

                    <svg class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-300 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                        fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path
                            d="M3 4a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4zm5 3a1 1 0 0 0-1 1v9a1 1 0 0 0 2 0V8a1 1 0 0 0-1-1zm5 4a1 1 0 0 0-1 1v5a1 1 0 0 0 2 0v-5a1 1 0 0 0-1-1zm4-6a1 1 0 0 0-1 1v11a1 1 0 0 0 2 0V6a1 1 0 0 0-1-1z" />
                    </svg>

                    <span class="menu-text hidden flex-1 ml-3 text-left whitespace-nowrap">Delivery</span>
                    <svg sidebar-toggle-item="" class="menu-text hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>

                <ul id="delivery-dropdown" class="space-y-2 hidden">
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('projects.index') ? route('projects.index') : url('/projects') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Projects
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('tasks.index') ? route('tasks.index') : url('/tasks') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Tasks
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('project-board.index') ? route('project-board.index') : url('/project-board') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Project Board
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('task-statuses.index') ? route('task-statuses.index') : url('/task-statuses') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Task Statuses
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('project-files.index') ? route('project-files.index') : url('/project-files') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Project Files
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('project-notes.index') ? route('project-notes.index') : url('/project-notes') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Project Notes
                        </a>
                    </li>
                </ul>
            </li>

            {{-- =======================
                TIME (Time Logs)
            ======================== --}}
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                    aria-controls="time-dropdown" data-collapse-toggle="time-dropdown" aria-expanded="false">

                    <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-300 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                        viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm.75 5.25a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h3a.75.75 0 0 0 0-1.5h-2.25V7.25Z" />
                    </svg>

                    <span class="menu-text hidden flex-1 ml-3 text-left whitespace-nowrap">Time</span>
                    <svg sidebar-toggle-item="" class="menu-text hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>

                <ul id="time-dropdown" class="space-y-2 hidden">
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('time-logs.index') ? route('time-logs.index') : url('/time-logs') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Time Logs
                        </a>
                    </li>
                </ul>
            </li>

            {{-- =======================
                CLIENTS
            ======================== --}}
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                    aria-controls="client-dropdown" data-collapse-toggle="client-dropdown" aria-expanded="false">

                    <svg class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-300 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path
                            d="M12 12c2.7 0 4.9-2.2 4.9-4.9S14.7 2.2 12 2.2 7.1 4.4 7.1 7.1 9.3 12 12 12zm0 2.2c-3.1 0-9.3 1.6-9.3 4.8V21h18.6v-2c0-3.2-6.2-4.8-9.3-4.8z" />
                    </svg>

                    <span class="menu-text hidden flex-1 ml-3 text-left whitespace-nowrap">Clients</span>
                    <svg sidebar-toggle-item="" class="menu-text hidden w-6 h-6" fill="currentColor"
                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>

                <ul id="client-dropdown" class="space-y-2 hidden">
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('clients.index') ? route('clients.index') : url('/clients') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Clients
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('client-contacts.index') ? route('client-contacts.index') : url('/client-contacts') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Contacts
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('client-notes.index') ? route('client-notes.index') : url('/client-notes') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Notes
                        </a>
                    </li>
                </ul>
            </li>

            {{-- =======================
                FINANCE (Invoices / Payments / Expenses / Taxes / Terms)
            ======================== --}}
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                    aria-controls="finance-dropdown" data-collapse-toggle="finance-dropdown" aria-expanded="false">

                    <svg class="flex-shrink-0 w-6 h-6 text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white transition duration-300"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        aria-hidden="true">
                        <path
                            d="M4 2a1 1 0 0 0-1 1v18l3-2 3 2 3-2 3 2 3-2 3 2V3a1 1 0 0 0-1-1H4zm8 5a1 1 0 0 1 1 1v.25c1.24.29 2.25 1.38 2.25 2.75s-1.01 2.46-2.25 2.75V15a1 1 0 1 1-2 0v-1.25c-1.24-.29-2.25-1.38-2.25-2.75s1.01-2.46 2.25-2.75V8a1 1 0 0 1 1-1z" />
                    </svg>

                    <span class="menu-text hidden flex-1 ml-3 text-left whitespace-nowrap">Finance</span>
                    <svg sidebar-toggle-item="" class="menu-text hidden w-6 h-6" fill="currentColor"
                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>

                <ul id="finance-dropdown" class="space-y-2 hidden">
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('invoices.index') ? route('invoices.index') : url('/invoices') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Invoices
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('invoice-items.index') ? route('invoice-items.index') : url('/invoice-items') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Invoice Items
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('item-categories.index') ? route('item-categories.index') : url('/item-categories') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Item Categories
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('payments.index') ? route('payments.index') : url('/payments') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Payments
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('payment-methods.index') ? route('payment-methods.index') : url('/payment-methods') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Payment Methods
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('expenses.index') ? route('expenses.index') : url('/expenses') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Expenses
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('tax-rules.index') ? route('tax-rules.index') : url('/tax-rules') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Tax Rules
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('terms.index') ? route('terms.index') : url('/terms') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Terms &amp; Conditions
                        </a>
                    </li>
                </ul>
            </li>

            {{-- =======================
                RENEWALS (Services / Service Renewals)
            ======================== --}}
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                    aria-controls="renewals-dropdown" data-collapse-toggle="renewals-dropdown" aria-expanded="false">

                    <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-300 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                        viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M12 6V3L8 7l4 4V8c2.757 0 5 2.243 5 5 0 1.137-.382 2.182-1.026 3.02l1.458 1.458A6.963 6.963 0 0 0 19 13c0-3.86-3.141-7-7-7Zm-5.974.98A6.963 6.963 0 0 0 5 13c0 3.86 3.141 7 7 7v3l4-4-4-4v3c-2.757 0-5-2.243-5-5 0-1.137.382-2.182 1.026-3.02L6.026 6.98Z" />
                    </svg>

                    <span class="menu-text hidden flex-1 ml-3 text-left whitespace-nowrap">Renewals</span>
                    <svg sidebar-toggle-item="" class="menu-text hidden w-6 h-6" fill="currentColor"
                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>

                <ul id="renewals-dropdown" class="space-y-2 hidden">
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('services.index') ? route('services.index') : url('/services') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Services
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('renewals.index') ? route('renewals.index') : url('/renewals') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Service Renewals
                        </a>
                    </li>
                </ul>
            </li>

            {{-- =======================
                HR / EMPLOYEE MANAGEMENT (Tree অনুযায়ী)
            ======================== --}}
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                    aria-controls="hr-dropdown" data-collapse-toggle="hr-dropdown" aria-expanded="false">

                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-300 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white">
                        <path fill-rule="evenodd"
                            d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z"
                            clip-rule="evenodd" />
                    </svg>

                    <span class="menu-text hidden flex-1 ml-3 text-left whitespace-nowrap">HR</span>
                    <svg sidebar-toggle-item="" class="menu-text hidden w-6 h-6" fill="currentColor"
                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>

                <ul id="hr-dropdown" class="space-y-2 hidden">
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('departments.index') ? route('departments.index') : url('/departments') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Departments
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('designations.index') ? route('designations.index') : url('/designations') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Designations
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('employees.index') ? route('employees.index') : url('/employees') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Employees
                        </a>
                    </li>

                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('employee-histories.index') ? route('employee-histories.index') : url('/employee-histories') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Employee Histories
                        </a>
                    </li>

                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('employee-documents.index') ? route('employee-documents.index') : url('/employee-documents') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Employee Documents
                        </a>
                    </li>

                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('skills.index') ? route('skills.index') : url('/skills') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Skills
                        </a>
                    </li>

                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('employee-skills.index') ? route('employee-skills.index') : url('/employee-skills') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Employee Skills
                        </a>
                    </li>

                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('shifts.index') ? route('shifts.index') : url('/shifts') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Shifts
                        </a>
                    </li>

                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('employee-shifts.index') ? route('employee-shifts.index') : url('/employee-shifts') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Employee Shifts
                        </a>
                    </li>

                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('employee-dependents.index') ? route('employee-dependents.index') : url('/employee-dependents') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Employee Dependents
                        </a>
                    </li>

                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('employee-resignations.index') ? route('employee-resignations.index') : url('/employee-resignations') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Employee Resignations
                        </a>
                    </li>

                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('employee-disciplinary-actions.index') ? route('employee-disciplinary-actions.index') : url('/employee-disciplinary-actions') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Disciplinary Actions
                        </a>
                    </li>
                </ul>
            </li>

            {{-- =======================
                ATTENDANCE
            ======================== --}}
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                    aria-controls="attendance-dropdown" data-collapse-toggle="attendance-dropdown" aria-expanded="false">

                    <svg class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-300 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm.75 5.25a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h3a.75.75 0 0 0 0-1.5h-2.25V7.25Z" />
                    </svg>

                    <span class="menu-text hidden flex-1 ml-3 text-left whitespace-nowrap">Attendance</span>
                    <svg sidebar-toggle-item="" class="menu-text hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>

                <ul id="attendance-dropdown" class="space-y-2 hidden">
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('attendances.index') ? route('attendances.index') : url('/attendances') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Attendance Logs
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('attendance-settings.index') ? route('attendance-settings.index') : url('/attendance-settings') }}"
                            class="menu-text text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700">
                            Attendance Config
                        </a>
                    </li>
                </ul>
            </li>

            {{-- =======================
                SETTINGS (Optional placeholder)
            ======================== --}}
            <li>
                <a href="#"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-300 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white">
                        <path fill-rule="evenodd"
                            d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 0 0-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 0 0-2.282.819l-.922 1.597a1.875 1.875 0 0 0 .432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 0 0 0 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 0 0-.432 2.385l.922 1.597a1.875 1.875 0 0 0 2.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 0 0 2.28-.819l.923-1.597a1.875 1.875 0 0 0-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 0 0 0-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 0 0-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 0 0-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 0 0-1.85-1.567h-1.843ZM12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z"
                            clip-rule="evenodd" />
                    </svg>

                    <span class="menu-text hidden flex-1 ms-3 whitespace-nowrap">Setting</span>
                </a>
            </li>

        </ul>
    </div>
</aside>
