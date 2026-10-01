<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Password;

class PasswordResetService
{
    public function sendResetLink(string $email): void
    {
        Password::sendResetLink(['email' => $email]);
    }
}

