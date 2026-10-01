<?php

namespace App\Services\Pokemon;

use App\Models\Pokemon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PokemonListingService
{
    public function getPaginatedList(?string $search = null, ?string $type = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = Pokemon::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($type) {
            $query->whereHas('types', function ($q) use ($type) {
                $q->where('name', $type);
            });
        }

        return $query->paginate($perPage);
    }
}

