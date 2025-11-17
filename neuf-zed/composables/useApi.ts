// composables/useApi.ts - Base API configuration
export const useApi = () => {
  const config = useRuntimeConfig()
  const baseURL = config.public.apiBaseUrl
  
  // Create authenticated fetch instance
  const apiFetch = $fetch.create({
    baseURL,
    credentials: 'include', // Important for Sanctum cookies
    
    onRequest({ options }) {
      // Add auth token if exists
      const token = useCookie('auth_token').value
      if (token) {
        options.headers = {
          ...options.headers,
          Authorization: `Bearer ${token}`,
          Accept: 'application/json',
        }
      }
    },
    
    onResponseError({ response }) {
      // Handle 401 Unauthorized
      if (response.status === 401) {
        // Clear auth and redirect to login
        useCookie('auth_token').value = null
        useCookie('user').value = null
        useCookie('promoter').value = null
        navigateTo('/login')
      }
    },
  })

  return {
    apiFetch,
    baseURL,
  }
}

// Helper to get full image URL
export const useImageUrl = (path: string | null | undefined): string => {
  if (!path) return ''
  
  const config = useRuntimeConfig()
  
  // If it's already a full URL (Backblaze), return as is
  if (path.startsWith('http://') || path.startsWith('https://')) {
    return path
  }
  
  // If it's a relative path, prepend storage URL
  return `${config.public.storageUrl}/${path}`.replace('//', '/')
}
