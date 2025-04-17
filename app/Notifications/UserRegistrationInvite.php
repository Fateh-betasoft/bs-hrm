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
            // Get the email address from the notifiable object
            $email = $notifiable->routes['mail'] ?? null;
            
            // Log the attempt for debugging
            Log::info('Attempting to send registration invite email', [
                'email' => $email,
                'name' => $this->name,
                'token' => $this->token
            ]);
            
            // Use direct Mail facade as fallback if needed
            if (config('app.debug')) {
                \Illuminate\Support\Facades\Mail::send('emails.user-registration-invite', [
                    'name' => $this->name,
                    'url' => url("/user-details/{$this->token}")
                ], function ($message) use ($email) {
                    $message->to($email)
                            ->subject('Complete Your Registration');
                });
                
                Log::info('Direct mail sent successfully', ['email' => $email]);
            }
            
            return (new MailMessage)
                ->view('emails.user-registration-invite', [
                    'name' => $this->name,
                    'url' => url("/user-details/{$this->token}")
                ]);
        } catch (\Exception $e) {
            Log::error('Failed to send registration invite email', [
                'error' => $e->getMessage(),
                'email' => $notifiable->routes['mail'] ?? 'unknown',
                'name' => $this->name
            ]);
            throw $e;
        }
    }
}