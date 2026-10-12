<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Early Cake — Maniskan momen, rayakan cerita')</title>
    <meta name="description" content="@yield('description', 'Kue artisan yang dibuat sepenuh hati oleh Early Cake. Selalu fresh, bahan pilihan.')">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col">

    <x-customer.navbar />

    <main class="flex-1">
        @yield('content')
    </main>

    <x-customer.footer />

    @stack('scripts')
</body>
</html>
