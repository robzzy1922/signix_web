<nav class="bg-white shadow-sm">
    <div class="container mx-auto px-4 py-2 flex justify-between items-center">
        <!-- Logo -->
        <div class="flex items-center">
            <img src="{{ asset('images/logo_signix.png') }}" alt="SIGNIX Logo" class="w-12 mr-3" />
        </div>

        <!-- Menu Links -->
        <div class="flex space-x-3">
            <a href="{{ route('dosen.dashboard') }}" class="text-black font-medium hover:text-blue-500 {{ request()->routeIs('dosen.dashboard') ? 'border-b-2 border-blue-500' : '' }}">
                Beranda
            </a>
            <a href="{{ route('user.dosen.create') }}" class="text-black font-medium hover:text-blue-500 {{ request()->routeIs('user.dosen.create') ? 'border-b-2 border-blue-500' : '' }}">
                Buat Tanda Tangan
            </a>
            <a href="{{ route('dosen.riwayat') }}" class="text-black font-medium hover:text-blue-500 {{ request()->routeIs('dosen.riwayat') ? 'border-b-2 border-blue-500' : '' }}">
                Riwayat
            </a>
        </div>

        <!-- Notification and Profile Icon -->
        <div class="flex space-x-2 items-center">
            <!-- Notification Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="text-black focus:outline-none">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <!-- Icon Bell SVG -->
                        <path d="M12 24c1.104 0 2-.896 2-2h-4c0 1.104.896 2 2 2zm7-6v-5c0-3.071-1.641-5.645-4.5-6.32v-.68c0-.828-.672-1.5-1.5-1.5s-1.5.672-1.5 1.5v.68c-2.859.675-4.5 3.25-4.5 6.32v5l-1 1v1h14v-1l-1-1zm-9 0v-5c0-2.485 1.149-4.475 3-4.475s3 1.99 3 4.475v5h-6z"/>
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-1 w-36 bg-white rounded-md shadow-lg py-1 z-10" x-cloak>
                    <!-- Notification items go here -->
                    <a href="#" class="block px-3 py-1 text-sm text-gray-700 hover:bg-gray-100">Notification 1</a>
                    <a href="#" class="block px-3 py-1 text-sm text-gray-700 hover:bg-gray-100">Notification 2</a>
                    <a href="#" class="block px-3 py-1 text-sm text-gray-700 hover:bg-gray-100">Notification 3</a>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="text-black focus:outline-none">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <!-- Icon Profile SVG -->
                        <path d="M12 12c2.485 0 4.5-2.015 4.5-4.5S14.485 3 12 3 7.5 5.015 7.5 7.5 9.515 12 12 12zm0 2c-3.315 0-10 1.68-10 5v2h20v-2c0-3.32-6.685-5-10-5z"/>
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-1 w-36 bg-white rounded-md shadow-lg py-1 z-10" x-cloak>
                    <a href="#" class="block px-3 py-1 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                    <a href="#" class="block px-3 py-1 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-1 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>