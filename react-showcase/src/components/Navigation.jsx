import { Link, useLocation } from 'react-router-dom'

function Navigation() {
  const location = useLocation()

  const isActive = (path) => {
    if (path === '/') {
      return location.pathname === '/'
    }
    return location.pathname.startsWith(path)
  }

  return (
    <header className="header">
      <div className="container header-inner">
        <Link to="/" className="brand">
          <div className="pokeball pokeball-sm">
            <div className="pokeball-top"></div>
            <div className="pokeball-bottom"></div>
            <div className="pokeball-band"></div>
            <div className="pokeball-button">
              <div className="pokeball-button-inner"></div>
            </div>
          </div>
          <span className="brand-text">Poké<span>API</span></span>
        </Link>

        <nav className="nav-links" aria-label="Navegação principal">
          <Link 
            to="/" 
            className={`nav-link ${isActive('/') && location.pathname !== '/pokemon' ? 'is-active' : ''}`}
          >
            Painel
          </Link>
          <Link 
            to="/pokemon" 
            className={`nav-link ${isActive('/pokemon') ? 'is-active' : ''}`}
          >
            Pokémon
          </Link>
          <Link 
            to="/favorites" 
            className={`nav-link ${isActive('/favorites') ? 'is-active' : ''}`}
          >
            Favoritos
          </Link>
        </nav>

        <div className="nav-actions">
          <div className="btn btn-ghost btn-sm">
            <span>Usuário Demo</span>
          </div>
        </div>
      </div>
    </header>
  )
}

export default Navigation
