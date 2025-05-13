<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    {{-- Style for mobile nav --}}
    <style>
        /* Ensure the sidebar transition */
        #sidebar {
            transition: transform 0.3s ease-in-out;
            z-index: 40;
            /* Ensure the sidebar is on top of other content */
        }

        /* Hide sidebar by default */
        #sidebar.hidden {
            transform: translateX(-100%);
        }

        /* Hide sidebar on small devices */
        @media (max-width: 640px) {
            #sidebar {
                position: fixed;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                /* ✅ Smooth Animation */
            }

            #sidebar.open {
                transform: translateX(0);
            }

            #content {
                margin-left: 0 !important;
            }
        }

        /* ✅ Smooth Overlay Animation */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 30;
            /* Ensure the overlay is below the sidebar but above other content */
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out;
        }

        .overlay.show {
            opacity: 1;
            visibility: visible;
        }
    </style>


    {{-- Dropdown rotate 180deg --}}
    <style>
        .rotate-180 {
            transform: rotate(180deg);
            transition: transform 0.3s ease;
        }
    </style>


</head>


<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gray-100">
        @include('layouts.admin-nav')
        <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">
            @include('layouts.admin-sidebar')
            @include('layouts.admin-content')

        </div>

        {{-- <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main> --}}
    </div>

    @stack('modals')

    @livewireScripts
</body>



{{-- Darkmood Switcher --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const themeToggleBtn = document.getElementById("theme-toggle");
        const darkIcon = document.getElementById("theme-toggle-dark-icon");
        const lightIcon = document.getElementById("theme-toggle-light-icon");

        // Show correct icon on load
        if (
            localStorage.getItem("color-theme") === "dark" ||
            (!("color-theme" in localStorage) &&
                window.matchMedia("(prefers-color-scheme: dark)").matches)
        ) {
            document.documentElement.classList.add("dark");
            darkIcon.classList.remove("hidden");
        } else {
            document.documentElement.classList.remove("dark");
            lightIcon.classList.remove("hidden");
        }

        // Toggle button click
        themeToggleBtn.addEventListener("click", function() {
            darkIcon.classList.toggle("hidden");
            lightIcon.classList.toggle("hidden");

            if (localStorage.getItem("color-theme")) {
                if (localStorage.getItem("color-theme") === "light") {
                    document.documentElement.classList.add("dark");
                    localStorage.setItem("color-theme", "dark");
                } else {
                    document.documentElement.classList.remove("dark");
                    localStorage.setItem("color-theme", "light");
                }
            } else {
                if (document.documentElement.classList.contains("dark")) {
                    document.documentElement.classList.remove("dark");
                    localStorage.setItem("color-theme", "light");
                } else {
                    document.documentElement.classList.add("dark");
                    localStorage.setItem("color-theme", "dark");
                }
            }
        });
    });
</script>


{{-- Side bar --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const sidebar = document.getElementById("sidebar");
        const menuText = document.querySelectorAll(".menu-text");
        const toggleButton = document.getElementById("toggle-button");
        const content = document.getElementById("content");

        let isSidebarOpen = false;
        updateSidebar();

        // ✅ Toggle Sidebar on Click
        toggleButton.addEventListener("click", function(event) {
            event.stopPropagation();
            isSidebarOpen = !isSidebarOpen;
            updateSidebar();
            if (window.innerWidth <= 640) {
                sidebar.classList.toggle("open");
            }
        });

        // ✅ Close Sidebar on Body Click (for small devices)
        document.addEventListener("click", function(event) {
            if (window.innerWidth <= 640 && isSidebarOpen && !sidebar.contains(event.target) && event
                .target !== toggleButton) {
                sidebar.style.transition = "transform 0.3s ease-in-out"; // Smooth animation
                sidebar.classList.remove("open");
                isSidebarOpen = false;
                updateSidebar();
            }
        });

        // ✅ Expand Sidebar on Hover (if not manually opened)
        sidebar.addEventListener("mouseenter", function() {
            if (!isSidebarOpen) {
                sidebar.classList.add("w-64");
                sidebar.classList.remove("w-20");
                content.classList.add("sm:ml-64");
                content.classList.remove("sm:ml-20");
                menuText.forEach(text => text.classList.remove("hidden"));
            }
        });

        // ✅ Collapse Sidebar on Mouse Leave (if not manually opened)
        sidebar.addEventListener("mouseleave", function() {
            if (!isSidebarOpen) {
                sidebar.classList.add("w-20");
                sidebar.classList.remove("w-64");
                content.classList.add("sm:ml-20");
                content.classList.remove("sm:ml-64");
                menuText.forEach(text => text.classList.add("hidden"));
            }
        });

        // ✅ Close Sidebar when clicking outside (Smooth Animation)
        document.addEventListener("click", function(event) {
            if (!profileDropdown.contains(event.target) && event.target !== profileButton) {
                profileDropdown.classList.add("hidden");
                isDropdownOpen = false;

                // ✅ Sidebar Smoothly Close When Clicking Outside
                if (isSidebarOpen && !sidebar.contains(event.target) && event.target !== toggleButton) {
                    sidebar.style.transition = "transform 0.3s ease-in-out"; // Smooth animation
                    sidebar.classList.remove("open");
                    isSidebarOpen = false;
                    updateSidebar();
                }
            }
        });



        // ✅ Function to update sidebar and content size
        function updateSidebar() {
            if (isSidebarOpen) {
                sidebar.classList.add("w-64");
                sidebar.classList.remove("w-20");
                if (window.innerWidth > 640) {
                    content.classList.add("sm:ml-64");
                    content.classList.remove("sm:ml-20");
                }
                menuText.forEach(text => text.classList.remove("hidden"));
            } else {
                sidebar.classList.add("w-20");
                sidebar.classList.remove("w-64");
                if (window.innerWidth > 640) {
                    content.classList.add("sm:ml-20");
                    content.classList.remove("sm:ml-64");
                }
                menuText.forEach(text => text.classList.add("hidden"));
            }
        }
    });
</script>

{{-- Drop Down menu --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggleButtons = document.querySelectorAll("[data-collapse-toggle]");

        toggleButtons.forEach(function(btn) {
            const targetId = btn.getAttribute("data-collapse-toggle");
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                btn.addEventListener("click", function() {
                    // Toggle hidden class
                    targetElement.classList.toggle("hidden");

                    // Optionally: toggle rotate class on arrow icon (if any)
                    const arrowIcon = btn.querySelector("svg:last-child");
                    if (arrowIcon) {
                        arrowIcon.classList.toggle("rotate-180");
                    }
                });
            }
        });
    });
</script>


</html>
