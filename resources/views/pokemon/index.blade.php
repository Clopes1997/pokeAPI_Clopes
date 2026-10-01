<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Lista de Pokémon</h2>
    </x-slot>

    <div class="card">
        @can('import', App\Models\Pokemon::class)
            <div style="margin-bottom: 1rem;">
                <a href="#import-modal" class="btn btn-primary">Importar</a>
            </div>
        @endcan

        <form method="GET" action="{{ route('pokemon.index') }}" class="search-form">
            <div class="row">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nome" class="input">
                <input type="text" name="type" value="{{ request('type') }}" placeholder="Filtrar por tipo" class="input">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>
    </div>

    @if($pokemon->count() > 0)
        <div class="grid grid-3">
                @foreach($pokemon as $p)
                    <div class="card pokemon-card" style="position: relative;">
                        <div class="pokemon-info">
                            <h3 class="section-title">{{ $p->formatted_name }}</h3>
                            <p class="section-text">Altura: {{ $p->height_in_meters }}</p>
                            <p class="section-text">Peso: {{ $p->weight_in_kilograms }}</p>
                            <a href="{{ route('pokemon.show', $p->api_id) }}" class="text-link">Ver detalhes</a>
                        </div>
                        @if($p->sprite)
                            <img src="{{ $p->sprite }}" alt="{{ $p->formatted_name }}" class="pokemon-sprite">
                        @endif
                        
                        @can('delete', $p)
                            <a href="#delete-modal-{{ $p->api_id }}" style="position: absolute; bottom: 0.5rem; right: 2.5rem; background: none; border: none; cursor: pointer; font-size: 1.2rem; color: #ef4444; padding: 0; line-height: 1; text-decoration: none;" title="Excluir">✕</a>
                        @endcan

                        @can('favorite', $p)
                            @if(in_array($p->id, $favoriteIds ?? [], true))
                                <form method="POST" action="{{ route('pokemon.unfavorite', $p->api_id) }}" class="favorite-form" style="position: absolute; bottom: 0.5rem; right: 0.5rem;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; cursor: pointer; font-size: 1.5rem; color: #FFD700; padding: 0; line-height: 1;">★</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('pokemon.favorite', $p->api_id) }}" class="favorite-form" style="position: absolute; bottom: 0.5rem; right: 0.5rem;">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; cursor: pointer; font-size: 1.5rem; color: #ccc; padding: 0; line-height: 1;">☆</button>
                                </form>
                            @endif
                        @endcan
                    </div>
                @endforeach
        </div>

        <div class="pagination-wrapper">
            {{ $pokemon->links('vendor.pagination.default') }}
        </div>
    @else
        <div class="card">
            <p class="section-text">Nenhum Pokémon encontrado.</p>
        </div>
    @endif

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

    @foreach($pokemon as $p)
        @can('delete', $p)
            <div id="delete-modal-{{ $p->api_id }}" class="modal-overlay">
                <div class="modal">
                    <div class="modal-body">
                        <h3 class="section-title">Confirmar Exclusão</h3>
                        <p class="section-text" style="margin-bottom: 1rem;">
                        Este Pokémon será removido do sistema.
                        Para visualizá-lo novamente, será necessário importá-lo manualmente informando o ID "{{ $p->api_id }}".
                        </p>
                        <form method="POST" action="{{ route('pokemon.destroy', $p->api_id) }}" class="delete-confirm-form">
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
    @endforeach

    <script>
        document.querySelectorAll('.delete-confirm-form').forEach(form => {
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

    <div id="import-modal" class="modal-overlay">
        <div class="modal">
            <div class="modal-body">
                <h3 class="section-title">Importar Pokémon</h3>
                <p class="section-text" style="margin-bottom: 1rem;">Escolha o modo de importação:</p>
                
                <form id="import-form" method="POST" action="{{ route('pokemon.import') }}">
                    @csrf
                    <div class="stack">
                        <div>
                            <label class="label" for="pokemon-id">ID único (opcional)</label>
                            <input type="number" id="pokemon-id" name="pokemon_id" class="input" placeholder="Ex: 25" min="1">
                            <p class="help">Importar um Pokémon específico</p>
                        </div>
                        <div>
                            <label class="label" for="start-id">ID inicial (opcional)</label>
                            <input type="number" id="start-id" name="start_id" class="input" placeholder="Ex: 1" min="1">
                            <p class="help">Início do intervalo (máximo 100 por vez)</p>
                        </div>
                        <div>
                            <label class="label" for="end-id">ID final (opcional)</label>
                            <input type="number" id="end-id" name="end_id" class="input" placeholder="Ex: 100" min="1">
                            <p class="help">Fim do intervalo (máximo 100 por vez)</p>
                        </div>
                        <p class="help" style="margin-top: 0.5rem;">Deixe todos os campos vazios para importar os próximos 100 Pokémon automaticamente</p>
                        <div class="modal-actions">
                            <a href="#" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Importar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="loading-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; padding: 2rem; border-radius: 8px; text-align: center; max-width: 400px;">
            <h3 class="section-title" style="margin-bottom: 1rem;">Importando Pokémon, por favor aguarde...</h3>
            <div style="border: 4px solid #f3f3f3; border-top: 4px solid #3b82f6; border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite; margin: 0 auto;"></div>
        </div>
    </div>

    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <script>
        document.getElementById('import-form')?.addEventListener('submit', function(e) {
            const pokemonId = document.getElementById('pokemon-id').value.trim();
            const startId = document.getElementById('start-id').value.trim();
            const endId = document.getElementById('end-id').value.trim();

            if (pokemonId && (startId || endId)) {
                e.preventDefault();
                alert('Por favor, informe apenas o ID único OU o intervalo, não ambos.');
                return;
            }

            if ((startId && !endId) || (!startId && endId)) {
                e.preventDefault();
                alert('Por favor, informe ambos os IDs do intervalo (inicial e final).');
                return;
            }

            if (startId && endId) {
                const start = parseInt(startId);
                const end = parseInt(endId);
                if (start >= end) {
                    e.preventDefault();
                    alert('O ID inicial deve ser menor que o ID final.');
                    return;
                }
            }

            document.getElementById('loading-overlay').style.display = 'flex';
        });
    </script>
</x-app-layout>