@extends('layouts.customer')

@section('title', 'Hubungi Kami — Early Cake')

@section('content')
<main class="mx-auto max-w-6xl px-4 py-10 sm:px-8 lg:py-14">

    {{-- Header --}}
    <header class="mb-9">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-rose-600">
            Kami senang mendengar ceritamu
        </p>

        <h1 class="mt-3 text-3xl font-semibold tracking-tight text-[#49352e] sm:text-4xl">
            Hubungi Early Cake
        </h1>

        <p class="mt-3 max-w-xl text-sm leading-relaxed text-[#806d65]">
            Tanya soal rasa, konsultasi desain custom cake, atau informasi pesanan.
            Tim Early Cake siap membantu.
        </p>
    </header>

    {{-- Layout Kontak --}}
    <div class="grid gap-5 lg:grid-cols-[11fr_20fr]">

        {{-- Informasi Toko --}}
        <section
            aria-labelledby="store-title"
            class="rounded-3xl bg-white p-6 shadow-[0_8px_35px_rgba(90,55,45,0.06)] sm:p-7"
        >
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-rose-50 text-xl">
                📍
            </div>

            <h2
                id="store-title"
                class="mt-5 text-lg font-semibold text-[#49352e]"
            >
                {{ $store['name'] }}
            </h2>

            <address class="mt-3 text-sm not-italic leading-7 text-[#806d65]">
                {{ $store['address'] }}
            </address>

            {{-- Jam Operasional --}}
            <div class="mt-6 border-t border-rose-100/70 pt-5">
                <h3 class="text-sm font-semibold text-[#49352e]">
                    Jam Operasional
                </h3>

                <p class="mt-2 text-sm leading-6 text-[#806d65]">
                    {{ $store['hours'] }}
                </p>

                <p class="mt-1 text-xs leading-5 text-[#9b8277]">
                    {{ $store['hours_note'] }}
                </p>
            </div>

            {{-- Google Maps Button --}}
            <a
                href="{{ $store['maps_url'] }}"
                target="_blank"
                rel="noopener noreferrer"
                class="mt-6 inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-xl bg-[#fff1f2] px-4 text-sm font-semibold text-rose-700 transition duration-200 hover:bg-rose-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-500"
            >
                <span aria-hidden="true">↗</span>
                Buka di Google Maps
            </a>
        </section>

        {{-- Google Maps --}}
        <section
            aria-label="Peta lokasi Early Cake"
            class="relative min-h-[250px] overflow-hidden rounded-3xl bg-[#f9e9e5] shadow-[0_8px_35px_rgba(90,55,45,0.06)] lg:min-h-[300px]"
        >
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3957.5249793056305!2d112.7676444!3d-7.2947586!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sid!2sid!4v1792125254975!5m2!1sid!2sid"
                title="Lokasi Early Cake di Google Maps"
                class="absolute inset-0 h-full w-full border-0"
                allowfullscreen
                loading="lazy"
                referrerpolicy="strict-origin-when-cross-origin">
            </iframe>
        </section>

        {{-- Bantuan dan Kontak --}}
        <section
            aria-labelledby="help-title"
            class="flex flex-col rounded-3xl bg-[#FCE8DE] p-6 shadow-[0_8px_30px_rgba(130,75,75,0.09)] sm:p-7"
        >
            {{-- Icon --}}
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/80 text-xl">
                💬
            </div>

            <h2
                id="help-title"
                class="mt-5 text-lg font-semibold text-[#49352e]"
            >
                Ada yang bisa kami bantu?
            </h2>

            <p class="mt-2 text-sm leading-6 text-[#806d65]">
                Kami siap membantu menjawab pertanyaan dan kebutuhan kue spesialmu.
            </p>

            {{-- Informasi Kontak --}}
            <div class="mt-6 space-y-4">

                {{-- WhatsApp --}}
                <div>
                    <p class="text-xs font-medium text-[#9b8277]">
                        WhatsApp
                    </p>

                    <p class="mt-1 text-sm font-medium text-[#49352e]">
                        {{ $store['whatsapp_display'] }}
                    </p>
                </div>

                {{-- Email --}}
                <div>
                    <p class="text-xs font-medium text-[#9b8277]">
                        Email
                    </p>

                    <a
                        href="mailto:{{ $store['email'] }}"
                        class="mt-1 block break-words text-sm font-medium text-[#49352e] transition hover:text-rose-600"
                    >
                        {{ $store['email'] }}
                    </a>
                </div>
            </div>

            <p class="mt-6 text-xs leading-6 text-[#806d65]">
                Hubungi kami pada jam operasional untuk informasi produk dan pemesanan.
            </p>

            {{-- WhatsApp Button --}}
            <a
                href="{{ $whatsappUrl }}"
                target="_blank"
                rel="noopener noreferrer"
                class="mt-6 inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white shadow-[0_5px_15px_rgba(225,29,72,0.15)] transition duration-200 hover:bg-rose-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-500 lg:mt-auto"
            >
                <span aria-hidden="true">💬</span>
                Chat via WhatsApp
            </a>
        </section>

        {{-- Formulir Kontak --}}
        <section
            aria-labelledby="form-title"
            class="rounded-3xl bg-white p-6 shadow-[0_8px_35px_rgba(90,55,45,0.06)] sm:p-7"
        >
            <h2
                id="form-title"
                class="text-lg font-semibold text-[#49352e]"
            >
                Tulis pesan untuk kami
            </h2>

            <p class="mt-2 text-sm text-[#806d65]">
                Punya pertanyaan? Kirimkan pesan melalui formulir berikut.
            </p>

            {{-- Notifikasi Berhasil --}}
            @if (session('status'))
                <div
                    role="status"
                    class="mt-5 rounded-xl bg-green-50 px-4 py-3 text-sm text-green-700"
                >
                    {{ session('status') }}
                </div>
            @endif

            {{-- Notifikasi Error --}}
            @if (session('error'))
                <div
                    role="alert"
                    class="mt-5 rounded-xl bg-rose-50 px-4 py-3 text-sm text-rose-700"
                >
                    {{ session('error') }}
                </div>
            @endif

            {{-- Validasi Form --}}
            @if ($errors->any())
                <div
                    role="alert"
                    class="mt-5 rounded-xl bg-rose-50 px-4 py-3 text-sm text-rose-700"
                >
                    <p class="font-semibold">
                        Periksa kembali formulirmu.
                    </p>

                    <ul class="mt-2 list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form
                method="POST"
                action="{{ route('contact.send') }}"
                class="mt-6 space-y-5"
            >
                @csrf

                <div class="grid gap-5 sm:grid-cols-2">

                    {{-- Nama --}}
                    <div>
                        <label
                            for="name"
                            class="mb-2 block text-xs font-semibold text-[#49352e]"
                        >
                            Nama
                        </label>

                        <input
                            id="name"
                            name="name"
                            type="text"
                            required
                            maxlength="100"
                            autocomplete="name"
                            placeholder="Nama lengkap"
                            value="{{ old('name') }}"
                            @error('name') aria-invalid="true" @enderror
                            class="w-full rounded-xl border border-rose-100 bg-rose-50/40 px-4 py-3 text-sm text-[#49352e] outline-none transition placeholder:text-[#b29d96] focus:border-rose-300 focus:bg-white focus:ring-2 focus:ring-rose-100"
                        >

                        @error('name')
                            <p class="mt-2 text-xs text-rose-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label
                            for="email"
                            class="mb-2 block text-xs font-semibold text-[#49352e]"
                        >
                            Email
                        </label>

                        <input
                            id="email"
                            name="email"
                            type="email"
                            required
                            maxlength="150"
                            autocomplete="email"
                            placeholder="nama@email.com"
                            value="{{ old('email') }}"
                            @error('email') aria-invalid="true" @enderror
                            class="w-full rounded-xl border border-rose-100 bg-rose-50/40 px-4 py-3 text-sm text-[#49352e] outline-none transition placeholder:text-[#b29d96] focus:border-rose-300 focus:bg-white focus:ring-2 focus:ring-rose-100"
                        >

                        @error('email')
                            <p class="mt-2 text-xs text-rose-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Pesan --}}
                <div>
                    <label
                        for="message"
                        class="mb-2 block text-xs font-semibold text-[#49352e]"
                    >
                        Pesan
                    </label>

                    <textarea
                        id="message"
                        name="message"
                        rows="5"
                        required
                        minlength="10"
                        maxlength="2000"
                        placeholder="Ceritakan pertanyaan atau kebutuhan kue kamu..."
                        @error('message') aria-invalid="true" @enderror
                        class="block min-h-[130px] w-full resize-none rounded-xl border border-rose-100 bg-rose-50/40 px-4 py-3 text-sm text-[#49352e] outline-none transition placeholder:text-[#b29d96] focus:border-rose-300 focus:bg-white focus:ring-2 focus:ring-rose-100"
                    >{{ old('message') }}</textarea>

                    @error('message')
                        <p class="mt-2 text-xs text-rose-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <button
                    type="submit"
                    class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-rose-600 px-6 py-3 text-sm font-semibold text-white shadow-[0_5px_15px_rgba(225,29,72,0.12)] transition duration-200 hover:bg-rose-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-500"
                >
                    <span aria-hidden="true">➤</span>
                    Kirim Pesan
                </button>

                <p class="text-xs leading-6 text-[#9b8277]">
                    Pesan akan diproses melalui email dan kamu akan diarahkan
                    ke WhatsApp Early Cake untuk melanjutkan percakapan.
                </p>
            </form>
        </section>

    </div>
</main>
@endsection
