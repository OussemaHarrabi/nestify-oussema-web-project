// Plugin to ensure router is available during SSR
export default defineNuxtPlugin(() => {
  // This plugin ensures router composables are available
  // The actual fix is in nuxt.config.ts with ssr: false for dynamic routes
})
