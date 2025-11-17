// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: true },

  modules: [
    '@nuxtjs/tailwindcss',
    '@pinia/nuxt',
    '@vueuse/nuxt',
  ],

  css: ['~/assets/css/main.css'],

  app: {
    head: {
      title: 'neuf.tn - Trouvez votre projet neuf idéal',
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        {
          name: 'description',
          content: 'Découvrez les meilleurs projets immobiliers neufs en Tunisie. Appartements, villas et résidences de standing.'
        },
      ],
      link: [
        { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
        { rel: 'stylesheet', href: 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css' }
      ],
    },
  },

  tailwindcss: {
    cssPath: '~/assets/css/main.css',
    configPath: 'tailwind.config',
    exposeConfig: false,
    viewer: true,
  },

  // Route rules to handle dynamic routes properly
  routeRules: {
    '/project/**': { ssr: false },
    '/dashboard/**': { ssr: false },
    '/listing/**': { ssr: false },
    '/developer/**': { ssr: false },
  },

  // Auto-import composables from subdirectories
  imports: {
    dirs: [
      'composables',
      'composables/*/index.{ts,js,mjs,mts}',
      'composables/**'
    ]
  },

  // Runtime config for environment variables
  runtimeConfig: {
    public: {
      apiBaseUrl: process.env.NUXT_PUBLIC_API_BASE_URL || 'http://localhost:8000/api',
      appUrl: process.env.NUXT_PUBLIC_APP_URL || 'http://localhost:3000',
      storageUrl: process.env.NUXT_PUBLIC_STORAGE_URL || 'http://localhost:8000/storage',
    },
  },

  compatibilityDate: '2024-04-03',
})
