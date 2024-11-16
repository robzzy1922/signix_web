<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite('resources/css/app.css')
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="flex flex-col min-h-screen">
    @include('components.navbar_ormawa')

    <main class="flex-grow">
        @yield('content')
    </main>

    @include('components.footer_ormawa')
</body>
</html>
