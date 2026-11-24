<?php

namespace Paymenter\Extensions\Others\MagicLink;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class MagicLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $loginUrl;

    public function __construct(User $user, string $loginUrl)
    {
        $this->user = $user;
        $this->loginUrl = $loginUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Magic Link Login',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'magiclink::email',
            with: [
                'user' => $this->user,
                'loginUrl' => $this->loginUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
