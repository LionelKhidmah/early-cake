
@extends('layouts.customer')

@section('title', 'Detail Promo — Early Cake')

@section('description', 'Nikmati promo spesial Early Cake untuk pesanan pertamamu.')

@section('content')
@php
    $promo = [
        'title' => 'Momen pertama, lebih manis',
        'badge' => 'Khusus pelanggan baru',
        'description' => 'Kenalan dengan rasa favorit Early Cake dan nikmati diskon 10% untuk pesanan pertamamu.',
        'code' => 'MANIS10',
        'period' => '1–31 Okt 2026',

        // Foto sementara dari Unsplash
        'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=1200&q=85',

        'terms' => [
            'Diskon 10%, maksimal potongan Rp50.000.',
            'Minimum subtotal belanja Rp250.000, tidak termasuk ongkir.',
            'Berlaku untuk pesanan pertama pelanggan, satu kali penggunaan.',
            'Berlaku untuk produk menu reguler, tidak termasuk kue custom.',
            'Tidak dapat digabungkan dengan promo lain. Masukkan kode di keranjang sebelum checkout.',
            'Promo berlaku sampai 31 Oktober 2026, pukul 23.59 WIB.',
        ],
    ];
@endphp

<div class="min-h-screen bg-[#FCF8F5]">
    <div class="mx-auto max-w-6xl px-4 pb-16 pt-8 sm:px-6 lg:px-8">

        {{-- BREADCRUMB --}}
        <nav aria-label="Breadcrumb" class="mb-8">
            <ol class="flex flex-wrap items-center gap-2 text-xs text-[#806D65]">
                <li>
                    <a href="{{ route('promo.index') }}"
                       class="transition hover:text-[#A85E67]">
                        Promo
                    </a>
                </li>
                <li>/</li>
                <li class="text-[#49352E]">
                    {{ $promo['title'] }}
                </li>
            </ol>
        </nav>

        {{-- HERO --}}
        <section class="grid grid-cols-1 items-start gap-8 lg:grid-cols-2 lg:gap-8">

            {{-- FOTO KUE --}}
            <div class="overflow-hidden rounded-3xl bg-[#F0E4DC]">
                <img
                    src="{{ $promo['image'] }}"
                    alt="Foto kue spesial untuk promo Early Cake"
                    class="aspect-[5/4] w-full object-cover"
                    loading="eager"
                    onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1488477181946-6428a0291777?auto=format&fit=crop&w=1200&q=85';"
                >
            </div>

            {{-- INFORMASI PROMO --}}
            <div class="flex min-w-0 flex-col">

                <span class="inline-flex w-fit rounded-full bg-[#F9E9ED] px-3 py-1 text-xs font-medium text-[#A85E67]">
                    {{ $promo['badge'] }}
                </span>

                <h1 class="mt-4 text-3xl font-bold leading-tight tracking-tight text-[#49352E] sm:text-4xl">
                    {{ $promo['title'] }}
                </h1>

                <p class="mt-3 text-sm leading-relaxed text-[#806D65]">
                    {{ $promo['description'] }}
                </p>

                {{-- KODE PROMO --}}
                <div class="mt-6 rounded-2xl bg-[#F9E9ED] p-5 sm:p-6">

                    <p class="text-xs font-medium uppercase tracking-wide text-[#A85E67]">
                        Kode Promo
                    </p>

                    <p class="mt-4 text-3xl font-medium tracking-tight text-[#49352E] sm:text-4xl">
                        {{ $promo['code'] }}
                    </p>

                    <button
                        type="button"
                        id="copy-promo-btn"
                        data-code="{{ $promo['code'] }}"
                        class="mt-5 rounded-lg border border-[#F0E4DC] bg-white px-4 py-3 text-xs font-semibold text-[#49352E] shadow-sm transition hover:bg-[#FCF8F5]"
                    >
                        <span id="copy-promo-label">
                            Salin Kode Promo
                        </span>
                    </button>

                    <p class="mt-4 text-xs text-[#49352E]">
                        Periode · {{ $promo['period'] }}
                    </p>

                    <p id="copy-promo-status"
                       class="sr-only"
                       role="status"
                       aria-live="polite"></p>

                </div>

                {{-- TOMBOL CTA --}}
                <a href="{{ route('products.index') }}"
                   class="mt-5 inline-flex h-12 w-full items-center justify-center gap-2 rounded-lg bg-[#A85E67] px-5 text-sm font-semibold text-white transition hover:bg-[#914B54]">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 12h14M13 6l6 6-6 6"/>
                    </svg>

                    Pilih Kue Sekarang
                </a>

            </div>
        </section>

        {{-- SYARAT DAN KETENTUAN --}}
        <section class="mt-10 rounded-3xl bg-white p-5 shadow-[0_6px_24px_rgba(73,53,46,0.08)] sm:p-7">

            <h2 class="text-lg font-semibold text-[#49352E]">
                Syarat &amp; Ketentuan
            </h2>

            <hr class="mt-4 border-[#F0E4DC]">

            <ol class="mt-5 list-decimal space-y-4 pl-5 text-sm leading-relaxed text-[#806D65]">
                @foreach ($promo['terms'] as $term)
                    <li>{{ $term }}</li>
                @endforeach
            </ol>

        </section>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('copy-promo-btn');
    const label = document.getElementById('copy-promo-label');
    const status = document.getElementById('copy-promo-status');

    if (!button || !label || !status) return;

    let resetTimer;

    function feedback(message) {
        label.textContent = message;
        status.textContent = message;

        clearTimeout(resetTimer);

        resetTimer = setTimeout(() => {
            label.textContent = 'Salin Kode Promo';
            status.textContent = '';
        }, 2000);
    }

    button.addEventListener('click', async function () {
        const code = button.dataset.code;

        try {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(code);
            } else {
                const input = document.createElement('textarea');
                input.value = code;
                input.style.position = 'fixed';
                input.style.opacity = '0';
                document.body.appendChild(input);
                input.select();

                const success = document.execCommand('copy');
                input.remove();

                if (!success) throw new Error('Copy failed');
            }

            feedback('Kode Berhasil Disalin ✓');
        } catch (error) {
            feedback('Gagal menyalin kode');
        }
    });
});
</script>
@endpush
