<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\LaptopRepair;

class RepairDetailsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $repair;

    /**
     * Create a new message instance.
     */
    public function __construct(LaptopRepair $repair)
    {
        $this->repair = $repair;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Repair Details - ' . $this->repair->note_number,
            from: env('MAIL_FROM_ADDRESS', 'jayathissa1999max@gmail.com'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.repair-details',
            with: [
                'repair' => $this->repair,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}