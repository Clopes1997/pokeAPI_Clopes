<?php

namespace App\Services\Pokemon;

use App\Models\Pokemon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PokemonDetailService
{
    public function __construct(
        private PokemonImportService $importService
    ) {
    }

    public function getById(int $id): Pokemon
    {
        $pokemon = Pokemon::with(['types', 'moves', 'abilities'])
            ->where('api_id', $id)
            ->first();

        if ($pokemon) {
            return $pokemon;
        }

        $this->importService->import($id);
        
        $pokemon = Pokemon::with(['types', 'moves', 'abilities'])
            ->where('api_id', $id)
            ->first();
        
        if (!$pokemon) {
            throw new ModelNotFoundException('Pokémon não encontrado');
        }

        return $pokemon;
    }
}

