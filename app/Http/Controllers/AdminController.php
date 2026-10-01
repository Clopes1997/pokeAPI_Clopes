<?php

namespace App\Http\Controllers;

use App\Services\Admin\AdminUserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(private AdminUserService $adminUserService)
    {
    }

    public function users(): View
    {
        Gate::authorize('admin');

        $users = $this->adminUserService->getUsers();
        $roles = $this->adminUserService->getRoles();

        return view('admin.users', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function updateUserRole(Request $request, int $userId): RedirectResponse
    {
        Gate::authorize('admin');

        $roleId = (int) $request->input('role_id');

        $this->adminUserService->updateUserRole($userId, $roleId);

        return redirect()->route('admin.users')->with('success', 'Função do usuário atualizada com sucesso');
    }
}

