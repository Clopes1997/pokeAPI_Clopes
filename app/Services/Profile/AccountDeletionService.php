<?php

namespace App\Services\Profile;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AccountDeletionService
{
    public function delete(Request $request, User $user): void
    {
        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function deleteResponse(Request $request): RedirectResponse|JsonResponse
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Conta excluída com sucesso!',
                'redirect' => url('/')
            ]);
        }

        return Redirect::to('/');
    }
}

