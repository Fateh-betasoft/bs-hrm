<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Extract first name (assuming first word is the first name)
        $firstName = explode(' ', $data['name'])[0];

        // Generate email
        $data['email'] = strtolower($firstName) . '.betasoft@outlook.com';

        // Generate password
        $password = $firstName . '@' . $data['position'] . '#' . Carbon::now()->year;
        $data['password'] = Hash::make($password);

        // Remove position from data as it's not a DB column
        unset($data['position']);

        return $data;
    }
}
