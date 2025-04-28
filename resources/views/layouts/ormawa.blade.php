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
    <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="flex flex-col min-h-screen bg-gray-50">
    <div class="flex overflow-x-hidden flex-col w-full min-h-screen">
        @include('components.navbar_ormawa')

        <main class="container flex-grow px-4 py-8 mx-auto sm:px-6 lg:px-8">
            @yield('content')
        </main>

        @include('components.footer_ormawa')
    </div>

    <script src="{{ asset('js/app.js') }}"></script>

    {{-- <form method="POST" action="{{ route('ormawa.logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form> --}}
</body>
</html>
