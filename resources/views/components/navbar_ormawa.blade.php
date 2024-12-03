<nav class="flex justify-between items-center p-4 bg-white shadow-sm">
    <!-- Logo -->
    <div class="flex items-center">
      <img src="{{ asset('images/logo_signix.png') }}" alt="SIGNIX Logo" class="w-[100px] mr-4" />
    </div>

    <!-- Menu Links -->
    <div class="flex space-x-6">
      <a href="{{ route('ormawa.dashboard') }}" class="text-black font-medium hover:text-blue-500 {{ request()->routeIs('ormawa.dashboard') ? 'border-b-2 border-blue-500' : '' }}">
        {{ request()->routeIs('ormawa.dashboard') ? 'Home' : 'Home' }}
      </a>
      <a href="{{ route('ormawa.pengajuan') }}" class="text-black font-medium hover:text-blue-500 {{ request()->routeIs('ormawa.pengajuan') ? 'border-b-2 border-blue-500' : '' }}">
        {{ request()->routeIs('ormawa.pengajuan') ? 'Pengajuan' : 'Pengajuan' }}
      </a>
      <a href="{{ route('ormawa.riwayat') }}" class="text-black font-medium hover:text-blue-500 {{ request()->routeIs('ormawa.riwayat') ? 'border-b-2 border-blue-500' : '' }}">
        {{ request()->routeIs('ormawa.riwayat') ? 'Riwayat' : 'Riwayat' }}
      </a>
    </div>

    <!-- Notification and Profile Icon -->
    <div class="flex items-center space-x-4">

      <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" class="text-black">
          @if(Auth::guard('ormawa')->user()->profile && file_exists(public_path('profiles/' . Auth::guard('ormawa')->user()->profile)))
            <img src="{{ asset('profiles/' . Auth::guard('ormawa')->user()->profile) }}"
                 alt="Profile Picture"
                 class="object-cover w-8 h-8 rounded-full">
          @else
            {{-- Fallback ke icon default jika tidak ada foto --}}
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 12c2.485 0 4.5-2.015 4.5-4.5S14.485 3 12 3 7.5 5.015 7.5 7.5 9.515 12 12 12zm0 2c-3.315 0-10 1.68-10 5v2h20v-2c0-3.32-6.685-5-10-5z"/>
            </svg>
          @endif
        </button>
        <div x-show="open" @click.away="open = false" class="absolute right-0 z-10 py-1 mt-2 w-48 bg-white rounded-md shadow-lg">
          <div class="block px-4 py-2 text-sm text-gray-700 border-b border-gray-200">
            {{ Auth::user()->namaMahasiswa }}
          </div>
          <a href="{{ route('ormawa.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>

          <form method="POST" action="{{ route('ormawa.logout') }}">
            @csrf
            <button type="submit" class="block px-4 py-2 w-full text-sm text-left text-gray-700 hover:bg-gray-100">Logout</button>
          </form>
        </div>
      </div>
    </div>
  </nav>
