<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pokemon\ImportPokemonRequest;
use App\Models\Pokemon;
use App\Services\Pokemon\PokemonDeleteService;
use App\Services\Pokemon\PokemonDetailService;
use App\Services\Pokemon\PokemonFavoriteService;
use App\Services\Pokemon\PokemonImportService;
use App\Services\Pokemon\PokemonListingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PokemonController extends Controller
{
    public function __construct(
        private PokemonListingService $listingService,
        private PokemonImportService $importService,
        private PokemonFavoriteService $favoriteService,
        private PokemonDetailService $detailService,
        private PokemonDeleteService $deleteService
    ) {
    }

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Pokemon::class);

        $search = $request->query('search');
        $type = $request->query('type');

        $pokemon = $this->listingService->getPaginatedList($search, $type);
        $favoriteIds = $this->favoriteService->getFavoriteIds($request->user());

        return view('pokemon.index', [
            'pokemon' => $pokemon,
            'favoriteIds' => $favoriteIds,
        ]);
    }

    public function show(Request $request, int $id): View
    {
        $pokemon = $this->detailService->getById($id);

        Gate::authorize('view', $pokemon);

        $isFavorited = $this->favoriteService->isFavorite($request->user(), $pokemon);

        return view('pokemon.show', [
            'pokemon' => $pokemon,
            'isFavorited' => $isFavorited,
        ]);
    }

    public function import(ImportPokemonRequest $request)
    {
        Gate::authorize('import', Pokemon::class);

        $pokemonId = $request->input('pokemon_id');
        $startId = $request->input('start_id');
        $endId = $request->input('end_id');

        if ($pokemonId) {
            $this->importService->importSingle($pokemonId);
            return redirect()->route('pokemon.index')->with('success', 'Pokémon importado com sucesso.');
        }

        if ($startId && $endId) {
            $result = $this->importService->importInterval($startId, $endId);
            
            if ($result['already_existed'] > 0) {
                $message = "{$result['imported']} Pokémon importados. {$result['already_existed']} já existiam no sistema.";
            } else {
                $message = "{$result['imported']} Pokémon importados com sucesso.";
            }
            
            return redirect()->route('pokemon.index')->with('success', $message);
        }

        $result = $this->importService->importIncremental();
        
        if ($result['already_existed'] > 0) {
            $message = "{$result['imported']} Pokémon importados. {$result['already_existed']} já existiam no sistema.";
        } else {
            $message = "{$result['imported']} Pokémon importados com sucesso.";
        }
        
        return redirect()->route('pokemon.index')->with('success', $message);
    }

    public function importLegacy(ImportPokemonRequest $request, int $apiId)
    {
        Gate::authorize('import', Pokemon::class);

        $this->importService->importSingle($apiId);

        session()->flash('success', 'Pokémon importado com sucesso');
        return response('Pokémon importado com sucesso', 200);
    }

    public function favorite(int $id)
    {
        $pokemon = $this->detailService->getById($id);
        Gate::authorize('favorite', $pokemon);

        $this->favoriteService->addFavorite(auth()->user(), $id);

        session()->flash('success', 'Pokémon favoritado com sucesso');
        return response('Pokémon favoritado com sucesso', 200);
    }

    public function unfavorite(int $id)
    {
        $pokemon = $this->detailService->getById($id);
        Gate::authorize('unfavorite', $pokemon);

        $this->favoriteService->removeFavorite(auth()->user(), $id);

        session()->flash('success', 'Pokémon removido dos favoritos');
        return response('Pokémon removido dos favoritos', 200);
    }

    public function favorites(): View
    {
        Gate::authorize('viewFavorites', Pokemon::class);

        $favorites = $this->favoriteService->getUserFavorites(auth()->user());

        return view('pokemon.favorites', ['favorites' => $favorites]);
    }

    public function destroy(int $id)
    {
        $pokemon = $this->detailService->getById($id);
        Gate::authorize('delete', $pokemon);

        $this->deleteService->delete($id);

        return redirect()->route('pokemon.index')->with('success', 'Pokémon excluído com sucesso');
    }
}
