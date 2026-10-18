<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactMessageRequest;
use App\Mail\ContactMessageMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
   {
    	$store = config('early_cake');

    	$whatsappUrl = 'https://wa.me/'
        	. $store['whatsapp_number']
        	. '?text=' . urlencode($store['whatsapp_message']);

    	return view('contact', compact('store', 'whatsappUrl'));
    }

    public function send(ContactMessageRequest $request)
    {
        $validated = $request->validated();

        // Kirim pesan ke email admin Early Cake.
        Mail::to(config('early_cake.inbox'))
            ->send(new ContactMessageMail($validated));

        // Siapkan pesan WhatsApp dengan data yang sama.
        $phone = config('early_cake.whatsapp_number');

        $message = "Halo Early Cake!\n\n"
            . "Nama: {$validated['name']}\n"
            . "Email: {$validated['email']}\n"
            . "Pesan: {$validated['message']}";

        // Arahkan pengunjung ke WhatsApp.
        return redirect()->away(
            'https://wa.me/' . $phone . '?text=' . urlencode($message)
        );
    }
}
