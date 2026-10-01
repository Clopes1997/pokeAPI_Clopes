<?php

namespace Tests\Feature\Pokemon;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PokemonPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_policy_allows_viewer_to_view_pokemon(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->get('/pokemon/1');

        $response->assertStatus(200);
    }

    public function test_policy_allows_editor_to_view_pokemon(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        $response = $this->actingAs($user)->get('/pokemon/1');

        $response->assertStatus(200);
    }

    public function test_policy_allows_admin_to_view_pokemon(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $user = User::factory()->create();
        $user->roles()->attach($adminRole);

        $response = $this->actingAs($user)->get('/pokemon/1');

        $response->assertStatus(200);
    }

    public function test_policy_denies_viewer_to_import_pokemon(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->post('/pokemon/import/25');

        $response->assertStatus(403);
    }

    public function test_policy_allows_editor_to_import_pokemon(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        $response = $this->actingAs($user)->post('/pokemon/import/25');

        $response->assertStatus(200);
    }

    public function test_policy_allows_admin_to_import_pokemon(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $user = User::factory()->create();
        $user->roles()->attach($adminRole);

        $response = $this->actingAs($user)->post('/pokemon/import/25');

        $response->assertStatus(200);
    }

    public function test_policy_denies_viewer_to_favorite_pokemon(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->post('/pokemon/1/favorite');

        $response->assertStatus(403);
    }

    public function test_policy_allows_editor_to_favorite_pokemon(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        $response = $this->actingAs($user)->post('/pokemon/1/favorite');

        $response->assertStatus(200);
    }

    public function test_policy_allows_admin_to_favorite_pokemon(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $user = User::factory()->create();
        $user->roles()->attach($adminRole);

        $response = $this->actingAs($user)->post('/pokemon/1/favorite');

        $response->assertStatus(200);
    }

    public function test_policy_allows_editor_to_manage_favorites(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        $favoriteResponse = $this->actingAs($user)->post('/pokemon/1/favorite');
        $favoriteResponse->assertStatus(200);

        $unfavoriteResponse = $this->actingAs($user)->delete('/pokemon/1/favorite');
        $unfavoriteResponse->assertStatus(200);
    }

    public function test_policy_allows_admin_to_manage_favorites(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $user = User::factory()->create();
        $user->roles()->attach($adminRole);

        $favoriteResponse = $this->actingAs($user)->post('/pokemon/1/favorite');
        $favoriteResponse->assertStatus(200);

        $unfavoriteResponse = $this->actingAs($user)->delete('/pokemon/1/favorite');
        $unfavoriteResponse->assertStatus(200);
    }
}

