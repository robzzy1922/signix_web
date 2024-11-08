<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Formulir Pengajuan</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="flex flex-col min-h-screen">
    @include('user.ormawa.header.navbar')

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
                    <input type="text" id="nama_pengaju" name="nama_pengaju" class="w-full px-3 py-2 border rounded-md" required>
                </div>

                <div>
                    <label for="nama_ormawa" class="block mb-1 font-medium">Nama Ormawa</label>
                    <input type="text" id="nama_ormawa" name="nama_ormawa" class="w-full px-3 py-2 border rounded-md" required>
                </div>

                <div>
                    <label for="kepada_tujuan" class="block mb-1 font-medium">Kepada / Tujuan</label>
                    <select id="kepada_tujuan" name="kepada_tujuan" class="w-full px-3 py-2 border rounded-md" required>
                        <option value="">Pilih Tujuan</option>
                        <option value="dekan">Dekan</option>
                        <option value="wadek">Wakil Dekan</option>
                        <option value="kaprodi">Kepala Program Studi</option>
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

    @include('user.ormawa.header.footer')
</body>
</html>
