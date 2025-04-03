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
            class="fixed md:relative bg-[#2A3042] text-white min-h-screen z-40 transition-all duration-300"
            :class="$store.sidebar.open ? 'w-64' : 'w-16'"
            @click.away="if (window.innerWidth < 768) $store.sidebar.open = false">
            <div class="p-4">
                <div class="flex items-center space-x-2" x-show="$store.sidebar.open">
                    <img src="{{ asset('images/logo_signix.png') }}" alt="Logo" class="h-8">
                    <span class="text-lg font-semibold">Admin Panel</span>
                </div>
            </div>
            <nav class="mt-4">
                <a href="{{ route('admin.adminDashboard') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.adminDashboard') ? 'bg-[#fd7e14]' : 'hover:bg-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"  :class="$store.sidebar.open ? 'mr-3' : 'mx-auto'" fill="currentColor" class="bi bi-columns-gap" viewBox="0 0 16 16">
                        <path d="M6 1v3H1V1zM1 0a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1zm14 12v3h-5v-3zm-5-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1zM6 8v7H1V8zM1 7a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1zm14-6v7h-5V1zm-5-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1z"/>
                      </svg>
                    <span x-show="$store.sidebar.open">Dashboard</span>
                </a>
                <a href="{{ route('admin.dosen.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.dosen.*') ? 'bg-[#fd7e14]' : 'hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" :class="$store.sidebar.open ? 'mr-3' : 'mx-auto'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span x-show="$store.sidebar.open">Dosen</span>
                </a>
                <a href="{{ route('admin.kemahasiswaan.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.kemahasiswaan.*') ? 'bg-[#fd7e14]' : 'hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" :class="$store.sidebar.open ? 'mr-3' : 'mx-auto'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span x-show="$store.sidebar.open">Kemahasiswaan</span>
                </a>
                <a href="{{ route('admin.ormawa.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.ormawa.*') ? 'bg-[#fd7e14]' : 'hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" :class="$store.sidebar.open ? 'mr-3' : 'mx-auto'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span x-show="$store.sidebar.open">Ormawa</span>
                </a>
                <a href="{{ route('admin.dokumen.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.dokumen.*') ? 'bg-[#fd7e14]' : 'hover:bg-gray-700' }}">
                    <svg class="w-5 h-5" :class="$store.sidebar.open ? 'mr-3' : 'mx-auto'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span x-show="$store.sidebar.open">Dokumen</span>
                </a>
            </nav>
        </aside>

        <div class="flex-1">
            <header class="bg-white shadow">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <button
                            @click="$store.sidebar.toggle()"
                            class="p-2 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @include('layouts.admin.partials.navbar')
    </header>

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
