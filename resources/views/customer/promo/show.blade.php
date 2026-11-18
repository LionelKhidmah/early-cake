@extends('layouts.customer')

@section('title', $promo->title . ' — Early Cake')

@section('description', $promo->description ?? 'Nikmati promo spesial Early Cake.')

@section('content')

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

                <li class="min-w-0 truncate font-medium text-[#49352E]">
                    {{ $promo->title }}
                </li>

            </ol>

        </nav>

        {{-- HERO --}}
        <section class="grid grid-cols-1 items-start gap-8 lg:grid-cols-2">

            {{-- FOTO PROMO --}}
            <div class="overflow-hidden rounded-3xl bg-[#F0E4DC]">

                @if ($promo->image)

                    <img
                        src="{{ asset('storage/' . $promo->image) }}"
                        alt="{{ $promo->title }}"
                        class="aspect-[5/4] w-full object-cover"
                        loading="eager"
                    >

                @else

                    <div class="flex aspect-[5/4] flex-col items-center justify-center gap-4 bg-gradient-to-br from-[#F9E9ED] to-[#FFF7E9]">

                        <span class="text-7xl" aria-hidden="true">
                            🎂
                        </span>

                        <span class="text-lg font-semibold text-[#A85E67]">
                            Early Cake Special
                        </span>

                    </div>

                @endif

            </div>

            {{-- INFORMASI PROMO --}}
            <div class="flex min-w-0 flex-col">

                {{-- BADGE --}}
                @if ($promo->badge)

                    <span class="inline-flex w-fit rounded-full bg-[#F9E9ED] px-3 py-1 text-xs font-medium text-[#A85E67]">
                        {{ $promo->badge }}
                    </span>

                @elseif ($promo->first_order_only)

                    <span class="inline-flex w-fit rounded-full bg-[#F9E9ED] px-3 py-1 text-xs font-medium text-[#A85E67]">
                        Khusus Pelanggan Baru
                    </span>

                @else

                    <span class="inline-flex w-fit rounded-full bg-[#F9E9ED] px-3 py-1 text-xs font-medium text-[#A85E67]">
                        Promo Spesial
                    </span>

                @endif

                {{-- JUDUL --}}
                <h1 class="mt-4 text-3xl font-bold leading-tight tracking-tight text-[#49352E] sm:text-4xl">
                    {{ $promo->title }}
                </h1>

                {{-- DESKRIPSI --}}
                <p class="mt-4 text-sm leading-relaxed text-[#806D65] sm:text-base">
                    {{ $promo->description }}
                </p>

                {{-- INFORMASI DISKON --}}
                <div class="mt-6 rounded-2xl border border-[#F3E4E7] bg-white p-5">

                    <p class="text-xs font-medium uppercase tracking-wide text-[#806D65]">
                        Penawaran Spesial
                    </p>

                    <div class="mt-3 flex flex-wrap items-center gap-3">

                        <span class="text-3xl font-bold text-[#A85E67] sm:text-4xl">

                            @if ($promo->discount_type === 'percentage')

                                {{ number_format($promo->discount_value, 0, ',', '.') }}%

                            @else

                                Rp{{ number_format($promo->discount_value, 0, ',', '.') }}

                            @endif

                        </span>

                        <span class="text-sm font-medium text-[#806D65]">
                            Potongan Harga
                        </span>

                    </div>

                    {{-- MAKSIMAL DISKON --}}
                    @if ($promo->discount_type === 'percentage' && $promo->max_discount)

                        <p class="mt-3 text-xs text-[#806D65]">
                            Maksimal potongan:
                            <span class="font-semibold text-[#49352E]">
                                Rp{{ number_format($promo->max_discount, 0, ',', '.') }}
                            </span>
                        </p>

                    @endif

                    {{-- MINIMAL PEMBELIAN --}}
                    @if ($promo->min_purchase > 0)

                        <p class="mt-2 text-xs text-[#806D65]">
                            Minimum pembelian:
                            <span class="font-semibold text-[#49352E]">
                                Rp{{ number_format($promo->min_purchase, 0, ',', '.') }}
                            </span>
                        </p>

                    @endif

                    {{-- KHUSUS PESANAN PERTAMA --}}
                    @if ($promo->first_order_only)

                        <p class="mt-3 text-xs font-medium text-[#A85E67]">
                            Berlaku khusus untuk pesanan pertama pelanggan.
                        </p>

                    @endif

                </div>

                {{-- KODE PROMO --}}
                <div class="mt-5 rounded-2xl bg-[#F9E9ED] p-5 sm:p-6">

                    <p class="text-xs font-medium uppercase tracking-wide text-[#A85E67]">
                        Kode Promo
                    </p>

                    <p class="mt-4 break-all text-3xl font-semibold tracking-tight text-[#49352E] sm:text-4xl">
                        {{ $promo->code }}
                    </p>

                    {{-- TOMBOL SALIN --}}
                    <button
                        type="button"
                        id="copy-promo-btn"
                        data-code="{{ $promo->code }}"
                        class="mt-5 inline-flex items-center justify-center gap-2 rounded-lg border border-[#F0E4DC] bg-white px-4 py-3 text-xs font-semibold text-[#49352E] shadow-sm transition hover:bg-[#FCF8F5]">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8"
                             stroke-linecap="round"
                             stroke-linejoin="round"
                             aria-hidden="true">

                            <rect x="8" y="8" width="12" height="12" rx="2"/>
                            <path d="M16 8V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2"/>
                        </svg>

                        <span id="copy-promo-label">
                            Salin Kode Promo
                        </span>

                    </button>

                    {{-- PERIODE --}}
                    <div class="mt-5 flex items-start gap-2 text-xs text-[#49352E]">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4 shrink-0"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8"
                             stroke-linecap="round"
                             aria-hidden="true">

                            <rect x="3" y="5" width="18" height="16" rx="2"/>
                            <path d="M7 3v4M17 3v4M3 10h18"/>
                        </svg>

                        <span>
                            Berlaku:
                            {{ $promo->starts_at->translatedFormat('d F Y, H:i') }}
                            –
                            {{ $promo->ends_at->translatedFormat('d F Y, H:i') }}
                            WIB
                        </span>

                    </div>

                    {{-- COPY STATUS --}}
                    <p
                        id="copy-promo-status"
                        class="sr-only"
                        role="status"
                        aria-live="polite">
                    </p>

                </div>

                {{-- TOMBOL CTA --}}
                <a
                    href="{{ route('products.index') }}"
                    class="mt-5 inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#A85E67] px-5 text-sm font-semibold text-white transition hover:bg-[#914B54]">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         aria-hidden="true">

                        <path d="M5 12h14M13 6l6 6-6 6"/>
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

            @if (!empty($promo->terms) && is_array($promo->terms))

                <ol class="mt-5 list-decimal space-y-4 pl-5 text-sm leading-relaxed text-[#806D65]">

                    @foreach ($promo->terms as $term)

                        <li>
                            {{ $term }}
                        </li>

                    @endforeach

                </ol>

            @else

                <p class="mt-5 text-sm leading-relaxed text-[#806D65]">
                    Belum ada syarat dan ketentuan tambahan untuk promo ini.
                </p>

            @endif

        </section>

        {{-- NAVIGASI BAWAH --}}
        <div class="mt-8 flex flex-wrap items-center justify-between gap-4">

            <a
                href="{{ route('promo.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-[#A85E67] transition hover:text-[#914B54]">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round"
                     aria-hidden="true">

                    <path d="M19 12H5M11 18l-6-6 6-6"/>
                </svg>

                Kembali ke Daftar Promo
            </a>

        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const button = document.getElementById('copy-promo-btn');
    const label = document.getElementById('copy-promo-label');
    const status = document.getElementById('copy-promo-status');

    if (!button || !label || !status) {
        return;
    }

    let resetTimer;

    function feedback(message) {
        label.textContent = message;
        status.textContent = message;

        clearTimeout(resetTimer);

        resetTimer = setTimeout(function () {
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

                input.focus();
                input.select();

                const success = document.execCommand('copy');

                input.remove();

                if (!success) {
                    throw new Error('Gagal menyalin kode.');
                }
            }

            feedback('Kode Berhasil Disalin ✓');

        } catch (error) {

            feedback('Gagal menyalin kode');

        }

    });

});
</script>
@endpush
