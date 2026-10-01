<?php

namespace App\Services\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class AdminUserService
{
    public function getUsers(): Collection
    {
        return User::with('roles')->get();
    }

    public function getRoles(): Collection
    {
        return Role::all();
    }

    public function updateUserRole(int $userId, int $roleId): void
    {
        $user = User::findOrFail($userId);
        $user->roles()->sync([$roleId]);
    }
}

