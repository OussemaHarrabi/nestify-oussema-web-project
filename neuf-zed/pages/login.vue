<template>
  <div class="min-h-screen bg-white">
    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white border-b border-border">
      <div class="max-w-[1760px] mx-auto px-6 lg:px-20">
        <div class="flex items-center justify-between h-20">
          <NuxtLink to="/" class="flex items-center space-x-3">
            <div class="bg-primary rounded-lg p-2">
              <div class="w-7 h-7 bg-white rounded-sm flex items-center justify-center">
                <span class="text-primary text-sm font-bold">N</span>
              </div>
            </div>
            <span class="text-2xl font-semibold text-primary">neuf.tn</span>
          </NuxtLink>
          <NuxtLink to="/" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
            ← Retour à l'accueil
          </NuxtLink>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <div class="max-w-[1760px] mx-auto px-6 lg:px-20 py-12 lg:py-20">
      <div class="max-w-md mx-auto">
        <div class="text-center mb-8">
          <h1 class="text-3xl lg:text-4xl font-bold text-foreground mb-3">Connexion Promoteur</h1>
          <p class="text-muted-foreground">Accédez à votre espace de gestion</p>
        </div>

        <!-- Demo Credentials -->
        <div class="mb-6 bg-primary/5 border-2 border-primary/20 rounded-xl p-4">
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div class="flex-1">
              <h3 class="font-semibold text-foreground mb-2">Identifiants de démonstration</h3>
              <div class="space-y-1 text-sm text-muted-foreground">
                <p><span class="font-medium text-foreground">Email:</span> demo@promoteur.tn</p>
                <p><span class="font-medium text-foreground">Mot de passe:</span> demo123456</p>
              </div>
              <p class="text-xs text-primary mt-2">Utilisez ces identifiants pour tester le système</p>
            </div>
          </div>
        </div>

        <!-- Login Form -->
        <form class="bg-white border-2 border-border rounded-xl p-6 lg:p-8 space-y-6" @submit.prevent="handleLogin">
          <div>
            <label for="email" class="block text-sm font-medium text-foreground mb-2">Adresse email</label>
            <input
              id="email"
              v-model="credentials.email"
              type="email"
              required
              class="w-full px-4 py-3 border-2 border-border rounded-xl text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
              placeholder="exemple@email.com"
              :disabled="loading"
            />
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-foreground mb-2">Mot de passe</label>
            <input
              id="password"
              v-model="credentials.password"
              type="password"
              required
              class="w-full px-4 py-3 border-2 border-border rounded-xl text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
              placeholder="••••••••"
              :disabled="loading"
            />
          </div>

          <div v-if="error" class="rounded-xl bg-destructive/10 border-2 border-destructive/20 p-4">
            <div class="flex gap-3">
              <svg class="w-5 h-5 text-destructive flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <p class="text-sm text-destructive">{{ error }}</p>
            </div>
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full bg-primary hover:bg-primary/90 text-primary-foreground font-semibold py-3 px-4 rounded-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
          >
            <svg v-if="loading" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ loading ? 'Connexion en cours...' : 'Se connecter' }}</span>
          </button>

          <div class="text-center">
            <a href="#" class="text-sm text-muted-foreground hover:text-primary transition-colors">Mot de passe oublié?</a>
          </div>
        </form>

        <div class="mt-6 text-center bg-muted/50 rounded-xl p-4 border-2 border-border">
          <p class="text-sm text-muted-foreground mb-2">Vous n'avez pas encore de compte?</p>
          <NuxtLink to="/register" class="inline-flex items-center gap-2 text-primary hover:text-primary/80 font-semibold transition-colors">
            <span>Créer un compte promoteur</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
          </NuxtLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const { login } = useAuthApi()
const router = useRouter()

const credentials = ref({
  email: '',
  password: '',
})

const loading = ref(false)
const error = ref('')

const handleLogin = async () => {
  loading.value = true
  error.value = ''

  try {
    // Mock login for demo purposes
    if (credentials.value.email === 'demo@promoteur.tn' && credentials.value.password === 'demo123456') {
      await new Promise(resolve => setTimeout(resolve, 1000))
      
      const authToken = useCookie('auth_token', { maxAge: 60 * 60 * 24 * 7 })
      const userCookie = useCookie('user', { maxAge: 60 * 60 * 24 * 7 })
      const promoterCookie = useCookie('promoter', { maxAge: 60 * 60 * 24 * 7 })
      
      authToken.value = 'mock-token-' + Date.now()
      userCookie.value = JSON.stringify({
        id: 1,
        name: 'Promoteur Démo',
        email: 'demo@promoteur.tn',
        user_type: 'promoter',
        profile_picture: null,
      })
      promoterCookie.value = JSON.stringify({
        id: 1,
        company_name: 'Demo Development',
        verified: true,
      })
      
      await router.push('/dashboard')
      return
    }
    
    const response = await login(credentials.value)
    
    if (response.user.user_type !== 'promoter') {
      error.value = 'Accès réservé aux promoteurs uniquement'
      return
    }

    await router.push('/dashboard')
  } catch (err: any) {
    console.error('Login error:', err)
    error.value = err?.data?.message || 'Email ou mot de passe incorrect'
  } finally {
    loading.value = false
  }
}
</script>
