// composables/api/usePropertiesApi.ts - Properties/Listings API
import type { Property, PaginatedResponse } from '~/types/api'

interface PropertyFilters {
  project_id?: number
  city?: string
  type?: string
  min_price?: number
  max_price?: number
  min_surface?: number
  max_surface?: number
  bedrooms?: number
  bathrooms?: number
  availability_status?: 'available' | 'reserved' | 'sold'
  is_vefa?: boolean
  sort?: 'price' | 'surface' | 'created_at'
  order?: 'asc' | 'desc'
  per_page?: number
  page?: number
}

export const usePropertiesApi = () => {
  const { apiFetch } = useApi()

  // PUBLIC: Get all available properties with filters
  const getProperties = async (filters: PropertyFilters = {}) => {
    const query = new URLSearchParams()
    
    Object.entries(filters).forEach(([key, value]) => {
      if (value !== undefined && value !== null && value !== '') {
        query.append(key, String(value))
      }
    })

    return apiFetch<PaginatedResponse<Property>>(`/properties?${query.toString()}`)
  }

  // PUBLIC: Get property by ID
  const getPropertyById = async (id: number | string) => {
    return apiFetch<Property>(`/properties/${id}`)
  }

  // PUBLIC: Get similar properties
  const getSimilarProperties = async (id: number | string) => {
    return apiFetch<Property[]>(`/properties/${id}/similar`)
  }

  return {
    getProperties,
    getPropertyById,
    getSimilarProperties,
  }
}
