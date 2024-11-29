@extends('layouts.ormawa')
@section('title', 'Formulir Pengajuan')
@section('content')
    <div class="container flex-grow px-4 mx-auto mt-8 max-w-3xl">
        <h1 class="mb-6 text-2xl font-bold">FORMULIR PENGAJUAN</h1>

        @if(session('success'))
            <div id="alert-success" class="relative p-4 mb-6 text-green-700 bg-green-100 rounded border border-green-400 transition-all duration-300 transform" role="alert">
                <div class="flex items-center">
                    <svg class="mr-2 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <span class="font-bold">Success! </span>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
                <!-- Progress bar container -->
                <div class="absolute bottom-0 left-0 w-full h-1 bg-green-200">
                    <div id="progress-bar" class="h-1 bg-green-600 transition-all duration-[5000ms] ease-linear w-full"></div>
                </div>
            </div>

            <script>
                // Get elements
                const alert = document.getElementById('alert-success');
                const progressBar = document.getElementById('progress-bar');

                // Force browser reflow to ensure animation starts properly
                progressBar.getBoundingClientRect();

                // Start progress bar animation
                progressBar.style.width = '0%';

                // Remove alert after 5 seconds with fade out animation
                setTimeout(() => {
                    alert.style.transform = 'translateX(100%)';
                    alert.style.opacity = '0';

                    // Remove element after animation completes
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            </script>
        @endif

        <form action="{{ route('ormawa.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="nomor_surat" class="block mb-1 font-medium">Nomor Surat</label>
                    <input type="text" id="nomor_surat" name="nomor_surat" class="px-3 py-2 w-full rounded-md border" required>
                </div>

                <div>
                    <label for="nama_pengaju" class="block mb-1 font-medium">Nama Pengaju</label>
                    <input type="text" id="nama_pengaju" name="nama_pengaju" class="px-3 py-2 w-full bg-gray-200 rounded-md border" value="{{ $ormawa->namaMahasiswa }}" readonly required>
                </div>

                <div>
                    <label for="nama_ormawa" class="block mb-1 font-medium">Nama Ormawa</label>
                    <input type="text" id="nama_ormawa" name="nama_ormawa" class="px-3 py-2 w-full bg-gray-200 rounded-md border" value="{{ $ormawa->namaOrmawa }}" readonly required>
                </div>

                <div>
                    <label for="kepada_tujuan" class="block mb-1 font-medium">Kepada / Tujuan</label>
                    <select id="kepada_tujuan" name="kepada_tujuan" class="px-3 py-2 w-full rounded-md border" required>
                        <option value="">Pilih Tujuan</option>
                        @foreach($dosenList as $dosen)
                            <option value="{{ $dosen->id }}">{{ $dosen->nama_dosen }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="hal" class="block mb-1 font-medium">Hal</label>
                    <input type="text" id="hal" name="hal" class="px-3 py-2 w-full rounded-md border" required>
                </div>

                <div>
                    <label for="unggah_dokumen" class="block mb-1 font-medium">Unggah Dokumen</label>
                    <input type="file" id="unggah_dokumen" name="unggah_dokumen" class="px-3 py-2 w-full rounded-md border" required>
                </div>

                <div>
                    <label for="catatan" class="block mb-1 font-medium">Catatan (opsional)</label>
                    <textarea id="catatan" name="catatan" rows="4" class="px-3 py-2 w-full rounded-md border"></textarea>
                </div>

                <div class="mt-6 mb-6">
                    <button type="submit" class="px-4 py-2 mb-6 text-white bg-blue-500 rounded-md hover:bg-blue-600">Ajukan</button>
                </div>
            </div>
        </form>
    </div>
@endsection
