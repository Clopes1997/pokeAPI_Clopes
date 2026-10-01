<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Detalhes do Pokémon</h2>
    </x-slot>

    <div class="card" style="position: relative;">
        @can('favorite', $pokemon)
            @if($isFavorited)
                <form method="POST" action="{{ route('pokemon.unfavorite', $pokemon->api_id) }}" class="favorite-form" style="position: absolute; top: 1rem; right: 1rem;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background: none; border: none; cursor: pointer; font-size: 2rem; color: #FFD700; padding: 0; line-height: 1;">★</button>
                </form>
            @else
                <form method="POST" action="{{ route('pokemon.favorite', $pokemon->api_id) }}" class="favorite-form" style="position: absolute; top: 1rem; right: 1rem;">
                    @csrf
                    <button type="submit" style="background: none; border: none; cursor: pointer; font-size: 2rem; color: #ccc; padding: 0; line-height: 1;">☆</button>
                </form>
            @endif
        @endcan

        <h1 class="page-title">{{ $pokemon->formatted_name }}</h1>
        
        @if($pokemon->sprite)
            <img src="{{ $pokemon->sprite }}" alt="{{ $pokemon->formatted_name }}">
        @endif

        <div class="grid">
            <div>
                <p class="section-text"><strong>Altura:</strong> {{ $pokemon->height_in_meters }}</p>
            </div>
            <div>
                <p class="section-text"><strong>Peso:</strong> {{ $pokemon->weight_in_kilograms }}</p>
            </div>
        </div>

        <div class="stack section-group">
            <h2 class="section-title">Tipos</h2>
            <div class="row">
                @foreach($pokemon->types as $type)
                    <span class="pill">{{ $type->name }}</span>
                @endforeach
            </div>
        </div>

        <div class="stack section-group">
            <h2 class="section-title">Movimentos</h2>
            <div class="row">
                @foreach($pokemon->moves as $move)
                    <span class="pill">{{ $move->name }}</span>
                @endforeach
            </div>
        </div>

        <div class="stack section-group">
            <h2 class="section-title">Habilidades</h2>
            <div class="row">
                @foreach($pokemon->abilities as $ability)
                    <span class="pill">{{ $ability->name }}</span>
                @endforeach
            </div>
        </div>

        @can('delete', $pokemon)
            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e7eb;">
                <a href="#delete-modal-{{ $pokemon->api_id }}" class="btn btn-danger">Excluir Pokémon</a>
            </div>
        @endcan
    </div>

    <script>
        document.querySelectorAll('.favorite-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch(this.action, {
                    method: this.method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(() => {
                    window.location.reload();
                });
            });
        });

    </script>

    @can('delete', $pokemon)
        <div id="delete-modal-{{ $pokemon->api_id }}" class="modal-overlay">
            <div class="modal">
                <div class="modal-body">
                    <h3 class="section-title">Confirmar Exclusão</h3>
                    <p class="section-text" style="margin-bottom: 1rem;">
                        Este Pokémon será removido do sistema.
                        Para visualizá-lo novamente, será necessário importá-lo manualmente informando o ID "{{ $pokemon->api_id }}".
                    </p>
                    <form method="POST" action="{{ route('pokemon.destroy', $pokemon->api_id) }}" class="delete-confirm-form">
                        @csrf
                        @method('DELETE')
                        <div class="modal-actions">
                            <a href="#" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-danger">Confirmar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
</x-app-layout>