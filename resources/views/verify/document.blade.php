<div class="container px-4 py-8 mx-auto">
    <div class="p-6 mx-auto max-w-2xl bg-white rounded-lg shadow-lg">
        @if(isset($verified) && $verified)
            <div class="mb-6 text-center">
                <h1 class="mb-2 text-2xl font-bold text-green-600">✓ Dokumen Terverifikasi</h1>
                <p class="text-gray-600">Diverifikasi pada: {{ $timestamp }}</p>
            </div>

            <div class="py-4 border-t border-gray-200">
                <h2 class="mb-4 text-xl font-semibold">Detail Dokumen:</h2>
                <div class="space-y-3">
                    <p><span class="font-semibold">Nomor Surat:</span> {{ $dokumen->nomor_surat }}</p>
                    <p><span class="font-semibold">Tanggal:</span> {{ $dokumen->tanggal_pengajuan }}</p>
                    <p><span class="font-semibold">Perihal:</span> {{ $dokumen->perihal }}</p>
                    <p><span class="font-semibold">Status:</span>
                        <span class="px-2 py-1 text-white bg-green-500 rounded">{{ ucfirst($dokumen->status_dokumen) }}</span>
                    </p>
                    @if($dokumen->dosen)
                        <p><span class="font-semibold">Ditandatangani oleh:</span> {{ $dokumen->dosen->nama_dosen }}</p>
                    @endif
                </div>
            </div>

            @if($dokumen->file)
                <div class="mt-6 text-center">
                    <a href="{{ Storage::disk('public')->url($dokumen->file) }}"
                       class="inline-block px-6 py-2 text-white bg-blue-500 rounded hover:bg-blue-600"
                       target="_blank">
                        Lihat Dokumen
                    </a>
                </div>
            @endif
        @else
            <div class="text-center">
                <h1 class="mb-2 text-2xl font-bold text-red-600">✗ Verifikasi Gagal</h1>
                <p class="text-gray-600">{{ $message ?? 'Dokumen tidak dapat diverifikasi' }}</p>
            </div>
        @endif
    </div>
</div>
