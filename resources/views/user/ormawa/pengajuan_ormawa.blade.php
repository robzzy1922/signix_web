@extends('layouts.ormawa')
@section('title', 'Formulir Pengajuan')
@section('content')
    <div class="container flex-grow px-4 mx-auto mt-4 max-w-3xl sm:px-6 sm:mt-8">
        <h1 class="mb-4 text-xl font-bold text-center sm:mb-6 sm:text-2xl sm:text-left">FORMULIR PENGAJUAN</h1>

        @if(session('success'))
            <div id="alert-success" class="relative p-3 mb-4 text-sm text-green-700 bg-green-100 rounded border border-green-400 transition-all duration-300 transform sm:p-4 sm:mb-6 sm:text-base" role="alert">
                <div class="flex items-center">
                    <svg class="mr-2 w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <span class="font-bold">Success! </span>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 w-full h-1 bg-green-200">
                    <div id="progress-bar" class="h-1 bg-green-600 transition-all duration-[5000ms] ease-linear w-full"></div>
                </div>
            </div>

            <script>
                const alert = document.getElementById('alert-success');
                const progressBar = document.getElementById('progress-bar');
                progressBar.getBoundingClientRect();
                progressBar.style.width = '0%';
                setTimeout(() => {
                    alert.style.transform = 'translateX(100%)';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            </script>
        @endif

        @if ($errors->any())
            <div class="p-4 mb-4 text-red-700 bg-red-100 rounded border border-red-400">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('ormawa.pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="w-full" id="pengajuanForm">
            @csrf
            <div class="space-y-3 sm:space-y-4">
                <div>
                    <label for="nomor_surat" class="block mb-1 text-sm font-medium sm:text-base">Nomor Surat</label>
                    <input type="text" id="nomor_surat" name="nomor_surat" class="px-3 py-2 w-full text-sm rounded-md border sm:text-base" required>
                </div>

                <div>
                    <label for="nama_pengaju" class="block mb-1 text-sm font-medium sm:text-base">Nama Pengaju</label>
                    <input type="text" id="nama_pengaju" name="nama_pengaju" class="px-3 py-2 w-full text-sm bg-gray-200 rounded-md border sm:text-base" value="{{ $ormawa->namaMahasiswa }}" readonly required>
                </div>

                <div>
                    <label for="nama_ormawa" class="block mb-1 text-sm font-medium sm:text-base">Nama Ormawa</label>
                    <input type="text" id="nama_ormawa" name="nama_ormawa" class="px-3 py-2 w-full text-sm bg-gray-200 rounded-md border sm:text-base" value="{{ $ormawa->namaOrmawa }}" readonly required>
                </div>

                <div>
                    <label for="tujuan_pengajuan" class="block mb-1 text-sm font-medium sm:text-base">Tujuan Pengajuan</label>
                    <select id="tujuan_pengajuan" name="tujuan_pengajuan" class="px-3 py-2 w-full text-sm rounded-md border sm:text-base" required>
                        <option value="">Pilih Tujuan Pengajuan</option>
                        <option value="dosen">Dosen</option>
                        <option value="kemahasiswaan">Kemahasiswaan</option>
                    </select>
                    @error('tujuan_pengajuan')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div id="dosen_section" style="display: none;">
                    <label for="kepada_tujuan" class="block mb-1 text-sm font-medium sm:text-base">Pilih Dosen</label>
                    <select id="kepada_tujuan" name="kepada_tujuan" class="px-3 py-2 w-full text-sm rounded-md border sm:text-base">
                        <option value="">Pilih Dosen</option>
                        @foreach($dosenList as $dosen)
                            <option value="{{ $dosen->id }}">{{ $dosen->nama_dosen }}</option>
                        @endforeach
                    </select>
                    @error('kepada_tujuan')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div id="kemahasiswaan_section" style="display: none;">
                    <label for="kepada_kemahasiswaan" class="block mb-1 text-sm font-medium sm:text-base">Pilih Kemahasiswaan</label>
                    <select id="kepada_kemahasiswaan" name="kepada_kemahasiswaan" class="px-3 py-2 w-full text-sm rounded-md border sm:text-base">
                        <option value="">Pilih Kemahasiswaan</option>
                        @foreach($kemahasiswaanList as $kemahasiswaan)
                            <option value="{{ $kemahasiswaan->id }}">{{ $kemahasiswaan->nama_kemahasiswaan }}</option>
                        @endforeach
                    </select>
                    @error('kepada_kemahasiswaan')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="hal" class="block mb-1 text-sm font-medium sm:text-base">Hal</label>
                    <input type="text" id="hal" name="hal" class="px-3 py-2 w-full text-sm rounded-md border sm:text-base" required>
                </div>

                <div>
                    <label for="unggah_dokumen" class="block mb-1 text-sm font-medium sm:text-base">Unggah Dokumen</label>
                    <input type="file" id="unggah_dokumen" name="unggah_dokumen" class="px-3 py-2 w-full text-sm rounded-md border sm:text-base" required>
                </div>

                <div>
                    <label for="catatan" class="block mb-1 text-sm font-medium sm:text-base">Catatan (opsional)</label>
                    <textarea id="catatan" name="catatan" rows="4" class="px-3 py-2 w-full text-sm rounded-md border sm:text-base"></textarea>
                </div>

                <div class="mt-4 sm:mt-6">
                    <button type="submit" class="px-4 py-2 w-full text-sm text-white bg-blue-500 rounded-md sm:w-auto sm:text-base hover:bg-blue-600">Ajukan</button>
                </div>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('pengajuanForm');
        const tujuanSelect = document.getElementById('tujuan_pengajuan');
        const dosenSection = document.getElementById('dosen_section');
        const kemahasiswaanSection = document.getElementById('kemahasiswaan_section');
        const kepada_tujuan = document.getElementById('kepada_tujuan');
        const kepada_kemahasiswaan = document.getElementById('kepada_kemahasiswaan');

        tujuanSelect.addEventListener('change', function() {
            if (this.value === 'dosen') {
                dosenSection.style.display = 'block';
                kemahasiswaanSection.style.display = 'none';
                kepada_tujuan.required = true;
                kepada_kemahasiswaan.required = false;
                kepada_kemahasiswaan.value = '';
            } else if (this.value === 'kemahasiswaan') {
                dosenSection.style.display = 'none';
                kemahasiswaanSection.style.display = 'block';
                kepada_tujuan.required = false;
                kepada_kemahasiswaan.required = true;
                kepada_tujuan.value = '';
            } else {
                dosenSection.style.display = 'none';
                kemahasiswaanSection.style.display = 'none';
                kepada_tujuan.required = false;
                kepada_kemahasiswaan.required = false;
                kepada_tujuan.value = '';
                kepada_kemahasiswaan.value = '';
            }
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Form submitted');

            const tujuan = tujuanSelect.value;
            console.log('Tujuan:', tujuan);

            if (tujuan === 'dosen') {
                console.log('Dosen value:', kepada_tujuan.value);
                if (!kepada_tujuan.value) {
                    alert('Silakan pilih dosen tujuan');
                    return;
                }
            }
            if (tujuan === 'kemahasiswaan') {
                console.log('Kemahasiswaan value:', kepada_kemahasiswaan.value);
                if (!kepada_kemahasiswaan.value) {
                    alert('Silakan pilih kemahasiswaan tujuan');
                    return;
                }
            }

            console.log('Submitting form...');
            this.submit();
        });
    });
    </script>
@endsection
