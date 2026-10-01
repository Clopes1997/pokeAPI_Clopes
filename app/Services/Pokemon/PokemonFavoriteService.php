<?php

namespace App\Services\Pokemon;

use App\Models\Pokemon;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class PokemonFavoriteService
{
    public function addFavorite(User $user, int $apiId): void
    {
        $pokemon = Pokemon::query()->where('api_id', $apiId)->first();

        if (!$pokemon) {
            throw new ModelNotFoundException('Pokémon não encontrado');
        }

        $pokemonId = (int) $pokemon->getKey();

        if ($user->favorites()->where('pokemon_id', $pokemonId)->exists()) {
            throw ValidationException::withMessages([
                'pokemon' => ['Este Pokémon já está nos seus favoritos'],
            ]);
        }

        $user->favorites()->attach($pokemonId);
    }

    public function removeFavorite(User $user, int $apiId): void
    {
        $pokemon = Pokemon::query()->where('api_id', $apiId)->first();

        if (!$pokemon) {
            throw new ModelNotFoundException('Pokémon não encontrado');
        }

        $pokemonId = (int) $pokemon->getKey();
        $user->favorites()->detach($pokemonId);
    }

    public function getUserFavorites(User $user): Collection
    {
        return $user->favorites()->get();
    }

    public function getFavoriteIds(User $user): array
    {
        return $user->favorites()
            ->pluck('pokemon_id')
            ->map(fn (int $pokemonId) => $pokemonId)
            ->all();
    }

    public function isFavorite(User $user, Pokemon $pokemon): bool
    {
        return $user->favorites()
            ->where('pokemon_id', $pokemon->getKey())
            ->exists();
    }
}

