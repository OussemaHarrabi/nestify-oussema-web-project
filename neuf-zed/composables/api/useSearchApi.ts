// composables/api/useSearchApi.ts - Search & Filter Options API

interface SearchParams {
  query?: string
  city?: string
  type?: 'project' | 'property'
  min_price?: number
  max_price?: number
}

interface FilterOptions {
  cities: Array<{ value: string; label: string; count: number }>
  property_types: Array<{ value: string; label: string; count: number }>
  amenities: Array<{ value: string; label: string; count: number }>
  price_ranges: Array<{ min: number; max: number; label: string }>
}

export const useSearchApi = () => {
  const { apiFetch } = useApi()

  // PUBLIC: Search projects and properties
  const search = async (params: SearchParams = {}) => {
    const query = new URLSearchParams()
    
    Object.entries(params).forEach(([key, value]) => {
      if (value !== undefined && value !== null && value !== '') {
        query.append(key, String(value))
      }
    })

    return apiFetch<any>(`/search?${query.toString()}`)
  }

  // PUBLIC: Get available cities
  const getCities = async () => {
    return apiFetch<Array<{ name: string; count: number }>>('/cities')
  }

  // PUBLIC: Get property types
  const getPropertyTypes = async () => {
    return apiFetch<Array<{ name: string; count: number }>>('/property-types')
  }

  // PUBLIC: Get all filter options (cities, types, amenities, etc.)
  const getFilterOptions = async () => {
    return apiFetch<FilterOptions>('/filters/options')
  }

  return {
    search,
    getCities,
    getPropertyTypes,
    getFilterOptions,
  }
}
