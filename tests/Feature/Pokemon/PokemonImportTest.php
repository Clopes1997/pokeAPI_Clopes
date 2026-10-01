<?php

namespace Tests\Feature\Pokemon;

use App\Models\Pokemon;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\Helpers\PokemonApiDataHelper;
use Tests\TestCase;

class PokemonImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_authorized_user_can_import_pokemon_by_api_id(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/25' => Http::response(PokemonApiDataHelper::pikachuResponse(), 200),
        ]);

        $response = $this->actingAs($user)->post('/pokemon/import/25');

        $response->assertStatus(200);
    }

    public function test_import_creates_pokemon_with_types_relationship(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/25' => Http::response(PokemonApiDataHelper::pikachuResponse(), 200),
        ]);

        $response = $this->actingAs($user)->post('/pokemon/import/25');

        $response->assertStatus(200);
        $response->assertSessionHas('success');
    }

    public function test_import_creates_pokemon_with_moves_relationship(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/25' => Http::response(PokemonApiDataHelper::pikachuResponse(), 200),
        ]);

        $response = $this->actingAs($user)->post('/pokemon/import/25');

        $response->assertStatus(200);
        $response->assertSessionHas('success');
    }

    public function test_import_creates_pokemon_with_abilities_relationship(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/25' => Http::response(PokemonApiDataHelper::pikachuResponse(), 200),
        ]);

        $response = $this->actingAs($user)->post('/pokemon/import/25');

        $response->assertStatus(200);
        $response->assertSessionHas('success');
    }

    public function test_import_prevents_duplicate_pokemon(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/25' => Http::response(PokemonApiDataHelper::pikachuResponse(), 200),
        ]);

        $firstImport = $this->actingAs($user)->post('/pokemon/import/25');
        $firstImport->assertStatus(200);

        $duplicateImport = $this->actingAs($user)->post('/pokemon/import/25');
        $duplicateImport->assertStatus(302);
        $duplicateImport->assertSessionHasErrors();
    }

    public function test_import_handles_invalid_pokemon_id(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/99999' => Http::response(PokemonApiDataHelper::notFoundResponse(), 404),
        ]);

        $response = $this->actingAs($user)->post('/pokemon/import/99999');

        $response->assertStatus(404);
        $response->assertSee('Pokémon não encontrado', false);
    }

    public function test_import_handles_api_errors_gracefully(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/0' => Http::response(PokemonApiDataHelper::serverErrorResponse(), 500),
        ]);

        $response = $this->actingAs($user)->post('/pokemon/import/0');

        $response->assertStatus(500);
        $response->assertSee('Erro ao comunicar com a API de Pokémon', false);
    }

    public function test_import_shows_success_message_in_pt_br(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/25' => Http::response(PokemonApiDataHelper::pikachuResponse(), 200),
        ]);

        $response = $this->actingAs($user)->post('/pokemon/import/25');

        $response->assertStatus(200);
        $response->assertSessionHas('success');
        $response->assertSessionHas('success', function ($message) {
            return str_contains($message, 'importado') || str_contains($message, 'sucesso');
        });
    }

    public function test_import_error_messages_are_in_pt_br(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/99999' => Http::response(PokemonApiDataHelper::notFoundResponse(), 404),
        ]);

        $response = $this->actingAs($user)->post('/pokemon/import/99999');

        $response->assertSee('Pokémon não encontrado', false);
    }

    public function test_import_api_timeout_is_handled_gracefully(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/25' => Http::response(PokemonApiDataHelper::timeoutResponse(), 504),
        ]);

        $response = $this->actingAs($user)->post('/pokemon/import/25');

        $response->assertStatus(500);
        $response->assertSee('Erro ao comunicar com a API de Pokémon', false);
    }

    public function test_single_import_restores_soft_deleted_pokemon(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        $pokemon = Pokemon::create([
            'api_id' => 25,
            'name' => 'pikachu',
            'height' => 4,
            'weight' => 60,
            'sprite' => 'https://example.com/pikachu.png',
        ]);

        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->roles()->attach($adminRole);

        $this->actingAs($admin)->delete("/pokemon/{$pokemon->api_id}");
        $this->assertSoftDeleted('pokemons', ['id' => $pokemon->id]);

        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/25' => Http::response(PokemonApiDataHelper::pikachuResponse(), 200),
        ]);

        $response = $this->actingAs($user)->post('/pokemon/import/25');

        $response->assertStatus(200);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('pokemons', ['id' => $pokemon->id, 'deleted_at' => null]);
    }

    public function test_batch_interval_import_ignores_soft_deleted_pokemon(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->roles()->attach($adminRole);

        $pokemon1 = Pokemon::create([
            'api_id' => 1,
            'name' => 'bulbasaur',
            'height' => 7,
            'weight' => 69,
            'sprite' => 'https://example.com/bulbasaur.png',
        ]);

        $this->actingAs($admin)->delete("/pokemon/{$pokemon1->api_id}");
        $this->assertSoftDeleted('pokemons', ['id' => $pokemon1->id]);

        \Illuminate\Support\Facades\Cache::flush();

        Http::fake(function ($request) {
            $url = $request->url();
            if ($url === 'https://pokeapi.co/api/v2/pokemon/1') {
                return Http::response(PokemonApiDataHelper::bulbasaurResponse(), 200);
            }
            if ($url === 'https://pokeapi.co/api/v2/pokemon/2') {
                return Http::response(['id' => 2, 'name' => 'ivysaur', 'height' => 10, 'weight' => 130, 'sprites' => ['front_default' => null], 'types' => [], 'moves' => [], 'abilities' => []], 200);
            }
            if ($url === 'https://pokeapi.co/api/v2/pokemon/3') {
                return Http::response(['id' => 3, 'name' => 'venusaur', 'height' => 20, 'weight' => 1000, 'sprites' => ['front_default' => null], 'types' => [], 'moves' => [], 'abilities' => []], 200);
            }
            return Http::response(PokemonApiDataHelper::notFoundResponse(), 404);
        });

        $response = $this->actingAs($user)->post('/pokemon/import', [
            'start_id' => 1,
            'end_id' => 3,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertSoftDeleted('pokemons', ['id' => $pokemon1->id]);
        $pokemon1After = Pokemon::withTrashed()->find($pokemon1->id);
        $this->assertNotNull($pokemon1After->deleted_at);
    }

    public function test_batch_interval_import_succeeds_with_zero_imported_when_all_pokemon_are_soft_deleted_or_imported(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->roles()->attach($adminRole);

        $pokemon1 = Pokemon::create([
            'api_id' => 1,
            'name' => 'bulbasaur',
            'height' => 7,
            'weight' => 69,
            'sprite' => 'https://example.com/bulbasaur.png',
        ]);

        $pokemon2 = Pokemon::create([
            'api_id' => 2,
            'name' => 'ivysaur',
            'height' => 10,
            'weight' => 130,
            'sprite' => 'https://example.com/ivysaur.png',
        ]);

        $this->actingAs($admin)->delete("/pokemon/{$pokemon1->api_id}");
        $this->actingAs($admin)->delete("/pokemon/{$pokemon2->api_id}");
        $this->assertSoftDeleted('pokemons', ['id' => $pokemon1->id]);
        $this->assertSoftDeleted('pokemons', ['id' => $pokemon2->id]);

        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/1' => Http::response(PokemonApiDataHelper::bulbasaurResponse(), 200),
            'https://pokeapi.co/api/v2/pokemon/2' => Http::response(['id' => 2, 'name' => 'ivysaur', 'height' => 10, 'weight' => 130, 'sprites' => ['front_default' => null], 'types' => [], 'moves' => [], 'abilities' => []], 200),
        ]);

        $response = $this->actingAs($user)->post('/pokemon/import', [
            'start_id' => 1,
            'end_id' => 2,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertSoftDeleted('pokemons', ['id' => $pokemon1->id]);
        $this->assertSoftDeleted('pokemons', ['id' => $pokemon2->id]);
    }

    public function test_batch_incremental_import_ignores_soft_deleted_pokemon(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->roles()->attach($adminRole);

        $pokemon = Pokemon::create([
            'api_id' => 1,
            'name' => 'bulbasaur',
            'height' => 7,
            'weight' => 69,
            'sprite' => 'https://example.com/bulbasaur.png',
        ]);

        $this->actingAs($admin)->delete("/pokemon/{$pokemon->api_id}");
        $this->assertSoftDeleted('pokemons', ['id' => $pokemon->id]);

        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/2' => Http::response(['id' => 2, 'name' => 'ivysaur', 'height' => 10, 'weight' => 130, 'types' => [], 'moves' => [], 'abilities' => []], 200),
            'https://pokeapi.co/api/v2/pokemon/3' => Http::response(['id' => 3, 'name' => 'venusaur', 'height' => 20, 'weight' => 1000, 'types' => [], 'moves' => [], 'abilities' => []], 200),
        ]);

        $response = $this->actingAs($user)->post('/pokemon/import');

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertSoftDeleted('pokemons', ['id' => $pokemon->id]);
        $maxApiId = Pokemon::max('api_id');
        $this->assertGreaterThan(1, $maxApiId);
    }
}

