<?php

namespace Tests\Feature\Pokemon;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PokemonFavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_authorized_user_can_favorite_imported_pokemon(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        $response = $this->actingAs($user)->post('/pokemon/1/favorite');

        $response->assertStatus(200);
        $response->assertSessionHas('success');
    }

    public function test_user_can_unfavorite_pokemon(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        $favoriteResponse = $this->actingAs($user)->post('/pokemon/1/favorite');
        $favoriteResponse->assertStatus(200);

        $unfavoriteResponse = $this->actingAs($user)->delete('/pokemon/1/favorite');
        $unfavoriteResponse->assertStatus(200);
        $unfavoriteResponse->assertSessionHas('success');
    }

    public function test_duplicate_favorites_are_prevented(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        $firstFavorite = $this->actingAs($user)->post('/pokemon/1/favorite');
        $firstFavorite->assertStatus(200);

        $duplicateFavorite = $this->actingAs($user)->post('/pokemon/1/favorite');
        $duplicateFavorite->assertStatus(302);
        $duplicateFavorite->assertSessionHasErrors();
    }

    public function test_user_can_list_their_own_favorites(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        $response = $this->actingAs($user)->get('/pokemon/favorites');

        $response->assertStatus(200);
        $response->assertViewHas('favorites');
    }

    public function test_authorization_rules_are_enforced_for_favorites(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->post('/pokemon/1/favorite');

        $response->assertStatus(403);
        $response->assertSee('Você não tem permissão para realizar esta ação', false);
    }

    public function test_favorite_success_message_is_in_pt_br(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        $response = $this->actingAs($user)->post('/pokemon/1/favorite');

        $response->assertStatus(200);
        $response->assertSessionHas('success');
        $response->assertSessionHas('success', function ($message) {
            return str_contains($message, 'favoritado') || str_contains($message, 'sucesso');
        });
    }

    public function test_unfavorite_success_message_is_in_pt_br(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        $favoriteResponse = $this->actingAs($user)->post('/pokemon/1/favorite');
        $favoriteResponse->assertStatus(200);

        $unfavoriteResponse = $this->actingAs($user)->delete('/pokemon/1/favorite');
        $unfavoriteResponse->assertStatus(200);
        $unfavoriteResponse->assertSessionHas('success');
        $unfavoriteResponse->assertSessionHas('success', function ($message) {
            return str_contains($message, 'removido') || str_contains($message, 'favoritos');
        });
    }

    public function test_favorite_error_messages_are_in_pt_br(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        $firstFavorite = $this->actingAs($user)->post('/pokemon/1/favorite');
        $firstFavorite->assertStatus(200);

        $duplicateFavorite = $this->actingAs($user)->post('/pokemon/1/favorite');
        $duplicateFavorite->assertSessionHasErrors('pokemon');
        $duplicateFavorite->assertSessionHasErrors(['pokemon' => 'Este Pokémon já está nos seus favoritos']);
    }

    public function test_viewer_cannot_access_favorites_list(): void
    {
        $viewerRole = Role::create(['name' => 'viewer']);
        $user = User::factory()->create();
        $user->roles()->attach($viewerRole);

        $response = $this->actingAs($user)->get('/pokemon/favorites');

        $response->assertStatus(403);
    }

    public function test_editor_can_access_favorites_list(): void
    {
        $editorRole = Role::create(['name' => 'editor']);
        $user = User::factory()->create();
        $user->roles()->attach($editorRole);

        $response = $this->actingAs($user)->get('/pokemon/favorites');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_favorites_list(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $user = User::factory()->create();
        $user->roles()->attach($adminRole);

        $response = $this->actingAs($user)->get('/pokemon/favorites');

        $response->assertStatus(200);
    }
}

