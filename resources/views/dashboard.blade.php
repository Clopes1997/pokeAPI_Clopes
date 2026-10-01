<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">Painel</h2>
    </x-slot>

    @if(isset($favorites) && $favorites->count() > 0)
        <div class="card">
            <h2 class="section-title">Meus Pokémon Favoritos</h2>
            <div class="grid grid-3" style="margin-top: 1.5rem;">
                @foreach($favorites as $pokemon)
                    <div class="card pokemon-card" style="position: relative;">
                        <div class="pokemon-info">
                            <h3 class="section-title">{{ $pokemon->formatted_name }}</h3>
                            <a href="{{ route('pokemon.show', $pokemon->api_id) }}" class="text-link">Ver detalhes</a>
                        </div>
                        @if($pokemon->sprite)
                            <img src="{{ $pokemon->sprite }}" alt="{{ $pokemon->formatted_name }}" class="pokemon-sprite">
                        @endif
                        
                        @can('unfavorite', $pokemon)
                            <form method="POST" action="{{ route('pokemon.unfavorite', $pokemon->api_id) }}" class="favorite-form" style="position: absolute; bottom: 0.5rem; right: 0.5rem;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; cursor: pointer; font-size: 1.5rem; color: #FFD700; padding: 0; line-height: 1;">★</button>
                            </form>
                        @endcan
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="card">
            <div style="text-align: center; padding: 2rem 1rem;">
                <h2 class="section-title" style="margin-bottom: 1rem;">Nenhum favorito ainda</h2>
                <p class="section-text" style="margin-bottom: 0.75rem;">Você ainda não tem Pokémon favoritados.</p>
                <p class="section-text" style="margin-bottom: 1.5rem;">Favoritos são uma forma de guardar seus Pokémon preferidos para acesso rápido.</p>
                <a href="{{ route('pokemon.index') }}" class="btn btn-primary">Explorar Pokémon</a>
            </div>
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

        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.transition = 'opacity 0.5s';
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 500);
            }, 1000);
        }
    </script>
</x-app-layout>