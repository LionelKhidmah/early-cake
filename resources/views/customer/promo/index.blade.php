
@extends('layouts.customer')

@section('title', 'Promo Spesial — Early Cake')

@section('description', 'Temukan berbagai promo dan penawaran spesial dari Early Cake.')

@section('content')

@php
    // Foto sementara, sama dengan halaman detail promo
    $promoImage = 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=1200&q=85';
@endphp

<div class="min-h-screen bg-[#FCF8F5]">

    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 sm:py-14 lg:px-8">

        {{-- HEADER --}}
        <header class="mb-10">

            <span class="inline-flex items-center rounded-full bg-[#F9E9ED] px-4 py-2 text-xs font-semibold text-[#A85E67]">
                Penawaran Spesial
            </span>

            <h1 class="mt-5 text-3xl font-bold tracking-tight text-[#49352E] sm:text-4xl">
                Promo Manis Early Cake
            </h1>

            <p class="mt-3 max-w-2xl text-sm leading-relaxed text-[#806D65] sm:text-base">
                Temukan berbagai penawaran spesial untuk membuat setiap
                momenmu semakin manis bersama Early Cake.
            </p>

        </header>

        {{-- DAFTAR PROMO --}}
        <section aria-labelledby="promo-heading">

            <h2 id="promo-heading"
                class="mb-6 text-lg font-semibold text-[#49352E]">
                Promo yang Tersedia
            </h2>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

                {{-- CARD PROMO --}}
                <article class="group overflow-hidden rounded-3xl bg-white shadow-[0_6px_24px_rgba(73,53,46,0.10)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_32px_rgba(73,53,46,0.14)]">

                    {{-- GAMBAR PROMO --}}
                    <a href="{{ route('promo.show', 1) }}"
                       class="block overflow-hidden"
                       aria-label="Lihat detail promo Momen pertama, lebih manis">

                        <div class="aspect-[5/4] overflow-hidden bg-[#F9E9ED]">

                            <img
                                src="{{ $promoImage }}"
                                alt="Kue spesial Early Cake"
                                loading="lazy"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=1200&q=85';"
                            >

                        </div>

                    </a>

                    {{-- INFORMASI PROMO --}}
                    <div class="p-5 sm:p-6">

                        <span class="inline-flex items-center rounded-full bg-[#F9E9ED] px-3 py-1 text-xs font-medium text-[#A85E67]">
                            Khusus pelanggan baru
                        </span>

                        <h3 class="mt-4 text-xl font-bold leading-snug text-[#49352E]">
                            Momen pertama, lebih manis
                        </h3>

                        <p class="mt-3 text-sm leading-relaxed text-[#806D65]">
                            Kenalan dengan rasa favorit Early Cake dan nikmati
                            diskon 10% untuk pesanan pertamamu.
                        </p>

                        {{-- KODE PROMO --}}
                        <div class="mt-5 flex flex-wrap items-center justify-between gap-3 rounded-xl bg-[#FCF8F5] px-4 py-3">

                            <div>
                                <p class="text-[10px] font-medium uppercase tracking-wider text-[#806D65]">
                                    Kode Promo
                                </p>

                                <p class="mt-1 text-base font-bold tracking-wide text-[#A85E67]">
                                    MANIS10
                                </p>
                            </div>

                            <span class="rounded-lg bg-[#F9E9ED] px-3 py-2 text-xs font-semibold text-[#A85E67]">
                                Diskon 10%
                            </span>

                        </div>

                        {{-- PERIODE --}}
                        <div class="mt-4 flex items-center gap-2 text-xs text-[#806D65]">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4 shrink-0"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.8"
                                 aria-hidden="true">

                                <rect x="3" y="5" width="18" height="16" rx="2"/>

                                <path stroke-linecap="round"
                                      d="M7 3v4M17 3v4M3 10h18"/>
                            </svg>

                            <span>
                                1–31 Oktober 2026
                            </span>

                        </div>

                        {{-- TOMBOL DETAIL --}}
                        <a href="{{ route('promo.show', 1) }}"
                           class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#A85E67] px-5 py-3 text-sm font-semibold text-white transition-colors hover:bg-[#914B54] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#A85E67] focus-visible:ring-offset-2">

                            Lihat Detail Promo

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2"
                                 aria-hidden="true">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M5 12h14M13 6l6 6-6 6"/>
                            </svg>

                        </a>

                    </div>

                </article>

            </div>

        </section>

        {{-- INFORMASI TAMBAHAN --}}
        <section class="mt-14 rounded-3xl bg-[#F9E9ED] px-6 py-8 text-center sm:px-10 sm:py-10">

            <h2 class="text-xl font-bold text-[#49352E] sm:text-2xl">
                Ada yang manis menantimu!
            </h2>

            <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-[#806D65]">
                Jelajahi koleksi kue Early Cake dan temukan pilihan
                favorit untuk merayakan momen spesialmu.
            </p>

            <a href="{{ route('products.index') }}"
               class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-semibold text-[#A85E67] shadow-sm transition hover:bg-[#FCF8F5]">

                Jelajahi Menu Kue

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2"
                     aria-hidden="true">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M5 12h14M13 6l6 6-6 6"/>
                </svg>

            </a>

        </section>

    </div>

</div>

@endsection
