import { useState, useEffect } from 'react'
import { useParams, Link } from 'react-router-dom'
import { getPokemon } from '../services/pokeapi'
import { useFavorites } from '../hooks/useFavorites'

function PokemonDetail() {
  const { id } = useParams()
  const [pokemon, setPokemon] = useState(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState(null)
  const { isFavorite, toggleFavorite } = useFavorites()

  useEffect(() => {
    loadPokemon()
  }, [id])

  const loadPokemon = async () => {
    setLoading(true)
    setError(null)
    try {
      const data = await getPokemon(id)
      setPokemon(data)
    } catch (error) {
      setError('Pokémon não encontrado.')
      console.error(error)
    } finally {
      setLoading(false)
    }
  }

  const formatName = (name) => {
    return name.charAt(0).toUpperCase() + name.slice(1)
  }

  const formatHeight = (height) => {
    return `${(height / 10).toFixed(1)} m`
  }

  const formatWeight = (weight) => {
    return `${(weight / 10).toFixed(1)} kg`
  }

  if (loading) {
    return (
      <>
        <div className="page-header">
          <div className="container">
            <h2 className="page-title">Detalhes do Pokémon</h2>
          </div>
        </div>
        <main className="page-content">
          <div className="container">
            <div className="loading">
              <div className="loading-spinner"></div>
              <p className="section-text" style={{ marginTop: '1rem' }}>
                Carregando...
              </p>
            </div>
          </div>
        </main>
      </>
    )
  }

  if (error || !pokemon) {
    return (
      <>
        <div className="page-header">
          <div className="container">
            <h2 className="page-title">Detalhes do Pokémon</h2>
          </div>
        </div>
        <main className="page-content">
          <div className="container">
            <div className="card">
              <p className="section-text" style={{ color: 'var(--pokemon-red)' }}>
                {error || 'Pokémon não encontrado.'}
              </p>
              <Link to="/pokemon" className="btn btn-primary" style={{ marginTop: '1rem' }}>
                Voltar para lista
              </Link>
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
          <h2 className="page-title">Detalhes do Pokémon</h2>
        </div>
      </div>

      <main className="page-content">
        <div className="container">
          <div className="card" style={{ position: 'relative' }}>
            <button
              onClick={() => toggleFavorite(pokemon.id)}
              style={{
                position: 'absolute',
                top: '1rem',
                right: '1rem',
                background: 'none',
                border: 'none',
                cursor: 'pointer',
                fontSize: '2rem',
                color: isFavorite(pokemon.id) ? '#FFD700' : '#ccc',
                padding: 0,
                lineHeight: 1
              }}
              title={isFavorite(pokemon.id) ? 'Remover dos favoritos' : 'Adicionar aos favoritos'}
            >
              {isFavorite(pokemon.id) ? '★' : '☆'}
            </button>

            <h1 className="page-title">{formatName(pokemon.name)}</h1>

            {pokemon.sprites?.front_default && (
              <img
                src={pokemon.sprites.front_default}
                alt={formatName(pokemon.name)}
                style={{ marginBottom: '1.5rem' }}
              />
            )}

            <div className="grid">
              <div>
                <p className="section-text">
                  <strong>Altura:</strong> {formatHeight(pokemon.height)}
                </p>
              </div>
              <div>
                <p className="section-text">
                  <strong>Peso:</strong> {formatWeight(pokemon.weight)}
                </p>
              </div>
            </div>

            <div className="stack section-group">
              <h2 className="section-title">Tipos</h2>
              <div className="row">
                {pokemon.types?.map((type) => (
                  <span key={type.type.name} className="pill">
                    {formatName(type.type.name)}
                  </span>
                ))}
              </div>
            </div>

            <div className="stack section-group">
              <h2 className="section-title">Movimentos</h2>
              <div className="row">
                {pokemon.moves?.slice(0, 20).map((move) => (
                  <span key={move.move.name} className="pill">
                    {formatName(move.move.name)}
                  </span>
                ))}
                {pokemon.moves?.length > 20 && (
                  <span className="pill">+{pokemon.moves.length - 20} mais</span>
                )}
              </div>
            </div>

            <div className="stack section-group">
              <h2 className="section-title">Habilidades</h2>
              <div className="row">
                {pokemon.abilities?.map((ability) => (
                  <span key={ability.ability.name} className="pill">
                    {formatName(ability.ability.name)}
                  </span>
                ))}
              </div>
            </div>

            <div style={{ marginTop: '2rem', paddingTop: '2rem', borderTop: '1px solid var(--border)' }}>
              <Link to="/pokemon" className="btn btn-secondary">
                Voltar para lista
              </Link>
            </div>
          </div>
        </div>
      </main>
    </>
  )
}

export default PokemonDetail
