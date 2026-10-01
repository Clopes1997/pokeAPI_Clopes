<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Meus Favoritos</h2>
    </x-slot>

    <div class="card">
        @if($favorites->count() > 0)
            <div class="grid grid-3">
                @foreach($favorites as $pokemon)
                    <div class="card pokemon-card">
                        <div class="pokemon-info">
                            <h3 class="section-title">{{ $pokemon->formatted_name }}</h3>
                            <p class="section-text">Altura: {{ $pokemon->height_in_meters }}</p>
                            <p class="section-text">Peso: {{ $pokemon->weight_in_kilograms }}</p>
                            <a href="{{ route('pokemon.show', $pokemon->api_id) }}" class="text-link">Ver detalhes</a>
                        </div>
                        @if($pokemon->sprite)
                            <img src="{{ $pokemon->sprite }}" alt="{{ $pokemon->formatted_name }}" class="pokemon-sprite">
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="section-text">Você ainda não tem Pokémon favoritados.</p>
        @endif
    </div>
</x-app-layout>