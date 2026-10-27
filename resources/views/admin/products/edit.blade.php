@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('breadcrumb', 'Edit Produk')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <div>
        <a href="{{ route('admin.products.index') }}"
           class="text-sm font-medium text-[#a85e67] hover:underline">
            ← Kembali ke Manajemen Produk
        </a>
        <h1 class="mt-3 text-2xl font-bold text-[#49352e]">
            Edit Produk
        </h1>
        <p class="mt-2 text-sm text-[#806d65]">
            Perbarui informasi {{ $product->name }}.
        </p>
    </div>

    @include('admin.products.partials.form')
</div>
@endsection