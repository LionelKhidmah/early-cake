@extends('layouts.customer')

@section('content')
<main class="mx-auto max-w-6xl px-4 py-8 sm:px-8 sm:py-10 lg:px-11">

    {{-- Header --}}
    <header>
        <p class="text-[0.6875rem] font-medium uppercase tracking-wide text-brand">
            Kami senang mendengar ceritamu
        </p>

        <h1 class="mt-3 text-3xl font-semibold leading-tight tracking-tight sm:text-4xl">
            Hubungi Early Cake
        </h1>

        <p class="mt-2 max-w-xl text-sm leading-relaxed text-muted">
            Tanya soal rasa, konsultasi desain custom cake, atau informasi pesanan.
            Tim Early Cake siap membantu.
        </p>
    </header>

    <div class="mt-8 grid gap-4 lg:grid-cols-[11fr_20fr] lg:gap-x-5">

        {{-- Kartu informasi toko --}}
        <section aria-labelledby="store-title"
                 class="rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6 lg:col-start-1 lg:row-start-1">

            <span class="text-brand" aria-hidden="true">📍</span>

            <h2 id="store-title" class="mt-4 text-lg font-semibold">
                {{ $store['name'] }}
            </h2>

            <address class="mt-3 text-sm not-italic leading-relaxed text-muted">
                {{ $store['address'] }}
            </address>

            <h3 class="mt-4 text-sm font-semibold">
                Jam operasional
            </h3>

            <p class="mt-3 text-xs leading-relaxed text-muted">
                {{ $store['hours'] }}
                {{ $store['hours_note'] }}
            </p>

            <a href="{{ $store['maps_url'] }}"
               target="_blank"
               rel="noopener noreferrer"
               class="mt-5 inline-flex h-10 w-full items-center justify-center gap-2 rounded-lg border border-line bg-white text-xs font-medium text-ink transition hover:bg-cream focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand">

                <span aria-hidden="true">📍</span>
                Buka di Google Maps
            </a>
        </section>

        {{-- Google Maps Early Cake --}}
        <div class="relative min-h-52 overflow-hidden rounded-2xl border-2 border-blush-line bg-map-bg shadow-card aspect-505/218 lg:col-start-2 lg:row-start-1 lg:aspect-auto">

            <iframe
                src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3957.5249793056305!2d112.7676444!3d-7.2947586!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sid!2sid!4v1792125254975!5m2!1sid!2sid"
                title="Lokasi Early Cake di Google Maps"
                class="absolute inset-0 h-full w-full rounded-2xl border-0"
                allowfullscreen
                loading="lazy"
                referrerpolicy="strict-origin-when-cross-origin">
            </iframe>

        </div>

        {{-- Kartu bantuan dan kontak --}}
        <section aria-labelledby="help-title"
                 class="flex h-full flex-col rounded-2xl border border-blush-line bg-blush p-5 shadow-card sm:p-6 lg:col-start-1 lg:row-start-2">

            <h2 id="help-title" class="text-lg font-semibold">
                Ada yang bisa kami bantu?
            </h2>

            <p class="mt-4 text-xs">
                WhatsApp · {{ $store['whatsapp_display'] }}
            </p>

            <p class="mt-3 text-xs">
                Email ·
                <a href="mailto:{{ $store['email'] }}"
                   class="break-words hover:underline">
                    {{ $store['email'] }}
                </a>
            </p>

            <p class="mt-4 text-[0.6875rem] text-muted">
                Hubungi kami pada jam operasional untuk informasi produk
                dan pemesanan.
            </p>

            <a href="{{ $whatsappUrl }}"
               target="_blank"
               rel="noopener noreferrer"
               class="mt-auto inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-lg border border-rose-600 bg-rose-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:border-rose-700 hover:bg-rose-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600">

                <span aria-hidden="true">💬</span>
                Chat via WhatsApp
            </a>
        </section>

        {{-- Kartu formulir --}}
        <section aria-labelledby="form-title"
                 class="rounded-2xl border border-line bg-white p-5 shadow-card sm:p-6 lg:col-start-2 lg:row-start-2">

            <h2 id="form-title" class="text-lg font-semibold">
                Tulis pesan untuk kami
            </h2>

            @if (session('status'))
                <p role="status"
                   class="mt-4 rounded-lg border border-line bg-cream px-3 py-2 text-sm">
                    {{ session('status') }}
                </p>
            @endif

            @if (session('error'))
                <p role="alert"
                   class="mt-4 rounded-lg border border-blush-line bg-blush px-3 py-2 text-sm">
                    {{ session('error') }}
                </p>
            @endif

            @if ($errors->any())
                <div role="alert"
                     class="mt-4 rounded-lg border border-blush-line bg-blush px-3 py-2 text-sm">

                    <p class="font-semibold">
                        Periksa kembali formulirmu.
                    </p>

                    <ul class="mt-2 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                  action="{{ route('contact.send') }}"
                  class="mt-4 space-y-3">

                @csrf

                <div class="grid gap-3 sm:grid-cols-2 sm:gap-4">

                    {{-- Nama --}}
                    <div>
                        <label for="name"
                               class="block rounded-lg border border-field-line px-3 py-2 transition focus-within:border-brand focus-within:ring-2 focus-within:ring-brand/20">

                            <span class="block text-xs">Nama</span>

                            <input id="name"
                                   name="name"
                                   type="text"
                                   required
                                   maxlength="100"
                                   autocomplete="name"
                                   placeholder="Nama lengkap"
                                   value="{{ old('name') }}"
                                   @error('name') aria-invalid="true" @enderror
                                   class="mt-0.5 block w-full bg-transparent text-xs text-ink outline-none placeholder:text-muted/70">
                        </label>

                        @error('name')
                            <p class="mt-1 text-xs text-brand-dark">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email"
                               class="block rounded-lg border border-field-line px-3 py-2 transition focus-within:border-brand focus-within:ring-2 focus-within:ring-brand/20">

                            <span class="block text-xs">Email</span>

                            <input id="email"
                                   name="email"
                                   type="email"
                                   required
                                   maxlength="150"
                                   autocomplete="email"
                                   placeholder="nama@email.com"
                                   value="{{ old('email') }}"
                                   @error('email') aria-invalid="true" @enderror
                                   class="mt-0.5 block w-full bg-transparent text-xs text-ink outline-none placeholder:text-muted/70">
                        </label>

                        @error('email')
                            <p class="mt-1 text-xs text-brand-dark">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Pesan --}}
                <div>
                    <label for="message"
                           class="block rounded-lg border border-field-line px-3 py-2 transition focus-within:border-brand focus-within:ring-2 focus-within:ring-brand/20">

                        <span class="block text-xs">Pesan</span>

                        <textarea id="message"
                                  name="message"
                                  rows="3"
                                  required
                                  minlength="10"
                                  maxlength="2000"
                                  placeholder="Ceritakan pertanyaan atau kebutuhan kue kamu..."
                                  @error('message') aria-invalid="true" @enderror
                                  class="mt-0.5 block min-h-14 w-full resize-y bg-transparent text-xs text-ink outline-none placeholder:text-muted/70">{{ old('message') }}</textarea>
                    </label>

                    @error('message')
                        <p class="mt-1 text-xs text-brand-dark">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button type="submit"
                        class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg border border-rose-600 bg-rose-600 px-6 py-2 text-sm font-semibold text-white shadow-sm transition hover:border-rose-700 hover:bg-rose-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600">

                    <span aria-hidden="true">➤</span>
                    Kirim Pesan
                </button>

                <p class="text-[0.6875rem] leading-relaxed text-muted">
                    Pesan akan diproses melalui email dan kamu akan diarahkan
                    ke WhatsApp Early Cake untuk melanjutkan percakapan.
                </p>
            </form>
        </section>
    </div>
</main>
@endsection