// middleware/auth.ts - Protect routes requiring authentication
export default defineNuxtRouteMiddleware((to, from) => {
  const { isAuthenticated } = useAuthApi()

  // If not authenticated, redirect to login
  if (!isAuthenticated.value) {
    return navigateTo('/login', { 
      replace: true,
      // Save the intended destination to redirect after login
      query: { redirect: to.fullPath }
    })
  }
})
