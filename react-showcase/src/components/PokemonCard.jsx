import { Link } from 'react-router-dom'

function PokemonCard({ pokemon, showFavorite = false, isFavorite = false, onToggleFavorite }) {
  const formatName = (name) => {
    return name.charAt(0).toUpperCase() + name.slice(1)
  }

  const formatHeight = (height) => {
    return `${(height / 10).toFixed(1)} m`
  }

  const formatWeight = (weight) => {
    return `${(weight / 10).toFixed(1)} kg`
  }

  return (
    <div className="card pokemon-card" style={{ position: 'relative' }}>
      <div className="pokemon-info">
        <h3 className="section-title">{formatName(pokemon.name)}</h3>
        <p className="section-text">Altura: {formatHeight(pokemon.height)}</p>
        <p className="section-text">Peso: {formatWeight(pokemon.weight)}</p>
        <Link to={`/pokemon/${pokemon.id}`} className="text-link">
          Ver detalhes
        </Link>
      </div>
      {pokemon.sprites?.front_default && (
        <img 
          src={pokemon.sprites.front_default} 
          alt={formatName(pokemon.name)} 
          className="pokemon-sprite"
        />
      )}
      
      {showFavorite && (
        <button
          onClick={() => onToggleFavorite && onToggleFavorite(pokemon.id)}
          style={{
            position: 'absolute',
            bottom: '0.5rem',
            right: '0.5rem',
            background: 'none',
            border: 'none',
            cursor: 'pointer',
            fontSize: '1.5rem',
            color: isFavorite ? '#FFD700' : '#ccc',
            padding: 0,
            lineHeight: 1
          }}
          title={isFavorite ? 'Remover dos favoritos' : 'Adicionar aos favoritos'}
        >
          {isFavorite ? '★' : '☆'}
        </button>
      )}
    </div>
  )
}

export default PokemonCard
