<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactMessageMail extends Mailable
{
    /**
     * @param array{name: string, email: string, message: string} $data
     */
    public function __construct(public array $data)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [
                new Address(
                    $this->data['email'],
                    $this->data['name']
                ),
            ],
            subject: 'Pesan baru dari ' . $this->data['name'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact'
        );
    }
}
