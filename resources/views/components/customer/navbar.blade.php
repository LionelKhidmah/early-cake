@php
$links = [
['label' => 'Beranda', 'route' => 'home', 'pattern' => ''],
['label' => 'Menu', 'route' => 'products.index', 'pattern' => 'produk*'],
['label' => 'Promo', 'route' => 'promo.index', 'pattern' => 'promo*'],
['label' => 'Kue Kustom', 'route' => 'custom-cake.index', 'pattern' => 'custom-cake*'],
['label' => 'Tentang Kami', 'route' => 'about', 'pattern' => 'tentang-kami*'],
['label' => 'Kontak', 'route' => 'contact', 'pattern' => 'kontak*'],
];

// Jumlah seluruh item di keranjang berdasarkan session Laravel.
$cartCount = array_sum(session()->get('cart', []));

@endphp

<header id="site-header"
    class="sticky top-0 z-50 border-t-[3px] border-choco bg-cream-50/90 backdrop-blur transition-shadow"><nav class="mx-auto flex h-16 max-w-6xl items-center justify-between px-5 lg:px-8"
    aria-label="Navigasi utama">

    {{-- Logo --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center">
            <img
                src="{{ asset('images/logo/logo.jpg') }}"
                alt="Early Cake"
                class="block h-13 w-auto max-w-[150px] object-contain"
            >
        </a>

        <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight text-choco">
            Early Cakes<span class="text-rose-kue">.</span>
        </a>
    </div>

    {{-- Menu desktop --}}
    <ul class="hidden items-center gap-8 md:flex">
        @foreach ($links as $link)
            <li>
                <a href="{{ route($link['route']) }}"
                    @if ($link['route'] === 'home')
                        @if (request()->routeIs('home')) aria-current="page" @endif
                    @elseif (request()->is($link['pattern']))
                        aria-current="page"
                    @endif
                    class="nav-link {{ ($link['route'] === 'home' && request()->routeIs('home')) || ($link['pattern'] !== '' && request()->is($link['pattern'])) ? 'is-active' : '' }}">
                    {{ $link['label'] }}
                </a>
            </li>
        @endforeach
    </ul>

    {{-- Aksi kanan desktop --}}
    <div class="hidden items-center gap-5 md:flex">
        <a href="{{ route('orders.track') }}"
            class="text-sm font-medium text-rose-kue transition hover:text-rose-kue-dark">
            Lacak Pesanan
        </a>

        <a href="{{ route('cart.index') }}"
            @if (request()->is('keranjang*')) aria-current="page" @endif
            class="relative inline-flex items-center gap-1.5 text-sm font-medium text-rose-kue transition hover:text-rose-kue-dark">

            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M6 6h15l-1.5 9h-12z"/>
                <path d="M6 6 5 3H2"/>
                <circle cx="9" cy="20" r="1"/>
                <circle cx="18" cy="20" r="1"/>
            </svg>

            Keranjang

            <span id="cart-count"
                class="min-w-5 rounded-full bg-rose-kue px-1.5 text-center text-[11px] font-semibold leading-5 text-white">
                {{ $cartCount }}
            </span>
        </a>
    </div>

    {{-- Tombol hamburger mobile --}}
    <button id="menu-toggle" type="button"
        class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-choco hover:bg-cream md:hidden"
        aria-controls="mobile-menu"
        aria-expanded="false"
        aria-label="Buka menu">

        <svg id="icon-open" class="h-6 w-6" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="1.8"
            stroke-linecap="round" aria-hidden="true">
            <path d="M4 7h16M4 12h16M4 17h16"/>
        </svg>

        <svg id="icon-close" class="hidden h-6 w-6" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="1.8"
            stroke-linecap="round" aria-hidden="true">
            <path d="M6 6l12 12M18 6 6 18"/>
        </svg>
    </button>
</nav>

{{-- Menu mobile --}}
<div id="mobile-menu" class="hidden border-t border-choco/10 bg-cream-50 md:hidden">
    <ul class="mx-auto max-w-6xl space-y-1 px-5 py-4">

        @foreach ($links as $link)
            @php
                $isActive = $link['route'] === 'home'
                    ? request()->routeIs('home')
                    : ($link['pattern'] !== '' && request()->is($link['pattern']));
            @endphp

            <li>
                <a href="{{ route($link['route']) }}"
                    @if ($isActive) aria-current="page" @endif
                    class="block rounded-lg px-3 py-2.5 text-sm {{ $isActive ? 'bg-cream font-semibold text-choco' : 'text-choco-light hover:bg-cream' }}">
                    {{ $link['label'] }}
                </a>
            </li>
        @endforeach

        <li>
            <a href="{{ route('orders.track') }}"
                class="mt-2 block rounded-lg px-3 py-2.5 text-sm text-choco-light transition hover:bg-cream">
                Lacak Pesanan
            </a>
        </li>

        <li>
            <a href="{{ route('cart.index') }}"
                class="mt-1 flex items-center justify-between rounded-lg border border-rose-200 px-3 py-2.5 text-sm font-semibold text-rose-kue transition hover:bg-rose-50">

                <span class="flex items-center gap-2">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M6 6h15l-1.5 9h-12z"/>
                        <path d="M6 6 5 3H2"/>
                        <circle cx="9" cy="20" r="1"/>
                        <circle cx="18" cy="20" r="1"/>
                    </svg>
                    Keranjang
                </span>

                <span id="cart-count-mobile"
                    class="min-w-5 rounded-full bg-rose-kue px-1.5 text-center text-[11px] font-semibold leading-5 text-white">
                    {{ $cartCount }}
                </span>
            </a>
        </li>

    </ul>
</div>

</header>