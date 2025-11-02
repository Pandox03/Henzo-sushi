<?php

namespace App\Mail;

use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PromoCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $promoCode;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(PromoCode $promoCode, User $user)
    {
        $this->promoCode = $promoCode;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = '🎉 Special Offer: ' . $this->promoCode->name;
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.promo-code',
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