{{-- resources/views/layouts/admin-sidebar.blade.php --}}

@php
    // Active route detection for dropdown open states
    $crmOpen = request()->routeIs(
        'leads.*',
        'deals.*',
        'activities.*'
    );

    $deliveryOpen = request()->routeIs(
        'projects.*',
        'tasks.*',
        'project-files.*',
        'project-notes.*'
    );

    $clientOpen = request()->routeIs(
        'clients.*',
        'client-contacts.*',
        'client-notes.*'
    );

    $financeOpen = request()->routeIs(
        'invoices.*',
        'invoice-items.*',
        'item-categories.*',
        'payments.*',
        'expenses.*',
        'tax-rules.*',
        'terms.*'
    );

    $renewalsOpen = request()->routeIs(
        'services.*',
        'renewals.*'
    );

    $dashboardActive = request()->routeIs('dashboard') || request()->is('dashboard');
    $ownerDashboardActive = request()->routeIs('owner-dashboard') || request()->is('owner-dashboard');

    // Reusable classes
    $parentActiveClass = 'bg-gray-100/60 dark:bg-gray-700/55';
    $submenuPanelClass = 'mt-2 space-y-1 rounded-lg px-2 py-2 bg-white/60 dark:bg-gray-900/40 ring-1 ring-gray-200/70 dark:ring-gray-700/70';
    $submenuItemBaseClass = 'menu-text text-base text-gray-900 dark:text-gray-500 rounded-md flex items-center p-2 transition duration-75 pl-9 hover:bg-gray-100/80 dark:hover:bg-gray-700/50';
    $submenuItemActiveClass = 'bg-gray-100 dark:bg-gray-700/70 ring-1 ring-gray-200/60 dark:ring-gray-600/70';

@endphp

<aside id="sidebar"
    class="fixed top-0 left-0 z-40 w-20 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-900 dark:border-gray-700"
    aria-label="Sidebar">

    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-900">
        <ul class="space-y-2 font-medium">

            {{-- =======================
                DASHBOARD
            ======================== --}}
            <li>
                <a href="{{ \Illuminate\Support\Facades\Route::has('dashboard') ? route('dashboard') : url('/dashboard') }}"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ $dashboardActive ? $parentActiveClass : '' }}">
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
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ $ownerDashboardActive ? $parentActiveClass : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6 text-gray-500 transition duration-300 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
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
                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ $crmOpen ? $parentActiveClass : '' }}"
                    aria-controls="crm-dropdown"
                    data-collapse-toggle="crm-dropdown"
                    aria-expanded="{{ $crmOpen ? 'true' : 'false' }}">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-300 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                        viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M6 2h12v2H6V2Zm-2 6h16v2H4V8Zm2 4h12v10H6V12Z" />
                    </svg>

                    <span class="menu-text hidden flex-1 ml-3 text-left whitespace-nowrap">CRM</span>

                    <svg sidebar-toggle-item=""
                        class="menu-text hidden w-6 h-6 transition-transform duration-300 {{ $crmOpen ? 'rotate-180' : '' }}"
                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>

                <ul id="crm-dropdown" class="{{ $submenuPanelClass }} {{ $crmOpen ? '' : 'hidden' }}">
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('leads.index') ? route('leads.index') : url('/leads') }}"
                            class="{{ $submenuItemBaseClass }} {{ request()->routeIs('leads.*') ? $submenuItemActiveClass : '' }}">
                            Leads
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('deals.index') ? route('deals.index') : url('/deals') }}"
                            class="{{ $submenuItemBaseClass }} {{ request()->routeIs('deals.*') && !request()->routeIs('deals.pipeline') ? $submenuItemActiveClass : '' }}">
                            Deals
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('deals.pipeline') ? route('deals.pipeline') : url('/deals/pipeline') }}"
                            class="{{ $submenuItemBaseClass }} {{ request()->routeIs('deals.pipeline') ? $submenuItemActiveClass : '' }}">
                            Deals Pipeline
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('activities.index') ? route('activities.index') : url('/activities') }}"
                            class="{{ $submenuItemBaseClass }} {{ request()->routeIs('activities.*') ? $submenuItemActiveClass : '' }}">
                            Activities
                        </a>
                    </li>
                </ul>
            </li>

            {{-- =======================
                DELIVERY (Projects / Tasks / Files / Notes)
            ======================== --}}
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ $deliveryOpen ? $parentActiveClass : '' }}"
                    aria-controls="delivery-dropdown"
                    data-collapse-toggle="delivery-dropdown"
                    aria-expanded="{{ $deliveryOpen ? 'true' : 'false' }}">

                    <svg class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-300 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                        fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path
                            d="M3 4a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4zm5 3a1 1 0 0 0-1 1v9a1 1 0 0 0 2 0V8a1 1 0 0 0-1-1zm5 4a1 1 0 0 0-1 1v5a1 1 0 0 0 2 0v-5a1 1 0 0 0-1-1zm4-6a1 1 0 0 0-1 1v11a1 1 0 0 0 2 0V6a1 1 0 0 0-1-1z" />
                    </svg>

                    <span class="menu-text hidden flex-1 ml-3 text-left whitespace-nowrap">Delivery</span>

                    <svg sidebar-toggle-item=""
                        class="menu-text hidden w-6 h-6 transition-transform duration-300 {{ $deliveryOpen ? 'rotate-180' : '' }}"
                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>

                <ul id="delivery-dropdown" class="{{ $submenuPanelClass }} {{ $deliveryOpen ? '' : 'hidden' }}">
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('projects.index') ? route('projects.index') : url('/projects') }}"
                            class="{{ $submenuItemBaseClass }} {{ request()->routeIs('projects.*') ? $submenuItemActiveClass : '' }}">
                            Projects
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('tasks.index') ? route('tasks.index') : url('/tasks') }}"
                            class="{{ $submenuItemBaseClass }} {{ request()->routeIs('tasks.*') ? $submenuItemActiveClass : '' }}">
                            Tasks
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('project-files.index') ? route('project-files.index') : url('/project-files') }}"
                            class="{{ $submenuItemBaseClass }} {{ request()->routeIs('project-files.*') ? $submenuItemActiveClass : '' }}">
                            Project Files
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('project-notes.index') ? route('project-notes.index') : url('/project-notes') }}"
                            class="{{ $submenuItemBaseClass }} {{ request()->routeIs('project-notes.*') ? $submenuItemActiveClass : '' }}">
                            Project Notes
                        </a>
                    </li>
                </ul>
            </li>

            {{-- =======================
                CLIENTS
            ======================== --}}
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ $clientOpen ? $parentActiveClass : '' }}"
                    aria-controls="client-dropdown"
                    data-collapse-toggle="client-dropdown"
                    aria-expanded="{{ $clientOpen ? 'true' : 'false' }}">

                    <svg class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-300 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M12 12c2.7 0 4.9-2.2 4.9-4.9S14.7 2.2 12 2.2 7.1 4.4 7.1 7.1 9.3 12 12 12zm0 2.2c-3.1 0-9.3 1.6-9.3 4.8V21h18.6v-2c0-3.2-6.2-4.8-9.3-4.8z" />
                    </svg>

                    <span class="menu-text hidden flex-1 ml-3 text-left whitespace-nowrap">Clients</span>

                    <svg sidebar-toggle-item=""
                        class="menu-text hidden w-6 h-6 transition-transform duration-300 {{ $clientOpen ? 'rotate-180' : '' }}"
                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>

                <ul id="client-dropdown" class="{{ $submenuPanelClass }} {{ $clientOpen ? '' : 'hidden' }}">
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('clients.index') ? route('clients.index') : url('/clients') }}"
                            class="{{ $submenuItemBaseClass }} {{ request()->routeIs('clients.*') ? $submenuItemActiveClass : '' }}">
                            Clients
                        </a>
                    </li>

                    {{-- ✅ Contacts need {client} param, so we link to Clients list (select client then contacts) --}}
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('clients.index') ? route('clients.index') : url('/clients') }}"
                            class="{{ $submenuItemBaseClass }}">
                            Contacts
                        </a>
                    </li>

                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('client-notes.index') ? route('client-notes.index') : url('/client-notes') }}"
                            class="{{ $submenuItemBaseClass }} {{ request()->routeIs('client-notes.*') ? $submenuItemActiveClass : '' }}">
                            Notes
                        </a>
                    </li>
                </ul>
            </li>

            {{-- =======================
                FINANCE
            ======================== --}}
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ $financeOpen ? $parentActiveClass : '' }}"
                    aria-controls="finance-dropdown"
                    data-collapse-toggle="finance-dropdown"
                    aria-expanded="{{ $financeOpen ? 'true' : 'false' }}">

                    <svg class="flex-shrink-0 w-6 h-6 text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white transition duration-300"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path
                            d="M4 2a1 1 0 0 0-1 1v18l3-2 3 2 3-2 3 2 3-2 3 2V3a1 1 0 0 0-1-1H4zm8 5a1 1 0 0 1 1 1v.25c1.24.29 2.25 1.38 2.25 2.75s-1.01 2.46-2.25 2.75V15a1 1 0 1 1-2 0v-1.25c-1.24-.29-2.25-1.38-2.25-2.75s1.01-2.46 2.25-2.75V8a1 1 0 0 1 1-1z" />
                    </svg>

                    <span class="menu-text hidden flex-1 ml-3 text-left whitespace-nowrap">Finance</span>

                    <svg sidebar-toggle-item=""
                        class="menu-text hidden w-6 h-6 transition-transform duration-300 {{ $financeOpen ? 'rotate-180' : '' }}"
                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>

                <ul id="finance-dropdown" class="{{ $submenuPanelClass }} {{ $financeOpen ? '' : 'hidden' }}">
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('invoices.index') ? route('invoices.index') : url('/invoices') }}"
                            class="{{ $submenuItemBaseClass }} {{ request()->routeIs('invoices.*') ? $submenuItemActiveClass : '' }}">
                            Invoices
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('invoice-items.index') ? route('invoice-items.index') : url('/invoice-items') }}"
                            class="{{ $submenuItemBaseClass }} {{ request()->routeIs('invoice-items.*') ? $submenuItemActiveClass : '' }}">
                            Invoice Items
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('item-categories.index') ? route('item-categories.index') : url('/item-categories') }}"
                            class="{{ $submenuItemBaseClass }} {{ request()->routeIs('item-categories.*') ? $submenuItemActiveClass : '' }}">
                            Item Categories
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('payments.index') ? route('payments.index') : url('/payments') }}"
                            class="{{ $submenuItemBaseClass }} {{ request()->routeIs('payments.*') ? $submenuItemActiveClass : '' }}">
                            Payments
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('expenses.index') ? route('expenses.index') : url('/expenses') }}"
                            class="{{ $submenuItemBaseClass }} {{ request()->routeIs('expenses.*') ? $submenuItemActiveClass : '' }}">
                            Expenses
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('tax-rules.index') ? route('tax-rules.index') : url('/tax-rules') }}"
                            class="{{ $submenuItemBaseClass }} {{ request()->routeIs('tax-rules.*') ? $submenuItemActiveClass : '' }}">
                            Tax Rules
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('terms.index') ? route('terms.index') : url('/terms') }}"
                            class="{{ $submenuItemBaseClass }} {{ request()->routeIs('terms.*') ? $submenuItemActiveClass : '' }}">
                            Terms &amp; Conditions
                        </a>
                    </li>
                </ul>
            </li>

            {{-- =======================
                RENEWALS
            ======================== --}}
            <li>
                <button type="button"
                    class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700 {{ $renewalsOpen ? $parentActiveClass : '' }}"
                    aria-controls="renewals-dropdown"
                    data-collapse-toggle="renewals-dropdown"
                    aria-expanded="{{ $renewalsOpen ? 'true' : 'false' }}">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-300 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                        viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M12 6V3L8 7l4 4V8c2.757 0 5 2.243 5 5 0 1.137-.382 2.182-1.026 3.02l1.458 1.458A6.963 6.963 0 0 0 19 13c0-3.86-3.141-7-7-7Zm-5.974.98A6.963 6.963 0 0 0 5 13c0 3.86 3.141 7 7 7v3l4-4-4-4v3c-2.757 0-5-2.243-5-5 0-1.137.382-2.182 1.026-3.02L6.026 6.98Z" />
                    </svg>

                    <span class="menu-text hidden flex-1 ml-3 text-left whitespace-nowrap">Renewals</span>

                    <svg sidebar-toggle-item=""
                        class="menu-text hidden w-6 h-6 transition-transform duration-300 {{ $renewalsOpen ? 'rotate-180' : '' }}"
                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>

                <ul id="renewals-dropdown" class="{{ $submenuPanelClass }} {{ $renewalsOpen ? '' : 'hidden' }}">
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('services.index') ? route('services.index') : url('/services') }}"
                            class="{{ $submenuItemBaseClass }} {{ request()->routeIs('services.*') ? $submenuItemActiveClass : '' }}">
                            Services
                        </a>
                    </li>
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('renewals.index') ? route('renewals.index') : url('/renewals') }}"
                            class="{{ $submenuItemBaseClass }} {{ request()->routeIs('renewals.*') ? $submenuItemActiveClass : '' }}">
                            Service Renewals
                        </a>
                    </li>
                </ul>
            </li>

            {{-- =======================
                SETTINGS (Placeholder)
            ======================== --}}
            <li>
                <a href="#"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-300 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white">
                        <path fill-rule="evenodd"
                            d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 0 0-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 0 0-2.282.819l-.922 1.597a1.875 1.875 0 0 0 .432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 0 0 0 1.139c.015.2-.059.352-.153.430l-.841.692a1.875 1.875 0 0 0-.432 2.385l.922 1.597a1.875 1.875 0 0 0 2.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 0 0 2.28-.819l.923-1.597a1.875 1.875 0 0 0-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 0 0 0-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 0 0-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 0 0-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 0 0-1.85-1.567h-1.843ZM12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z"
                            clip-rule="evenodd" />
                    </svg>

                    <span class="menu-text hidden flex-1 ms-3 whitespace-nowrap">Setting</span>
                </a>
            </li>

        </ul>
    </div>
</aside>
