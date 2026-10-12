@php
    $footerLinks = [
        ['label' => 'Menu',          'route' => 'products.index'],
        ['label' => 'Promo',         'route' => 'promo.index'],
        ['label' => 'Kue Custom',    'route' => 'custom-cake.index'],
        ['label' => 'Tentang Kami',  'route' => 'about'],
        ['label' => 'Kontak',        'route' => 'contact'],
        ['label' => 'Lacak Pesanan', 'route' => 'orders.track'],
    ];
@endphp

<footer class="mt-16 bg-choco text-cream">
    <div class="mx-auto max-w-6xl px-5 py-10 lg:px-8">

        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div>
                <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight text-white">
                    Early Cake<span class="text-rose-soft">.</span>
                </a>
                <span class="ml-2 text-sm text-cream/70">Sedikit manis, banyak suka.</span>
            </div>

            <ul class="flex flex-wrap gap-x-6 gap-y-2 text-sm text-cream/80">
                @foreach ($footerLinks as $link)
                    <li>
                        <a href="{{ route($link['route']) }}" class="transition hover:text-white">{{ $link['label'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="mt-8 flex flex-col gap-3 border-t border-cream/15 pt-5 text-xs text-cream/60 md:flex-row md:items-center md:justify-between">
            <p>
                Jl. Cittadri No. 24
                <span class="mx-1">·</span> WhatsApp 0812-3456-7890
                <span class="mx-1">·</span> Setiap hari 08.00–20.00 WIB
            </p>
            <p>&copy; {{ date('Y') }} Early Cake</p>
        </div>
    </div>
</footer>
