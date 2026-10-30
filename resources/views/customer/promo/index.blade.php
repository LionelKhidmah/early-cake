@extends('layouts.customer')

@section('title', 'Promo Spesial — Early Cake')
@section('description', 'Temukan berbagai promo dan penawaran spesial dari Early Cake.')

@section('content')
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

            <h2 id="promo-heading" class="mb-6 text-lg font-semibold text-[#49352E]">
                Promo yang Tersedia
            </h2>

            @if ($promos->isEmpty())
                <div class="rounded-3xl bg-white px-6 py-16 text-center shadow-sm">
                    <div class="text-5xl">🎂</div>

                    <h3 class="mt-5 text-xl font-bold text-[#49352E]">
                        Belum Ada Promo Aktif
                    </h3>

                    <p class="mx-auto mt-3 max-w-md text-sm leading-relaxed text-[#806D65]">
                        Nantikan penawaran spesial dari Early Cake.
                        Sementara itu, kamu bisa menjelajahi koleksi kue kami.
                    </p>

                    <a href="{{ route('products.index') }}"
                       class="mt-6 inline-flex rounded-xl bg-[#A85E67] px-6 py-3 text-sm font-semibold text-white hover:bg-[#914B54]">
                        Jelajahi Menu Kue
                    </a>
                </div>
            @else

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

                    @foreach ($promos as $promo)
                        <article class="group flex flex-col overflow-hidden rounded-3xl bg-white shadow-[0_6px_24px_rgba(73,53,46,0.10)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_32px_rgba(73,53,46,0.14)]">

                            {{-- FOTO PROMO --}}
                            <a href="{{ route('promo.show', $promo->id) }}"
                               class="block overflow-hidden">

                                <div class="aspect-[5/4] overflow-hidden bg-[#F9E9ED]">

                                    @if ($promo->image)
                                        <img
                                            src="{{ asset('storage/' . $promo->image) }}"
                                            alt="{{ $promo->title }}"
                                            loading="lazy"
                                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    @else
                                        <div class="flex h-full items-center justify-center bg-[#F9E9ED] text-6xl">
                                            🎂
                                        </div>
                                    @endif

                                </div>
                            </a>

                            {{-- INFORMASI --}}
                            <div class="flex flex-1 flex-col p-5 sm:p-6">

                                @if ($promo->badge)
                                    <span class="inline-flex w-fit rounded-full bg-[#F9E9ED] px-3 py-1 text-xs font-medium text-[#A85E67]">
                                        {{ $promo->badge }}
                                    </span>
                                @endif

                                <h3 class="mt-4 text-xl font-bold leading-snug text-[#49352E]">
                                    {{ $promo->title }}
                                </h3>

                                <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-[#806D65]">
                                    {{ $promo->description }}
                                </p>

                                {{-- KODE DAN DISKON --}}
                                <div class="mt-5 flex flex-wrap items-center justify-between gap-3 rounded-xl bg-[#FCF8F5] px-4 py-3">

                                    <div>
                                        <p class="text-[10px] font-medium uppercase tracking-wider text-[#806D65]">
                                            Kode Promo
                                        </p>

                                        <p class="mt-1 text-base font-bold tracking-wide text-[#A85E67]">
                                            {{ $promo->code }}
                                        </p>
                                    </div>

                                    <span class="rounded-lg bg-[#F9E9ED] px-3 py-2 text-xs font-semibold text-[#A85E67]">
                                        @if ($promo->discount_type === 'percentage')
                                            Diskon {{ number_format($promo->discount_value, 0) }}%
                                        @else
                                            Hemat Rp{{ number_format($promo->discount_value, 0, ',', '.') }}
                                        @endif
                                    </span>
                                </div>

                                {{-- PERIODE --}}
                                <div class="mt-4 flex items-center gap-2 text-xs text-[#806D65]">
                                    <svg class="h-4 w-4 shrink-0"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="1.8"
                                         aria-hidden="true">
                                        <rect x="3" y="5" width="18" height="16" rx="2"/>
                                        <path stroke-linecap="round" d="M7 3v4M17 3v4M3 10h18"/>
                                    </svg>

                                    <span>
                                        {{ $promo->starts_at->format('d M Y') }}
                                        –
                                        {{ $promo->ends_at->format('d M Y') }}
                                    </span>
                                </div>

                                {{-- DETAIL --}}
                                <a href="{{ route('promo.show', $promo->id) }}"
                                   class="mt-auto inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#A85E67] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#914B54] pt-3">
                                    Lihat Detail Promo
                                    <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </article>
                    @endforeach

                </div>

                @if ($promos->hasPages())
                    <div class="mt-8">
                        {{ $promos->links() }}
                    </div>
                @endif
            @endif

        </section>

        {{-- INFORMASI TAMBAHAN --}}
        <section class="mt-14 rounded-3xl bg-[#F9E9ED] px-6 py-8 text-center sm:px-10 sm:py-10">

            <h2 class="text-xl font-bold text-[#49352E] sm:text-2xl">
                Ada yang manis menantimu!
            </h2>

            <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-[#806D65]">
                Jelajahi koleksi kue Early Cake dan temukan pilihan favorit
                untuk merayakan momen spesialmu.
            </p>

            <a href="{{ route('products.index') }}"
               class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-white px-6 py-3 text-sm font-semibold text-[#A85E67] shadow-sm transition hover:bg-[#FCF8F5]">
                Jelajahi Menu Kue
                <span aria-hidden="true">→</span>
            </a>
        </section>

    </div>
</div>
@endsection
