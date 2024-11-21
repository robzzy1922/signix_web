<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite('resources/css/app.css')
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="flex flex-col min-h-screen">
    @include('components.navbar_ormawa')

    <main class="flex-grow">
        @yield('content')
    </main>

    @include('components.footer_ormawa')

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
