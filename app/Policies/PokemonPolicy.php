<?php

namespace App\Policies;

use App\Models\Pokemon;
use App\Models\User;

class PokemonPolicy
{
    private function hasRole(User $user, string $roleName): bool
    {
        return $user->roles()->where('name', $roleName)->exists();
    }

    private function canEdit(User $user): bool
    {
        return $this->hasRole($user, 'editor') || $this->hasRole($user, 'admin');
    }

    public function viewAny(User $user): bool
    {
        return $this->hasRole($user, 'viewer') || $this->hasRole($user, 'editor') || $this->hasRole($user, 'admin');
    }

    public function view(User $user, Pokemon $pokemon): bool
    {
        return $this->hasRole($user, 'viewer') || $this->hasRole($user, 'editor') || $this->hasRole($user, 'admin');
    }

    public function import(User $user): bool
    {
        return $this->canEdit($user);
    }

    public function favorite(User $user, Pokemon $pokemon): bool
    {
        return $this->canEdit($user);
    }

    public function unfavorite(User $user, Pokemon $pokemon): bool
    {
        return $this->canEdit($user);
    }

    public function viewFavorites(User $user): bool
    {
        return $this->canEdit($user);
    }

    public function delete(User $user, Pokemon $pokemon): bool
    {
        return $this->hasRole($user, 'admin');
    }
}
