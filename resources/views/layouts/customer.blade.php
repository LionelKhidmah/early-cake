<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Early Cake — Maniskan momen, rayakan cerita')</title>
    <meta name="description" content="@yield('description', 'Kue artisan yang dibuat sepenuh hati oleh Early Cake. Selalu fresh, bahan pilihan.')">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-rose-pale text-choco">

    <x-customer.navbar />

    <main class="flex-1">
        @yield('content')
    </main>

    <x-customer.footer />

    @stack('scripts')
</body>
</html>
