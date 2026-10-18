<?php

return [
    'name' => 'Early Cake',

    // Ganti dengan nomor WhatsApp resmi Early Cake.
    // Format internasional tanpa +, spasi, atau tanda hubung.
    'whatsapp_number' => env('EARLY_CAKE_WHATSAPP', '087722619655'),

    'whatsapp_display' => env(
        'EARLY_CAKE_WHATSAPP_DISPLAY',
        '0877-2261-9655'
    ),

    'whatsapp_message' => env(
        'EARLY_CAKE_WHATSAPP_MESSAGE',
        'Halo Early Cake, saya ingin bertanya.'
    ),

    'email' => env('EARLY_CAKE_EMAIL', 'litarr24@gmail.com'),

    // Alamat email admin penerima pesan formulir kontak.
    'inbox' => env('EARLY_CAKE_CONTACT_INBOX', 'litarr24@gmail.com'),

    'address' => env(
    'EARLY_CAKE_ADDRESS',
    'Jl. Manyar Rejo III No. 4, Kota Surabaya, Jawa Timur 60119'),

    'short_address' => env(
    'EARLY_CAKE_SHORT_ADDRESS',
    'Jl. Manyar Rejo III No. 4 Surabaya'),

    'hours' => env(
    'EARLY_CAKE_HOURS',
    'Setiap Hari · 10.00–21.00 WIB'),

    'hours_note' => env(
    'EARLY_CAKE_HOURS_NOTE',
    'Jam pelayanan dapat berubah saat hari libur.'),

    'maps_url' => env(
    'EARLY_CAKE_MAPS_URL',
    'https://maps.app.goo.gl/FQ17c1Roq673wbzS9?g_st=ac'),

];
