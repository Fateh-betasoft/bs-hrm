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

        Notification::route('mail', $this->data['email'])
            ->notify(new UserRegistrationInvite($registrationToken->token, $this->data['name']));

        $this->halt();
        $this->notify('success', 'Registration invitation has been sent to the email address.');
    }
}
