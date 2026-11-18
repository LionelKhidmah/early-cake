@extends('layouts.customer')

@section('title', 'Cek Status Custom Cake - Early Cake')

@section('content')
<section class="min-h-screen bg-[#fffaf5] px-4 py-16">
    <div class="mx-auto max-w-xl">

        <a href="{{ route('custom-cake.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-rose-500 transition hover:text-rose-700">
            <span>&larr;</span>
            Kembali ke Custom Cake
        </a>

        <div class="mt-8 rounded-3xl border border-rose-100 bg-white p-6 shadow-sm sm:p-9">

            <div class="text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-rose-50 text-3xl">
                    <span>🎂</span>
                </div>

                <p class="mt-5 text-xs font-bold uppercase tracking-[0.2em] text-rose-400">
                    Early Cake
                </p>

                <h1 class="mt-2 text-2xl font-bold text-[#49352f] sm:text-3xl">
                    Cek Status Pengajuan
                </h1>

                <p class="mt-3 text-sm leading-6 text-[#907d73]">
                    Masukkan kode pengajuan dan nomor WhatsApp
                    yang kamu gunakan saat memesan kue kustom.
                </p>
            </div>

            @if($errors->has('lookup'))
                <div class="mt-6 rounded-xl bg-red-50 p-4 text-sm text-red-600">
                    {{ $errors->first('lookup') }}
                </div>
            @endif

            @if($errors->any() && !$errors->has('lookup'))
                <div class="mt-6 rounded-xl bg-red-50 p-4 text-sm text-red-600">
                    <ul class="list-inside list-disc">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                action="{{ route('custom-cake.status.lookup') }}"
                method="POST"
                class="mt-8 space-y-5"
            >
                @csrf

                <div>
                    <label for="order_number"
                           class="mb-2 block text-sm font-semibold text-[#49352f]">
                        Kode Pengajuan
                    </label>

                    <input
                        type="text"
                        name="order_number"
                        id="order_number"
                        value="{{ old('order_number') }}"
                        placeholder="Contoh: EC-XXXXXXXXXX"
                        required
                        maxlength="50"
                        autocomplete="off"
                        class="w-full rounded-xl border border-rose-100 bg-[#fffaf5] px-4 py-3 text-sm text-[#49352f] outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100"
                    >
                </div>

                <div>
                    <label for="phone"
                           class="mb-2 block text-sm font-semibold text-[#49352f]">
                        Nomor WhatsApp
                    </label>

                    <input
                        type="tel"
                        name="phone"
                        id="phone"
                        placeholder="Contoh: 081234567890"
                        required
                        maxlength="20"
                        autocomplete="tel"
                        class="w-full rounded-xl border border-rose-100 bg-[#fffaf5] px-4 py-3 text-sm text-[#49352f] outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full rounded-full bg-rose-500 px-6 py-3.5 text-sm font-semibold text-white transition hover:bg-rose-600"
                >
                    Cek Status Pengajuan
                </button>
            </form>

            <div class="mt-6 rounded-xl bg-amber-50 p-4">
                <p class="text-xs leading-6 text-amber-800">
                    Pastikan kode pengajuan dan nomor WhatsApp
                    sesuai dengan informasi saat kamu mengirim
                    pengajuan Custom Cake.
                </p>
            </div>

        </div>
    </div>
</section>
@endsection
