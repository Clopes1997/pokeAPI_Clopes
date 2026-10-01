<?php

namespace App\Services\Pokemon;

use App\ApiClients\PokemonApiClient;
use App\Models\Ability;
use App\Models\Move;
use App\Models\Pokemon;
use App\Models\Type;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PokemonImportService
{
    public function __construct(
        private PokemonApiClient $apiClient
    ) {
    }

    public function import(int $apiId): void
    {
        $this->importSingle($apiId);
    }

    public function importSingle(int $apiId): void
    {
        $pokemon = Pokemon::withTrashed()->where('api_id', $apiId)->first();

        if ($pokemon) {
            if ($pokemon->trashed()) {
                $pokemon->restore();
                $this->importPokemon($apiId, $pokemon);
                return;
            }

            throw ValidationException::withMessages([
                'pokemon' => ['Este Pokémon já foi importado.'],
            ]);
        }

        $this->importPokemon($apiId);
    }

    public function importInterval(int $startId, int $endId): array
    {
        $allIdsInRange = range($startId, $endId);
        $existingIds = Pokemon::whereBetween('api_id', [$startId, $endId])
            ->pluck('api_id')
            ->toArray();

        $softDeletedIds = Pokemon::withTrashed()
            ->whereBetween('api_id', [$startId, $endId])
            ->whereNotNull('deleted_at')
            ->pluck('api_id')
            ->toArray();

        $idsToImport = array_diff($allIdsInRange, $existingIds);

        $allIdsAreExistingOrSoftDeleted = count($existingIds) + count($softDeletedIds) === count($allIdsInRange);

        if (empty($idsToImport) && $allIdsAreExistingOrSoftDeleted) {
            throw ValidationException::withMessages([
                'pokemon' => ['Todos os Pokémon deste intervalo já foram importados.'],
            ]);
        }

        $importedCount = 0;
        $alreadyExistedCount = count($existingIds);

        foreach ($idsToImport as $apiId) {
            try {
                $this->importPokemon($apiId);
                $importedCount++;
            } catch (\Exception $e) {
                Log::warning('Falha ao importar Pokémon no intervalo', [
                    'api_id' => $apiId,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return [
            'imported' => $importedCount,
            'already_existed' => $alreadyExistedCount,
        ];
    }

    public function importIncremental(): array
    {
        $maxImportedId = Pokemon::max('api_id') ?? 0;
        $startId = $maxImportedId + 1;
        $endId = $startId + 99;

        if ($startId > 1000) {
            throw ValidationException::withMessages([
                'pokemon' => ['Não há novos Pokémon disponíveis para importação.'],
            ]);
        }

        $importedCount = 0;
        $alreadyExistedCount = 0;
        $softDeletedCount = 0;
        $allIdsInRange = range($startId, $endId);

        for ($apiId = $startId; $apiId <= $endId; $apiId++) {
            try {
                $pokemon = Pokemon::withTrashed()->where('api_id', $apiId)->first();
                if ($pokemon) {
                    if ($pokemon->trashed()) {
                        $softDeletedCount++;
                        continue;
                    }
                    $alreadyExistedCount++;
                    continue;
                }

                $this->importPokemon($apiId);
                $importedCount++;
            } catch (\Exception $e) {
                Log::warning('Falha ao importar Pokémon incremental', [
                    'api_id' => $apiId,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $allIdsAreExistingOrSoftDeleted = count($allIdsInRange) === ($alreadyExistedCount + $softDeletedCount);

        if ($importedCount === 0 && $allIdsAreExistingOrSoftDeleted) {
            throw ValidationException::withMessages([
                'pokemon' => ['Todos os Pokémon deste intervalo já foram importados.'],
            ]);
        }

        if ($importedCount === 0 && $alreadyExistedCount === 0) {
            throw ValidationException::withMessages([
                'pokemon' => ['Não há novos Pokémon disponíveis para importação.'],
            ]);
        }

        return [
            'imported' => $importedCount,
            'already_existed' => $alreadyExistedCount,
        ];
    }

    private function importPokemon(int $apiId, ?Pokemon $existingPokemon = null): void
    {
        try {
            $data = $this->apiClient->getPokemon($apiId);
        } catch (\RuntimeException $e) {
            Log::error('Falha ao importar Pokémon: timeout', [
                'api_id' => $apiId,
                'error' => $e->getMessage(),
            ]);
            throw new HttpException(500, 'Erro ao comunicar com a API de Pokémon');
        }

        if (isset($data['detail'])) {
            if ($data['detail'] === 'Not found.') {
                throw new ModelNotFoundException('Pokémon não encontrado');
            }
            Log::error('Falha ao importar Pokémon: resposta da API', [
                'api_id' => $apiId,
                'detail' => $data['detail'],
            ]);
            throw new HttpException(500, 'Erro ao comunicar com a API de Pokémon');
        }

        DB::transaction(function () use ($data, $existingPokemon) {
            $sprite = $data['sprites']['front_default'] ?? null;

            if ($existingPokemon) {
                $existingPokemon->update([
                    'name' => $data['name'],
                    'height' => $data['height'],
                    'weight' => $data['weight'],
                    'sprite' => $sprite,
                ]);
                $pokemon = $existingPokemon;
            } else {
                $pokemon = Pokemon::create([
                    'api_id' => $data['id'],
                    'name' => $data['name'],
                    'height' => $data['height'],
                    'weight' => $data['weight'],
                    'sprite' => $sprite,
                ]);
            }

            $typeIds = [];
            foreach ($data['types'] ?? [] as $typeData) {
                $type = Type::firstOrCreate(['name' => $typeData['type']['name']]);
                $typeIds[] = $type->id;
            }
            $pokemon->types()->sync($typeIds);

            $moveIds = [];
            foreach ($data['moves'] ?? [] as $moveData) {
                $move = Move::firstOrCreate(['name' => $moveData['move']['name']]);
                $moveIds[] = $move->id;
            }
            $pokemon->moves()->sync($moveIds);

            $abilityIds = [];
            foreach ($data['abilities'] ?? [] as $abilityData) {
                $ability = Ability::firstOrCreate(['name' => $abilityData['ability']['name']]);
                $abilityIds[] = $ability->id;
            }
            $pokemon->abilities()->sync($abilityIds);
        });
    }
}

