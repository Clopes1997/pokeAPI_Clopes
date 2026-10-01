<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\Profile\AccountDeletionService;
use App\Services\Profile\ProfileUpdateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private ProfileUpdateService $profileUpdateService,
        private AccountDeletionService $accountDeletionService
    ) {
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();

        $this->profileUpdateService->update($user, $request->validated());

        return $this->profileUpdateService->updateResponse($request, $user);
    }

    public function destroy(Request $request): RedirectResponse|JsonResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        $this->accountDeletionService->delete($request, $user);

        return $this->accountDeletionService->deleteResponse($request);
    }
}
