@extends('layouts.ormawa')
@section('title', 'Formulir Pengajuan')
@section('content')
    <div class="container flex-grow max-w-3xl px-4 mx-auto mt-8">
        <h1 class="mb-6 text-2xl font-bold">FORMULIR PENGAJUAN</h1>

        @if(session('success'))
            <div id="alert-success" class="relative p-4 mb-6 text-green-700 bg-green-100 border border-green-400 rounded" role="alert">
                <div class="flex">
                    <span class="font-bold">Success!</span>
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <button type="button" class="absolute top-0 right-0 p-4" onclick="document.getElementById('alert-success').remove()">
                        <svg class="w-4 h-4 text-green-700 fill-current" role="button" viewBox="0 0 20 20">
                            <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <form action="{{ route('ormawa.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="nomor_surat" class="block mb-1 font-medium">Nomor Surat</label>
                    <input type="text" id="nomor_surat" name="nomor_surat" class="w-full px-3 py-2 border rounded-md" required>
                </div>

                <div>
                    <label for="nama_pengaju" class="block mb-1 font-medium">Nama Pengaju</label>
                    <input type="text" id="nama_pengaju" name="nama_pengaju" class="w-full px-3 py-2 bg-gray-200 border rounded-md" value="{{ $ormawa->namaMahasiswa }}" readonly required>
                </div>

                <div>
                    <label for="nama_ormawa" class="block mb-1 font-medium">Nama Ormawa</label>
                    <input type="text" id="nama_ormawa" name="nama_ormawa" class="w-full px-3 py-2 bg-gray-200 border rounded-md" value="{{ $ormawa->namaOrmawa }}" readonly required>
                </div>

                <div>
                    <label for="kepada_tujuan" class="block mb-1 font-medium">Kepada / Tujuan</label>
                    <select id="kepada_tujuan" name="kepada_tujuan" class="w-full px-3 py-2 border rounded-md" required>
                        <option value="">Pilih Tujuan</option>
                        @foreach($dosenList as $dosen)
                            <option value="{{ $dosen->id }}">{{ $dosen->nama_dosen }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="hal" class="block mb-1 font-medium">Hal</label>
                    <input type="text" id="hal" name="hal" class="w-full px-3 py-2 border rounded-md" required>
                </div>

                <div>
                    <label for="unggah_dokumen" class="block mb-1 font-medium">Unggah Dokumen</label>
                    <input type="file" id="unggah_dokumen" name="unggah_dokumen" class="w-full px-3 py-2 border rounded-md" required>
                </div>

                <div>
                    <label for="catatan" class="block mb-1 font-medium">Catatan (opsional)</label>
                    <textarea id="catatan" name="catatan" rows="4" class="w-full px-3 py-2 border rounded-md"></textarea>
                </div>

                <div class="mt-6 mb-6">
                    <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">Ajukan</button>
                </div>
            </div>
        </form>
    </div>
@endsection
