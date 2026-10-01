<?php

use App\ApiClients\PokemonApiClient;
use App\Services\Pokemon\PokemonImportService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('pokemon:import {ids*}', function (PokemonImportService $importService) {
    $ids = collect($this->argument('ids'))
        ->map(fn ($id) => (int) $id)
        ->filter(fn ($id) => $id > 0)
        ->unique()
        ->values();

    if ($ids->isEmpty()) {
        $this->error('Informe pelo menos um ID válido.');
        return 1;
    }

    $ids->each(fn ($id) => importPokemon($this, $importService, $id));

    return 0;
})->purpose('Importa Pokémon da PokéAPI por IDs');

Artisan::command(
    'pokemon:import-auto {--limit=200} {--offset=0}',
    function (PokemonApiClient $apiClient, PokemonImportService $importService) {
        try {
            $list = $apiClient->listPokemon(
                (int) $this->option('limit'),
                (int) $this->option('offset')
            );
        } catch (\Throwable) {
            $this->error('Falha ao comunicar com a API de Pokémon.');
            return 1;
        }

        $results = collect($list['results'] ?? []);

        if ($results->isEmpty()) {
            $this->warn('Nenhum Pokémon retornado.');
            return 0;
        }

        $results
            ->map(fn ($item) => extractPokemonId($item['url'] ?? null))
            ->filter()
            ->each(fn ($id) => importPokemon($this, $importService, $id));

        return 0;
    }
)->purpose('Importa Pokémon automaticamente a partir da listagem da PokéAPI');

if (!function_exists('importPokemon')) {
    function importPokemon($command, PokemonImportService $service, int $id): void
    {
        try {
            $service->import($id);
            $command->info("Pokémon {$id} importado com sucesso.");
        } catch (ValidationException | ModelNotFoundException | HttpException $e) {
            $command->warn("ID {$id}: {$e->getMessage()}");
        } catch (\Throwable) {
            $command->warn("ID {$id}: erro inesperado.");
        }
    }
}

if (!function_exists('extractPokemonId')) {
    function extractPokemonId(?string $url): ?int
    {
        if (!$url) {
            return null;
        }

        if (preg_match('#/pokemon/(\d+)/?$#', $url, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }
}
