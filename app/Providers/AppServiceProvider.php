<?php

namespace App\Providers;

use App\Models\Pokemon;
use App\Models\User;
use App\Policies\PokemonPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Pokemon::class => PokemonPolicy::class,
    ];

    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('admin', function (User $user) {
            return $user->roles()->where('name', 'admin')->exists();
        });
    }
}
