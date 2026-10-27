@php
    $editing = isset($product) && $product->exists;
@endphp

<form
    action="{{ $editing
        ? route('admin.products.update', $product)
        : route('admin.products.store') }}"
    method="POST"
    enctype="multipart/form-data"
    class="space-y-6"
>
    @csrf
    @if ($editing)
        @method('PUT')
    @endif

    <div class="rounded-2xl border border-[#f0e4dc] bg-white p-5 shadow-sm sm:p-7">
        <h2 class="text-lg font-semibold text-[#49352e]">Informasi Produk</h2>
        <p class="mt-1 text-sm text-[#806d65]">
            Lengkapi informasi produk Early Cake.
        </p>

        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="name" class="mb-2 block text-sm font-medium text-[#49352e]">
                    Nama Produk *
                </label>
                <input id="name" name="name" required
                       value="{{ old('name', $product->name ?? '') }}"
                       class="w-full rounded-xl border border-[#eadbd2] px-4 py-3 text-sm focus:border-[#bd7b83] focus:outline-none">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category_id" class="mb-2 block text-sm font-medium text-[#49352e]">
                    Kategori *
                </label>
                <select id="category_id" name="category_id" required
                        class="w-full rounded-xl border border-[#eadbd2] bg-white px-4 py-3 text-sm focus:border-[#bd7b83] focus:outline-none">
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            @selected((string) old('category_id', $product->category_id ?? '') === (string) $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div class="mt-3 flex flex-wrap items-center gap-4">
    <button type="button"
            id="toggle-category"
            class="text-sm font-semibold text-[#a85e67] hover:underline">
        + Tambah Kategori Baru
    </button>

    <a href="{{ route('admin.categories.index') }}"
       class="inline-flex items-center gap-2 rounded-lg border border-[#eadbd2] bg-white px-3 py-2 text-sm font-medium text-[#49352e] transition hover:bg-[#fff8f4]">
        Kelola Kategori
    </a>
</div>

<div id="new-category-box"
     class="mt-3 hidden rounded-xl border border-[#eadbd2] bg-[#fff8f4] p-4">

    <label for="new-category-name"
           class="mb-2 block text-sm font-medium text-[#49352e]">
        Nama Kategori Baru
    </label>

    <input type="text"
           id="new-category-name"
           maxlength="255"
           placeholder="Contoh: Cheesecake"
           class="w-full rounded-xl border border-[#eadbd2] bg-white px-4 py-3 text-sm focus:outline-none">

    <p id="category-message"
       role="status"
       aria-live="polite"
       class="mt-2 hidden text-sm"></p>

    <div class="mt-3 flex gap-2">
        <button type="button"
                id="save-category"
                class="rounded-xl bg-[#a85e67] px-4 py-2 text-sm font-semibold text-white hover:bg-[#914b54]">
            Simpan Kategori
        </button>

        <button type="button"
                id="cancel-category"
                class="rounded-xl border border-[#eadbd2] px-4 py-2 text-sm text-[#49352e]">
            Batal
        </button>
    </div>
</div>
            </div>

            <div>
                <label for="price" class="mb-2 block text-sm font-medium text-[#49352e]">
                    Harga (Rp) *
                </label>
                <input id="price" name="price" type="number" min="0" step="1" required
                       value="{{ old('price', $product->price ?? '') }}"
                       class="w-full rounded-xl border border-[#eadbd2] px-4 py-3 text-sm focus:border-[#bd7b83] focus:outline-none">
                @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="stock" class="mb-2 block text-sm font-medium text-[#49352e]">
                    Stok *
                </label>
                <input id="stock" name="stock" type="number" min="0" required
                       value="{{ old('stock', $product->stock ?? 0) }}"
                       class="w-full rounded-xl border border-[#eadbd2] px-4 py-3 text-sm focus:border-[#bd7b83] focus:outline-none">
                @error('stock')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="description" class="mb-2 block text-sm font-medium text-[#49352e]">
                    Deskripsi
                </label>
                <textarea id="description" name="description" rows="4"
                          class="w-full rounded-xl border border-[#eadbd2] px-4 py-3 text-sm focus:border-[#bd7b83] focus:outline-none"
                          placeholder="Jelaskan rasa, ukuran, atau detail produk...">{{ old('description', $product->description ?? '') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="image" class="mb-2 block text-sm font-medium text-[#49352e]">
                    Foto Produk
                </label>

                @if ($editing && $product->image)
                    <img src="{{ asset('storage/' . $product->image) }}"
                         alt="{{ $product->name }}"
                         class="mb-3 h-32 w-32 rounded-xl object-cover">

                    <p class="mb-3 text-xs text-[#806d65]">
                        Pilih foto baru jika ingin mengganti foto saat ini.
                    </p>
                @endif

                <input id="image" name="image" type="file" accept="image/*"
                       class="block w-full rounded-xl border border-[#eadbd2] p-3 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-[#f8e8e8] file:px-3 file:py-2 file:text-sm file:font-semibold file:text-[#914b54]">
                <p class="mt-2 text-xs text-[#806d65]">
                    Format gambar yang didukung oleh validasi Laravel, maksimal 2 MB.
                </p>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="flex items-center gap-3 rounded-xl bg-[#fff8f4] p-4">
                    <input type="hidden" name="is_available" value="0">
                    <input type="checkbox" name="is_available" value="1"
                           @checked((bool) old('is_available', $product->is_available ?? true))
                           class="h-5 w-5 rounded border-[#eadbd2] text-[#a85e67] focus:ring-[#bd7b83]">
                    <span>
                        <span class="block text-sm font-semibold text-[#49352e]">
                            Produk tersedia
                        </span>
                        <span class="mt-1 block text-xs text-[#806d65]">
                            Aktifkan agar produk dapat ditawarkan kepada pelanggan.
                        </span>
                    </span>
                </label>
                @error('is_available')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('admin.products.index') }}"
           class="rounded-xl border border-[#eadbd2] px-5 py-3 text-center text-sm font-semibold text-[#49352e] hover:bg-[#fff8f4]">
            Batal
        </a>

        <button type="submit"
                class="rounded-xl bg-[#a85e67] px-5 py-3 text-sm font-semibold text-white hover:bg-[#914b54]">
            {{ $editing ? 'Simpan Perubahan' : 'Simpan Produk' }}
        </button>
    </div>
</form>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('toggle-category');
    const box = document.getElementById('new-category-box');
    const input = document.getElementById('new-category-name');
    const save = document.getElementById('save-category');
    const cancel = document.getElementById('cancel-category');
    const select = document.getElementById('category_id');
    const message = document.getElementById('category-message');

    function showMessage(text, isError = true) {
        message.textContent = text;
        message.classList.remove('hidden');
        message.classList.toggle('text-red-600', isError);
        message.classList.toggle('text-green-700', !isError);
    }

    toggle.addEventListener('click', function () {
        box.classList.toggle('hidden');

        if (!box.classList.contains('hidden')) {
            input.focus();
        }
    });

    cancel.addEventListener('click', function () {
        box.classList.add('hidden');
        input.value = '';
        message.classList.add('hidden');
    });

    save.addEventListener('click', async function () {
        const name = input.value.trim();

        if (!name) {
            showMessage('Nama kategori wajib diisi.');
            return;
        }

        save.disabled = true;
        save.textContent = 'Menyimpan...';
        message.classList.add('hidden');

        try {
            const response = await fetch(
                @json(route('admin.categories.quick-store')),
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token())
                    },
                    body: JSON.stringify({ name: name })
                }
            );

            const data = await response.json();

            if (!response.ok) {
                const error = data.errors?.name?.[0]
                    || data.message
                    || 'Gagal menyimpan kategori.';

                throw new Error(error);
            }

            const option = new Option(
                data.category.name,
                data.category.id,
                true,
                true
            );

            select.add(option);
            select.dispatchEvent(new Event('change', {
                bubbles: true
            }));

            input.value = '';
            box.classList.add('hidden');

        } catch (error) {
            showMessage(error.message);
        } finally {
            save.disabled = false;
            save.textContent = 'Simpan Kategori';
        }
    });
});
</script>