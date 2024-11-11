<nav class="bg-white shadow-md">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="flex space-x-6">
            <a href="{{ route('dosen.dashboard') }}" class="text-black font-medium hover:text-blue-500 {{ request()->routeIs('dosen.dashboard') ? 'border-b-2 border-blue-500' : '' }}">
              {{ request()->routeIs('dosen.dashboard') ? 'Beranda' : 'Beranda' }}
            </a>
            <a href="{{ route('user.dosen.create') }}" class="text-black font-medium hover:text-blue-500 {{ request()->routeIs('user.dosen.create') ? 'border-b-2 border-blue-500' : '' }}">
              {{ request()->routeIs('user.dosen.create') ? 'Buat Tanda Tangan' : 'Buat Tanda Tangan' }}
            </a>
            <a href="{{ route('ormawa.riwayat') }}" class="text-black font-medium hover:text-blue-500 {{ request()->routeIs('ormawa.riwayat') ? 'border-b-2 border-blue-500' : '' }}">
              {{ request()->routeIs('ormawa.riwayat') ? 'Riwayat' : 'Riwayat' }}
            </a>
          </div>
        <div class="flex items-center">
            <span class="text-xl">🔔</span>
            <span class="ml-4 text-xl">👤</span>
        </div>
    </div>
</nav>