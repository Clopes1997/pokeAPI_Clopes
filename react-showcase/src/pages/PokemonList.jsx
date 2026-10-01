import { useState, useEffect } from 'react'
import { getPokemonList, searchPokemon } from '../services/pokeapi'
import PokemonCard from '../components/PokemonCard'
import { useFavorites } from '../hooks/useFavorites'

function PokemonList() {
  const [pokemon, setPokemon] = useState([])
  const [loading, setLoading] = useState(true)
  const [searchTerm, setSearchTerm] = useState('')
  const [typeFilter, setTypeFilter] = useState('')
  const [page, setPage] = useState(0)
  const [totalPages, setTotalPages] = useState(1)
  const [error, setError] = useState(null)
  const { isFavorite, toggleFavorite } = useFavorites()

  const ITEMS_PER_PAGE = 20

  useEffect(() => {
    loadPokemon()
  }, [page])

  const loadPokemon = async () => {
    setLoading(true)
    setError(null)
    try {
      const offset = page * ITEMS_PER_PAGE
      const data = await getPokemonList(offset, ITEMS_PER_PAGE)
      setPokemon(data.results)
      setTotalPages(Math.ceil(data.count / ITEMS_PER_PAGE))
    } catch (error) {
      setError('Erro ao carregar Pokémon. Tente novamente.')
      console.error(error)
    } finally {
      setLoading(false)
    }
  }

  const handleSearch = async (e) => {
    e.preventDefault()
    if (!searchTerm.trim()) {
      loadPokemon()
      return
    }

    setLoading(true)
    setError(null)
    try {
      const result = await searchPokemon(searchTerm.trim())
      setPokemon([result])
      setTotalPages(1)
    } catch (error) {
      setError('Pokémon não encontrado.')
      setPokemon([])
    } finally {
      setLoading(false)
    }
  }

  const filteredPokemon = pokemon.filter((p) => {
    if (typeFilter) {
      return p.types?.some(
        (type) => type.type.name.toLowerCase() === typeFilter.toLowerCase()
      )
    }
    return true
  })

  return (
    <>
      <div className="page-header">
        <div className="container">
          <h2 className="page-title">Lista de Pokémon</h2>
        </div>
      </div>

      <main className="page-content">
        <div className="container">
          <div className="card">
            <form onSubmit={handleSearch} className="search-form">
              <div className="row">
                <input
                  type="text"
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                  placeholder="Buscar por nome ou ID"
                  className="input"
                />
                <input
                  type="text"
                  value={typeFilter}
                  onChange={(e) => setTypeFilter(e.target.value)}
                  placeholder="Filtrar por tipo"
                  className="input"
                />
                <button type="submit" className="btn btn-primary">
                  Buscar
                </button>
                {searchTerm && (
                  <button
                    type="button"
                    onClick={() => {
                      setSearchTerm('')
                      setTypeFilter('')
                      setPage(0)
                      loadPokemon()
                    }}
                    className="btn btn-secondary"
                  >
                    Limpar
                  </button>
                )}
              </div>
            </form>
          </div>

          {error && (
            <div className="card">
              <p className="section-text" style={{ color: 'var(--pokemon-red)' }}>
                {error}
              </p>
            </div>
          )}

          {loading ? (
            <div className="loading">
              <div className="loading-spinner"></div>
              <p className="section-text" style={{ marginTop: '1rem' }}>
                Carregando Pokémon...
              </p>
            </div>
          ) : filteredPokemon.length > 0 ? (
            <>
              <div className="grid grid-3">
                {filteredPokemon.map((p) => (
                  <PokemonCard
                    key={p.id}
                    pokemon={p}
                    showFavorite
                    isFavorite={isFavorite(p.id)}
                    onToggleFavorite={toggleFavorite}
                  />
                ))}
              </div>

              {!searchTerm && totalPages > 1 && (
                <div style={{ marginTop: '2rem', display: 'flex', justifyContent: 'center', gap: '0.5rem' }}>
                  <button
                    onClick={() => setPage(prev => Math.max(0, prev - 1))}
                    disabled={page === 0}
                    className="btn btn-secondary"
                  >
                    Anterior
                  </button>
                  <span style={{ display: 'flex', alignItems: 'center', padding: '0 1rem' }}>
                    Página {page + 1} de {totalPages}
                  </span>
                  <button
                    onClick={() => setPage(prev => Math.min(totalPages - 1, prev + 1))}
                    disabled={page >= totalPages - 1}
                    className="btn btn-secondary"
                  >
                    Próxima
                  </button>
                </div>
              )}
            </>
          ) : (
            <div className="card">
              <p className="section-text">Nenhum Pokémon encontrado.</p>
            </div>
          )}
        </div>
      </main>
    </>
  )
}

export default PokemonList
