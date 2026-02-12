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
    {{-- <x-banner /> --}}

<div class="min-h-screen border-0 bg-gradient-to-br from-green-50 via-white to-green-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 rounded-lg shadow">
        @include('layouts.admin-nav')
        <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">
            @include('layouts.admin-sidebar')
        </div>

        <!-- Page Heading -->
        <div id="content" class="mx-auto px-8 pt-6 transition-all duration-300 dark:border-gray-900">
            <div class="p-4 border-2  border-gray-200 border-dashed rounded-lg dark:border-gray-700">
                @if (isset($header))
                    <header>
                        {{ $header }}
                    </header>
                @endif

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>

    @stack('modals')

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>


<script>
    document.addEventListener("DOMContentLoaded", function() {

        /* =========================
            DARK MODE SWITCHER
        ========================== */
        const themeToggleBtn = document.getElementById("theme-toggle");
        const darkIcon = document.getElementById("theme-toggle-dark-icon");
        const lightIcon = document.getElementById("theme-toggle-light-icon");

        if (themeToggleBtn && darkIcon && lightIcon) {
            // Show correct icon on load
            if (
                localStorage.getItem("color-theme") === "dark" ||
                (!("color-theme" in localStorage) &&
                    window.matchMedia("(prefers-color-scheme: dark)").matches)
            ) {
                document.documentElement.classList.add("dark");
                darkIcon.classList.remove("hidden");
                lightIcon.classList.add("hidden");
            } else {
                document.documentElement.classList.remove("dark");
                lightIcon.classList.remove("hidden");
                darkIcon.classList.add("hidden");
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
        }

        /* =========================
            SIDEBAR (Hover + Pin + Mobile Slide)
        ========================== */
        const sidebar = document.getElementById("sidebar");
        const menuText = document.querySelectorAll(".menu-text");
        const toggleButton = document.getElementById("toggle-button");
        const content = document.getElementById("content");

        if (!sidebar || !toggleButton || !content) return;

        const STORAGE_KEY = "sidebar_pinned";
        let isPinned = localStorage.getItem(STORAGE_KEY) === "1"; // Desktop pin
        let isMobileOpen = false; // Mobile slide open state

        // ✅ Apply initial state immediately
        applyState();

        // ✅ Toggle button click: Mobile slide OR Desktop pin/unpin
        toggleButton.addEventListener("click", function(event) {
            event.stopPropagation();

            if (window.innerWidth <= 640) {
                // Mobile: slide open/close
                isMobileOpen = !isMobileOpen;
                sidebar.classList.toggle("open", isMobileOpen);
                return;
            }

            // Desktop: pin/unpin
            isPinned = !isPinned;
            localStorage.setItem(STORAGE_KEY, isPinned ? "1" : "0");
            applyState();
        });

        // ✅ Close mobile sidebar on outside click
        document.addEventListener("click", function(event) {
            if (window.innerWidth > 640) return;
            if (!isMobileOpen) return;

            if (!sidebar.contains(event.target) && event.target !== toggleButton) {
                sidebar.style.transition = "transform 0.3s ease-in-out";
                sidebar.classList.remove("open");
                isMobileOpen = false;
            }
        });

        // ✅ Hover expand (Desktop only, only when NOT pinned)
        sidebar.addEventListener("mouseenter", function() {
            if (window.innerWidth <= 640) return;
            if (isPinned) return;

            setExpanded(true);
        });

        // ✅ Hover collapse (Desktop only, only when NOT pinned)
        sidebar.addEventListener("mouseleave", function() {
            if (window.innerWidth <= 640) return;
            if (isPinned) return;

            setExpanded(false);
        });

        // ✅ Keep correct layout on resize
        window.addEventListener("resize", function() {
            // Reset mobile state when moving to desktop
            if (window.innerWidth > 640) {
                sidebar.classList.remove("open");
                isMobileOpen = false;
                applyState();
            }
        });

        // ------------------------
        // Helpers
        // ------------------------
        function applyState() {
            // Mobile: do not force width classes here; slide is handled by .open
            if (window.innerWidth <= 640) return;

            // Desktop: pinned => expanded, not pinned => collapsed
            setExpanded(isPinned);
        }

        function setExpanded(expanded) {
            if (expanded) {
                sidebar.classList.add("w-64");
                sidebar.classList.remove("w-20");

                content.classList.add("sm:ml-64");
                content.classList.remove("sm:ml-20");

                menuText.forEach(text => text.classList.remove("hidden"));
            } else {
                sidebar.classList.add("w-20");
                sidebar.classList.remove("w-64");

                content.classList.add("sm:ml-20");
                content.classList.remove("sm:ml-64");

                menuText.forEach(text => text.classList.add("hidden"));
            }
        }
    });
</script>



</html>
