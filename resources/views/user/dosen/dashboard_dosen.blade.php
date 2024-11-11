<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    @include('user.dosen.header.navbar')

    <div class="container mx-auto px-4 py-8 flex">
        <div class="flex-1">
            <div class="flex justify-between space-x-4 mb-8">
                <div class="w-1/2 bg-yellow-400 text-black p-8 rounded-lg text-center">
                    <i class="fas fa-envelope text-4xl mb-4"></i>
                    <h2 class="text-2xl font-bold">10 Surat diajukan</h2>
                </div>
                <div class="w-1/2 bg-green-400 text-black p-8 rounded-lg text-center">
                    <i class="fas fa-envelope text-4xl mb-4"></i>
                    <h2 class="text-2xl font-bold">10 Surat sudah tertanda</h2>
                </div>
            </div>

            <div class="flex items-center space-x-4 mb-8">
                <div class="flex flex-col">
                    <label for="search" class="text-sm font-semibold mb-1">Cari Surat</label>
                    <div class="flex items-center border border-gray-300 p-2 rounded-lg">
                        <i class="fas fa-search mr-2"></i>
                        <input id="search" type="text" placeholder="Nama surat, nomor surat..." class="outline-none">
                    </div>
                </div>
                <div class="flex flex-col">
                    <label for="status" class="text-sm font-semibold mb-1">Status</label>
                    <select id="status" class="border border-gray-300 p-2 rounded-lg">
                        <option value="sudah_tertanda">Sudah Tertanda</option>
                        <option value="revisi">Revisi</option>
                        <option value="belum_tertanda">Belum Tertanda</option>
                    </select>
                </div>
            </div>

            <!-- New section for the list of letters -->
            <div class="bg-gray-200 p-4 rounded-lg">
                <ul class="space-y-2">
                    <li class="bg-white p-4 rounded-lg shadow">
                        <h4 class="font-bold">Surat 1</h4>
                        <p>Status: Sudah Tertanda</p>
                    </li>
                    <li class="bg-white p-4 rounded-lg shadow">
                        <h4 class="font-bold">Surat 2</h4>
                        <p>Status: Revisi</p>
                    </li>
                    <li class="bg-white p-4 rounded-lg shadow">
                        <h4 class="font-bold">Surat 3</h4>
                        <p>Status: Belum Tertanda</p>
                    </li>
                    <!-- Add more list items as needed -->
                </ul>
            </div>

        </div>

        <aside class="w-1/4 bg-white p-4 rounded-lg shadow-lg ml-4">
            <h3 class="text-xl font-bold mb-4">Notifikasi</h3>
            <div class="bg-gray-200 p-2 rounded-lg mb-2">
                <p><strong>Robi</strong> telah mengajukan permintaan tanda tangan anda</p>
                <button class="bg-blue-500 text-white px-2 py-1 rounded-lg mt-2">Lihat Detail</button>
            </div>
            <!-- Add more notifications as needed -->
        </aside>
    </div>

 @include('user.dosen.header.footer')

</body>
</html>