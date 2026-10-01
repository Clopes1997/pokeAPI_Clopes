<header class="header">
    <input class="nav-toggle" type="checkbox" id="nav-toggle">
    <div class="container header-inner">
        <a href="{{ route('dashboard') }}" class="brand">
            <div class="pokeball pokeball-sm">
                <div class="pokeball-top"></div>
                <div class="pokeball-bottom"></div>
                <div class="pokeball-band"></div>
                <div class="pokeball-button"><div class="pokeball-button-inner"></div></div>
            </div>
            <span class="brand-text">Poké<span>API</span></span>
        </a>

        <nav class="nav-links" aria-label="Navegação principal">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">Painel</a>
            <a href="{{ route('pokemon.index') }}" class="nav-link {{ request()->routeIs('pokemon.*') ? 'is-active' : '' }}">Pokémon</a>
            @can('admin')
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'is-active' : '' }}">Admin</a>
            @endcan
        </nav>

        <div class="nav-actions">
            @auth
            <details class="menu">
                <summary class="btn btn-ghost btn-sm">
                    <span>{{ Auth::user()->name }}</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </summary>
                <div class="menu-panel" role="menu">
                    <div class="menu-meta">
                        <div class="menu-meta-name">{{ Auth::user()->name }}</div>
                        <div class="menu-meta-email">{{ Auth::user()->email }}</div>
                    </div>
                    <a href="{{ route('profile.edit') }}" role="menuitem">Perfil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" role="menuitem">Sair</button>
                    </form>
                </div>
            </details>
            @endauth

            <label class="btn btn-ghost btn-icon nav-toggle-btn" for="nav-toggle" aria-label="Abrir menu">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                </svg>
            </label>
        </div>
    </div>

    <div class="nav-mobile">
        <div class="container nav-mobile-inner">
            <a href="{{ route('dashboard') }}" class="nav-mobile-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">Painel</a>
            <a href="{{ route('pokemon.index') }}" class="nav-mobile-link {{ request()->routeIs('pokemon.*') ? 'is-active' : '' }}">Pokémon</a>
            @can('admin')
                <a href="{{ route('admin.users') }}" class="nav-mobile-link {{ request()->routeIs('admin.*') ? 'is-active' : '' }}">Admin</a>
            @endcan

            @auth
            <div class="nav-mobile-section">
                <div class="menu-meta">
                    <div class="menu-meta-name">{{ Auth::user()->name }}</div>
                    <div class="menu-meta-email">{{ Auth::user()->email }}</div>
                </div>
                <a href="{{ route('profile.edit') }}" class="nav-mobile-link">Perfil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-mobile-link">Sair</button>
                </form>
            </div>
            @endauth
        </div>
    </div>
</header>