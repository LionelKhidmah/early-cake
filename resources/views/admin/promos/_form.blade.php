@php
    $isEdit = isset($promo);

    $inputClass = 'mt-2 block w-full rounded-xl border border-rose-200 bg-white px-4 py-3 text-sm text-[#49352E] focus:border-[#B75C78] focus:ring-[#B75C78]';

    $labelClass = 'block text-sm font-semibold text-[#49352E]';

    $terms = old(
        'terms_text',
        $isEdit ? implode("\n", $promo->terms ?? []) : ''
    );
@endphp

<form
    action="{{ $isEdit ? route('admin.promos.update', $promo) : route('admin.promos.store') }}"
    method="POST"
    enctype="multipart/form-data"
    class="space-y-6"
>
    @csrf

    @if ($isEdit)
        @method('PUT')
    @endif

    {{-- VALIDASI --}}
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">
            <p class="font-semibold text-red-700">
                Periksa kembali data promo.
            </p>

            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- INFORMASI PROMO --}}
    <section class="rounded-2xl border border-rose-100 bg-white p-6 shadow-sm">

        <h2 class="text-lg font-bold text-[#49352E]">
            Informasi Promo
        </h2>

        <p class="mt-1 text-sm text-[#806D65]">
            Masukkan informasi utama promo yang akan ditampilkan kepada pelanggan.
        </p>

        <div class="mt-6 grid gap-5 md:grid-cols-2">

            <div class="md:col-span-2">
                <label for="title" class="{{ $labelClass }}">
                    Judul Promo <span class="text-red-500">*</span>
                </label>

                <input
                    id="title"
                    type="text"
                    name="title"
                    value="{{ old('title', $promo->title ?? '') }}"
                    placeholder="Contoh: Momen Pertama Lebih Manis"
                    maxlength="255"
                    required
                    class="{{ $inputClass }}"
                >
            </div>

            <div>
                <label for="badge" class="{{ $labelClass }}">
                    Label Promo
                </label>

                <input
                    id="badge"
                    type="text"
                    name="badge"
                    value="{{ old('badge', $promo->badge ?? '') }}"
                    placeholder="Contoh: Promo Spesial"
                    maxlength="255"
                    class="{{ $inputClass }}"
                >
            </div>

            <div>
                <label for="code" class="{{ $labelClass }}">
                    Kode Promo <span class="text-red-500">*</span>
                </label>

                <input
                    id="code"
                    type="text"
                    name="code"
                    value="{{ old('code', $promo->code ?? '') }}"
                    placeholder="Contoh: MANIS10"
                    maxlength="50"
                    required
                    class="{{ $inputClass }} uppercase"
                >

                <p class="mt-2 text-xs text-[#806D65]">
                    Gunakan huruf, angka, tanda hubung, atau underscore.
                </p>
            </div>

            <div class="md:col-span-2">
                <label for="description" class="{{ $labelClass }}">
                    Deskripsi Promo <span class="text-red-500">*</span>
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    required
                    maxlength="5000"
                    class="{{ $inputClass }} resize-none"
                    placeholder="Jelaskan penawaran promo kepada pelanggan..."
                >{{ old('description', $promo->description ?? '') }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label for="image" class="{{ $labelClass }}">
                    Banner Promo
                </label>

                @if ($isEdit && $promo->image)
                    <div class="mt-3">
                        <img
                            src="{{ asset('storage/' . $promo->image) }}"
                            alt="{{ $promo->title }}"
                            class="h-44 w-full max-w-sm rounded-xl object-cover"
                        >
                        <p class="mt-2 text-xs text-[#806D65]">
                            Banner saat ini. Unggah foto baru untuk menggantinya.
                        </p>
                    </div>
                @endif

                <input
                    id="image"
                    type="file"
                    name="image"
                    accept="image/jpeg,image/png,image/webp"
                    class="{{ $inputClass }}"
                >

                <p class="mt-2 text-xs text-[#806D65]">
                    Format JPG, PNG, atau WebP. Maksimal 2 MB.
                </p>
            </div>
        </div>
    </section>

    {{-- PENGATURAN DISKON --}}
    <section class="rounded-2xl border border-rose-100 bg-white p-6 shadow-sm">

        <h2 class="text-lg font-bold text-[#49352E]">
            Pengaturan Diskon
        </h2>

        <p class="mt-1 text-sm text-[#806D65]">
            Tentukan nilai potongan dan syarat pembelian.
        </p>

        <div class="mt-6 grid gap-5 md:grid-cols-2">

            <div>
                <label for="discount_type" class="{{ $labelClass }}">
                    Jenis Diskon <span class="text-red-500">*</span>
                </label>

                <select
                    id="discount_type"
                    name="discount_type"
                    required
                    class="{{ $inputClass }}"
                >
                    <option
                        value="percentage"
                        @selected(old('discount_type', $promo->discount_type ?? 'percentage') === 'percentage')
                    >
                        Persentase (%)
                    </option>

                    <option
                        value="fixed"
                        @selected(old('discount_type', $promo->discount_type ?? '') === 'fixed')
                    >
                        Nominal (Rp)
                    </option>
                </select>
            </div>

            <div>
                <label for="discount_value" class="{{ $labelClass }}">
                    Nilai Diskon <span class="text-red-500">*</span>
                </label>

                <input
                    id="discount_value"
                    type="number"
                    name="discount_value"
                    min="0.01"
                    step="0.01"
                    required
                    value="{{ old('discount_value', $promo->discount_value ?? '') }}"
                    placeholder="Contoh: 10"
                    class="{{ $inputClass }}"
                >
            </div>

            <div>
                <label for="max_discount" class="{{ $labelClass }}">
                    Maksimal Potongan (Rp)
                </label>

                <input
                    id="max_discount"
                    type="number"
                    name="max_discount"
                    min="0"
                    step="0.01"
                    value="{{ old('max_discount', $promo->max_discount ?? '') }}"
                    placeholder="Contoh: 50000"
                    class="{{ $inputClass }}"
                >
            </div>

            <div>
                <label for="min_purchase" class="{{ $labelClass }}">
                    Minimal Pembelian (Rp) <span class="text-red-500">*</span>
                </label>

                <input
                    id="min_purchase"
                    type="number"
                    name="min_purchase"
                    min="0"
                    step="0.01"
                    required
                    value="{{ old('min_purchase', $promo->min_purchase ?? 0) }}"
                    class="{{ $inputClass }}"
                >
            </div>

            <div>
                <label for="usage_limit" class="{{ $labelClass }}">
                    Batas Penggunaan
                </label>

                <input
                    id="usage_limit"
                    type="number"
                    name="usage_limit"
                    min="1"
                    value="{{ old('usage_limit', $promo->usage_limit ?? '') }}"
                    placeholder="Kosongkan jika tanpa batas"
                    class="{{ $inputClass }}"
                >
            </div>
        </div>
    </section>

    {{-- PERIODE --}}
    <section class="rounded-2xl border border-rose-100 bg-white p-6 shadow-sm">

        <h2 class="text-lg font-bold text-[#49352E]">
            Periode & Ketentuan
        </h2>

        <div class="mt-6 grid gap-5 md:grid-cols-2">

            <div>
                <label for="starts_at" class="{{ $labelClass }}">
                    Tanggal Mulai <span class="text-red-500">*</span>
                </label>

                <input
                    id="starts_at"
                    type="datetime-local"
                    name="starts_at"
                    required
                    value="{{ old('starts_at', $isEdit ? $promo->starts_at->format('Y-m-d\TH:i') : '') }}"
                    class="{{ $inputClass }}"
                >
            </div>

            <div>
                <label for="ends_at" class="{{ $labelClass }}">
                    Tanggal Berakhir <span class="text-red-500">*</span>
                </label>

                <input
                    id="ends_at"
                    type="datetime-local"
                    name="ends_at"
                    required
                    value="{{ old('ends_at', $isEdit ? $promo->ends_at->format('Y-m-d\TH:i') : '') }}"
                    class="{{ $inputClass }}"
                >
            </div>

            <div class="md:col-span-2">
                <label for="terms_text" class="{{ $labelClass }}">
                    Syarat dan Ketentuan
                </label>

                <textarea
                    id="terms_text"
                    name="terms_text"
                    rows="5"
                    maxlength="10000"
                    class="{{ $inputClass }} resize-none"
                    placeholder="Minimal belanja Rp250.000&#10;Hanya berlaku untuk produk reguler&#10;Tidak dapat digabung dengan promo lain"
                >{{ $terms }}</textarea>

                <p class="mt-2 text-xs text-[#806D65]">
                    Pisahkan setiap ketentuan menggunakan baris baru.
                </p>
            </div>

            <div class="md:col-span-2">
                <div class="space-y-4 rounded-xl bg-[#FFF7F9] p-5">

                    <label class="flex cursor-pointer items-center gap-3">
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            @checked(old('is_active', $promo->is_active ?? true))
                            class="rounded border-rose-300 text-[#B75C78] focus:ring-[#B75C78]"
                        >

                        <span class="text-sm font-medium text-[#49352E]">
                            Aktifkan Promo
                        </span>
                    </label>

                    <label class="flex cursor-pointer items-center gap-3">
                        <input
                            type="checkbox"
                            name="first_order_only"
                            value="1"
                            @checked(old('first_order_only', $promo->first_order_only ?? false))
                            class="rounded border-rose-300 text-[#B75C78] focus:ring-[#B75C78]"
                        >

                        <span class="text-sm font-medium text-[#49352E]">
                            Khusus Pesanan Pertama
                        </span>
                    </label>
                </div>
            </div>
        </div>
    </section>

    {{-- TOMBOL --}}
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

        <a
            href="{{ route('admin.promos.index') }}"
            class="rounded-xl border border-rose-200 bg-white px-6 py-3 text-center text-sm font-semibold text-[#806D65] transition hover:bg-rose-50"
        >
            Batal
        </a>

        <button
            type="submit"
            class="rounded-xl bg-[#B75C78] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#9F4866]"
        >
            {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Promo' }}
        </button>

    </div>
</form>
