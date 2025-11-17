<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl w-full space-y-8">
      <!-- Header -->
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Inscription Promoteur
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Créez votre compte pour gérer vos projets immobiliers
        </p>
      </div>

      <!-- Registration Form -->
      <form class="mt-8 space-y-6 bg-white p-8 rounded-xl shadow-lg" @submit.prevent="handleRegister">
        <!-- Personal Information -->
        <div class="space-y-4">
          <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">
            Informations personnelles
          </h3>

          <!-- Full Name -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
              Nom complet <span class="text-red-500">*</span>
            </label>
            <input
              id="name"
              v-model="formData.name"
              type="text"
              required
              class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              placeholder="Votre nom complet"
              :disabled="loading"
            />
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
              Adresse email <span class="text-red-500">*</span>
            </label>
            <input
              id="email"
              v-model="formData.email"
              type="email"
              required
              class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              placeholder="exemple@email.com"
              :disabled="loading"
            />
          </div>

          <!-- Phone -->
          <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
              Numéro de téléphone <span class="text-red-500">*</span>
            </label>
            <input
              id="phone"
              v-model="formData.phone"
              type="tel"
              required
              class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              placeholder="+216 XX XXX XXX"
              :disabled="loading"
            />
          </div>

          <!-- Password -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                Mot de passe <span class="text-red-500">*</span>
              </label>
              <input
                id="password"
                v-model="formData.password"
                type="password"
                required
                minlength="8"
                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                placeholder="Min. 8 caractères"
                :disabled="loading"
              />
            </div>

            <div>
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                Confirmer le mot de passe <span class="text-red-500">*</span>
              </label>
              <input
                id="password_confirmation"
                v-model="formData.password_confirmation"
                type="password"
                required
                minlength="8"
                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                placeholder="Répéter le mot de passe"
                :disabled="loading"
              />
            </div>
          </div>

          <!-- Password match warning -->
          <p v-if="formData.password && formData.password_confirmation && formData.password !== formData.password_confirmation" class="text-sm text-red-600">
            Les mots de passe ne correspondent pas
          </p>
        </div>

        <!-- Company Information -->
        <div class="space-y-4">
          <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">
            Informations de l'entreprise
          </h3>

          <!-- Company Name -->
          <div>
            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">
              Nom de l'entreprise <span class="text-red-500">*</span>
            </label>
            <input
              id="company_name"
              v-model="formData.company_name"
              type="text"
              required
              class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              placeholder="Nom de votre société"
              :disabled="loading"
            />
          </div>

          <!-- License Number (Optional) -->
          <div>
            <label for="license_number" class="block text-sm font-medium text-gray-700 mb-1">
              Numéro de licence (optionnel)
            </label>
            <input
              id="license_number"
              v-model="formData.license_number"
              type="text"
              class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              placeholder="Numéro de licence professionnelle"
              :disabled="loading"
            />
          </div>

          <!-- Profile Picture (Optional) -->
          <div>
            <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-1">
              Photo de profil (optionnel)
            </label>
            <div class="flex items-center space-x-4">
              <input
                id="profile_picture"
                ref="fileInput"
                type="file"
                accept="image/*"
                class="hidden"
                :disabled="loading"
                @change="handleFileChange"
              />
              <button
                type="button"
                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                :disabled="loading"
                @click="$refs.fileInput?.click()"
              >
                Choisir une image
              </button>
              <span v-if="fileName" class="text-sm text-gray-600">{{ fileName }}</span>
            </div>
            <p class="mt-1 text-xs text-gray-500">
              JPG, PNG ou GIF. Max 5MB.
            </p>
          </div>
        </div>

        <!-- Error Message -->
        <div v-if="error" class="rounded-md bg-red-50 p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-red-800">Erreur d'inscription</h3>
              <div class="mt-2 text-sm text-red-700">
                <p>{{ error }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Success Message -->
        <div v-if="success" class="rounded-md bg-green-50 p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-green-800">Inscription réussie! Redirection...</p>
            </div>
          </div>
        </div>

        <!-- Terms and Conditions -->
        <div class="flex items-start">
          <div class="flex items-center h-5">
            <input
              id="terms"
              v-model="acceptTerms"
              type="checkbox"
              required
              class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
              :disabled="loading"
            />
          </div>
          <div class="ml-3 text-sm">
            <label for="terms" class="font-medium text-gray-700">
              J'accepte les <a href="#" class="text-blue-600 hover:text-blue-500">conditions d'utilisation</a> et la <a href="#" class="text-blue-600 hover:text-blue-500">politique de confidentialité</a>
            </label>
          </div>
        </div>

        <!-- Submit Button -->
        <div>
          <button
            type="submit"
            :disabled="loading || !acceptTerms || formData.password !== formData.password_confirmation"
            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="loading" class="absolute left-0 inset-y-0 flex items-center pl-3">
              <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
            {{ loading ? 'Inscription en cours...' : "S'inscrire" }}
          </button>
        </div>

        <!-- Login Link -->
        <div class="text-center text-sm">
          <span class="text-gray-600">Vous avez déjà un compte?</span>
          <NuxtLink to="/login" class="ml-1 font-medium text-blue-600 hover:text-blue-500">
            Se connecter
          </NuxtLink>
        </div>
      </form>

      <!-- Back to Home -->
      <div class="text-center">
        <NuxtLink to="/" class="text-sm text-gray-600 hover:text-gray-900">
          ← Retour à l'accueil
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const { register } = useAuthApi()
const router = useRouter()

const formData = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  phone: '',
  company_name: '',
  license_number: '',
  profile_picture: undefined as File | undefined,
})

const loading = ref(false)
const error = ref('')
const success = ref(false)
const acceptTerms = ref(false)
const fileName = ref('')
const fileInput = ref<HTMLInputElement | null>(null)

const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  
  if (file) {
    // Validate file size (5MB max)
    if (file.size > 5 * 1024 * 1024) {
      error.value = 'La taille de l\'image ne doit pas dépasser 5MB'
      return
    }

    // Validate file type
    if (!file.type.startsWith('image/')) {
      error.value = 'Veuillez sélectionner une image valide'
      return
    }

    formData.value.profile_picture = file
    fileName.value = file.name
    error.value = ''
  }
}

const handleRegister = async () => {
  loading.value = true
  error.value = ''
  success.value = false

  // Validate passwords match
  if (formData.value.password !== formData.value.password_confirmation) {
    error.value = 'Les mots de passe ne correspondent pas'
    loading.value = false
    return
  }

  try {
    await register(formData.value)
    
    success.value = true
    
    // Redirect to dashboard after 1.5 seconds
    setTimeout(async () => {
      await router.push('/dashboard')
    }, 1500)
  } catch (err: any) {
    console.error('Registration error:', err)
    
    // Handle validation errors
    if (err?.data?.errors) {
      const errors = err.data.errors
      const firstError = Object.values(errors)[0] as string[]
      error.value = firstError[0] || 'Une erreur est survenue'
    } else if (err?.data?.message) {
      error.value = err.data.message
    } else {
      error.value = 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.'
    }
  } finally {
    loading.value = false
  }
}
</script>
