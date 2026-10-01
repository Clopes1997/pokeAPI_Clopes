import axios from 'axios'

const BASE_URL = 'https://pokeapi.co/api/v2'

const api = axios.create({
  baseURL: BASE_URL,
  timeout: 10000
})

export const getPokemon = async (id) => {
  try {
    const response = await api.get(`/pokemon/${id}`)
    return response.data
  } catch (error) {
    console.error(`Error fetching Pokemon ${id}:`, error)
    throw error
  }
}

export const getPokemonList = async (offset = 0, limit = 20) => {
  try {
    const response = await api.get(`/pokemon?offset=${offset}&limit=${limit}`)
    const results = await Promise.all(
      response.data.results.map(async (pokemon) => {
        const id = pokemon.url.split('/').filter(Boolean).pop()
        return await getPokemon(id)
      })
    )
    return {
      results,
      count: response.data.count,
      next: response.data.next,
      previous: response.data.previous
    }
  } catch (error) {
    console.error('Error fetching Pokemon list:', error)
    throw error
  }
}

export const searchPokemon = async (query) => {
  try {
    // Try to get by ID first
    if (!isNaN(query)) {
      return await getPokemon(query)
    }
    // Otherwise search by name
    return await getPokemon(query.toLowerCase())
  } catch (error) {
    console.error(`Error searching Pokemon: ${query}`, error)
    throw error
  }
}
