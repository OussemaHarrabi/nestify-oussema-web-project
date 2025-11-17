// composables/api/useProjectsApi.ts - Projects API
import type { Project, PaginatedResponse } from '~/types/api'

interface ProjectFilters {
  city?: string
  status?: string
  min_price?: number
  max_price?: number
  amenities?: string
  available_units?: boolean
  featured?: boolean
  sort?: 'created_at' | 'price' | 'views'
  order?: 'asc' | 'desc'
  per_page?: number
  page?: number
}

export const useProjectsApi = () => {
  const { apiFetch } = useApi()

  // PUBLIC: Get all published projects with filters
  const getProjects = async (filters: ProjectFilters = {}) => {
    const query = new URLSearchParams()
    
    Object.entries(filters).forEach(([key, value]) => {
      if (value !== undefined && value !== null && value !== '') {
        query.append(key, String(value))
      }
    })

    return apiFetch<PaginatedResponse<Project>>(`/projects?${query.toString()}`)
  }

  // PUBLIC: Get project by ID
  const getProjectById = async (id: number | string) => {
    return apiFetch<Project>(`/projects/${id}`)
  }

  // PUBLIC: Get project by slug
  const getProjectBySlug = async (slug: string) => {
    return apiFetch<Project>(`/projects/${slug}`)
  }

  // PUBLIC: Get properties in a project
  const getProjectProperties = async (id: number | string, filters: any = {}) => {
    const query = new URLSearchParams()
    
    Object.entries(filters).forEach(([key, value]) => {
      if (value !== undefined && value !== null && value !== '') {
        query.append(key, String(value))
      }
    })

    return apiFetch<PaginatedResponse<any>>(`/projects/${id}/properties?${query.toString()}`)
  }

  return {
    getProjects,
    getProjectById,
    getProjectBySlug,
    getProjectProperties,
  }
}
