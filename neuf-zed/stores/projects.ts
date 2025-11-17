import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type { Project as ApiProject } from '~/types/api'

// Frontend Project interface (simplified from backend)
export interface Project {
  id: number
  title: string
  location: string
  image: string
  price: string
  type: string
  status: string
  developer: string
  developerLogo: string
  publishDate?: string
  totalUnits?: number
  constructionStatus?: string
  deliveryDate?: string
  description?: string
  amenities?: Array<{ icon: any; name: string }>
  images?: string[]
  units?: Array<{
    type: string
    priceRange: string
    count: string
    details: Array<{
      type: string
      surface: string
      rooms: string
      bathrooms: string
      price: string
    }>
  }>
  coordinates?: { lat: number; lng: number }
}

// Helper function to transform backend project to frontend format
const transformProject = (apiProject: ApiProject): Project => {
  const getDefaultImage = () => {
    const images = [
      'https://images.unsplash.com/photo-1664892798972-079f15663b16?w=800',
      'https://images.unsplash.com/photo-1670352702722-058a93937fc1?w=800',
      'https://images.unsplash.com/photo-1677553512940-f79af72efd1b?w=800',
    ]
    return images[Math.floor(Math.random() * images.length)]
  }

  return {
    id: apiProject.id,
    title: apiProject.name,
    location: `${apiProject.district || apiProject.city}, ${apiProject.city}`,
    image: apiProject.cover_image || (apiProject.images && apiProject.images[0]) || getDefaultImage(),
    price: apiProject.starting_price ? Math.floor(apiProject.starting_price).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') : '0',
    type: `Projet immobilier ${apiProject.total_units} unités`,
    status: apiProject.construction_progress || getStatusLabel(apiProject.status),
    developer: apiProject.promoter?.company_name || 'Promoteur',
    developerLogo: apiProject.promoter?.company_name?.split(' ').map(w => w[0]).join('').slice(0, 4) || 'XX',
    publishDate: apiProject.published_at ? new Date(apiProject.published_at).toLocaleDateString('fr-FR') : undefined,
    totalUnits: apiProject.total_units,
    constructionStatus: apiProject.construction_progress || undefined,
    deliveryDate: apiProject.expected_delivery ? new Date(apiProject.expected_delivery).toLocaleDateString('fr-FR') : undefined,
    description: apiProject.description || undefined,
    images: apiProject.images || [getDefaultImage()],
    coordinates: apiProject.latitude && apiProject.longitude ? {
      lat: Number(apiProject.latitude),
      lng: Number(apiProject.longitude)
    } : undefined
  }
}

const getStatusLabel = (status: string): string => {
  const labels: Record<string, string> = {
    'planning': 'En planification',
    'under_construction': 'En construction',
    'near_completion': 'Livraison imminente',
    'completed': 'Finalisé'
  }
  return labels[status] || status
}

export const useProjectsStore = defineStore('projects', () => {
  // State
  const projects = ref<Project[]>([])
  const isLoading = ref(false)
  const error = ref<string | null>(null)
  const searchFilters = ref({
    location: '',
    type: '',
    budget: '',
    minPrice: 0,
    maxPrice: 1000000
  })

  // Getters
  const allProjects = computed(() => projects.value)

  const filteredProjects = computed(() => {
    let filtered = projects.value

    if (searchFilters.value.location) {
      filtered = filtered.filter(p =>
        p.location.toLowerCase().includes(searchFilters.value.location.toLowerCase())
      )
    }

    if (searchFilters.value.type) {
      filtered = filtered.filter(p =>
        p.type.toLowerCase().includes(searchFilters.value.type.toLowerCase())
      )
    }

    if (searchFilters.value.minPrice > 0) {
      filtered = filtered.filter(p => {
        const price = parseInt(p.price.replace(/\s/g, ''))
        return price >= searchFilters.value.minPrice
      })
    }

    if (searchFilters.value.maxPrice < 1000000) {
      filtered = filtered.filter(p => {
        const price = parseInt(p.price.replace(/\s/g, ''))
        return price <= searchFilters.value.maxPrice
      })
    }

    return filtered
  })

  const featuredProjects = computed(() =>
    projects.value.slice(0, 3)
  )

  const projectById = (id: number) => {
    return projects.value.find(p => p.id === id)
  }

  const projectsByDeveloper = (developerName: string) => {
    return projects.value.filter(p =>
      p.developer.toLowerCase() === developerName.toLowerCase()
    )
  }

  // Actions
  const fetchProjects = async () => {
    isLoading.value = true
    error.value = null

    try {
      const { getProjects } = useProjectsApi()
      const response = await getProjects({ per_page: 50 })
      
      // Transform API projects to frontend format
      projects.value = response.data.map(transformProject)
    } catch (e: any) {
      error.value = e.message || 'Failed to fetch projects'
      console.error('Error fetching projects:', e)
      
      // Fallback to empty array on error
      projects.value = []
    } finally {
      isLoading.value = false
    }
  }

  const fetchProjectById = async (id: number) => {
    isLoading.value = true
    error.value = null

    try {
      const { getProjectById } = useProjectsApi()
      const apiProject = await getProjectById(id)
      return transformProject(apiProject)
    } catch (e: any) {
      error.value = e.message || 'Failed to fetch project'
      console.error('Error fetching project:', e)
      return null
    } finally {
      isLoading.value = false
    }
  }

  const addProject = (project: Omit<Project, 'id'>) => {
    const newProject: Project = {
      ...project,
      id: Math.max(...projects.value.map(p => p.id)) + 1
    }
    projects.value.push(newProject)
    return newProject
  }

  const updateProject = (id: number, updates: Partial<Project>) => {
    const index = projects.value.findIndex(p => p.id === id)
    if (index !== -1) {
      projects.value[index] = { ...projects.value[index], ...updates }
      return projects.value[index]
    }
    return null
  }

  const deleteProject = (id: number) => {
    const index = projects.value.findIndex(p => p.id === id)
    if (index !== -1) {
      projects.value.splice(index, 1)
      return true
    }
    return false
  }

  const setSearchFilters = (filters: Partial<typeof searchFilters.value>) => {
    searchFilters.value = { ...searchFilters.value, ...filters }
  }

  const clearSearchFilters = () => {
    searchFilters.value = {
      location: '',
      type: '',
      budget: '',
      minPrice: 0,
      maxPrice: 1000000
    }
  }

  return {
    // State
    projects,
    isLoading,
    error,
    searchFilters,

    // Getters
    allProjects,
    filteredProjects,
    featuredProjects,
    projectById,
    projectsByDeveloper,

    // Actions
    fetchProjects,
    fetchProjectById,
    addProject,
    updateProject,
    deleteProject,
    setSearchFilters,
    clearSearchFilters
  }
})
