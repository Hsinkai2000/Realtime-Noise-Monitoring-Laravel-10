<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        if ($this->data['type'] == 'leq') {
            return new Envelope(
                subject: "Leq{$this->data['leq_type']} Alert",
            );
        } else if ($this->data['type'] == 'dose') {
            return new Envelope(
                subject: "Dose >{$this->data['dose_limit']}% Alert",
            );
        } else if ($this->data['type'] == 'missing_data') {
            return new Envelope(
                subject: "Data not recorded",
            );
        } else if ($this->data['type'] == 'noon_check') {
            return new Envelope(
                subject: "Noise Device Dose Daily Noon Check for SN:" . $this->data["serial_number"]
            );
        }
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        if ($this->data['type'] == 'leq') {
            return new Content(
                view: 'emails.mail_leq_limit_exceeded',
            );
        } else if ($this->data['type'] == 'dose') {
            return new Content(
                view: "emails.mail_dose_limit_exceeded",
            );
        } else if ($this->data['type'] == 'missing_data') {
            return new Content(
                view: "emails.mail_missing_data_60_mins"
            );
        } else if ($this->data['type'] == 'noon_check') {
            return new Content(
                view: "emails.mail_noon_check"
            );
        }
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
