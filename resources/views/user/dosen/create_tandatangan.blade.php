<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Tanda Tangan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    @include('user.dosen.header.navbar')

    <div class="container mx-auto mt-20 text-center">
        <h1 class="text-4xl font-bold">Tanda Tangani Dokumen</h1>
        <p class="mt-4 text-lg">Alat untuk menandatangani dokumen secara elektronik. Tanda tangani dokumen sendiri atau tanda tangani dokumen orang lain.</p>
        
        <div class="mt-12">
            <label class="inline-block">
                <span class="sr-only">Choose File</span>
                <input type="file" class="hidden" />
                <div class="bg-yellow-500 text-white font-bold py-4 px-8 text-xl rounded-full cursor-pointer hover:bg-yellow-600">
                    PILIH FILE
                </div>
            </label>
            <p class="mt-4 text-sm text-gray-500">Atau jatuhkan file di sini</p>
        </div>
    </div>
    <br>
    @include('user.dosen.header.footer')
</body>
</html> 