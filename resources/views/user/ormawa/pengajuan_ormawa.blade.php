@extends('layouts.ormawa')
@section('title', 'Formulir Pengajuan')
@section('content')
    <div class="container mx-auto px-4 mt-8 max-w-3xl flex-grow">
        <h1 class="text-2xl font-bold mb-6">FORMULIR PENGAJUAN</h1>

        <form action="{{ route('ormawa.pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="mb-12">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="nomor_surat" class="block mb-1 font-medium">Nomor Surat</label>
                    <input type="text" id="nomor_surat" name="nomor_surat" class="w-full px-3 py-2 border rounded-md" required>
                </div>

                <div>
                    <label for="nama_pengaju" class="block mb-1 font-medium">Nama Pengaju</label>
                    <input type="text" id="nama_pengaju" name="nama_pengaju" class="w-full px-3 py-2 border rounded-md" value="{{ $ormawa->nama_mahasiswa }}" readonly required>
                </div>

                <div>
                    <label for="nama_ormawa" class="block mb-1 font-medium">Nama Ormawa</label>
                    <input type="text" id="nama_ormawa" name="nama_ormawa" class="w-full px-3 py-2 border rounded-md" value="{{ $ormawa->nama_ormawa }}" readonly required>
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

                <div class="mt-6">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Ajukan</button>
                </div>
            </div>
        </form>
    </div>
@endsection
