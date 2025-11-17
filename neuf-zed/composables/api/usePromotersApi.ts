// composables/api/usePromotersApi.ts - Promoters/Developers API
import type { Promoter, Project, PaginatedResponse } from '~/types/api'

interface PromoterFilters {
  verified?: boolean
  search?: string
  per_page?: number
  page?: number
}

export const usePromotersApi = () => {
  const { apiFetch } = useApi()

  // PUBLIC: Get all promoters
  const getPromoters = async (filters: PromoterFilters = {}) => {
    const query = new URLSearchParams()
    
    Object.entries(filters).forEach(([key, value]) => {
      if (value !== undefined && value !== null && value !== '') {
        query.append(key, String(value))
      }
    })

    return apiFetch<PaginatedResponse<Promoter>>(`/promoters?${query.toString()}`)
  }

  // PUBLIC: Get promoter by ID
  const getPromoterById = async (id: number | string) => {
    return apiFetch<Promoter>(`/promoters/${id}`)
  }

  // PUBLIC: Get promoter's projects
  const getPromoterProjects = async (id: number | string) => {
    return apiFetch<PaginatedResponse<Project>>(`/promoters/${id}/projects`)
  }

  return {
    getPromoters,
    getPromoterById,
    getPromoterProjects,
  }
}
