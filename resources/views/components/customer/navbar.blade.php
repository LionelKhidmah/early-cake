@php
    $links = [
        ['label' => 'Menu',         'route' => 'products.index',    'pattern' => 'menu*'],
        ['label' => 'Promo',        'route' => 'promo.index',       'pattern' => 'promo*'],
        ['label' => 'Kue Custom',   'route' => 'custom-cake.index', 'pattern' => 'kue-custom*'],
        ['label' => 'Tentang Kami', 'route' => 'about',             'pattern' => 'tentang-kami*'],
        ['label' => 'Kontak',       'route' => 'contact',           'pattern' => 'kontak*'],
    ];
@endphp

<header id="site-header" class="sticky top-0 z-50 border-t-[3px] border-choco bg-cream-50/90 backdrop-blur transition-shadow">
    <nav class="mx-auto flex h-16 max-w-6xl items-center justify-between px-5 lg:px-8" aria-label="Navigasi utama">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight text-choco">
            Early Cake<span class="text-rose-kue">.</span>
        </a>

        {{-- Menu desktop --}}
        <ul class="hidden items-center gap-8 md:flex">
            @foreach ($links as $link)
                <li>
                    <a href="{{ route($link['route']) }}"
                       class="nav-link {{ request()->is($link['pattern']) ? 'is-active' : '' }}">
                        {{ $link['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- Aksi kanan (desktop) --}}
        <div class="hidden items-center gap-5 md:flex">
            <a href="{{ route('orders.track') }}" class="text-sm font-medium text-rose-kue hover:text-rose-kue-dark">
                Lacak Pesanan
            </a>
            <a href="{{ route('checkout.index') }}"
               class="relative inline-flex items-center gap-1.5 text-sm font-medium text-rose-kue hover:text-rose-kue-dark">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M6 6h15l-1.5 9h-12z"/><path d="M6 6 5 3H2"/><circle cx="9" cy="20" r="1"/><circle cx="18" cy="20" r="1"/>
                </svg>
                Keranjang
                <span id="cart-count" class="rounded-full bg-rose-kue px-1.5 text-[11px] font-semibold leading-5 text-white">0</span>
            </a>
        </div>

        {{-- Tombol hamburger (mobile) --}}
        <button id="menu-toggle" type="button"
                class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-choco hover:bg-cream md:hidden"
                aria-controls="mobile-menu" aria-expanded="false" aria-label="Buka menu">
            <svg id="icon-open" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
            <svg id="icon-close" class="hidden h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg>
        </button>
    </nav>

    {{-- Menu mobile --}}
    <div id="mobile-menu" class="hidden border-t border-choco/10 bg-cream-50 md:hidden">
        <ul class="mx-auto max-w-6xl space-y-1 px-5 py-4">
            @foreach ($links as $link)
                <li>
                    <a href="{{ route($link['route']) }}"
                       class="block rounded-lg px-3 py-2.5 text-sm {{ request()->is($link['pattern']) ? 'bg-cream font-semibold text-choco' : 'text-choco-light hover:bg-cream' }}">
                        {{ $link['label'] }}
                    </a>
                </li>
            @endforeach
            <li class="mt-2 flex gap-3 border-t border-choco/10 pt-3">
                <a href="{{ route('orders.track') }}" class="btn btn-outline flex-1">Lacak Pesanan</a>
                <a href="{{ route('checkout.index') }}" class="btn btn-primary flex-1">Keranjang</a>
            </li>
        </ul>
    </div>
</header>
