
@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Str;

    $adminUser = auth()->user();
    $adminFullName = $adminUser?->name ?? 'Admin Early Cake';
    $adminFirstName = Str::before($adminFullName, ' ');
    $adminInitial = Str::upper(Str::substr($adminFirstName, 0, 1));

    $menuGroups = [
        [
            'title' => 'MENU UTAMA',
            'items' => [
                [
                    'label' => 'Dashboard',
                    'route' => 'admin.dashboard',
                    'active' => 'admin.dashboard',
                    'icon' => 'dashboard',
                ],
                [
                    'label' => 'Produk',
                    'route' => 'admin.products.index',
                    'active' => 'admin.products.*',
                    'icon' => 'products',
                ],
                [
                    'label' => 'Promo',
                    'route' => 'admin.promos.index',
                    'active' => 'admin.promos.*',
                    'icon' => 'promo',
                ],
                [
                    'label' => 'Pesanan',
                    'route' => 'admin.orders.index',
                    'active' => 'admin.orders.*',
                    'icon' => 'orders',
                ],
                [
                    'label' => 'Kue Kustom',
                    'route' => 'admin.custom-cakes.index',
                    'active' => 'admin.custom-cakes.*',
                    'icon' => 'cake',
                ],
            ],
        ],
        [
            'title' => 'KEUANGAN & ANALISIS',
            'items' => [
                [
                    'label' => 'Pembayaran',
                    'route' => 'admin.payments.index',
                    'active' => 'admin.payments.*',
                    'icon' => 'payment',
                ],
                [
                    'label' => 'Laporan',
                    'route' => 'admin.reports.index',
                    'active' => 'admin.reports.*',
                    'icon' => 'reports',
                ],
            ],
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | Early Cake Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-[#FFF1F4] font-sans text-[#403431] antialiased">

    {{-- MOBILE OVERLAY --}}
    <div
        id="sidebar-overlay"
        class="fixed inset-0 z-40 hidden bg-[#211A19]/40 backdrop-blur-[2px] lg:hidden"
        aria-hidden="true">
    </div>

    {{-- SIDEBAR --}}
    <aside
        id="admin-sidebar"
        class="fixed inset-y-0 left-0 z-50 flex w-64
               -translate-x-full flex-col bg-[#FFF7F9]
               shadow-[4px_0_24px_rgba(183,92,120,0.035)]
               transition-transform duration-300 ease-in-out
               lg:translate-x-0">

        {{-- BRAND --}}
        <div class="flex h-20 shrink-0 items-center justify-between px-6">

            <a
                href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3">

                <div class="flex size-10 items-center justify-center
                            rounded-xl bg-[#FCE8ED] text-[#AD5871]">

                    <svg
                        class="size-6"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linecap="round"
                        stroke-linejoin="round">

                        <path d="M4 11h16v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z"/>
                        <path d="M4 11c0-2 2-3 4-3s2 2 4 2 2-2 4-2 4 1 4 3"/>
                        <path d="M12 8V4"/>
                        <path d="M12 4c-1-1-1-2 0-3 1 1 1 2 0 3z"/>
                    </svg>
                </div>

                <div>
                    <h1 class="font-bold tracking-tight text-[#40302F]">
                        Early Cake<span class="text-[#B75C78]">.</span>
                    </h1>

                    <p class="mt-0.5 text-[10px] font-medium
                              tracking-[0.13em] text-[#A99A96]">
                        ADMIN WORKSPACE
                    </p>
                </div>
            </a>

            {{-- CLOSE MOBILE SIDEBAR --}}
            <button
                type="button"
                data-sidebar-close
                class="rounded-lg p-2 text-[#8B7B77]
                       hover:bg-[#FCE8ED] lg:hidden"
                aria-label="Tutup sidebar">

                <svg
                    class="size-5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round">

                    <path d="M6 6l12 12M18 6L6 18"/>
                </svg>
            </button>
        </div>

        {{-- NAVIGATION --}}
        <nav
            class="min-h-0 flex-1 space-y-7 overflow-y-auto
                   px-4 pb-5 pt-5"
            aria-label="Navigasi admin">

            @foreach ($menuGroups as $group)

                <div>
                    <p class="mb-3 px-3 text-[10px] font-bold
                              tracking-[0.14em] text-[#B2A5A1]">
                        {{ $group['title'] }}
                    </p>

                    <div class="space-y-1">

                        @foreach ($group['items'] as $item)
                            @php
                                $routeExists = Route::has($item['route']);
                                $isActive = request()->routeIs($item['active']);
                            @endphp

                            @if ($routeExists)

                                <a
                                    href="{{ route($item['route']) }}"
                                    @if ($isActive) aria-current="page" @endif
                                    class="group relative flex items-center
                                           gap-3 rounded-xl px-3 py-3
                                           text-[13px] transition-all duration-200
                                           {{ $isActive
                                               ? 'bg-[#FCE8ED] font-semibold text-[#A84D6A]'
                                               : 'font-medium text-[#837672] hover:bg-[#FCEEF1] hover:text-[#493A37]' }}">

                                    {{-- ACTIVE INDICATOR --}}
                                    @if ($isActive)
                                        <span
                                            class="absolute bottom-2 left-0 top-2
                                                   w-[3px] rounded-r-full
                                                   bg-[#B75C78]">
                                        </span>
                                    @endif

                                    {{-- ICON --}}
                                    <span class="flex size-5 shrink-0
                                                 items-center justify-center">

                                        @switch($item['icon'])

                                            {{-- DASHBOARD --}}
                                            @case('dashboard')
                                                <svg class="size-5" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor"
                                                     stroke-width="1.8"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round">

                                                    <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                                                    <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                                                    <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                                                    <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                                                </svg>
                                                @break

                                            {{-- PRODUK --}}
                                            @case('products')
                                                <svg class="size-5" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor"
                                                     stroke-width="1.8"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round">

                                                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                                    <path d="m3.3 7 8.7 5 8.7-5M12 22V12"/>
                                                </svg>
                                                @break

                                            {{-- PROMO --}}
                                            @case('promo')
                                                <svg class="size-5" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor"
                                                     stroke-width="1.8"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round">

                                                    <path d="M2 9a3 3 0 0 1 0 6v3a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-3a3 3 0 0 1 0-6V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v3Z"/>
                                                    <path d="M13 5v2m0 4v2m0 4v2"/>
                                                </svg>
                                                @break

                                            {{-- PESANAN --}}
                                            @case('orders')
                                                <svg class="size-5" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor"
                                                     stroke-width="1.8"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round">

                                                    <path d="M6 7h12l1 14H5L6 7z"/>
                                                    <path d="M9 9V6a3 3 0 0 1 6 0v3"/>
                                                </svg>
                                                @break

                                            {{-- CUSTOM CAKE --}}
                                            @case('cake')
                                                <svg class="size-5" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor"
                                                     stroke-width="1.8"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round">

                                                    <path d="M4 12h16v9H4z"/>
                                                    <path d="M4 12c0-2 2-3 4-3s2 2 4 2 2-2 4-2 4 1 4 3"/>
                                                    <path d="M12 9V5"/>
                                                    <path d="M12 5c-1-1-1-2 0-3 1 1 1 2 0 3z"/>
                                                </svg>
                                                @break

                                            {{-- PEMBAYARAN --}}
                                            @case('payment')
                                                <svg class="size-5" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor"
                                                     stroke-width="1.8"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round">

                                                    <rect x="2" y="5" width="20" height="14" rx="2"/>
                                                    <path d="M2 10h20"/>
                                                    <path d="M6 15h4"/>
                                                </svg>
                                                @break

                                            {{-- LAPORAN --}}
                                            @case('reports')
                                                <svg class="size-5" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor"
                                                     stroke-width="1.8"
                                                     stroke-linecap="round"
                                                     stroke-linejoin="round">

                                                    <path d="M4 20V10M10 20V4M16 20v-7M22 20V8"/>
                                                </svg>
                                                @break

                                        @endswitch
                                    </span>

                                    {{-- MENU LABEL --}}
                                    <span class="flex-1">
                                        {{ $item['label'] }}
                                    </span>

                                    {{-- ACTIVE DOT --}}
                                    @if ($isActive)
                                        <span class="size-1.5 rounded-full
                                                     bg-[#B75C78]">
                                        </span>
                                    @endif
                                </a>

                            @else

                                {{-- DISABLED MENU --}}
                                <div
                                    class="flex cursor-not-allowed items-center
                                           gap-3 rounded-xl px-3 py-3
                                           text-[13px] text-[#C0B7B4]"
                                    title="Halaman belum tersedia">

                                    <svg
                                        class="size-5 shrink-0"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round">

                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M12 8v4l3 2"/>
                                    </svg>

                                    <span class="flex-1">
                                        {{ $item['label'] }}
                                    </span>

                                    <span class="rounded-md bg-[#F7F0F2]
                                                 px-2 py-1 text-[9px]">
                                        Segera
                                    </span>
                                </div>

                            @endif

                        @endforeach
                    </div>
                </div>

            @endforeach
        </nav>

        {{-- SIDEBAR FOOTER --}}
        <div class="shrink-0 bg-[#FFF7F9] px-4 pb-5 pt-3">

            {{-- LOGOUT --}}
            <form
                method="POST"
                action="{{ route('admin.logout') }}"
                id="admin-logout-form">

                @csrf

                <button
                    type="submit"
                    class="flex w-full items-center gap-3
                           rounded-xl px-4 py-3 text-left
                           text-[13px] font-semibold
                           text-[#B75C78] transition-all
                           duration-200 hover:bg-[#FCE8ED]">

                    <svg
                        class="size-5 shrink-0"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round">

                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <path d="m16 17 5-5-5-5"/>
                        <path d="M21 12H9"/>
                    </svg>

                    <span>Logout</span>
                </button>
            </form>

            {{-- ADMIN PROFILE --}}
            <div class="mt-3 flex items-center gap-3
                        rounded-xl bg-[#FCE8ED] p-3">

                <div class="flex size-9 shrink-0 items-center
                            justify-center rounded-lg bg-white
                            text-sm font-bold text-[#B75C78]">
                    {{ $adminInitial }}
                </div>

                <div class="min-w-0">
                    <p class="truncate text-xs font-semibold
                              text-[#493A37]">
                        {{ $adminFullName }}
                    </p>

                    <p class="mt-0.5 text-[10px] text-[#A87886]">
                        Administrator
                    </p>
                </div>
            </div>
        </div>
    </aside>

    {{-- MAIN WRAPPER --}}
    <div class="flex min-h-screen min-w-0 flex-col lg:pl-64">

        {{-- TOPBAR --}}
        <header
            class="sticky top-0 z-30 flex h-[76px] shrink-0
                   items-center justify-between bg-[#FFF7F9]/95
                   px-4 shadow-[0_4px_20px_rgba(183,92,120,0.025)]
                   backdrop-blur-md sm:px-8">

            {{-- TOPBAR LEFT --}}
            <div class="flex min-w-0 items-center gap-4">

                <button
                    type="button"
                    data-sidebar-open
                    class="rounded-xl p-2 text-[#645652]
                           transition hover:bg-[#FCE8ED] lg:hidden"
                    aria-label="Buka sidebar"
                    aria-controls="admin-sidebar"
                    aria-expanded="false">

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

                <div class="min-w-0">
                    <p class="hidden text-[11px] font-medium
                              text-[#AA9C97] sm:block">
                        Early Cake / Admin Panel
                    </p>

                    <h2 class="truncate text-base font-bold
                               tracking-tight text-[#403431]
                               sm:text-lg">
                        @yield('breadcrumb', 'Dashboard')
                    </h2>
                </div>
            </div>

            {{-- TOPBAR RIGHT --}}
            <div class="ml-3 flex shrink-0 items-center gap-3">

                <div class="hidden text-right sm:block">
                    <p class="text-[13px] font-semibold text-[#493A37]">
                        {{ $adminFirstName }}
                    </p>

                    <p class="mt-0.5 text-[10px] text-[#A99A96]">
                        Administrator
                    </p>
                </div>

                <div
                    class="flex size-10 items-center justify-center
                           rounded-xl bg-[#FCE8ED] text-sm
                           font-bold text-[#AD5871]"
                    title="{{ $adminFullName }}">

                    {{ $adminInitial }}
                </div>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="min-w-0 flex-1 px-4 py-6 sm:px-8 sm:py-8">

            <div class="mx-auto w-full max-w-[1500px]">
                @yield('content')
            </div>

        </main>

        {{-- FOOTER --}}
        <footer class="px-4 py-5 sm:px-8">

            <div class="mx-auto flex max-w-[1500px]
                        flex-col items-center justify-between
                        gap-2 text-center text-[11px]
                        text-[#A99A96] sm:flex-row sm:text-left">

                <p>
                    © {{ date('Y') }} Early Cake.
                    All rights reserved.
                </p>

                <p>
                    Sistem Informasi Pemesanan dan Pengelolaan Kue.
                </p>
            </div>
        </footer>
    </div>

    {{-- MOBILE SIDEBAR SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const openButton = document.querySelector('[data-sidebar-open]');
            const closeButtons = document.querySelectorAll('[data-sidebar-close]');
            const mobileBreakpoint = window.matchMedia('(min-width: 1024px)');

            if (!sidebar || !overlay || !openButton) {
                return;
            }

            function openSidebar() {
                if (mobileBreakpoint.matches) return;

                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                openButton.setAttribute('aria-expanded', 'true');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                openButton.setAttribute('aria-expanded', 'false');
            }

            openButton.addEventListener('click', openSidebar);

            closeButtons.forEach(function (button) {
                button.addEventListener('click', closeSidebar);
            });

            overlay.addEventListener('click', closeSidebar);

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            });

            mobileBreakpoint.addEventListener('change', function (event) {
                if (event.matches) {
                    closeSidebar();
                }
            });
        });
    </script>

    @stack('scripts')

</body>
</html>
