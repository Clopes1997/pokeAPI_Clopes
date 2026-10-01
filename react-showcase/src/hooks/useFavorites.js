import { useState, useEffect } from 'react'

const FAVORITES_KEY = 'pokeapi_favorites'

export function useFavorites() {
  const [favorites, setFavorites] = useState(() => {
    const stored = localStorage.getItem(FAVORITES_KEY)
    return stored ? JSON.parse(stored) : []
  })

  useEffect(() => {
    localStorage.setItem(FAVORITES_KEY, JSON.stringify(favorites))
  }, [favorites])

  const addFavorite = (pokemonId) => {
    setFavorites(prev => {
      if (!prev.includes(pokemonId)) {
        return [...prev, pokemonId]
      }
      return prev
    })
  }

  const removeFavorite = (pokemonId) => {
    setFavorites(prev => prev.filter(id => id !== pokemonId))
  }

  const toggleFavorite = (pokemonId) => {
    if (favorites.includes(pokemonId)) {
      removeFavorite(pokemonId)
    } else {
      addFavorite(pokemonId)
    }
  }

  const isFavorite = (pokemonId) => {
    return favorites.includes(pokemonId)
  }

  return {
    favorites,
    addFavorite,
    removeFavorite,
    toggleFavorite,
    isFavorite
  }
}
