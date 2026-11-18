@extends('layouts.customer')

@section('title', 'Studio Kue Kustom - Early Cake')

@section('content')
<div class="min-h-screen bg-cream py-8 sm:py-10">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        {{-- HEADER --}}
        <div class="mb-7 text-center sm:mb-9">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-rose-500">
                Studio Kue Kustom Early Cake
            </p>

            <h1 class="mt-2 font-serif text-3xl font-bold text-choco sm:text-4xl">
                Desain kue sesuka hatimu.
            </h1>

            <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-choco-light">
                Ikuti enam langkah singkat. Setiap perubahan langsung terlihat di preview.
            </p>
        </div>

        <form id="custom-cake-form"
              action="{{ route('custom-cake.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <input type="hidden" name="cake_type" value="Custom Cake">
            <input type="hidden" name="design_config" id="design-config">

            {{--
                LAYOUT STUDIO
                Desktop : dua kolom sama lebar. Preview (kiri) menempel di layar
                          dan cukup pendek sehingga tidak perlu di-scroll;
                          panel langkah (kanan) memiliki tinggi minimum tetap.
                Mobile  : preview di atas, panel langkah di bawahnya.
            --}}
            <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-2 lg:gap-8">

                {{-- ================= KIRI: PREVIEW ================= --}}
                <aside class="lg:sticky lg:top-20">
                    <section class="rounded-3xl border border-rose-100 bg-white p-4 shadow-sm sm:p-5">

                        <div class="mb-3 flex items-center justify-between gap-3">
                            <p class="text-xs font-semibold uppercase tracking-widest text-rose-400">
                                Live Preview
                            </p>

                            <div class="inline-flex rounded-full bg-rose-50 p-1">
                                <button type="button"
                                        data-preview-tab="front"
                                        class="rounded-full bg-rose-400 px-4 py-1.5 text-sm font-semibold text-white shadow-sm transition">
                                    Depan
                                </button>
                                <button type="button"
                                        data-preview-tab="top"
                                        class="rounded-full px-4 py-1.5 text-sm font-semibold text-choco transition">
                                    Atas
                                </button>
                            </div>
                        </div>

                        {{-- Rasio dikunci mengikuti viewBox SVG (400:274) agar tidak terpotong --}}
                        <div class="rounded-2xl bg-[#FFF9F5] p-3 sm:p-4">
                            <div id="cake-preview" class="mx-auto flex aspect-[400/274] w-full max-w-[400px] items-center justify-center">
                                <p class="text-center text-sm text-choco-light">
                                    Preview kue akan muncul di sini.
                                </p>
                            </div>
                        </div>

                        {{-- Ukuran tulisan (dihubungkan oleh cake-builder.js) --}}
                        <div id="cake-message-font-controls"
                             class="mt-3 flex items-center justify-between gap-2 rounded-2xl bg-rose-50/70 px-3 py-2">
                            <span class="text-xs font-medium text-choco-light">Ukuran tulisan</span>

                            <div class="flex items-center gap-1.5">
                                <button type="button"
                                        id="cake-message-font-minus"
                                        aria-label="Perkecil tulisan"
                                        class="flex h-9 w-9 items-center justify-center rounded-full border border-rose-200 bg-white text-sm font-semibold text-choco transition hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-40">
                                    A−
                                </button>

                                <span id="cake-message-font-value"
                                      class="w-12 text-center text-sm font-semibold text-choco">
                                    16 px
                                </span>

                                <button type="button"
                                        id="cake-message-font-plus"
                                        aria-label="Perbesar tulisan"
                                        class="flex h-9 w-9 items-center justify-center rounded-full border border-rose-200 bg-white text-sm font-semibold text-choco transition hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-40">
                                    A+
                                </button>

                                <button type="button"
                                        id="cake-message-font-reset"
                                        class="ml-1 rounded-full px-2.5 py-1.5 text-xs font-semibold text-rose-500 transition hover:bg-rose-100">
                                    Default
                                </button>
                            </div>
                        </div>

                        {{-- Ruang catatan dipesan agar tinggi kartu tidak berubah --}}
                        <div class="min-h-[1.25rem] pt-1.5">
                            <p id="cake-message-font-note"
                               class="hidden text-center text-[11px] leading-4 text-choco-light"></p>
                        </div>

                        <p class="mt-1 text-center text-[11px] text-choco-light">
                            Ilustrasi 2D &middot; geser tulisan lewat tampak <span class="font-semibold">Atas</span>
                        </p>
                    </section>
                </aside>

                {{-- ================= KANAN: LANGKAH DESAIN ================= --}}
                <section id="design-wizard"
                         class="scroll-mt-24 overflow-hidden rounded-3xl border border-rose-100 bg-white shadow-sm">

                    {{-- Navigasi langkah --}}
                    <div role="tablist"
                         aria-label="Langkah desain kue"
                         class="grid grid-cols-6 gap-1 border-b border-rose-100 bg-rose-50/50 p-2 sm:p-3">
                        @foreach (['Dasar', 'Warna', 'Dekorasi', 'Tulisan', 'Detail', 'Pemesan'] as $index => $stepLabel)
                            <button type="button"
                                    role="tab"
                                    data-step-tab="{{ $index + 1 }}"
                                    aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                                    class="relative flex flex-col items-center gap-1 rounded-xl px-1 py-2 transition hover:bg-white/70 {{ $index === 0 ? 'bg-white shadow-sm' : '' }}">
                                <span data-step-num
                                      class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold {{ $index === 0 ? 'bg-rose-400 text-white' : 'bg-white text-rose-400 ring-1 ring-rose-200' }}">
                                    {{ $index + 1 }}
                                </span>
                                <span data-step-label
                                      class="text-[11px] font-semibold leading-none sm:text-xs {{ $index === 0 ? 'text-rose-500' : 'text-choco-light' }}">
                                    {{ $stepLabel }}
                                </span>
                                <span data-step-error-dot
                                      class="absolute right-1.5 top-1.5 hidden h-2 w-2 rounded-full bg-red-500"></span>
                            </button>
                        @endforeach
                    </div>

                    {{-- Isi langkah (tinggi minimum tetap agar layout stabil) --}}
                    <div class="min-h-[28rem] p-5 sm:p-6">

                        {{-- ===== 1. DASAR KUE ===== --}}
                        <div data-step-panel="1">
                            <div class="mb-5">
                                <h2 class="font-serif text-xl font-bold text-choco">Dasar Kue</h2>
                                <p class="mt-1 text-sm text-choco-light">
                                    Tentukan ukuran, rasa, dan bentuk kue.
                                </p>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="cake-size" class="mb-2 block text-sm font-semibold text-choco">
                                        Ukuran Kue
                                    </label>
                                    <select id="cake-size"
                                            name="size"
                                            required
                                            class="w-full rounded-xl border border-rose-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                        <option value="">Pilih ukuran</option>
                                        @foreach ($options->get('size', collect()) as $option)
                                            <option value="{{ $option->name }}"
                                                    data-additional-price="{{ $option->additional_price }}"
                                                    @selected(old('size') === $option->name)>
                                                {{ $option->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('size')
                                        <p data-field-error class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="cake-flavor" class="mb-2 block text-sm font-semibold text-choco">
                                        Rasa Kue
                                    </label>
                                    <select id="cake-flavor"
                                            name="flavor"
                                            required
                                            class="w-full rounded-xl border border-rose-100 bg-white px-4 py-3 text-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                        <option value="">Pilih rasa</option>
                                        @foreach ($options->get('flavor', collect()) as $option)
                                            <option value="{{ $option->name }}"
                                                    data-additional-price="{{ $option->additional_price }}"
                                                    @selected(old('flavor') === $option->name)>
                                                {{ $option->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('flavor')
                                        <p data-field-error class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6">
                                <p class="mb-2 text-sm font-semibold text-choco">Bentuk Kue</p>

                                <div class="grid grid-cols-3 gap-3">
                                    @foreach (['Bulat' => 'Bulat', 'Persegi' => 'Persegi', 'Hati' => 'Hati'] as $value => $label)
                                        <label class="cursor-pointer">
                                            <input type="radio"
                                                   name="shape"
                                                   value="{{ $value }}"
                                                   class="peer sr-only"
                                                   @checked(old('shape', 'Bulat') === $value)>

                                            <div class="rounded-2xl border border-rose-100 p-3 text-center transition hover:bg-rose-50 peer-checked:border-rose-400 peer-checked:bg-rose-50 peer-checked:ring-2 peer-checked:ring-rose-100 peer-focus-visible:ring-2 peer-focus-visible:ring-rose-400 sm:p-4">
                                                <div class="mx-auto flex h-12 items-center justify-center">
                                                    @if ($value === 'Bulat')
                                                        <div class="h-10 w-10 rounded-full border-2 border-rose-400 bg-rose-100"></div>
                                                    @elseif ($value === 'Persegi')
                                                        <div class="h-10 w-10 rounded-lg border-2 border-rose-400 bg-rose-100"></div>
                                                    @else
                                                        <span class="text-4xl leading-none text-rose-400">♥</span>
                                                    @endif
                                                </div>
                                                <span class="mt-2 block text-sm font-semibold text-choco">
                                                    {{ $label }}
                                                </span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                                @error('shape')
                                    <p data-field-error class="mt-2 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- ===== 2. WARNA ===== --}}
                        <div data-step-panel="2" class="hidden">
                            <div class="mb-5">
                                <h2 class="font-serif text-xl font-bold text-choco">Warna Kue</h2>
                                <p class="mt-1 text-sm text-choco-light">
                                    Atur warna bagian atas dan samping secara terpisah.
                                </p>
                            </div>

                            <div class="space-y-6">

                                {{-- Bagian atas --}}
                                <div>
                                    <label for="cake-color" class="mb-3 block text-sm font-semibold text-choco">
                                        Bagian Atas
                                    </label>

                                    <div class="flex flex-wrap items-center gap-2.5">
                                        @foreach (['#F4A6B7', '#F8D7DA', '#FFF0C9', '#A8D5BA', '#B8D8F0', '#C9B1E8', '#FFFFFF'] as $swatch)
                                            <button type="button"
                                                    data-color-swatch="{{ $swatch }}"
                                                    aria-label="Pilih warna {{ $swatch }}"
                                                    class="h-9 w-9 rounded-full border border-gray-200 shadow-sm transition hover:scale-110 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-400 focus-visible:ring-offset-2"
                                                    style="background-color: {{ $swatch }}">
                                            </button>
                                        @endforeach

                                        </div>

<div class="mt-3 flex items-center gap-2"><span class="mr-1 text-xs text-choco-light">Warna lain</span>
                                            <input type="color"
                                                   id="cake-color"
                                                   name="color"
                                                   value="{{ old('color', '#F4A6B7') }}"
                                                   aria-label="Pilih warna bagian atas"
                                                   class="h-9 w-11 shrink-0 cursor-pointer rounded-lg border border-rose-100 bg-white p-1">

                                            <input type="text"
                                                   id="cake-color-hex"
                                                   value="{{ old('color', '#F4A6B7') }}"
                                                   maxlength="7"
                                                   aria-label="Kode warna HEX bagian atas"
                                                   class="w-24 rounded-lg border border-rose-100 bg-white px-3 py-2 text-sm uppercase outline-none focus:border-rose-400">
                                        </div>
                                </div>

                                <div class="border-t border-rose-100"></div>

                                {{-- Bagian samping --}}
                                <div>
                                    <label for="cake-side-color" class="mb-3 block text-sm font-semibold text-choco">
                                        Bagian Samping
                                    </label>

                                    <div class="flex flex-wrap items-center gap-2.5">
                                        @foreach (['#D88FA1', '#E8A6B5', '#D9B58C', '#8FBFA5', '#91B9D8', '#A995C8', '#FFFFFF'] as $swatch)
                                            <button type="button"
                                                    data-side-color-swatch="{{ $swatch }}"
                                                    aria-label="Pilih warna samping {{ $swatch }}"
                                                    class="h-9 w-9 rounded-full border border-gray-200 shadow-sm transition hover:scale-110 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-400 focus-visible:ring-offset-2"
                                                    style="background-color: {{ $swatch }}">
                                            </button>
                                        @endforeach

                                        </div>

<div class="mt-3 flex items-center gap-2"><span class="mr-1 text-xs text-choco-light">Warna lain</span>
                                            <input type="color"
                                                   id="cake-side-color"
                                                   value="{{ old('side_color', '#D88FA1') }}"
                                                   aria-label="Pilih warna bagian samping"
                                                   class="h-9 w-11 shrink-0 cursor-pointer rounded-lg border border-rose-100 bg-white p-1">

                                            <input type="text"
                                                   id="cake-side-color-hex"
                                                   value="{{ old('side_color', '#D88FA1') }}"
                                                   maxlength="7"
                                                   aria-label="Kode warna HEX bagian samping"
                                                   class="w-24 rounded-lg border border-rose-100 bg-white px-3 py-2 text-sm uppercase outline-none focus:border-rose-400">
                                        </div>
                                </div>
                            </div>
                        </div>

                        {{-- ===== 3. DEKORASI ===== --}}
                        <div data-step-panel="3" class="hidden">
                            <div class="mb-5">
                                <h2 class="font-serif text-xl font-bold text-choco">Dekorasi Kue</h2>
                                <p class="mt-1 text-sm text-choco-light">
                                    Kamu bisa memilih lebih dari satu dekorasi, atau lewati langkah ini.
                                </p>
                            </div>


                            <style>
                                /* Style checkbox dekorasi memakai :checked asli (tanpa varian Tailwind
                                   yang bisa gagal ter-generate), jadi status centang selalu akurat. */
                                .deco-card {
                                    border-color: #FFE4E6;
                                    background: #fff;
                                    transition: background-color .15s, border-color .15s, box-shadow .15s;
                                }
                                .deco-option:hover .deco-card { background: #FFF1F2; }
                                .deco-check {
                                    border-color: #FDA4AF;
                                    background: #fff;
                                    color: #fff;
                                    transition: background-color .15s, border-color .15s;
                                }
                                .deco-tick { opacity: 0; transform: scale(.6); transition: opacity .15s, transform .15s; }
                                .deco-badge {
                                    display: none;
                                    font-size: 11px;
                                    font-weight: 600;
                                    color: #E11D48;
                                    background: #FFE4E6;
                                    border-radius: 9999px;
                                    padding: 2px 8px;
                                }
                                .deco-input:checked + .deco-card {
                                    border-color: #FB7185;
                                    background: #FFF1F2;
                                    box-shadow: 0 0 0 2px #FFE4E6;
                                }
                                .deco-input:checked + .deco-card .deco-check {
                                    border-color: #FB7185;
                                    background: #FB7185;
                                }
                                .deco-input:checked + .deco-card .deco-tick { opacity: 1; transform: scale(1); }
                                .deco-input:checked + .deco-card .deco-badge { display: inline-block; }
                                .deco-input:focus-visible + .deco-card { box-shadow: 0 0 0 2px #FB7185; }
                            </style>

                            <div class="grid gap-3 sm:grid-cols-2">
                                @forelse ($options->get('decoration', collect()) as $option)
                                    <label class="deco-option block cursor-pointer">
                                        <input type="checkbox"
                                               name="decorations[]"
                                               value="{{ $option->name }}"
                                               data-additional-price="{{ $option->additional_price }}"
                                               class="deco-input sr-only"
                                               @checked(in_array($option->name, old('decorations', [])))>

                                        <div class="deco-card flex items-center gap-3 rounded-xl border p-3.5">
                                            <span class="deco-check flex h-5 w-5 shrink-0 items-center justify-center rounded-md border">
                                                <svg class="deco-tick h-3 w-3" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M2.5 6.5l2.5 2.5 4.5-5.5" />
                                                </svg>
                                            </span>

                                            <span class="flex-1 text-sm text-choco">
                                                {{ $option->name }}
                                            </span>

                                            <span class="deco-badge">Dipilih</span>
                                        </div>
                                    </label>
                                @empty
                                    <p class="text-sm text-choco-light sm:col-span-2">
                                        Belum ada pilihan dekorasi.
                                    </p>
                                @endforelse
                            </div>

                            @error('decorations')
                                <p data-field-error class="mt-2 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ===== 4. TULISAN ===== --}}
                        <div data-step-panel="4" class="hidden">
                            <div class="mb-5">
                                <h2 class="font-serif text-xl font-bold text-choco">Tulisan pada Kue</h2>
                                <p class="mt-1 text-sm text-choco-light">
                                    Ucapan atau nama yang tampil di atas kue. Boleh dikosongkan.
                                </p>
                            </div>

                            <div>
                                <label for="cake-message" class="mb-2 block text-sm font-semibold text-choco">
                                    Kata-kata untuk Kue
                                </label>

                                <textarea name="message"
                                          id="cake-message"
                                          rows="3"
                                          maxlength="100"
                                          class="w-full resize-y rounded-xl border border-rose-100 px-4 py-3 text-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100"
                                          placeholder="Contoh:&#10;Happy Birthday!&#10;Nadia">{{ old('message') }}</textarea>

                                <div class="mt-2 flex flex-wrap items-center justify-between gap-2">
                                    <p id="cake-message-hint" class="text-xs text-choco-light">
                                        Batas karakter menyesuaikan ukuran kue.
                                    </p>
                                    <span id="cake-message-counter" class="whitespace-nowrap text-xs text-choco-light">
                                        0 karakter
                                    </span>
                                </div>

                                @error('message')
                                    <p data-field-error class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-6">
                                <label for="cake-message-color" class="mb-3 block text-sm font-semibold text-choco">
                                    Warna Tulisan
                                </label>

                                <div class="flex flex-wrap items-center gap-2.5">
                                    @foreach (['#4A2C27', '#FFFFFF', '#E63946', '#D63384', '#6F42C1', '#1D4ED8', '#198754', '#000000'] as $swatch)
                                        <button type="button"
                                                data-message-color-swatch="{{ $swatch }}"
                                                aria-label="Pilih warna tulisan {{ $swatch }}"
                                                class="h-9 w-9 rounded-full border border-gray-200 shadow-sm transition hover:scale-110 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-400 focus-visible:ring-offset-2"
                                                style="background-color: {{ $swatch }}">
                                        </button>
                                    @endforeach

                                    </div>

<div class="mt-3 flex items-center gap-2"><span class="mr-1 text-xs text-choco-light">Warna lain</span>
                                        <input type="color"
                                               id="cake-message-color"
                                               value="{{ old('message_color', '#4A2C27') }}"
                                               aria-label="Pilih warna tulisan"
                                               class="h-9 w-11 shrink-0 cursor-pointer rounded-lg border border-rose-100 bg-white p-1">

                                        <input type="text"
                                               id="cake-message-color-hex"
                                               value="{{ old('message_color', '#4A2C27') }}"
                                               maxlength="7"
                                               aria-label="Kode warna HEX tulisan"
                                               class="w-24 rounded-lg border border-rose-100 bg-white px-3 py-2 text-sm uppercase outline-none focus:border-rose-400">
                                    </div>
                            </div>

                            <p class="mt-5 rounded-xl bg-rose-50 px-4 py-3 text-xs leading-5 text-choco-light">
                                Ukuran tulisan diatur dengan tombol <span class="font-semibold">A−</span> /
                                <span class="font-semibold">A+</span> di bawah preview, dan posisinya bisa
                                digeser di tampak <span class="font-semibold">Atas</span>.
                            </p>
                        </div>

                        {{-- ===== 5. DETAIL ===== --}}
                        <div data-step-panel="5" class="hidden">
                            <div class="mb-5">
                                <h2 class="font-serif text-xl font-bold text-choco">Detail Tambahan</h2>
                                <p class="mt-1 text-sm text-choco-light">
                                    Opsional, tetapi membantu kami memahami desainmu.
                                </p>
                            </div>

                            <div class="space-y-5">
                                <div>
                                    <label for="cake-theme" class="mb-2 block text-sm font-semibold text-choco">
                                        Tema Kue
                                    </label>
                                    <input id="cake-theme"
                                           type="text"
                                           name="theme"
                                           value="{{ old('theme') }}"
                                           class="w-full rounded-xl border border-rose-100 px-4 py-3 text-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100"
                                           placeholder="Contoh: Minimalist, Korean Cake, Birthday">
                                    @error('theme')
                                        <p data-field-error class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="cake-description" class="mb-2 block text-sm font-semibold text-choco">
                                        Deskripsi Desain
                                    </label>
                                    <textarea id="cake-description"
                                              name="description"
                                              rows="4"
                                              class="w-full resize-y rounded-xl border border-rose-100 px-4 py-3 text-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100"
                                              placeholder="Ceritakan detail desain kue yang kamu inginkan...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p data-field-error class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="reference-image" class="mb-2 block text-sm font-semibold text-choco">
                                        Foto Referensi (Opsional)
                                    </label>
                                    <input id="reference-image"
                                           type="file"
                                           name="reference_image"
                                           accept=".jpg,.jpeg,.png,.webp"
                                           class="w-full rounded-xl border border-dashed border-rose-200 bg-rose-50/40 p-3 text-sm text-choco-light file:mr-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-rose-100 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-rose-500 hover:file:bg-rose-200">

                                    <p class="mt-2 text-xs text-choco-light">
                                        Format JPG, PNG, atau WEBP. Maksimal 2 MB.
                                    </p>

                                    <div id="reference-preview" class="mt-3 hidden">
                                        <img src=""
                                             alt="Preview foto referensi"
                                             class="max-h-48 rounded-xl object-contain">
                                    </div>

                                    @error('reference_image')
                                        <p data-field-error class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ===== 6. PEMESAN ===== --}}
                        <div data-step-panel="6" class="hidden">
                            <div class="mb-5">
                                <h2 class="font-serif text-xl font-bold text-choco">Data Pemesan</h2>
                                <p class="mt-1 text-sm text-choco-light">
                                    Digunakan untuk menghubungi dan mengirimkan pesananmu.
                                </p>
                            </div>

                            <div class="mb-5 rounded-2xl bg-rose-50/70 p-4">
                                <p class="text-sm font-semibold text-choco">Ringkasan desain</p>
                                <dl id="order-summary" class="mt-1 divide-y divide-rose-100"></dl>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="customer-name" class="mb-2 block text-sm font-semibold text-choco">
                                        Nama Lengkap
                                    </label>
                                    <input id="customer-name"
                                           type="text"
                                           name="name"
                                           value="{{ old('name', $prefill['name'] ?? '') }}"
                                           required
                                           autocomplete="name"
                                           class="w-full rounded-xl border border-rose-100 px-4 py-3 text-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100"
                                           placeholder="Nama kamu">
                                    @error('name')
                                        <p data-field-error class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer-phone" class="mb-2 block text-sm font-semibold text-choco">
                                        Nomor WhatsApp
                                    </label>
                                    <input id="customer-phone"
                                           type="tel"
                                           name="phone"
                                           value="{{ old('phone', $prefill['phone'] ?? '') }}"
                                           required
                                           autocomplete="tel"
                                           class="w-full rounded-xl border border-rose-100 px-4 py-3 text-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100"
                                           placeholder="08xxxxxxxxxx">
                                    @error('phone')
                                        <p data-field-error class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="customer-email" class="mb-2 block text-sm font-semibold text-choco">
                                        Email
                                    </label>
                                    <input id="customer-email"
                                           type="email"
                                           name="email"
                                           value="{{ old('email', $prefill['email'] ?? '') }}"
                                           required
                                           autocomplete="email"
                                           class="w-full rounded-xl border border-rose-100 px-4 py-3 text-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100"
                                           placeholder="email@contoh.com">
                                    @error('email')
                                        <p data-field-error class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="customer-address" class="mb-2 block text-sm font-semibold text-choco">
                                        Alamat Lengkap
                                    </label>
                                    <textarea id="customer-address"
                                              name="address"
                                              rows="3"
                                              required
                                              autocomplete="street-address"
                                              class="w-full resize-y rounded-xl border border-rose-100 px-4 py-3 text-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100"
                                              placeholder="Masukkan alamat lengkap">{{ old('address', $prefill['address'] ?? '') }}</textarea>
                                    @error('address')
                                        <p data-field-error class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Navigasi langkah --}}
                    <div class="flex items-center justify-between gap-3 border-t border-rose-100 bg-rose-50/30 px-5 py-4 sm:px-6">
                        <button type="button"
                                data-step-prev
                                class="invisible rounded-xl border border-rose-200 bg-white px-5 py-3 text-sm font-semibold text-choco transition hover:bg-rose-50">
                            &larr; Kembali
                        </button>

                        <span data-step-counter
                              class="hidden text-xs font-medium text-choco-light sm:block">
                            Langkah 1 dari 6
                        </span>

                        <button type="button"
                                data-step-next
                                class="rounded-xl bg-rose-400 px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-rose-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-400 focus-visible:ring-offset-2">
                            Lanjut &rarr;
                        </button>

                        <button type="submit"
                                data-step-submit
                                class="hidden rounded-xl bg-rose-400 px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-rose-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-400 focus-visible:ring-offset-2">
                            Kirim Desain
                        </button>
                    </div>
                </section>
            </div>
        </form>
    </div>
    {{-- BANNER CEK STATUS PENGAJUAN --}}
    <section class="mx-auto mt-10 max-w-6xl px-4 pb-4 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center justify-between gap-4 rounded-2xl border border-rose-100 bg-white p-5 shadow-sm sm:flex-row sm:p-6">
            <div>
                <h2 class="text-lg font-bold text-gray-900">
                    Sudah mengajukan Custom Cake?
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Pantau status dan perkembangan pengajuan custom cake kamu.
                </p>
            </div>

            <a href="{{ route('custom-cake.status.check') }}"
               class="inline-flex shrink-0 items-center justify-center rounded-xl bg-rose-400 px-6 py-3 text-sm font-semibold text-white transition hover:bg-rose-500">
                Cek Status Pengajuan
                <span class="ml-2">→</span>
            </a>
        </div>
    </section>
</div>
@endsection

@push('scripts')
    @vite('resources/js/cake-builder.js')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('custom-cake-form');
            const submitButton = form?.querySelector('[data-step-submit]');

            if (!form) return;

            let confirmed = false;

            const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, character => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;',
            }[character]));

            function readDesign() {
                try {
                    return JSON.parse(document.getElementById('design-config')?.value || '{}');
                } catch (error) {
                    return {};
                }
            }

            function buildSummary() {
                const design = readDesign();

                const decorations = Array.isArray(design.decorations) && design.decorations.length
                    ? design.decorations.join(', ')
                    : 'Tanpa dekorasi';

                const message = String(design.message || '').trim().replace(/\s*\n\s*/g, ' / ')
                    || 'Tanpa tulisan';

                const rows = [
                    ['Ukuran', design.size || '-'],
                    ['Rasa', design.flavor || '-'],
                    ['Bentuk', design.shape || '-'],
                    ['Dekorasi', decorations],
                    ['Tulisan', message],
                ];

                return `
                    <div style="text-align:left;font-size:14px;line-height:1.5">
                        ${rows.map(([label, value]) => `
                            <div style="display:flex;justify-content:space-between;gap:16px;padding:8px 0;border-bottom:1px solid #FDE4EA">
                                <span style="color:#8B6F66">${escapeHtml(label)}</span>
                                <strong style="text-align:right;color:#4A2C27">${escapeHtml(value)}</strong>
                            </div>
                        `).join('')}
                    </div>
                `;
            }

            // Event submit hanya muncul setelah validasi bawaan browser lolos,
            // sehingga konfirmasi ini tidak tampil saat isian wajib masih kosong.
            form.addEventListener('submit', event => {
                if (confirmed) return;

                // Tanpa SweetAlert, formulir tetap terkirim seperti biasa.
                if (!window.Swal) return;

                event.preventDefault();

                Swal.fire({
                    icon: 'question',
                    title: 'Kirim desain custom cake?',
                    html: buildSummary(),
                    showCancelButton: true,
                    confirmButtonText: 'Ya, kirim',
                    cancelButtonText: 'Periksa lagi',
                    confirmButtonColor: '#F08FA5',
                    cancelButtonColor: '#9CA3AF',
                    reverseButtons: true,
                    focusCancel: false,
                }).then(result => {
                    if (!result.isConfirmed) return;

                    confirmed = true;

                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.textContent = 'Mengirim...';
                        submitButton.classList.add('opacity-70', 'cursor-not-allowed');
                    }

                    Swal.fire({
                        title: 'Mengirim desain...',
                        text: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => Swal.showLoading(),
                    });

                    // Ditunda agar tidak berbenturan dengan event submit yang sedang berjalan.
                    setTimeout(() => form.requestSubmit(), 0);
                });
            });
        });
    </script>
@endpush