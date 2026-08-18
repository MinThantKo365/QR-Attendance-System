<?php

namespace App\Mail;

use App\Models\Invitation;
use App\Support\QrCodePng;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitationQrMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Invitation $invitation)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your QR Attendance Invitation',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invitation-qr',
            with: [
                'invitation' => $this->invitation,
                'qrPng' => $this->qrPng(),
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->qrPng(), 'invitation-qr.png')
                ->withMime('image/png'),
        ];
    }

    protected function qrPng(): string
    {
        return QrCodePng::generate($this->invitation->invite_id);
    }
}
