<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PokemonController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/pokemon', [PokemonController::class, 'index'])->name('pokemon.index');
    Route::get('/pokemon/favorites', [PokemonController::class, 'favorites'])->name('pokemon.favorites');
    Route::post('/pokemon/import', [PokemonController::class, 'import'])->name('pokemon.import');
    Route::post('/pokemon/import/{apiId}', [PokemonController::class, 'importLegacy'])->name('pokemon.import.legacy');
    Route::get('/pokemon/{id}', [PokemonController::class, 'show'])->name('pokemon.show');
    Route::delete('/pokemon/{id}', [PokemonController::class, 'destroy'])->name('pokemon.destroy');
    Route::post('/pokemon/{id}/favorite', [PokemonController::class, 'favorite'])->name('pokemon.favorite');
    Route::delete('/pokemon/{id}/favorite', [PokemonController::class, 'unfavorite'])->name('pokemon.unfavorite');

    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::patch('/admin/users/{userId}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.update-role');
});

require __DIR__.'/auth.php';
