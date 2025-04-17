<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Mail\Envelope;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class UserRegistrationInvite extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected string $token,
        protected string $name
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function envelope(object $notifiable): Envelope
    {
        return new Envelope(
            subject: 'Complete Your Registration',
            tags: ['registration', 'invite'],
            metadata: [
                'token' => $this->token,
                'user_name' => $this->name
            ]
        );
    }

    public function toMail(object $notifiable): MailMessage
    {
        try {
            return (new MailMessage)
                ->view('emails.user-registration-invite', [
                    'name' => $this->name,
                    'url' => url("/user-details/{$this->token}")
                ]);
        } catch (\Exception $e) {
            Log::error('Failed to send registration invite email', [
                'error' => $e->getMessage(),
                'email' => $notifiable->routes['mail'],
                'name' => $this->name
            ]);
            throw $e;
        }
    }
}