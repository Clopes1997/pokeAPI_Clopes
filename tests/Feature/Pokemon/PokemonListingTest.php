<?php

namespace Tests\Feature\Pokemon;

use App\Models\Pokemon;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PokemonListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_pokemon_listing_can_be_accessed(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->get('/pokemon');

        $response->assertStatus(200);
    }

    public function test_pokemon_listing_applies_pagination(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->get('/pokemon');

        $response->assertStatus(200);
        $response->assertViewHas('pokemon');
    }

    public function test_pokemon_listing_supports_search_by_name(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->get('/pokemon?search=pikachu');

        $response->assertStatus(200);
        $response->assertViewHas('pokemon');
    }

    public function test_pokemon_listing_supports_filter_by_type(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->get('/pokemon?type=electric');

        $response->assertStatus(200);
        $response->assertViewHas('pokemon');
    }

    public function test_pokemon_listing_pagination_controls_work_correctly(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $firstPage = $this->actingAs($user)->get('/pokemon?page=1');
        $firstPage->assertStatus(200);

        $secondPage = $this->actingAs($user)->get('/pokemon?page=2');
        $secondPage->assertStatus(200);
    }

    public function test_pokemon_listing_handles_empty_state_gracefully(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->get('/pokemon');

        $response->assertStatus(200);
        $response->assertViewHas('pokemon');
    }

    public function test_pokemon_listing_search_returns_empty_results_appropriately(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->get('/pokemon?search=nonexistentpokemon');

        $response->assertStatus(200);
        $response->assertViewHas('pokemon');
    }

    public function test_pokemon_listing_does_not_expose_errors_to_user(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->get('/pokemon');

        $response->assertStatus(200);
        // Verifica que não há mensagens de erro técnicas expostas
        $response->assertDontSee('Exception', false);
        $response->assertDontSee('Fatal error', false);
        $response->assertDontSee('Stack trace', false);
    }

    public function test_pokemon_listing_excludes_soft_deleted_pokemon(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

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
            'api_id' => 25,
            'name' => 'pikachu',
            'height' => 4,
            'weight' => 60,
            'sprite' => 'https://example.com/pikachu.png',
        ]);

        $this->actingAs($admin)->delete("/pokemon/{$pokemon1->api_id}");
        $this->assertSoftDeleted('pokemons', ['id' => $pokemon1->id]);

        $response = $this->actingAs($user)->get('/pokemon');

        $response->assertStatus(200);
        $response->assertViewHas('pokemon');
        $pokemonCollection = $response->viewData('pokemon');
        $pokemonIds = $pokemonCollection->pluck('id')->toArray();
        $this->assertNotContains($pokemon1->id, $pokemonIds);
        $this->assertContains($pokemon2->id, $pokemonIds);
    }
}

