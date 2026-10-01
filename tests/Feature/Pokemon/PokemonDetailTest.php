<?php

namespace Tests\Feature\Pokemon;

use App\Models\Pokemon;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\Helpers\PokemonApiDataHelper;
use Tests\TestCase;

class PokemonDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_pokemon_detail_page_is_accessible(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->get('/pokemon/1');

        $response->assertStatus(200);
    }

    public function test_pokemon_detail_displays_types_relationship(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->get('/pokemon/1');

        $response->assertStatus(200);
        $response->assertViewHas('pokemon');
    }

    public function test_pokemon_detail_displays_moves_relationship(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->get('/pokemon/1');

        $response->assertStatus(200);
        $response->assertViewHas('pokemon');
    }

    public function test_pokemon_detail_displays_abilities_relationship(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->get('/pokemon/1');

        $response->assertStatus(200);
        $response->assertViewHas('pokemon');
    }

    public function test_pokemon_detail_returns_404_for_nonexistent_pokemon(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->get('/pokemon/99999');

        $response->assertStatus(404);
        $response->assertSee('Pokémon não encontrado', false);
    }

    public function test_pokemon_detail_shows_friendly_error_message_in_pt_br(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->get('/pokemon/99999');

        $response->assertStatus(404);
        $response->assertSee('Pokémon não encontrado', false);
    }

    public function test_pokemon_detail_restores_soft_deleted_pokemon(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->roles()->attach($adminRole);

        $pokemon = Pokemon::create([
            'api_id' => 25,
            'name' => 'pikachu',
            'height' => 4,
            'weight' => 60,
            'sprite' => 'https://example.com/pikachu.png',
        ]);

        $this->actingAs($admin)->delete("/pokemon/{$pokemon->api_id}");
        $this->assertSoftDeleted('pokemons', ['id' => $pokemon->id]);

        Http::fake([
            'https://pokeapi.co/api/v2/pokemon/25' => Http::response(PokemonApiDataHelper::pikachuResponse(), 200),
        ]);

        $response = $this->actingAs($user)->get("/pokemon/{$pokemon->api_id}");

        $response->assertStatus(200);
        $this->assertDatabaseHas('pokemons', ['id' => $pokemon->id, 'deleted_at' => null]);
    }
}

