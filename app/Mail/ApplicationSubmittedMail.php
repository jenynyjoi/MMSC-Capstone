<?php

namespace App\Mail;

use App\Models\Application;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Application $application) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Application Received — ' . $this->application->reference_number . ' | My Messiah School of Cavite',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.application-submitted',
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('online-registration.pdf', ['application' => $this->application])
            ->setPaper('a4', 'portrait');

        return [
            Attachment::fromData(
                fn () => $pdf->output(),
                'Application-' . $this->application->reference_number . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}
