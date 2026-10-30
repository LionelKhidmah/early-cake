@extends('layouts.customer')

@section('title', $promo->title . ' — Early Cake')
@section('description', $promo->description)

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
                <li class="text-[#49352E]">
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
                        loading="eager">
                @else
                    <div class="flex aspect-[5/4] items-center justify-center bg-[#F9E9ED] text-7xl">
                        🎂
                    </div>
                @endif
            </div>

            {{-- INFORMASI PROMO --}}
            <div class="flex min-w-0 flex-col">

                @if ($promo->badge)
                    <span class="inline-flex w-fit rounded-full bg-[#F9E9ED] px-3 py-1 text-xs font-medium text-[#A85E67]">
                        {{ $promo->badge }}
                    </span>
                @endif

                <h1 class="mt-4 text-3xl font-bold leading-tight tracking-tight text-[#49352E] sm:text-4xl">
                    {{ $promo->title }}
                </h1>

                <p class="mt-3 text-sm leading-relaxed text-[#806D65]">
                    {{ $promo->description }}
                </p>

                {{-- KODE PROMO --}}
                <div class="mt-6 rounded-2xl bg-[#F9E9ED] p-5 sm:p-6">

                    <p class="text-xs font-medium uppercase tracking-wide text-[#A85E67]">
                        Kode Promo
                    </p>

                    <p class="mt-4 text-3xl font-medium tracking-tight text-[#49352E] sm:text-4xl">
                        {{ $promo->code }}
                    </p>

                    <button
                        type="button"
                        id="copy-promo-btn"
                        data-code="{{ $promo->code }}"
                        class="mt-5 rounded-lg border border-[#F0E4DC] bg-white px-4 py-3 text-xs font-semibold text-[#49352E] shadow-sm transition hover:bg-[#FCF8F5]">
                        <span id="copy-promo-label">Salin Kode Promo</span>
                    </button>

                    <p class="mt-4 text-xs text-[#49352E]">
                        Periode:
                        {{ $promo->starts_at->format('d M Y') }}
                        –
                        {{ $promo->ends_at->format('d M Y') }}
                    </p>

                    <p id="copy-promo-status"
                       class="sr-only"
                       role="status"
                       aria-live="polite"></p>
                </div>

                {{-- TOMBOL CTA --}}
                <a href="{{ route('products.index') }}"
                   class="mt-5 inline-flex h-12 w-full items-center justify-center gap-2 rounded-lg bg-[#A85E67] px-5 text-sm font-semibold text-white transition hover:bg-[#914B54]">
                    Pilih Kue Sekarang
                    <span aria-hidden="true">→</span>
                </a>
            </div>
        </section>

        {{-- INFORMASI DISKON --}}
        <section class="mt-10 rounded-3xl bg-white p-5 shadow-sm sm:p-7">
            <h2 class="text-lg font-semibold text-[#49352E]">
                Informasi Diskon
            </h2>

            <div class="mt-4 space-y-2 text-sm text-[#806D65]">
                <p>
                    <strong>Potongan:</strong>
                    @if ($promo->discount_type === 'percentage')
                        {{ number_format($promo->discount_value, 0) }}%
                    @else
                        Rp{{ number_format($promo->discount_value, 0, ',', '.') }}
                    @endif
                </p>

                <p>
                    <strong>Minimal pembelian:</strong>
                    Rp{{ number_format($promo->min_purchase, 0, ',', '.') }}
                </p>

                @if ($promo->max_discount !== null)
                    <p>
                        <strong>Maksimal potongan:</strong>
                        Rp{{ number_format($promo->max_discount, 0, ',', '.') }}
                    </p>
                @endif

                @if ($promo->first_order_only)
                    <p>
                        <strong>Ketentuan:</strong>
                        Khusus pesanan pertama pelanggan.
                    </p>
                @endif
            </div>
        </section>

        {{-- SYARAT DAN KETENTUAN --}}
        <section class="mt-6 rounded-3xl bg-white p-5 shadow-sm sm:p-7">

            <h2 class="text-lg font-semibold text-[#49352E]">
                Syarat &amp; Ketentuan
            </h2>

            <hr class="mt-4 border-[#F0E4DC]">

            @if (!empty($promo->terms))
                <ol class="mt-5 list-decimal space-y-4 pl-5 text-sm leading-relaxed text-[#806D65]">
                    @foreach ($promo->terms as $term)
                        <li>{{ $term }}</li>
                    @endforeach
                </ol>
            @else
                <p class="mt-5 text-sm text-[#806D65]">
                    Promo berlaku sesuai periode dan ketentuan yang tercantum.
                </p>
            @endif
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

            label.textContent = 'Kode Berhasil Disalin ✓';
            status.textContent = 'Kode berhasil disalin.';
        } catch (error) {
            label.textContent = 'Gagal menyalin kode';
            status.textContent = 'Kode gagal disalin.';
        }

        clearTimeout(resetTimer);

        resetTimer = setTimeout(function () {
            label.textContent = 'Salin Kode Promo';
            status.textContent = '';
        }, 2000);
    });
});
</script>
@endpush
