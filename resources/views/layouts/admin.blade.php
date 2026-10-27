@php
    $adminUser = auth()->user();
    $adminFullName = $adminUser->name ?? 'Admin Early Cake';
    $adminFirstName = \Illuminate\Support\Str::before($adminFullName, ' ');

    $menu = [
        [
            'label' => 'Dashboard',
            'route' => 'admin.dashboard',
            'active' => 'admin.dashboard',
        ],
        [
            'label' => 'Produk',
            'route' => 'admin.products.index',
            'active' => 'admin.products.*',
        ],
        [
            'label' => 'Pesanan',
            'route' => 'admin.orders.index',
            'active' => 'admin.orders.*',
        ],
        [
            'label' => 'Custom Cake',
            'route' => 'admin.custom-cakes.index',
            'active' => 'admin.custom-cakes.*',
        ],
        [
            'label' => 'Pembayaran',
            'route' => 'admin.payments.index',
            'active' => 'admin.payments.*',
        ],
        [
            'label' => 'Laporan',
            'route' => 'admin.reports.index',
            'active' => 'admin.reports.*',
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Dashboard') · Early Cake Admin
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-cream font-sans text-ink antialiased">

    {{-- Overlay sidebar untuk perangkat mobile --}}
    <div
        id="sidebar-overlay"
        data-sidebar-close
        class="fixed inset-0 z-30 hidden bg-ink/30 lg:hidden">
    </div>

    {{-- Sidebar --}}
    <aside
        id="admin-sidebar"
        class="fixed inset-y-0 left-0 z-40 flex w-54
               -translate-x-full flex-col border-r border-line
               bg-white transition-transform duration-200
               lg:translate-x-0">

        {{-- Logo --}}
        <div class="flex items-center justify-between px-6 pt-5">
            <a
                href="{{ route('admin.dashboard') }}"
                class="text-xl font-bold tracking-tight text-ink">
                Early Cake<span class="text-brand">.</span>
            </a>

            <button
                type="button"
                data-sidebar-close
                class="rounded p-1 text-muted lg:hidden"
                aria-label="Tutup menu">

                <svg
                    class="size-5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round">
                    <path d="M6 6l12 12M18 6L6 18"/>
                </svg>
            </button>
        </div>

        {{-- Navigasi --}}
        <nav class="mt-9 flex-1 space-y-1 px-6 text-[13px]">

            @foreach ($menu as $item)
                @php
                    $routeExists = \Illuminate\Support\Facades\Route::has(
                        $item['route']
                    );

                    $isActive = request()->routeIs($item['active']);
                @endphp

                @if ($routeExists)
                    <a
                        href="{{ route($item['route']) }}"
                        @if ($isActive) aria-current="page" @endif
                        class="flex items-center gap-2.5 rounded-lg
                               px-2 py-2.5 transition
                               {{ $isActive
                                    ? 'bg-rose-50 font-semibold text-brand'
                                    : 'text-muted hover:bg-cream hover:text-ink' }}">

                        <span
                            class="size-1.5 shrink-0 rounded-full
                            {{ $isActive ? 'bg-brand' : 'bg-transparent' }}">
                        </span>

                        {{ $item['label'] }}
                    </a>
                @else
                    <div
                        class="flex cursor-not-allowed items-center
                               gap-2.5 rounded-lg px-2 py-2.5
                               text-muted/60"
                        title="Halaman belum tersedia">

                        <span
                            class="size-1.5 shrink-0 rounded-full
                                   bg-transparent">
                        </span>

                        {{ $item['label'] }}
                        <span class="ml-auto text-[9px]">Segera</span>
                    </div>
                @endif
            @endforeach

            {{-- Logout --}}
            <a
                href="{{ route('admin.dashboard') }}#konfirmasi-logout"
                class="flex items-center gap-2.5 rounded-lg px-2
                       py-2.5 text-muted transition
                       hover:bg-cream hover:text-ink">

                <span
                    class="size-1.5 shrink-0 rounded-full
                           bg-transparent">
                </span>

                Logout
            </a>
        </nav>

        {{-- Informasi toko --}}
        <div class="mx-4 mb-5 rounded-xl bg-cream p-4
                    text-[11px] leading-relaxed text-muted">

            <p class="font-semibold text-ink">
                Early Cake
            </p>

            <p class="mt-1">
                Kelola pesanan dan penjualan toko dalam satu tempat.
            </p>
        </div>
    </aside>

    {{-- Area utama --}}
    <div class="flex min-h-screen flex-col lg:pl-54">

        {{-- Header --}}
        <header
            class="sticky top-0 z-20 flex h-14 items-center
                   justify-between border-b border-line bg-white
                   px-4 text-xs sm:px-8">

            <div class="flex min-w-0 items-center gap-3">

                <button
                    type="button"
                    data-sidebar-open
                    class="rounded p-1 text-ink lg:hidden"
                    aria-label="Buka menu">

                    <svg
                        class="size-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round">
                        <path d="M4 7h16M4 12h16M4 17h16"/>
                    </svg>
                </button>

                <span class="truncate text-muted">
                    Early Cake
                    <span class="mx-1">/</span>
                    <span class="text-ink">
                        @yield('breadcrumb', 'Dashboard')
                    </span>
                </span>
            </div>

            <div class="ml-3 shrink-0 text-right">
                <p class="font-semibold text-ink">
                    {{ $adminFirstName }}
                </p>
                <p class="text-[10px] text-muted">
                    Administrator
                </p>
            </div>
        </header>

        {{-- Konten halaman --}}
        <main class="flex-1 px-4 py-6 sm:px-8">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer
            class="border-t border-line px-4 py-5
                   text-[11px] text-muted sm:px-8">

            © {{ date('Y') }} Early Cake.
            Sistem Informasi Pemesanan dan Pengelolaan Kue.
        </footer>
    </div>
    
@stack('scripts')
</body>
</html>