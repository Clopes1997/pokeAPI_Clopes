<?php

namespace App\Services\Profile;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ProfileUpdateService
{
    public function update(User $user, array $data): void
    {
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
    }

    public function updateResponse(Request $request, User $user): RedirectResponse|JsonResponse
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Perfil atualizado com sucesso!',
                'name' => $user->name,
                'email' => $user->email,
            ]);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
}

