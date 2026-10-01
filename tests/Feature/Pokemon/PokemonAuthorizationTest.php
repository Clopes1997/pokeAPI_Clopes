<?php

namespace Tests\Feature\Pokemon;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PokemonAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_viewer_can_access_pokemon_listing(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->get('/pokemon');

        $response->assertStatus(200);
    }

    public function test_viewer_can_access_pokemon_detail(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->get('/pokemon/1');

        $response->assertStatus(200);
    }

    public function test_viewer_cannot_import_pokemon(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->post('/pokemon/import/25');

        $response->assertStatus(403);
        $response->assertSee('Você não tem permissão para realizar esta ação');
    }

    public function test_viewer_cannot_favorite_pokemon(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->post('/pokemon/1/favorite');

        $response->assertStatus(403);
        $response->assertSee('Você não tem permissão para realizar esta ação');
    }

    public function test_editor_can_access_pokemon_listing(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        $response = $this->actingAs($user)->get('/pokemon');

        $response->assertStatus(200);
    }

    public function test_editor_can_access_pokemon_detail(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        $response = $this->actingAs($user)->get('/pokemon/1');

        $response->assertStatus(200);
    }

    public function test_editor_can_import_pokemon(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        $response = $this->actingAs($user)->post('/pokemon/import/25');

        $response->assertStatus(200);
    }

    public function test_editor_can_favorite_pokemon(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        $response = $this->actingAs($user)->post('/pokemon/1/favorite');

        $response->assertStatus(200);
    }

    public function test_editor_can_unfavorite_pokemon(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        $response = $this->actingAs($user)->delete('/pokemon/1/favorite');

        $response->assertStatus(200);
    }

    public function test_admin_has_full_access_to_pokemon_listing(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $user = User::factory()->create();
        $user->roles()->attach($adminRole);

        $response = $this->actingAs($user)->get('/pokemon');

        $response->assertStatus(200);
    }

    public function test_admin_has_full_access_to_pokemon_detail(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $user = User::factory()->create();
        $user->roles()->attach($adminRole);

        $response = $this->actingAs($user)->get('/pokemon/1');

        $response->assertStatus(200);
    }

    public function test_admin_can_import_pokemon(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $user = User::factory()->create();
        $user->roles()->attach($adminRole);

        $response = $this->actingAs($user)->post('/pokemon/import/25');

        $response->assertStatus(200);
    }

    public function test_admin_can_favorite_pokemon(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $user = User::factory()->create();
        $user->roles()->attach($adminRole);

        $response = $this->actingAs($user)->post('/pokemon/1/favorite');

        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_is_redirected_to_login_when_accessing_pokemon_listing(): void
    {
        $response = $this->get('/pokemon');

        $response->assertRedirect('/login');
    }

    public function test_unauthenticated_user_is_redirected_to_login_when_accessing_pokemon_detail(): void
    {
        $response = $this->get('/pokemon/1');

        $response->assertRedirect('/login');
    }

    public function test_unauthenticated_user_is_redirected_to_login_when_importing_pokemon(): void
    {
        $response = $this->post('/pokemon/import/25');

        $response->assertRedirect('/login');
    }

    public function test_unauthenticated_user_is_redirected_to_login_when_favoriting_pokemon(): void
    {
        $response = $this->post('/pokemon/1/favorite');

        $response->assertRedirect('/login');
    }

    public function test_authorization_failure_returns_403_with_pt_br_message(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->post('/pokemon/import/25');

        $response->assertStatus(403);
        $response->assertSee('Você não tem permissão para realizar esta ação', false);
    }
}

