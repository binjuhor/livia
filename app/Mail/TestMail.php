<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $message;

    public function __construct(string $message = '')
    {
        $this->message = $message;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Response from Livia'
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.tests.test-mail'
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
