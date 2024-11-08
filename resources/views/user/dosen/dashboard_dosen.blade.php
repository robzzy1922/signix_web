<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <img src="logo.png" alt="Logo" class="h-10">
                <a href="#" class="ml-6 text-black font-bold underline">Beranda</a>
                <a href="#" class="ml-6 text-black">Buat Tanda Tangan</a>
                <a href="#" class="ml-6 text-black">Riwayat</a>
            </div>
            <div class="flex items-center">
                <span class="text-xl">🔔</span>
                <span class="ml-4 text-xl">👤</span>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8 flex justify-between">
        <div class="w-1/2 bg-yellow-400 text-white p-8 rounded-lg text-center">
            <h2 class="text-2xl font-bold">10 Surat diajukan</h2>
        </div>
        <div class="w-1/2 bg-green-400 text-white p-8 rounded-lg text-center">
            <h2 class="text-2xl font-bold">10 Surat sudah tertanda</h2>
        </div>
    </div>

    <div class="container mx-auto px-4 py-4 flex justify-center">
        <input type="text" placeholder="Nama surat, nomor surat..." class="border border-gray-300 p-2 rounded-lg mr-4">
        <select class="border border-gray-300 p-2 rounded-lg">
            <option value="tertanda">Tertanda</option>
            <!-- Add more options as needed -->
        </select>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-gray-300 p-4 rounded-lg">
            <p><strong>Robi</strong> telah mengajukan permintaan tanda tangan anda</p>
            <button class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-lg">Lihat Detail</button>
            <!-- Add more notifications as needed -->
        </div>
    </div>

    <footer class="bg-gray-300 py-8">
        <div class="container mx-auto text-center">
            <p class="font-bold">Contact Us</p>
            <p>📍 Locations</p>
            <p>📞 Call: +6281234567</p>
            <p>✉️ informatika@gmail.com</p>
        </div>
    </footer>

</body>
</html>