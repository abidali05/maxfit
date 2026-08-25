<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProfileReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $missingFields;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, array $missingFields = [])
    {
        $this->user = $user;
        $this->missingFields = $missingFields;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Action Required: Complete Your Profile Registration',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.profile_reminder',
        );
    }
}
