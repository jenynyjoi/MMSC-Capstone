<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FinanceReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $studentName,
        public string $reminderType,   // overdue | upcoming | general
        public string $balance,
        public string $schoolYear,
        public ?string $note = null,
    ) {}

    public function envelope(): Envelope
    {
        $subject = match($this->reminderType) {
            'overdue'  => "Overdue Payment Notice — MMSC SY {$this->schoolYear}",
            'upcoming' => "Upcoming Payment Reminder — MMSC SY {$this->schoolYear}",
            default    => "Finance Account Reminder — MMSC SY {$this->schoolYear}",
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.finance-reminder',
            with: [
                'studentName'  => $this->studentName,
                'reminderType' => $this->reminderType,
                'balance'      => $this->balance,
                'schoolYear'   => $this->schoolYear,
                'note'         => $this->note,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
