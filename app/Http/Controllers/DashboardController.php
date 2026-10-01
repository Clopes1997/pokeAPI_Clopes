<?php

namespace App\Http\Controllers;

use App\Services\Pokemon\PokemonFavoriteService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private PokemonFavoriteService $favoriteService)
    {
    }

    public function index(Request $request): View
    {
        $favorites = $this->favoriteService->getUserFavorites($request->user());

        return view('dashboard', ['favorites' => $favorites]);
    }
}

