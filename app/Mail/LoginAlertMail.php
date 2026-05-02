<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $userName,
        public readonly string $ip,
        public readonly string $attemptTime,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Security Alert – Multiple Failed Login Attempts');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.login-alert');
    }
}
