import { Link } from 'react-router-dom'
import { useFavorites } from '../hooks/useFavorites'
import { useEffect, useState } from 'react'
import { getPokemon } from '../services/pokeapi'
import PokemonCard from '../components/PokemonCard'

function Home() {
  const { favorites, isFavorite, toggleFavorite } = useFavorites()
  const [favoritePokemon, setFavoritePokemon] = useState([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    const loadFavorites = async () => {
      if (favorites.length === 0) {
        setLoading(false)
        return
      }

      try {
        const pokemonPromises = favorites.map(id => getPokemon(id))
        const pokemonData = await Promise.all(pokemonPromises)
        setFavoritePokemon(pokemonData)
      } catch (error) {
        console.error('Error loading favorites:', error)
      } finally {
        setLoading(false)
      }
    }

    loadFavorites()
  }, [favorites])

  return (
    <>
      <section className="hero">
        <div className="hero-decoration hero-decoration-1">
          <div className="pokeball pokeball-xl">
            <div className="pokeball-top"></div>
            <div className="pokeball-bottom"></div>
            <div className="pokeball-band"></div>
            <div className="pokeball-button">
              <div className="pokeball-button-inner"></div>
            </div>
          </div>
        </div>
        <div className="hero-decoration hero-decoration-2">
          <div className="pokeball pokeball-lg">
            <div className="pokeball-top"></div>
            <div className="pokeball-bottom"></div>
            <div className="pokeball-band"></div>
            <div className="pokeball-button">
              <div className="pokeball-button-inner"></div>
            </div>
          </div>
        </div>
        <div className="hero-decoration hero-decoration-3">
          <div className="pokeball pokeball-xl">
            <div className="pokeball-top"></div>
            <div className="pokeball-bottom"></div>
            <div className="pokeball-band"></div>
            <div className="pokeball-button">
              <div className="pokeball-button-inner"></div>
            </div>
          </div>
        </div>

        <div className="container hero-content">
          <h1 className="hero-title">
            Todos os dados<br />
            <span className="red-text">Pokémon</span>
          </h1>

          <p className="hero-subtitle">
            Acesse informações detalhadas sobre Pokémon, movimentos, habilidades e muito mais através de uma API completa e moderna.
          </p>
        </div>
      </section>

      <section className="cta">
        <div className="container">
          <div className="cta-box">
            <div className="cta-decoration cta-decoration-1">
              <div className="pokeball pokeball-xl">
                <div className="pokeball-top"></div>
                <div className="pokeball-bottom"></div>
                <div className="pokeball-band"></div>
                <div className="pokeball-button">
                  <div className="pokeball-button-inner"></div>
                </div>
              </div>
            </div>
            <div className="cta-decoration cta-decoration-2">
              <div className="pokeball pokeball-lg">
                <div className="pokeball-top"></div>
                <div className="pokeball-bottom"></div>
                <div className="pokeball-band"></div>
                <div className="pokeball-button">
                  <div className="pokeball-button-inner"></div>
                </div>
              </div>
            </div>

            <div className="cta-content">
              <h2 className="cta-title">Pronto para começar?</h2>
              <p className="cta-text">Temos que pegar todos!</p>
              <Link to="/pokemon" className="btn btn-secondary">
                Explorar Pokémon
              </Link>
            </div>
          </div>
        </div>
      </section>

      {favoritePokemon.length > 0 && (
        <main className="page-content">
          <div className="container">
            <div className="page-header">
              <h2 className="page-title">Meus Pokémon Favoritos</h2>
            </div>
            <div className="grid grid-3">
              {favoritePokemon.map((pokemon) => (
                <PokemonCard
                  key={pokemon.id}
                  pokemon={pokemon}
                  showFavorite
                  isFavorite={isFavorite(pokemon.id)}
                  onToggleFavorite={toggleFavorite}
                />
              ))}
            </div>
          </div>
        </main>
      )}

      {!loading && favoritePokemon.length === 0 && (
        <main className="page-content">
          <div className="container">
            <div className="card">
              <div style={{ textAlign: 'center', padding: '2rem 1rem' }}>
                <h2 className="section-title" style={{ marginBottom: '1rem' }}>
                  Nenhum favorito ainda
                </h2>
                <p className="section-text" style={{ marginBottom: '0.75rem' }}>
                  Você ainda não tem Pokémon favoritados.
                </p>
                <p className="section-text" style={{ marginBottom: '1.5rem' }}>
                  Favoritos são uma forma de guardar seus Pokémon preferidos para acesso rápido.
                </p>
                <Link to="/pokemon" className="btn btn-primary">
                  Explorar Pokémon
                </Link>
              </div>
            </div>
          </div>
        </main>
      )}
    </>
  )
}

export default Home
