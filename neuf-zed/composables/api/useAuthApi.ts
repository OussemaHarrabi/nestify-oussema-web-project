// composables/api/useAuthApi.ts - Authentication API
import type { Promoter, User } from '~/types/api'

interface LoginCredentials {
  email: string
  password: string
}

interface LoginResponse {
  user: User
  token: string
  promoter?: Promoter
}

interface RegisterData {
  name: string
  email: string
  password: string
  password_confirmation: string
  phone: string
  company_name: string
  license_number?: string
  profile_picture?: File
}

interface RegisterResponse {
  message: string
  user: User
  token: string
  promoter?: Promoter
}

export const useAuthApi = () => {
  const { apiFetch } = useApi()
  const authToken = useCookie('auth_token', { maxAge: 60 * 60 * 24 * 7 }) // 7 days
  const user = useCookie<User | null>('user')
  const promoter = useCookie<Promoter | null>('promoter')

  const register = async (data: RegisterData) => {
    try {
      // Use FormData for file upload
      const formData = new FormData()
      formData.append('name', data.name)
      formData.append('email', data.email)
      formData.append('password', data.password)
      formData.append('password_confirmation', data.password_confirmation)
      formData.append('phone', data.phone)
      formData.append('company_name', data.company_name)
      
      if (data.license_number) {
        formData.append('license_number', data.license_number)
      }
      
      if (data.profile_picture) {
        formData.append('profile_picture', data.profile_picture)
      }

      const response = await apiFetch<RegisterResponse>('/register', {
        method: 'POST',
        body: formData,
      })

      // Store auth data
      authToken.value = response.token
      user.value = response.user
      promoter.value = response.promoter || null

      return response
    } catch (error) {
      console.error('Registration error:', error)
      throw error
    }
  }

  const login = async (credentials: LoginCredentials) => {
    try {
      const response = await apiFetch<LoginResponse>('/login', {
        method: 'POST',
        body: credentials,
      })

      // Store auth data
      authToken.value = response.token
      user.value = response.user
      promoter.value = response.promoter || null

      return response
    } catch (error) {
      console.error('Login error:', error)
      throw error
    }
  }

  const logout = async () => {
    try {
      await apiFetch('/logout', { method: 'POST' })
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      // Clear auth data regardless of API success
      authToken.value = null
      user.value = null
      promoter.value = null
    }
  }

  const getUser = async () => {
    try {
      const response = await apiFetch<{ user: User; promoter?: Promoter }>('/user')
      user.value = response.user
      promoter.value = response.promoter || null
      return response
    } catch (error) {
      // If failed, clear auth
      authToken.value = null
      user.value = null
      promoter.value = null
      throw error
    }
  }

  const updateProfilePicture = async (file: File) => {
    const formData = new FormData()
    formData.append('profile_picture', file)

    return apiFetch('/user/profile-picture', {
      method: 'POST',
      body: formData,
    })
  }

  const isAuthenticated = computed(() => !!authToken.value)
  const isPromoter = computed(() => !!promoter.value)

  return {
    register,
    login,
    logout,
    getUser,
    updateProfilePicture,
    isAuthenticated,
    isPromoter,
    user,
    promoter,
    authToken,
  }
}
