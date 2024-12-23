<nav x-data="{ isOpen: false }" class="flex flex-wrap justify-between items-center p-4 bg-white shadow-sm">
    <div class="flex items-center">
        <img src="{{ asset('images/logo_signix.png') }}" alt="SIGNIX Logo" class="w-[100px] mr-4" />
    </div>

    <button @click="isOpen = !isOpen" class="md:hidden">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path :class="{'hidden': isOpen, 'inline-flex': !isOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{'hidden': !isOpen, 'inline-flex': isOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <div :class="{'hidden': !isOpen}" class="w-full md:flex md:w-auto md:items-center">
        <div class="flex flex-col md:flex-row md:space-x-6 mt-4 md:mt-0">
            <a href="{{ route('dosen.dashboard') }}" class="nav-item text-black font-medium hover:text-blue-500 py-2 md:py-0 {{ request()->routeIs('dosen.dashboard') ? 'text-blue-500' : '' }}">
                Home
            </a>
            <a href="{{ route('dosen.riwayat') }}" class="nav-item text-black font-medium hover:text-blue-500 py-2 md:py-0 {{ request()->routeIs('dosen.riwayat') ? 'text-blue-500' : '' }}">
                Riwayat
            </a>
            <div class="md:hidden">
                <div class="block py-2 text-sm text-gray-700 border-b border-gray-200">
                    {{ Auth::user()->nama_dosen }}
                </div>
                <a href="{{ route('dosen.profile') }}" class="nav-item text-black font-medium hover:text-blue-500 py-2 {{ request()->routeIs('dosen.profile') ? 'text-blue-500' : '' }}">
                    Profile
                </a>
                <form method="POST" action="{{ route('dosen.logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left text-black font-medium hover:text-blue-500 py-2">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <div class="hidden md:flex items-center space-x-4">
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="text-black">
                @if(Auth::guard('dosen')->user()->profile && file_exists(public_path('profiles/' . Auth::guard('dosen')->user()->profile)))
                    <img src="{{ asset('profiles/' . Auth::guard('dosen')->user()->profile) }}"
                         alt="Profile Picture"
                         class="object-cover w-8 h-8 rounded-full">
                @else
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.485 0 4.5-2.015 4.5-4.5S14.485 3 12 3 7.5 5.015 7.5 7.5 9.515 12 12 12zm0 2c-3.315 0-10 1.68-10 5v2h20v-2c0-3.32-6.685-5-10-5z"/>
                    </svg>
                @endif
            </button>
            <div x-show="open" @click.away="open = false" class="absolute right-0 z-10 py-1 mt-2 w-48 bg-white rounded-md shadow-lg">
                <div class="block px-4 py-2 text-sm text-gray-700 border-b border-gray-200">
                    {{ Auth::guard('dosen')->user()->nama_dosen }}
                </div>
                <a href="{{ route('dosen.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                <form method="POST" action="{{ route('dosen.logout') }}">
                    @csrf
                    <button type="submit" class="block px-4 py-2 w-full text-sm text-left text-gray-700 hover:bg-gray-100">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    .nav-item {
        position: relative;
    }
    .nav-item::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        display: block;
        margin-top: 5px;
        right: 0;
        background: #3b82f6; /* Tailwind's blue-500 */
        transition: width 0.4s ease;
        -webkit-transition: width 0.4s ease;
    }
    .nav-item:hover::after {
        width: 100%;
        left: 0;
        background: #3b82f6; /* Tailwind's blue-500 */
    }
</style>
