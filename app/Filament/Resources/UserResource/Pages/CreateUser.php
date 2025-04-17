<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\UserRegistrationToken;
use App\Notifications\UserRegistrationInvite;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Notification;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function beforeCreate(): void
    {
        $registrationToken = UserRegistrationToken::createToken(
            email: $this->data['email'],
            name: $this->data['name']
        );

        try {
            // Log before sending notification
            \Illuminate\Support\Facades\Log::info('Sending registration invitation', [
                'email' => $this->data['email'],
                'name' => $this->data['name'],
                'token' => $registrationToken->token
            ]);

            // Try direct mail sending first as a test
            \Illuminate\Support\Facades\Mail::send('emails.user-registration-invite', [
                'name' => $this->data['name'],
                'url' => url("/user-details/{$registrationToken->token}")
            ], function ($message) {
                $message->to($this->data['email'])
                        ->subject('Complete Your Registration');
            });

            // Also try the notification system
            Notification::route('mail', $this->data['email'])
                ->notify(new UserRegistrationInvite($registrationToken->token, $this->data['name']));

            $this->halt();
            $this->notify('success', 'Registration invitation has been sent to the email address.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send registration invitation', [
                'error' => $e->getMessage(),
                'email' => $this->data['email']
            ]);
            $this->halt();
            $this->notify('danger', 'Failed to send registration invitation: ' . $e->getMessage());
        }
    }
}
