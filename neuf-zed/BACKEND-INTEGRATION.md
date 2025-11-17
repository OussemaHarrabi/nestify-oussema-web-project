# 🔌 BACKEND API INTEGRATION ROADMAP
## Connecting Nuxt.js Frontend to Backend APIs

---

## 📋 TABLE OF CONTENTS

1. [API Architecture Overview](#api-architecture-overview)
2. [Required API Endpoints](#required-api-endpoints)
3. [Data Models](#data-models)
4. [Service Layer Setup](#service-layer-setup)
5. [Pinia Store Integration](#pinia-store-integration)
6. [Authentication Flow](#authentication-flow)
7. [Error Handling](#error-handling)
8. [Loading States](#loading-states)
9. [Image Upload](#image-upload)
10. [Testing Strategy](#testing-strategy)

---

## 🏗️ API ARCHITECTURE OVERVIEW

### **Backend Tech Stack (Assumed)**
- REST API
- Authentication: JWT tokens
- File uploads: Multipart/form-data
- Base URL: `https://api.neuf.tn` or `http://localhost:8000`

### **Frontend Integration Points**
```
Nuxt.js App
    ↓
Service Layer (API clients)
    ↓
Pinia Stores (state management)
    ↓
Components (UI)
```

---

## 🌐 REQUIRED API ENDPOINTS

### **1. Authentication Endpoints**

#### **POST /api/auth/register**
Register new developer account
```typescript
Request:
{
  email: string
  password: string
  companyName: string
  phoneNumber: string
  businessLicense: string
}

Response: 201 Created
{
  user: {
    id: number
    email: string
    companyName: string
    role: 'developer'
  }
  accessToken: string
  refreshToken: string
}
```

#### **POST /api/auth/login**
Login existing user
```typescript
Request:
{
  email: string
  password: string
}

Response: 200 OK
{
  user: {
    id: number
    email: string
    companyName: string
    role: 'developer' | 'admin'
  }
  accessToken: string
  refreshToken: string
}
```

#### **POST /api/auth/logout**
Logout user
```typescript
Request:
{
  refreshToken: string
}

Response: 204 No Content
```

#### **GET /api/auth/me**
Get current authenticated user
```typescript
Response: 200 OK
{
  id: number
  email: string
  companyName: string
  role: 'developer'
  profile: {
    phone: string
    logo: string
    bio: string
  }
}
```

#### **POST /api/auth/refresh**
Refresh access token
```typescript
Request:
{
  refreshToken: string
}

Response: 200 OK
{
  accessToken: string
  refreshToken: string
}
```

---

### **2. Projects Endpoints**

#### **GET /api/projects**
List all published projects (public)
```typescript
Query Params:
- page: number (default: 1)
- limit: number (default: 20)
- location?: string
- type?: string
- minPrice?: number
- maxPrice?: number
- status?: 'available' | 'sold'

Response: 200 OK
{
  projects: Project[]
  meta: {
    total: number
    page: number
    limit: number
    totalPages: number
  }
}
```

#### **GET /api/projects/:id**
Get single project details (public)
```typescript
Response: 200 OK
{
  id: number
  title: string
  location: string
  coordinates: { lat: number, lng: number }
  description: string
  image: string
  images: string[]
  price: string
  type: string
  status: string
  developer: {
    id: number
    name: string
    logo: string
  }
  amenities: string[]
  units: Unit[]
  publishedAt: string
  deliveryDate: string
}
```

#### **GET /api/developers/:developerId/projects**
Get all projects by developer (authenticated)
```typescript
Response: 200 OK
{
  projects: Project[]
}
```

#### **POST /api/projects**
Create new project (authenticated, developer only)
```typescript
Request:
{
  title: string
  location: string
  address: string
  coordinates: { lat: number, lng: number }
  description: string
  propertyTypes: string[]
  amenities: string[]
  deliveryDate: string
  constructionStatus: string
  images?: File[]
  brochure?: File
}

Response: 201 Created
{
  id: number
  title: string
  ...
}
```

#### **PUT /api/projects/:id**
Update project (authenticated, owner only)
```typescript
Request: (same as POST, all fields optional)

Response: 200 OK
{
  id: number
  title: string
  ...
}
```

#### **DELETE /api/projects/:id**
Delete project (authenticated, owner only)
```typescript
Response: 204 No Content
```

#### **PATCH /api/projects/:id/publish**
Publish/unpublish project (authenticated, owner only)
```typescript
Request:
{
  isPublished: boolean
}

Response: 200 OK
{
  id: number
  isPublished: boolean
}
```

---

### **3. Listings Endpoints**

#### **GET /api/listings**
List all published listings (public)
```typescript
Query Params:
- projectId?: number
- type?: string
- minPrice?: number
- maxPrice?: number
- minSurface?: number
- maxSurface?: number
- rooms?: number
- status?: 'available' | 'reserved' | 'sold'

Response: 200 OK
{
  listings: Listing[]
}
```

#### **GET /api/listings/:id**
Get single listing details (public)
```typescript
Response: 200 OK
{
  id: number
  projectId: number
  type: string
  surface: number
  landSurface?: number
  price: string
  bedrooms: number
  bathrooms: number
  floor: string
  orientation: string
  isFurnished: boolean
  equipment: string[]
  description: string
  images: string[]
  status: 'available' | 'reserved' | 'sold'
  project: {
    id: number
    title: string
    location: string
    developer: Developer
  }
}
```

#### **POST /api/listings**
Create new listing (authenticated, developer only)
```typescript
Request:
{
  projectId: number
  type: string
  surface: number
  landSurface?: number
  price: string
  bedrooms: number
  bathrooms: number
  floor: string
  orientation: string
  isFurnished: boolean
  equipment: string[]
  description: string
  images?: File[]
}

Response: 201 Created
{
  id: number
  type: string
  ...
}
```

#### **PUT /api/listings/:id**
Update listing (authenticated, owner only)
```typescript
Request: (same as POST, all fields optional)

Response: 200 OK
{
  id: number
  type: string
  ...
}
```

#### **DELETE /api/listings/:id**
Delete listing (authenticated, owner only)
```typescript
Response: 204 No Content
```

#### **PATCH /api/listings/:id/status**
Update listing status (authenticated, owner only)
```typescript
Request:
{
  status: 'available' | 'reserved' | 'sold'
}

Response: 200 OK
{
  id: number
  status: string
}
```

---

### **4. Contacts/Leads Endpoints**

#### **GET /api/contacts**
Get all contacts for developer (authenticated, developer only)
```typescript
Query Params:
- projectId?: number
- status?: 'new' | 'replied' | 'closed'

Response: 200 OK
{
  contacts: Contact[]
}
```

#### **GET /api/contacts/:id**
Get single contact details (authenticated)
```typescript
Response: 200 OK
{
  id: number
  firstName: string
  lastName: string
  email: string
  phone: string
  message: string
  projectId: number
  listingId?: number
  status: 'new' | 'replied' | 'closed'
  createdAt: string
}
```

#### **POST /api/contacts**
Submit contact form (public)
```typescript
Request:
{
  firstName: string
  lastName: string
  email: string
  phone: string
  message?: string
  projectId: number
  listingId?: number
}

Response: 201 Created
{
  id: number
  status: 'new'
}
```

#### **PATCH /api/contacts/:id**
Update contact status (authenticated, developer only)
```typescript
Request:
{
  status: 'new' | 'replied' | 'closed'
  notes?: string
}

Response: 200 OK
{
  id: number
  status: string
}
```

---

### **5. Developers Endpoints**

#### **GET /api/developers**
List all verified developers (public)
```typescript
Response: 200 OK
{
  developers: Developer[]
}
```

#### **GET /api/developers/:id**
Get developer profile (public)
```typescript
Response: 200 OK
{
  id: number
  companyName: string
  logo: string
  bio: string
  phone: string
  email: string
  website?: string
  certifications: string[]
  strengths: string[]
  projects: Project[]
  stats: {
    totalProjects: number
    totalListings: number
    completedProjects: number
  }
}
```

#### **PUT /api/developers/:id**
Update developer profile (authenticated, owner only)
```typescript
Request:
{
  companyName?: string
  bio?: string
  phone?: string
  website?: string
  logo?: File
  certifications?: string[]
  strengths?: string[]
}

Response: 200 OK
{
  id: number
  companyName: string
  ...
}
```

---

### **6. Media/Upload Endpoints**

#### **POST /api/media/upload**
Upload single file (authenticated)
```typescript
Request: multipart/form-data
{
  file: File
  type: 'project' | 'listing' | 'profile'
}

Response: 201 Created
{
  url: string
  filename: string
  size: number
  mimeType: string
}
```

#### **POST /api/media/upload-multiple**
Upload multiple files (authenticated)
```typescript
Request: multipart/form-data
{
  files: File[]
  type: 'project' | 'listing'
}

Response: 201 Created
{
  urls: string[]
}
```

---

## 📊 DATA MODELS

### **Project Model**
```typescript
interface Project {
  id: number
  title: string
  location: string
  address: string
  coordinates: {
    lat: number
    lng: number
  }
  description: string
  image: string
  images: string[]
  price: string
  type: string
  status: string
  propertyTypes: string[]
  amenities: string[]
  deliveryDate: string
  constructionStatus: string
  brochure?: string
  isPublished: boolean
  developerId: number
  developer: Developer
  listings: Listing[]
  totalListings: number
  availableListings: number
  reservedListings: number
  soldListings: number
  totalViews: number
  totalContacts: number
  createdAt: string
  updatedAt: string
  publishedAt?: string
}
```

### **Listing Model**
```typescript
interface Listing {
  id: number
  projectId: number
  type: string
  surface: number
  landSurface?: number
  price: string
  bedrooms: number
  bathrooms: number
  floor: string
  orientation: string
  isFurnished: boolean
  equipment: string[]
  description: string
  images: string[]
  status: 'available' | 'reserved' | 'sold'
  project: Project
  createdAt: string
  updatedAt: string
}
```

### **Contact Model**
```typescript
interface Contact {
  id: number
  firstName: string
  lastName: string
  email: string
  phone: string
  message?: string
  projectId: number
  listingId?: number
  status: 'new' | 'replied' | 'closed'
  notes?: string
  project: Project
  listing?: Listing
  createdAt: string
  updatedAt: string
}
```

### **Developer Model**
```typescript
interface Developer {
  id: number
  companyName: string
  logo: string
  bio: string
  phone: string
  email: string
  website?: string
  businessLicense: string
  certifications: string[]
  strengths: string[]
  isVerified: boolean
  projects: Project[]
  stats: {
    totalProjects: number
    totalListings: number
    completedProjects: number
  }
  createdAt: string
  updatedAt: string
}
```

### **User Model**
```typescript
interface User {
  id: number
  email: string
  role: 'developer' | 'admin' | 'user'
  developerId?: number
  developer?: Developer
  createdAt: string
  updatedAt: string
}
```

---

## 🔧 SERVICE LAYER SETUP

### **1. Create API Client**

**File:** `services/api.ts`
```typescript
import { $fetch, type FetchOptions } from 'ofetch'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api'

export const api = $fetch.create({
  baseURL: API_BASE_URL,
  
  // Add auth token to all requests
  onRequest({ options }) {
    const token = localStorage.getItem('accessToken')
    if (token) {
      options.headers = {
        ...options.headers,
        Authorization: `Bearer ${token}`
      }
    }
  },

  // Handle errors globally
  onResponseError({ response }) {
    if (response.status === 401) {
      // Token expired, try refresh
      refreshToken()
    }
  }
})

async function refreshToken() {
  try {
    const refreshToken = localStorage.getItem('refreshToken')
    if (!refreshToken) {
      throw new Error('No refresh token')
    }

    const { accessToken, refreshToken: newRefreshToken } = await api('/auth/refresh', {
      method: 'POST',
      body: { refreshToken }
    })

    localStorage.setItem('accessToken', accessToken)
    localStorage.setItem('refreshToken', newRefreshToken)
  } catch (error) {
    // Refresh failed, logout user
    localStorage.removeItem('accessToken')
    localStorage.removeItem('refreshToken')
    navigateTo('/login')
  }
}
```

---

### **2. Create Service Files**

#### **File:** `services/projects.ts`
```typescript
import { api } from './api'
import type { Project } from '~/types'

export const projectsService = {
  async getAll(params?: {
    page?: number
    limit?: number
    location?: string
    type?: string
    minPrice?: number
    maxPrice?: number
  }) {
    return api<{ projects: Project[], meta: any }>('/projects', {
      params
    })
  },

  async getById(id: number) {
    return api<Project>(`/projects/${id}`)
  },

  async create(data: Partial<Project>) {
    return api<Project>('/projects', {
      method: 'POST',
      body: data
    })
  },

  async update(id: number, data: Partial<Project>) {
    return api<Project>(`/projects/${id}`, {
      method: 'PUT',
      body: data
    })
  },

  async delete(id: number) {
    return api<void>(`/projects/${id}`, {
      method: 'DELETE'
    })
  },

  async publish(id: number, isPublished: boolean) {
    return api<Project>(`/projects/${id}/publish`, {
      method: 'PATCH',
      body: { isPublished }
    })
  },

  async getByDeveloper(developerId: number) {
    return api<{ projects: Project[] }>(`/developers/${developerId}/projects`)
  }
}
```

#### **File:** `services/listings.ts`
```typescript
import { api } from './api'
import type { Listing } from '~/types'

export const listingsService = {
  async getAll(params?: {
    projectId?: number
    type?: string
    minPrice?: number
    maxPrice?: number
    status?: string
  }) {
    return api<{ listings: Listing[] }>('/listings', {
      params
    })
  },

  async getById(id: number) {
    return api<Listing>(`/listings/${id}`)
  },

  async create(data: Partial<Listing>) {
    return api<Listing>('/listings', {
      method: 'POST',
      body: data
    })
  },

  async update(id: number, data: Partial<Listing>) {
    return api<Listing>(`/listings/${id}`, {
      method: 'PUT',
      body: data
    })
  },

  async delete(id: number) {
    return api<void>(`/listings/${id}`, {
      method: 'DELETE'
    })
  },

  async updateStatus(id: number, status: string) {
    return api<Listing>(`/listings/${id}/status`, {
      method: 'PATCH',
      body: { status }
    })
  }
}
```

#### **File:** `services/contacts.ts`
```typescript
import { api } from './api'
import type { Contact } from '~/types'

export const contactsService = {
  async getAll(params?: {
    projectId?: number
    status?: string
  }) {
    return api<{ contacts: Contact[] }>('/contacts', {
      params
    })
  },

  async getById(id: number) {
    return api<Contact>(`/contacts/${id}`)
  },

  async create(data: {
    firstName: string
    lastName: string
    email: string
    phone: string
    message?: string
    projectId: number
    listingId?: number
  }) {
    return api<Contact>('/contacts', {
      method: 'POST',
      body: data
    })
  },

  async updateStatus(id: number, status: string, notes?: string) {
    return api<Contact>(`/contacts/${id}`, {
      method: 'PATCH',
      body: { status, notes }
    })
  }
}
```

#### **File:** `services/auth.ts`
```typescript
import { api } from './api'
import type { User } from '~/types'

export const authService = {
  async login(email: string, password: string) {
    const response = await api<{
      user: User
      accessToken: string
      refreshToken: string
    }>('/auth/login', {
      method: 'POST',
      body: { email, password }
    })

    // Save tokens
    localStorage.setItem('accessToken', response.accessToken)
    localStorage.setItem('refreshToken', response.refreshToken)

    return response
  },

  async register(data: {
    email: string
    password: string
    companyName: string
    phoneNumber: string
    businessLicense: string
  }) {
    const response = await api<{
      user: User
      accessToken: string
      refreshToken: string
    }>('/auth/register', {
      method: 'POST',
      body: data
    })

    localStorage.setItem('accessToken', response.accessToken)
    localStorage.setItem('refreshToken', response.refreshToken)

    return response
  },

  async logout() {
    const refreshToken = localStorage.getItem('refreshToken')
    if (refreshToken) {
      await api('/auth/logout', {
        method: 'POST',
        body: { refreshToken }
      })
    }

    localStorage.removeItem('accessToken')
    localStorage.removeItem('refreshToken')
  },

  async me() {
    return api<User>('/auth/me')
  }
}
```

#### **File:** `services/upload.ts`
```typescript
import { api } from './api'

export const uploadService = {
  async uploadSingle(file: File, type: 'project' | 'listing' | 'profile') {
    const formData = new FormData()
    formData.append('file', file)
    formData.append('type', type)

    return api<{ url: string }>('/media/upload', {
      method: 'POST',
      body: formData
    })
  },

  async uploadMultiple(files: File[], type: 'project' | 'listing') {
    const formData = new FormData()
    files.forEach(file => {
      formData.append('files', file)
    })
    formData.append('type', type)

    return api<{ urls: string[] }>('/media/upload-multiple', {
      method: 'POST',
      body: formData
    })
  }
}
```

---

## 🏪 PINIA STORE INTEGRATION

### **Update:** `stores/projects.ts`
```typescript
import { defineStore } from 'pinia'
import { projectsService } from '~/services/projects'
import type { Project } from '~/types'

export const useProjectsStore = defineStore('projects', {
  state: () => ({
    projects: [] as Project[],
    currentProject: null as Project | null,
    isLoading: false,
    error: null as string | null,
    searchFilters: {
      location: '',
      type: '',
      minPrice: 0,
      maxPrice: 3000000
    }
  }),

  getters: {
    allProjects: (state) => state.projects,
    
    filteredProjects: (state) => {
      return state.projects.filter(project => {
        const matchesLocation = !state.searchFilters.location || 
          project.location.toLowerCase().includes(state.searchFilters.location.toLowerCase())
        
        const matchesType = !state.searchFilters.type || 
          project.type.toLowerCase().includes(state.searchFilters.type.toLowerCase())
        
        const price = parseInt(project.price.replace(/\s/g, ''))
        const matchesPrice = price >= state.searchFilters.minPrice && 
          price <= state.searchFilters.maxPrice
        
        return matchesLocation && matchesType && matchesPrice
      })
    },

    featuredProjects: (state) => state.projects.slice(0, 3)
  },

  actions: {
    async fetchProjects(params?: any) {
      this.isLoading = true
      this.error = null

      try {
        const { projects } = await projectsService.getAll(params)
        this.projects = projects
      } catch (err: any) {
        this.error = err.message || 'Failed to fetch projects'
        throw err
      } finally {
        this.isLoading = false
      }
    },

    async fetchProjectById(id: number) {
      this.isLoading = true
      this.error = null

      try {
        this.currentProject = await projectsService.getById(id)
        return this.currentProject
      } catch (err: any) {
        this.error = err.message || 'Project not found'
        throw err
      } finally {
        this.isLoading = false
      }
    },

    async createProject(data: Partial<Project>) {
      this.isLoading = true
      this.error = null

      try {
        const newProject = await projectsService.create(data)
        this.projects.push(newProject)
        return newProject
      } catch (err: any) {
        this.error = err.message || 'Failed to create project'
        throw err
      } finally {
        this.isLoading = false
      }
    },

    async updateProject(id: number, data: Partial<Project>) {
      this.isLoading = true
      this.error = null

      try {
        const updated = await projectsService.update(id, data)
        const index = this.projects.findIndex(p => p.id === id)
        if (index !== -1) {
          this.projects[index] = updated
        }
        return updated
      } catch (err: any) {
        this.error = err.message || 'Failed to update project'
        throw err
      } finally {
        this.isLoading = false
      }
    },

    async deleteProject(id: number) {
      this.isLoading = true
      this.error = null

      try {
        await projectsService.delete(id)
        this.projects = this.projects.filter(p => p.id !== id)
      } catch (err: any) {
        this.error = err.message || 'Failed to delete project'
        throw err
      } finally {
        this.isLoading = false
      }
    },

    setSearchFilters(filters: any) {
      this.searchFilters = { ...this.searchFilters, ...filters }
    },

    clearSearchFilters() {
      this.searchFilters = {
        location: '',
        type: '',
        minPrice: 0,
        maxPrice: 3000000
      }
    }
  }
})
```

---

## 🔐 AUTHENTICATION FLOW

### **1. Create Auth Store**

**File:** `stores/auth.ts`
```typescript
import { defineStore } from 'pinia'
import { authService } from '~/services/auth'
import type { User } from '~/types'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null as User | null,
    isAuthenticated: false,
    isLoading: false,
    error: null as string | null
  }),

  actions: {
    async login(email: string, password: string) {
      this.isLoading = true
      this.error = null

      try {
        const { user } = await authService.login(email, password)
        this.user = user
        this.isAuthenticated = true
        return user
      } catch (err: any) {
        this.error = err.message || 'Login failed'
        throw err
      } finally {
        this.isLoading = false
      }
    },

    async register(data: any) {
      this.isLoading = true
      this.error = null

      try {
        const { user } = await authService.register(data)
        this.user = user
        this.isAuthenticated = true
        return user
      } catch (err: any) {
        this.error = err.message || 'Registration failed'
        throw err
      } finally {
        this.isLoading = false
      }
    },

    async logout() {
      await authService.logout()
      this.user = null
      this.isAuthenticated = false
      navigateTo('/login')
    },

    async fetchUser() {
      try {
        this.user = await authService.me()
        this.isAuthenticated = true
      } catch (err) {
        this.user = null
        this.isAuthenticated = false
      }
    }
  }
})
```

### **2. Create Auth Middleware**

**File:** `middleware/auth.ts`
```typescript
export default defineNuxtRouteMiddleware(async (to, from) => {
  const authStore = useAuthStore()
  
  if (!authStore.isAuthenticated) {
    await authStore.fetchUser()
  }

  if (!authStore.isAuthenticated) {
    return navigateTo('/login')
  }
})
```

### **3. Use Middleware on Protected Pages**

```vue
<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})
</script>
```

---

## ⚠️ ERROR HANDLING

### **Global Error Handler**

**File:** `plugins/error-handler.ts`
```typescript
export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.vueApp.config.errorHandler = (error, instance, info) => {
    console.error('Global error:', error)
    
    const uiStore = useUiStore()
    uiStore.showError(
      'An error occurred',
      error.message || 'Something went wrong'
    )
  }
})
```

---

## 📸 IMAGE UPLOAD IMPLEMENTATION

### **Update CreateProjectFlow**

```vue
<script setup lang="ts">
import { uploadService } from '~/services/upload'

const handleImageUpload = async (event: Event) => {
  const target = event.target as HTMLInputElement
  const files = target.files
  
  if (!files) return

  uploadingImages.value = true
  
  try {
    const { urls } = await uploadService.uploadMultiple(
      Array.from(files),
      'project'
    )
    
    projectImages.value = urls
    
    uiStore.showSuccess('Images uploaded successfully')
  } catch (error) {
    uiStore.showError('Failed to upload images')
  } finally {
    uploadingImages.value = false
  }
}
</script>
```

---

## 🧪 TESTING STRATEGY

### **1. Unit Tests (Services)**
```typescript
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { projectsService } from '~/services/projects'

describe('projectsService', () => {
  it('should fetch all projects', async () => {
    const projects = await projectsService.getAll()
    expect(projects).toBeDefined()
    expect(Array.isArray(projects.projects)).toBe(true)
  })

  it('should create a project', async () => {
    const newProject = await projectsService.create({
      title: 'Test Project',
      location: 'Tunis'
    })
    expect(newProject.id).toBeDefined()
  })
})
```

### **2. Integration Tests (API)**
```typescript
import { describe, it, expect } from 'vitest'

describe('API Integration', () => {
  it('should authenticate and fetch user', async () => {
    const authStore = useAuthStore()
    await authStore.login('test@example.com', 'password')
    expect(authStore.isAuthenticated).toBe(true)
    expect(authStore.user).toBeDefined()
  })
})
```

---

## ✅ IMPLEMENTATION CHECKLIST

### **Phase 1: Setup**
- [ ] Create `.env` file with API_BASE_URL
- [ ] Create `services/api.ts` (API client)
- [ ] Create `types/index.ts` (TypeScript interfaces)

### **Phase 2: Services**
- [ ] Create `services/projects.ts`
- [ ] Create `services/listings.ts`
- [ ] Create `services/contacts.ts`
- [ ] Create `services/auth.ts`
- [ ] Create `services/upload.ts`

### **Phase 3: Stores**
- [ ] Update `stores/projects.ts`
- [ ] Create `stores/listings.ts`
- [ ] Create `stores/contacts.ts`
- [ ] Create `stores/auth.ts`

### **Phase 4: Auth**
- [ ] Create auth middleware
- [ ] Add middleware to protected routes
- [ ] Implement login page
- [ ] Implement register page

### **Phase 5: Integration**
- [ ] Update all pages to use services
- [ ] Replace mock data with API calls
- [ ] Implement error handling
- [ ] Add loading states

### **Phase 6: Testing**
- [ ] Test authentication flow
- [ ] Test CRUD operations
- [ ] Test file uploads
- [ ] Test error scenarios

---

## 🚀 READY TO INTEGRATE!

Follow this roadmap to connect your Nuxt.js frontend to the backend APIs. Start with Phase 1 and work your way through each phase systematically.

**Next Steps:**
1. Get backend API URL from backend team
2. Review API endpoint documentation
3. Start with authentication integration
4. Then integrate projects/listings
5. Test thoroughly

Good luck! 🎉
