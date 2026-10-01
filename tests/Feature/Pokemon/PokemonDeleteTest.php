<?php

namespace Tests\Feature\Pokemon;

use App\Models\Pokemon;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PokemonDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_imported_pokemon(): void
    {
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

        $response = $this->actingAs($admin)->delete("/pokemon/{$pokemon->api_id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Pokémon excluído com sucesso');
        $this->assertSoftDeleted('pokemons', ['id' => $pokemon->id]);
    }

    public function test_editor_cannot_delete_pokemon(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $editor = User::factory()->create();
        $editor->roles()->attach($editorRole);

        $pokemon = Pokemon::create([
            'api_id' => 25,
            'name' => 'pikachu',
            'height' => 4,
            'weight' => 60,
            'sprite' => 'https://example.com/pikachu.png',
        ]);

        $response = $this->actingAs($editor)->delete("/pokemon/{$pokemon->api_id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('pokemons', ['id' => $pokemon->id]);
    }

    public function test_viewer_cannot_delete_pokemon(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $viewer = User::factory()->create();
        $viewer->roles()->attach($viewerRole);

        $pokemon = Pokemon::create([
            'api_id' => 25,
            'name' => 'pikachu',
            'height' => 4,
            'weight' => 60,
            'sprite' => 'https://example.com/pikachu.png',
        ]);

        $response = $this->actingAs($viewer)->delete("/pokemon/{$pokemon->api_id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('pokemons', ['id' => $pokemon->id]);
    }

    public function test_guest_cannot_delete_pokemon(): void
    {
        $pokemon = Pokemon::create([
            'api_id' => 25,
            'name' => 'pikachu',
            'height' => 4,
            'weight' => 60,
            'sprite' => 'https://example.com/pikachu.png',
        ]);

        $response = $this->delete("/pokemon/{$pokemon->api_id}");

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('pokemons', ['id' => $pokemon->id]);
    }

    public function test_deleting_pokemon_does_not_affect_other_pokemon(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->roles()->attach($adminRole);

        $pokemon1 = Pokemon::create([
            'api_id' => 25,
            'name' => 'pikachu',
            'height' => 4,
            'weight' => 60,
            'sprite' => 'https://example.com/pikachu.png',
        ]);

        $pokemon2 = Pokemon::create([
            'api_id' => 1,
            'name' => 'bulbasaur',
            'height' => 7,
            'weight' => 69,
            'sprite' => 'https://example.com/bulbasaur.png',
        ]);

        $response = $this->actingAs($admin)->delete("/pokemon/{$pokemon1->api_id}");

        $response->assertRedirect();
        $this->assertSoftDeleted('pokemons', ['id' => $pokemon1->id]);
        $this->assertDatabaseHas('pokemons', ['id' => $pokemon2->id, 'deleted_at' => null]);
    }

    public function test_deleting_nonexistent_pokemon_returns_error(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->roles()->attach($adminRole);

        $nonExistentId = 9999;

        $response = $this->actingAs($admin)->delete("/pokemon/{$nonExistentId}");

        $response->assertStatus(404);
    }

    public function test_deleting_pokemon_removes_related_favorites(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->roles()->attach($adminRole);

        $editorRole = Role::create(['name' => 'editor']);
        $editor = User::factory()->create();
        $editor->roles()->attach($editorRole);

        $pokemon = Pokemon::create([
            'api_id' => 25,
            'name' => 'pikachu',
            'height' => 4,
            'weight' => 60,
            'sprite' => 'https://example.com/pikachu.png',
        ]);

        $editor->favorites()->attach($pokemon->id);
        $admin->favorites()->attach($pokemon->id);

        $this->assertDatabaseHas('favorites', ['pokemon_id' => $pokemon->id]);

        $response = $this->actingAs($admin)->delete("/pokemon/{$pokemon->api_id}");

        $response->assertRedirect();
        $this->assertSoftDeleted('pokemons', ['id' => $pokemon->id]);
    }

    public function test_deleting_pokemon_removes_related_types(): void
    {
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

        $pokemon->types()->create(['name' => 'electric']);

        $this->assertDatabaseHas('pokemon_type', ['pokemon_id' => $pokemon->id]);

        $response = $this->actingAs($admin)->delete("/pokemon/{$pokemon->api_id}");

        $response->assertRedirect();
        $this->assertSoftDeleted('pokemons', ['id' => $pokemon->id]);
    }

    public function test_deleting_pokemon_removes_related_abilities(): void
    {
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

        $pokemon->abilities()->create(['name' => 'static']);

        $this->assertDatabaseHas('ability_pokemon', ['pokemon_id' => $pokemon->id]);

        $response = $this->actingAs($admin)->delete("/pokemon/{$pokemon->api_id}");

        $response->assertRedirect();
        $this->assertSoftDeleted('pokemons', ['id' => $pokemon->id]);
    }

    public function test_deleting_pokemon_removes_related_moves(): void
    {
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

        $pokemon->moves()->create(['name' => 'thunderbolt']);

        $this->assertDatabaseHas('move_pokemon', ['pokemon_id' => $pokemon->id]);

        $response = $this->actingAs($admin)->delete("/pokemon/{$pokemon->api_id}");

        $response->assertRedirect();
        $this->assertSoftDeleted('pokemons', ['id' => $pokemon->id]);
    }
}

