<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserRegistrationToken extends Model
{
    protected $fillable = [
        'email',
        'token',
        'name',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public static function generateToken(): string
    {
        return Str::random(64);
    }

    public static function createToken(string $email, string $name): self
    {
        return static::create([
            'email' => $email,
            'name' => $name,
            'token' => static::generateToken(),
            'expires_at' => Carbon::now()->addDays(7),
        ]);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return !$this->isExpired();
    }
}