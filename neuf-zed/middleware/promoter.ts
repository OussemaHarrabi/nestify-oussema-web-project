// middleware/promoter.ts - Protect routes requiring promoter role
export default defineNuxtRouteMiddleware((to, from) => {
  const { isPromoter } = useAuthApi()

  // If not a promoter, redirect to home
  if (!isPromoter.value) {
    return navigateTo('/', { replace: true })
  }
})
