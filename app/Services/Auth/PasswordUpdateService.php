<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordUpdateService
{
    public function resetPassword(string $email, string $password, string $passwordConfirmation, string $token): RedirectResponse
    {
        $status = Password::reset(
            [
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $passwordConfirmation,
                'token' => $token,
            ],
            function (User $user) use ($password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Sua senha foi redefinida.');
        }

        if ($status == Password::INVALID_TOKEN) {
            return back()->withInput(['email' => $email])
                        ->withErrors(['email' => 'Este token de redefinição de senha é inválido.']);
        }

        if ($status == Password::INVALID_USER) {
            return back()->withInput(['email' => $email])
                        ->withErrors(['email' => 'Não encontramos um usuário com esse endereço de e-mail.']);
        }

        return back()->withInput(['email' => $email])
                    ->withErrors(['email' => 'Não foi possível redefinir a senha.']);
    }

    public function updatePassword(User $user, string $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    public function updatePasswordResponse(\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Senha atualizada com sucesso!',
            ]);
        }

        return back()->with('status', 'password-updated');
    }
}
