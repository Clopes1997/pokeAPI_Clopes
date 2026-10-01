# pokeapi-app-clopes

# Stack

- PHP 8+
- Laravel + Blade
- MySQL
- Composer
- PokéAPI v2 (HTTP GET)
- Cache e logs de integração

# Setup rápido

- composer install

- cp .env.example .env

- php artisan key:generate

- php artisan migrate --seed

- php artisan serve

- Acesse [http://127.0.0.1:8000](http://127.0.0.1:8000).

# Usuários padrão

- Senha para todos: password

- Viewer → viewer@example.com

- Editor → editor@example.com

- Admin → admin@example.com

# Funções

- Viewer: apenas visualização
- Editor: importa Pokémon e gerencia favoritos
- Admin: tudo do Editor + gerencia usuários e exclui Pokémon
- Permissões aplicadas via Policies.

# Importação de Pokémon

- Disponível para Editor e Admin.

- Modos:

- Importar 1 Pokémon por ID

- Importar intervalo de IDs

- Importação incremental (sem parâmetros)

- Regras:

- Máx. 100 Pokémon por importação

- Pokémon já importados são ignorados

- Pokémon excluídos (soft delete) não são reimportados automaticamente

- Pokémon excluído só volta por importação explícita via ID

- Durante a importação, o usuário fica em uma tela de loading.

- Também é possível importar Pokémon via linha de comando:

- php artisan pokemon:import-auto

# Exclusão (soft delete)

- Pokémon excluídos não aparecem mais no sistema
- Não são reimportados automaticamente
- Só podem ser restaurados por importação manual via ID

# Favoritos

- Favoritos são por usuário
- Apenas Editor e Admin
- Adicionar e remover via interface
