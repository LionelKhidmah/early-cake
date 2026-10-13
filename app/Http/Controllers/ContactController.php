<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        // Ganti dengan nomor WhatsApp resmi Early Cake
        $phone = '6281234567890';

        $message = "Halo Early Cake!\n\n"
            . "Nama: {$validated['name']}\n"
            . "Email: " . ($validated['email'] ?? '-') . "\n"
            . "Pesan: {$validated['message']}";

        return redirect()->away(
            'https://wa.me/' . $phone . '?text=' . urlencode($message)
        );
    }
}
