<footer class="border-t border-gray-200/70 dark:border-white/10 bg-white/70 dark:bg-white/[0.05] backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                © {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
            </p>

            <div class="flex flex-wrap items-center gap-3 text-sm">
                <a href="{{ url('/') }}" class="text-gray-700 hover:underline dark:text-gray-300">Home</a>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:underline dark:text-gray-300">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:underline dark:text-gray-300">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-gray-700 hover:underline dark:text-gray-300">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>
</footer>
