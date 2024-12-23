<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Signix</title>
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100">
    <div x-data class="flex min-h-screen relative">
        <aside 
            x-show="$store.sidebar.open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed md:relative w-64 bg-[#2A3042] text-white min-h-screen z-40"
            @click.away="if (window.innerWidth < 768) $store.sidebar.open = false">
            <div class="p-4">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo_signix.png') }}" alt="Logo" class="h-8">
                    <span class="text-lg font-semibold">Admin Panel</span>
                </div>
            </div>
        </aside>

        <div class="flex-1">
            <header class="bg-white shadow">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <!-- Toggle Button -->
                        <button
                            @click="$store.sidebar.toggle()"
                            class="p-2 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <h1 class="text-2xl font-semibold">@yield('title', 'Dashboard')</h1>
                    </div>
                </div>
            </header>

            <!-- Main Content Area with proper spacing -->
            <main class="p-6">
                <div class="overflow-x-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                open: window.innerWidth >= 768,
                toggle() {
                    this.open = !this.open
                }
            })
        })

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                Alpine.store('sidebar').open = true
            } else {
                Alpine.store('sidebar').open = false
            }
        })
    </script>
</body>
</html>