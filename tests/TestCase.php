<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;
use Tests\Helpers\PokemonApiDataHelper;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/1' => Http::response(PokemonApiDataHelper::bulbasaurResponse(), 200),
            'https://pokeapi.co/api/v2/pokemon/25' => Http::response(PokemonApiDataHelper::pikachuResponse(), 200),
            'https://pokeapi.co/api/v2/pokemon/0' => Http::response(PokemonApiDataHelper::serverErrorResponse(), 500),
            'https://pokeapi.co/api/v2/pokemon/*' => Http::response(PokemonApiDataHelper::notFoundResponse(), 404),
        ]);
    }
}
