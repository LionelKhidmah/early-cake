@extends('layouts.customer')

@section('title', 'Early Cake — Sweet Moments, Made with Love')

@section('content')

@php
    $categories = [
        ['🎂', 'Whole Cake',   'Untuk momen spesial', '#F7DDD6'],
        ['🍰', 'Cake & Slice', 'Manis setiap hari',   '#F4E5AF'],
        ['🧁', 'Cupcake',      'Kecil, penuh cinta',  '#EADFD2'],
        ['🍮', 'Sweet Treats', 'Teman bersantai',     '#F5DDE5'],
    ];
@endphp

<style>
    .cat-marquee {
        -webkit-mask-image: linear-gradient(to right, transparent, #000 6%, #000 94%, transparent);
                mask-image: linear-gradient(to right, transparent, #000 6%, #000 94%, transparent);
    }
    .cat-track {
        display: flex;
        width: max-content;
        animation: cat-scroll 28s linear infinite;
    }
    .cat-marquee:hover .cat-track,
    .cat-marquee:focus-within .cat-track {
        animation-play-state: paused;
    }
    @keyframes cat-scroll {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }
    @media (prefers-reduced-motion: reduce) {
        .cat-track { animation: none; }
        .cat-marquee { overflow-x: auto; }
    }
</style>

<div class="overflow-hidden bg-[#FFFCF6] text-[#49352E]">

    {{-- HERO --}}
    <section class="relative">
        <div class="mx-auto grid max-w-7xl items-center gap-10 px-5 py-12 sm:px-8 sm:py-16 lg:grid-cols-2 lg:gap-16 lg:px-12 lg:py-20">

            <div class="relative z-10">
                <span class="inline-flex items-center gap-2 rounded-full border border-[#EBDCC8] bg-white px-4 py-2 text-xs font-semibold tracking-[0.16em] text-[#986E58]">
                    <span class="h-2 w-2 rounded-full bg-[#DFA6A0]"></span>
                    BAKED WITH LOVE SINCE 2017
                </span>

                <h1 class="mt-7 max-w-xl font-serif text-5xl leading-[1.12] tracking-tight sm:text-6xl lg:text-7xl">
                    A little sweetness,
                    <span class="italic text-[#C77F79]">a lot of love.</span>
                </h1>

                <p class="mt-6 max-w-lg text-sm leading-7 text-[#806C60] sm:text-base sm:leading-8">
                    Dari kue ulang tahun yang istimewa sampai camilan favorit untuk menemani harimu.
                    Setiap kreasi Early Cake dibuat untuk menghadirkan momen yang lebih manis.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('products.index') }}"
                       class="inline-flex min-h-12 items-center justify-center rounded-full bg-[#49352E] px-7 py-3 text-sm font-semibold text-white transition hover:bg-[#684A3D]">
                        Jelajahi Menu <span class="ml-3" aria-hidden="true">↗</span>
                    </a>
                    <a href="{{ route('custom-cake.index') }}"
                       class="inline-flex min-h-12 items-center justify-center rounded-full border border-[#DCC9B8] bg-white px-7 py-3 text-sm font-semibold text-[#49352E] transition hover:bg-[#FFF0E9]">
                        Buat Kue Custom
                    </a>
                </div>

                <div class="mt-10 flex flex-wrap items-center gap-x-7 gap-y-4 border-t border-[#EDE1D4] pt-6">
                    <div>
                        <p class="font-serif text-2xl">Since 2017</p>
                        <p class="mt-1 text-xs text-[#8E796B]">Dibuat dengan cinta</p>
                    </div>
                    <div class="h-10 w-px bg-[#E6D6C7]"></div>
                    <div>
                        <p class="font-serif text-2xl">Made for you</p>
                        <p class="mt-1 text-xs text-[#8E796B]">Kue sesuai keinginanmu</p>
                    </div>
                </div>
            </div>

            <div class="relative mx-auto w-full max-w-xl">
                <div class="absolute -right-2 top-3 h-28 w-28 rounded-full bg-[#F6D9D3] sm:-right-5 sm:h-36 sm:w-36"></div>
                <div class="absolute -bottom-3 -left-3 h-28 w-28 rounded-full bg-[#F5E4A9] sm:-bottom-5 sm:-left-5 sm:h-36 sm:w-36"></div>

                <div class="relative overflow-hidden rounded-[46%_46%_18px_18px] border-[7px] border-white bg-[#F8E8DC] shadow-[0_20px_60px_rgba(95,66,47,0.10)]">
                    <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=1100&q=85"
                         alt="Kue ulang tahun dengan dekorasi krim"
                         class="aspect-[4/5] w-full object-cover"
                         fetchpriority="high">
                </div>

                <div class="absolute bottom-7 left-3 rounded-2xl border border-white/80 bg-white/95 px-4 py-3 shadow-lg sm:-left-6 sm:px-5">
                    <p class="text-xs text-[#9A7B69]">A special treat</p>
                    <p class="mt-1 font-serif text-lg">Just for your moments ♡</p>
                </div>

                <div class="absolute right-2 top-12 flex h-16 w-16 rotate-12 items-center justify-center rounded-full bg-[#F4DFA0] text-center text-xs font-semibold text-[#70543A] shadow-md sm:-right-3 sm:h-20 sm:w-20">
                    Sweet<br>little joy
                </div>
            </div>
        </div>
    </section>

    {{-- CATEGORY STRIP (carousel otomatis) --}}
    <section class="border-y border-[#EEE1D4] bg-[#FFF7E8]" aria-label="Kategori produk">
        <div class="cat-marquee overflow-hidden py-6">
            <div class="cat-track">
                {{-- Dua grup identik agar loop mulus --}}
                @foreach ([false, true] as $isClone)
                    <div class="flex shrink-0 gap-3 pr-3" @if ($isClone) aria-hidden="true" @endif>
                        {{-- Diulang 2x per grup supaya cukup panjang di layar lebar --}}
                        @for ($i = 0; $i < 2; $i++)
                            @foreach ($categories as [$icon, $label, $caption, $bg])
                                <a href="{{ route('products.index') }}"
                                   @if ($isClone) tabindex="-1" @endif
                                   class="group flex w-60 shrink-0 items-center gap-3 rounded-2xl p-3 transition hover:bg-white sm:w-64">
                                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full text-xl"
                                          style="background-color: {{ $bg }}">{{ $icon }}</span>
                                    <span>
                                        <span class="block text-sm font-semibold">{{ $label }}</span>
                                        <span class="mt-1 block text-xs text-[#90786A]">{{ $caption }}</span>
                                    </span>
                                </a>
                            @endforeach
                        @endfor
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- BEST SELLERS --}}
    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-8 sm:py-20 lg:px-12">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-xs font-semibold tracking-[0.22em] text-[#BC8278]">A LITTLE SOMETHING SWEET</p>
                <h2 class="mt-3 font-serif text-4xl sm:text-5xl">Made to make you smile.</h2>
                <p class="mt-4 max-w-xl text-sm leading-7 text-[#897468]">
                    Temukan kue dan camilan favorit untuk berbagi kebahagiaan dengan orang-orang tersayang.
                </p>
            </div>

            <a href="{{ route('products.index') }}"
               class="inline-flex items-center gap-2 self-start text-sm font-semibold text-[#A86F66] transition hover:text-[#49352E] sm:self-auto">
                Lihat semua produk <span aria-hidden="true">↗</span>
            </a>
        </div>

        @if (!empty($products) && count($products) > 0)
            <div class="mt-10 grid grid-cols-2 gap-4 sm:gap-6 lg:grid-cols-4">
                @foreach ($products as $product)
                    <article class="group min-w-0">
                        <a href="{{ route('products.show', $product) }}" class="relative block overflow-hidden rounded-2xl bg-[#F3E7DC]">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . ltrim(str_replace('public/', '', $product->image), '/')) }}"
                                     alt="{{ $product->name }}"
                                     loading="lazy"
                                     class="aspect-[4/5] w-full object-cover transition duration-500 group-hover:scale-105">
                            @else
                                <div class="flex aspect-[4/5] items-center justify-center bg-[#F8E9DF] text-4xl">🎂</div>
                            @endif

                            <span class="absolute left-3 top-3 rounded-full bg-white/95 px-3 py-1.5 text-[10px] font-semibold tracking-wide text-[#806353]">
                                EARLY CAKE
                            </span>
                        </a>

                        <div class="px-1 pt-4">
                            <h3 class="line-clamp-2 text-sm font-semibold sm:text-base">{{ $product->name }}</h3>
                            <p class="mt-2 text-sm font-medium text-[#B2766C]">
                                Rp{{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            <a href="{{ route('products.show', $product) }}"
                               class="mt-3 inline-flex text-xs font-semibold text-[#6D5548] underline decoration-[#D8B9A9] underline-offset-4 hover:text-[#BC8278]">
                                Lihat produk
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="mt-10 rounded-3xl border border-dashed border-[#DECABD] bg-white px-6 py-12 text-center">
                <span class="text-4xl">🍰</span>
                <h3 class="mt-4 font-serif text-2xl">Something sweet is coming.</h3>
                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-[#897468]">
                    Produk unggulan akan tampil di sini setelah produk tersedia di database dan ditandai tersedia.
                </p>
                <a href="{{ route('products.index') }}"
                   class="mt-5 inline-flex rounded-full bg-[#49352E] px-6 py-3 text-sm font-semibold text-white hover:bg-[#684A3D]">
                    Lihat Menu
                </a>
            </div>
        @endif
    </section>

    {{-- CUSTOM CAKE --}}
    <section class="bg-[#F8E8E1]">
        <div class="mx-auto grid max-w-7xl items-center gap-8 px-5 py-14 sm:px-8 sm:py-16 lg:grid-cols-2 lg:gap-16 lg:px-12">
            <div class="order-2 overflow-hidden rounded-[28px] border-[6px] border-white bg-white shadow-sm lg:order-1">
                <img src="https://images.unsplash.com/photo-1558301211-0d8c8ddee6ec?auto=format&fit=crop&w=1000&q=85"
                     alt="Kue custom dengan dekorasi cantik"
                     loading="lazy"
                     class="aspect-[5/4] w-full object-cover">
            </div>

            <div class="order-1 lg:order-2">
                <span class="inline-block rounded-full bg-white px-4 py-2 text-xs font-semibold tracking-[0.16em] text-[#A86F66]">
                    YOUR IDEA, OUR CAKE
                </span>
                <h2 class="mt-5 font-serif text-4xl leading-tight sm:text-5xl">A cake as unique as your story.</h2>
                <p class="mt-5 text-sm leading-7 text-[#806C60]">
                    Punya desain kue impian? Pilih ukuran, tentukan tema, dan ceritakan ide kamu.
                    Yuk, buat kue custom yang lebih personal untuk ulang tahun, perayaan,
                    atau kejutan kecil untuk orang tersayang.
                </p>

                <ul class="mt-6 space-y-3 text-sm text-[#70594D]">
                    <li class="flex items-start gap-3"><span class="mt-0.5 text-[#C77F79]">♡</span>Desain yang disesuaikan dengan keinginanmu</li>
                    <li class="flex items-start gap-3"><span class="mt-0.5 text-[#C77F79]">♡</span>Pilihan ukuran dan dekorasi</li>
                    <li class="flex items-start gap-3"><span class="mt-0.5 text-[#C77F79]">♡</span>Harga menyesuaikan desain dan tingkat kesulitan</li>
                </ul>

                <a href="{{ route('custom-cake.index') }}"
                   class="mt-8 inline-flex items-center rounded-full bg-[#49352E] px-7 py-3.5 text-sm font-semibold text-white transition hover:bg-[#684A3D]">
                    Mulai Custom Cake <span class="ml-3" aria-hidden="true">↗</span>
                </a>
            </div>
        </div>
    </section>

    {{-- BRAND STORY --}}
    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-8 sm:py-20 lg:px-12">
        <div class="relative overflow-hidden rounded-[28px] bg-[#F5E9C9] px-6 py-10 sm:px-12 sm:py-14 lg:px-16">
            <div class="absolute -right-12 -top-16 h-48 w-48 rounded-full border-[24px] border-white/30"></div>
            <div class="absolute -bottom-16 -left-10 h-40 w-40 rounded-full bg-[#E8C6B9]/50"></div>

            <div class="relative grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
                <div class="max-w-2xl">
                    <p class="text-xs font-semibold tracking-[0.2em] text-[#9A7257]">OUR LITTLE STORY</p>
                    <h2 class="mt-4 font-serif text-4xl leading-tight sm:text-5xl">Every celebration deserves a little cake.</h2>
                    <p class="mt-5 text-sm leading-7 text-[#806C60]">
                        Sejak 2017, Early Cake hadir untuk menemani berbagai momen melalui kue dan camilan
                        yang dibuat dengan penuh perhatian. Dari perayaan sederhana hingga hari yang paling
                        berkesan, kami ingin menjadi bagian kecil dari kebahagiaanmu.
                    </p>
                </div>

                <a href="{{ route('about') }}"
                   class="inline-flex w-fit items-center rounded-full border border-[#BFA68B] bg-white/80 px-6 py-3.5 text-sm font-semibold transition hover:bg-white">
                    Cerita Early Cake <span class="ml-3" aria-hidden="true">↗</span>
                </a>
            </div>
        </div>
    </section>

    {{-- FINAL CTA --}}
    <section class="px-5 pb-16 sm:px-8 sm:pb-20 lg:px-12">
        <div class="mx-auto max-w-5xl rounded-[28px] border border-[#F0DFD2] bg-white px-6 py-10 text-center sm:px-12 sm:py-14">
            <p class="text-3xl" aria-hidden="true">♡</p>
            <h2 class="mt-3 font-serif text-3xl sm:text-4xl">Let's make something sweet.</h2>
            <p class="mx-auto mt-4 max-w-lg text-sm leading-7 text-[#897468]">
                Sudah punya kue incaran atau masih mencari inspirasi? Kami siap membantu kamu menemukan pilihan yang tepat.
            </p>
            <div class="mt-7 flex flex-wrap justify-center gap-3">
                <a href="{{ route('products.index') }}"
                   class="rounded-full bg-[#49352E] px-7 py-3.5 text-sm font-semibold text-white transition hover:bg-[#684A3D]">
                    Lihat Menu
                </a>
                <a href="{{ route('contact') }}"
                   class="rounded-full border border-[#DCC9B8] px-7 py-3.5 text-sm font-semibold transition hover:bg-[#FFF7F0]">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </section>

</div>
@endsection