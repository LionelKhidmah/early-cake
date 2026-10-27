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
    <div class="mx-auto max-w-6xl px-5 py-12 lg:px-8">

        {{-- Bagian utama footer --}}
        <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Brand dan media sosial --}}
            <div class="lg:col-span-2">
                <a href="{{ route('home') }}"
                   class="inline-block text-2xl font-bold tracking-tight text-white">
                    Early Cakes<span class="text-rose-soft">.</span>
                </a>

                <p class="mt-3 max-w-sm text-sm leading-7 text-cream/75">
                    Sedikit manis, banyak suka.
                    Temukan kue favorit untuk menemani setiap momen spesialmu.
                </p>

                <div class="mt-5 inline-flex items-center rounded-full border border-cream/15 bg-white/5 px-4 py-2 text-xs text-cream/80">
                    Freshly made with love
                    <span class="ml-2 text-rose-soft">♥</span>
                </div>

                {{-- Media sosial --}}
                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-white">
                        Ikuti Kami
                    </h3>

                    <div class="mt-3 flex items-center gap-3">
                        {{-- Instagram --}}
                        <a href="https://www.instagram.com/USERNAME_INSTAGRAM/"
                           target="_blank"
                           rel="noopener noreferrer"
                           aria-label="Instagram Early Cake"
                           class="flex h-10 w-10 items-center justify-center rounded-full border border-cream/20 text-cream transition hover:border-rose-soft hover:bg-rose-soft hover:text-choco">
                            <svg class="h-5 w-5" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round"
                                 aria-hidden="true">
                                <rect x="3" y="3" width="18" height="18" rx="5"/>
                                <circle cx="12" cy="12" r="4"/>
                                <circle cx="17.5" cy="6.5" r="0.8" fill="currentColor" stroke="none"/>
                            </svg>
                        </a>

                        {{-- WhatsApp --}}
                        <a href="https://wa.me/6287722619655"
                           target="_blank"
                           rel="noopener noreferrer"
                           aria-label="WhatsApp Early Cake"
                           class="flex h-10 w-10 items-center justify-center rounded-full border border-cream/20 text-cream transition hover:border-rose-soft hover:bg-rose-soft hover:text-choco">
                            <svg class="h-5 w-5" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round"
                                 aria-hidden="true">
                                <path d="M21 11.5a8.5 8.5 0 0 1-12.6 7.4L3 20l1.2-5.1A8.5 8.5 0 1 1 21 11.5Z"/>
                                <path d="M8.5 9a.8.8 0 0 1 1.2-.7l1 1.7a.8.8 0 0 1-.1.9l-.6.6a6 6 0 0 0 2.5 2.5l.6-.6a.8.8 0 0 1 .9-.1l1.7 1a.8.8 0 0 1-.7 1.2A7.5 7.5 0 0 1 8.5 9Z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Navigasi --}}
            <div>
                <h3 class="text-sm font-semibold tracking-wide text-white">
                    Navigasi
                </h3>

                <ul class="mt-4 space-y-3 text-sm text-cream/75">
                    @foreach ($footerLinks as $link)
                        <li>
                            <a href="{{ route($link['route']) }}"
                               class="transition-colors hover:text-rose-soft">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Informasi kontak --}}
            <div>
                <h3 class="text-sm font-semibold tracking-wide text-white">
                    Hubungi Kami
                </h3>

                <div class="mt-4 space-y-4 text-sm leading-6 text-cream/75">
                    <p>
                        <span class="mb-1 block font-medium text-white">Alamat</span>
                        Jl. Manyar Rejo III No. 4,
                        Kota Surabaya, Jawa Timur 60119
                    </p>

                    <p>
                        <span class="mb-1 block font-medium text-white">WhatsApp</span>
                        <a href="https://wa.me/6287722619655"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="transition-colors hover:text-rose-soft">
                            0877-2261-9655
                        </a>
                    </p>

                    <p>
                        <span class="mb-1 block font-medium text-white">Jam Operasional</span>
                        Setiap hari, 10.00–21.00 WIB
                    </p>
                </div>
            </div>

        </div>

        {{-- Bagian bawah footer --}}
        <div class="mt-10 flex flex-col gap-4 border-t border-cream/15 pt-5 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs text-cream/60">
                &copy; {{ date('Y') }} Early Cake. All rights reserved.
            </p>

            <div class="flex flex-wrap items-center gap-4">
                <a href="{{ route('home') }}"
                   class="text-xs text-cream/60 transition-colors hover:text-rose-soft">
                    Maniskan momen, rayakan cerita.
                </a>

                {{-- Login Admin --}}
                <a href="{{ route('admin.login') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-cream/20 px-3 py-2 text-xs font-medium text-cream/80 transition hover:border-rose-soft hover:bg-rose-soft hover:text-choco">
                    <svg class="h-4 w-4" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <rect x="3" y="11" width="18" height="10" rx="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    Login Admin
                </a>
            </div>
        </div>

    </div>
</footer>