@extends('layouts.customer')

@section('title', 'Tentang Kami — Early Cake')

@section('description', 'Kenali cerita Early Cake, toko kue di Surabaya yang menghadirkan kue dan camilan untuk berbagai momen spesial.')

@php
    $stats = [
        ['value' => '2017', 'label' => 'Tahun memulai cerita'],
        ['value' => 'Fresh', 'label' => 'Dibuat dengan perhatian'],
        ['value' => 'Custom', 'label' => 'Kue sesuai keinginanmu'],
        ['value' => 'Sepenuh hati', 'label' => 'Untuk setiap momen'],
    ];

    $values = [
        [
            'icon' => 'leaf',
            'title' => 'Bahan pilihan',
            'text' => 'Mengutamakan bahan yang sesuai untuk menghasilkan kue dan camilan yang nikmat.',
        ],
        [
            'icon' => 'heart',
            'title' => 'Dibuat sepenuh hati',
            'text' => 'Setiap pesanan dipersiapkan dengan perhatian agar dapat menemani momen spesialmu.',
        ],
        [
            'icon' => 'sparkles',
            'title' => 'Sesuai keinginanmu',
            'text' => 'Pilih kue favorit atau pesan custom cake untuk merayakan momen dengan cara yang lebih personal.',
        ],
    ];

    $mapsUrl = 'https://www.google.com/maps/search/?api=1&query=Jl.%20Manyar%20Rejo%20III%20No.%204%2C%20Surabaya';
@endphp

@section('content')
<main class="mx-auto w-full max-w-6xl px-5 py-10 sm:px-6 lg:px-8">

    {{-- A. Hero / Cerita Early Cake --}}
    <section class="grid items-center gap-8 lg:grid-cols-2 lg:gap-12">

        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-kue">
                Cerita dari dapur kami
            </p>

            <h1 class="mt-4 font-display text-3xl font-semibold leading-tight tracking-tight text-choco sm:text-4xl lg:text-5xl">
                Berawal dari kecintaan pada kue, tumbuh bersama cerita manis.
            </h1>

            <p class="mt-6 text-sm leading-7 text-choco-light sm:text-base">
                Sejak 2017, Early Cakes hadir dari kecintaan membuat kue dan camilan.
                Kami percaya bahwa kue bukan sekadar hidangan, tetapi juga bagian
                dari momen spesial bersama orang-orang tersayang.
            </p>

            <p class="mt-4 text-sm leading-7 text-choco-light sm:text-base">
                Mulai dari kue tart, bento cake yang ekonomis, hingga camilan
                seperti bolu dan cheesecake, Early Cake ingin menemani berbagai
                perayaan dengan pilihan yang sesuai kebutuhanmu.
            </p>

            <figure class="mt-6 border-l-2 border-rose-kue pl-4">
                <blockquote class="font-display text-lg italic leading-relaxed text-choco">
                    "Setiap momen punya cerita, dan selalu ada ruang untuk sesuatu yang manis."
                </blockquote>
                <figcaption class="mt-3 text-xs text-choco-light">
                    Early Cake — Maniskan momen, rayakan cerita.
                </figcaption>
            </figure>

            <a href="{{ route('products.index') }}"
               class="btn btn-primary mt-7">
                Jelajahi Menu
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round"
                     aria-hidden="true">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="relative">
            <div class="absolute -right-2 -top-2 h-24 w-24 rounded-full bg-butter-soft sm:-right-4 sm:-top-4 sm:h-32 sm:w-32"></div>

            <img
                src="{{ asset('images/foto/tempat.jpeg') }}"
                alt="Kue dan camilan Early Cake"
                class="relative aspect-[6/5] w-full rounded-3xl object-cover shadow-sm"
                width="1200"
                height="1000"
                loading="eager"
            >

            <div class="absolute -bottom-4 left-4 rounded-2xl border border-choco/5 bg-white px-4 py-3 shadow-sm sm:bottom-5 sm:left-5">
                <p class="text-xs text-choco-light">Dari tahun</p>
                <p class="mt-0.5 font-display text-xl font-semibold text-rose-kue">2017</p>
            </div>
        </div>

    </section>

    {{-- B. Ringkasan Early Cake --}}
    
<section aria-label="Tentang Early Cake" class="mt-16 sm:mt-20">
    <dl class="grid grid-cols-2 gap-x-5 gap-y-7 rounded-3xl border border-[#E8D38B] bg-[#F7E7A9] p-6 shadow-sm sm:p-8 md:grid-cols-4">
        @foreach ($stats as $stat)
            <div class="flex flex-col gap-2">
                <dd class="font-display text-2xl font-semibold tracking-tight text-[#4A2C27] sm:text-3xl">
                    {{ $stat['value'] }}
                </dd>
                <dt class="text-xs leading-relaxed text-[#65453D] sm:text-sm">
                    {{ $stat['label'] }}
                </dt>
            </div>
        @endforeach
    </dl>
</section>


    {{-- C. Nilai dan Keunggulan --}}
    <section aria-labelledby="values-heading" class="mt-16 sm:mt-20">

        <div class="max-w-2xl">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-kue">
                Kenapa Early Cakes?
            </p>

            <h2 id="values-heading" class="mt-3 font-display text-3xl font-semibold tracking-tight text-choco sm:text-4xl">
                Hal manis yang selalu kami jaga.
            </h2>

            <p class="mt-3 text-sm leading-7 text-choco-light sm:text-base">
                Bukan hanya tentang tampilan yang menarik, tetapi juga pilihan,
                rasa, dan perhatian di setiap pesanan.
            </p>
        </div>

        <div class="mt-8 grid gap-5 md:grid-cols-3">

            @foreach ($values as $item)
                <article class="rounded-2xl border border-choco/10 bg-white p-6 transition duration-300 hover:-translate-y-1 hover:shadow-md sm:p-7">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-pale text-rose-kue">

                        @if ($item['icon'] === 'leaf')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.7"
                                 stroke-linecap="round" stroke-linejoin="round"
                                 aria-hidden="true">
                                <path d="M20 4c-7 0-13 2-13 9a5 5 0 0 0 5 5c7 0 8-8 8-14Z"/>
                                <path d="M4 21c2-6 6-9 12-12"/>
                            </svg>
                        @elseif ($item['icon'] === 'heart')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.7"
                                 stroke-linecap="round" stroke-linejoin="round"
                                 aria-hidden="true">
                                <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z"/>
                            </svg>
                        @else
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.7"
                                 stroke-linecap="round" stroke-linejoin="round"
                                 aria-hidden="true">
                                <path d="m12 3 1.9 5.8L20 11l-6.1 2.2L12 19l-1.9-5.8L4 11l6.1-2.2L12 3Z"/>
                                <path d="m19 14 1.2 2.8L23 18l-2.8 1.2L19 22l-1.2-2.8L15 18l2.8-1.2L19 14Z"/>
                            </svg>
                        @endif

                    </div>

                    <h3 class="mt-5 text-base font-semibold text-choco">
                        {{ $item['title'] }}
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-choco-light">
                        {{ $item['text'] }}
                    </p>

                </article>
            @endforeach

        </div>
    </section>

    {{-- D. CTA / Lokasi Toko --}}
    
    <section aria-labelledby="cta-heading" class="mt-16 overflow-hidden rounded-3xl border border-[#E8D38B] bg-[#F2D987] p-6 sm:mt-20 sm:p-10 lg:p-12">


        <div class="grid items-center gap-7 md:grid-cols-[1fr_auto]">

            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-kue">
                    Yuk, kenalan lebih dekat
                </p>

                <h2 id="cta-heading" class="mt-3 font-display text-3xl font-semibold leading-tight tracking-tight text-choco sm:text-4xl">
                    Ada momen spesial? Mari buat lebih manis.
                </h2>

                <p class="mt-3 max-w-xl text-sm leading-7 text-choco-light sm:text-base">
                    Temukan pilihan kue dan camilan Early Cake, atau pesan custom
                    cake untuk melengkapi momen istimewamu.
                </p>

                <p class="mt-4 flex items-start gap-2 text-sm leading-6 text-choco-light">
                    <svg class="mt-1 h-4 w-4 shrink-0 text-rose-kue" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/>
                        <circle cx="12" cy="10" r="2.5"/>
                    </svg>
                    Jl. Manyar Rejo III No. 4, Surabaya
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row md:flex-col">
                <a href="{{ $mapsUrl }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="btn btn-primary whitespace-nowrap">
                    Lihat Lokasi Toko
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <path d="M14 3h7v7"/>
                        <path d="M10 14 21 3"/>
                        <path d="M19 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h6"/>
                    </svg>
                </a>

                <a href="{{ route('products.index') }}"
                   class="btn btn-outline whitespace-nowrap">
                    Lihat Menu Kue
                </a>
            </div>

        </div>
    </section>

</main>
@endsection
