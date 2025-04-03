<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    @vite('resources/css/app.css')
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
</head>
<body class="flex flex-col min-h-screen bg-gray-50">
    <div class="flex overflow-x-hidden flex-col w-full min-h-screen">
        @include('components.navbar_kemahasiswaan')

        <main class="container flex-grow px-4 py-8 mx-auto sm:px-6 lg:px-8">
            @yield('content')
        </main>

        @include('components.footer_kemahasiswaan')
    </div>
</body>
</html>
