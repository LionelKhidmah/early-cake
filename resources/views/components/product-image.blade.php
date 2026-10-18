@props(['product'])

@if ($product->image)
    <img src="{{ asset($product->image)}}" alt="{{ $product->name }}"
    {{ $attributes->merge([
        'class' => 'size-full object-cover',
        'loading' => 'lazy',
    ]) }} >

@else
    <div class="flex size-full min-h-40 items-center justify-center bg-rose-50"
        role="img" aria-label="Foto {{ $product->name }} belum tersedia" >

    <span class="text-5xl" aria-hidden="true">🎂</span>

    </div>
@endif