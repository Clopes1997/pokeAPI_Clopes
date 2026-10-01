import { useState, useEffect } from 'react'
import { getPokemon } from '../services/pokeapi'
import { useFavorites } from '../hooks/useFavorites'
import PokemonCard from '../components/PokemonCard'

function Favorites() {
  const { favorites } = useFavorites()
  const [favoritePokemon, setFavoritePokemon] = useState([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    const loadFavorites = async () => {
      if (favorites.length === 0) {
        setLoading(false)
        return
      }

      setLoading(true)
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

  if (loading) {
    return (
      <>
        <div className="page-header">
          <div className="container">
            <h2 className="page-title">Meus Favoritos</h2>
          </div>
        </div>
        <main className="page-content">
          <div className="container">
            <div className="loading">
              <div className="loading-spinner"></div>
              <p className="section-text" style={{ marginTop: '1rem' }}>
                Carregando favoritos...
              </p>
            </div>
          </div>
        </main>
      </>
    )
  }

  return (
    <>
      <div className="page-header">
        <div className="container">
          <h2 className="page-title">Meus Favoritos</h2>
        </div>
      </div>

      <main className="page-content">
        <div className="container">
          <div className="card">
            {favoritePokemon.length > 0 ? (
              <div className="grid grid-3">
                {favoritePokemon.map((pokemon) => (
                  <PokemonCard key={pokemon.id} pokemon={pokemon} />
                ))}
              </div>
            ) : (
              <p className="section-text">Você ainda não tem Pokémon favoritados.</p>
            )}
          </div>
        </div>
      </main>
    </>
  )
}

export default Favorites
