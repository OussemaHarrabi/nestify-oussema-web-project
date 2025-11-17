<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header with Profile Dropdown -->
    <Header />

    <!-- Page Header -->
    <div class="bg-white border-b border-border">
      <div class="max-w-7xl mx-auto px-6 lg:px-10 py-6">
        <div class="flex items-center justify-between">
          <button
            @click="handleBack"
            class="flex items-center gap-2 text-muted-foreground hover:text-foreground transition-colors"
          >
            <ArrowLeft class="w-5 h-5" />
            <span>Retour</span>
          </button>
          <h1 class="text-2xl font-semibold">Créer un projet</h1>
          <div class="w-20"></div>
        </div>
      </div>
    </div>

    <!-- Progress Steps -->
    <div class="max-w-4xl mx-auto px-6 lg:px-10 py-8">
      <div class="flex items-center justify-between mb-12">
        <div
          v-for="step in steps"
          :key="step.number"
          class="flex items-center flex-1"
        >
          <div class="flex flex-col items-center flex-1">
            <div
              :class="[
                'w-12 h-12 rounded-full flex items-center justify-center font-semibold transition-all',
                currentStep > step.number
                  ? 'bg-primary text-white'
                  : currentStep === step.number
                  ? 'bg-primary text-white ring-4 ring-primary/20'
                  : 'bg-muted text-muted-foreground'
              ]"
            >
              <Check v-if="currentStep > step.number" class="w-6 h-6" />
              <span v-else>{{ step.number }}</span>
            </div>
            <span
              :class="[
                'text-sm mt-2 text-center',
                currentStep >= step.number ? 'text-foreground font-medium' : 'text-muted-foreground'
              ]"
            >
              {{ step.label }}
            </span>
          </div>
          <div
            v-if="step.number < steps.length"
            :class="[
              'flex-1 h-1 mx-4',
              currentStep > step.number ? 'bg-primary' : 'bg-muted'
            ]"
          ></div>
        </div>
      </div>

      <!-- Step Content -->
      <div class="bg-white rounded-2xl shadow-sm border border-border p-8">
        <!-- Step 1: Informations générales -->
        <div v-if="currentStep === 1" class="space-y-6">
          <h2 class="text-2xl font-semibold mb-6">Informations générales</h2>

          <div>
            <label class="block text-sm font-medium mb-2">Nom du projet *</label>
            <input
              v-model="formData.projectName"
              type="text"
              placeholder="Ex: Les Jardins de Carthage"
              class="w-full px-4 py-3 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
              required
            />
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium mb-2">Ville *</label>
              <select
                v-model="formData.city"
                class="w-full px-4 py-3 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                required
              >
                <option value="">Sélectionner une ville</option>
                <option value="tunis">Tunis</option>
                <option value="ariana">Ariana</option>
                <option value="ben-arous">Ben Arous</option>
                <option value="manouba">Manouba</option>
                <option value="nabeul">Nabeul</option>
                <option value="sousse">Sousse</option>
                <option value="sfax">Sfax</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium mb-2">Quartier *</label>
              <select
                v-model="formData.district"
                :disabled="!formData.city"
                class="w-full px-4 py-3 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary disabled:bg-muted"
                required
              >
                <option value="">Sélectionner un quartier</option>
                <option
                  v-for="district in districts"
                  :key="district"
                  :value="district"
                >
                  {{ district }}
                </option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Adresse</label>
            <input
              v-model="formData.address"
              type="text"
              placeholder="Ex: Avenue Habib Bourguiba"
              class="w-full px-4 py-3 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
            />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Localisation sur la carte *</label>
            <p class="text-sm text-muted-foreground mb-3">Cliquez sur la carte pour indiquer l'emplacement exact de votre projet</p>
            <div ref="mapContainer" class="w-full h-[400px] bg-muted rounded-xl overflow-hidden border-2 border-border relative">
              <div v-if="!formData.coordinates" class="absolute inset-0 flex items-center justify-center pointer-events-none z-10">
                <div class="bg-white/90 px-6 py-3 rounded-lg shadow-lg border border-primary/20">
                  <p class="text-sm font-medium text-primary">👆 Cliquez sur la carte pour placer un marqueur</p>
                </div>
              </div>
            </div>
            <div v-if="formData.coordinates" class="mt-2 flex items-center gap-2 text-sm">
              <span class="text-green-600 font-medium">✓ Emplacement sélectionné</span>
              <span class="text-muted-foreground">{{ formData.coordinates.lat.toFixed(6) }}, {{ formData.coordinates.lng.toFixed(6) }}</span>
            </div>
          </div>
        </div>

        <!-- Step 2: Type de biens & Description -->
        <div v-if="currentStep === 2" class="space-y-6">
          <h2 class="text-2xl font-semibold mb-6">Type de biens et Description</h2>

          <div>
            <label class="block text-sm font-medium mb-4">Types de biens disponibles *</label>
            <p class="text-muted-foreground mb-4">Sélectionnez les types de biens disponibles dans votre projet</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div
              v-for="type in propertyTypes"
              :key="type.id"
              @click="togglePropertyType(type.id)"
              :class="[
                'p-6 border-2 rounded-xl cursor-pointer transition-all',
                formData.selectedTypes.includes(type.id)
                  ? 'border-primary bg-primary/5'
                  : 'border-border hover:border-primary/50'
              ]"
            >
              <div class="flex items-center gap-4">
                <div
                  :class="[
                    'w-12 h-12 rounded-xl flex items-center justify-center transition-all',
                    formData.selectedTypes.includes(type.id)
                      ? 'bg-primary text-white'
                      : 'bg-muted text-muted-foreground'
                  ]"
                >
                  <component :is="type.icon" class="w-6 h-6" />
                </div>
                <div class="flex-1">
                  <h3 class="font-semibold">{{ type.label }}</h3>
                </div>
                <div
                  :class="[
                    'w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all',
                    formData.selectedTypes.includes(type.id)
                      ? 'border-primary bg-primary'
                      : 'border-border'
                  ]"
                >
                  <Check v-if="formData.selectedTypes.includes(type.id)" class="w-4 h-4 text-white" />
              </div>
            </div>
            </div>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Description du projet *</label>
            <textarea
              v-model="formData.description"
              rows="5"
              placeholder="Décrivez votre projet immobilier..."
              class="w-full px-4 py-3 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary resize-none"
              required
            ></textarea>
          </div>
        </div>

        <!-- Step 3: Calendrier -->
        <div v-if="currentStep === 3" class="space-y-6">
          <h2 class="text-2xl font-semibold mb-6">Calendrier de livraison</h2>

          <div>
            <label class="block text-sm font-medium mb-2">Statut du projet *</label>
            <select
              v-model="formData.projectStatus"
              class="w-full px-4 py-3 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
              required
            >
              <option value="">Sélectionner un statut</option>
              <option value="planning">Sur plan</option>
              <option value="construction">En cours de construction</option>
              <option value="finalized">Finalisé</option>
            </select>
          </div>

          <div class="flex items-center gap-4 p-4 bg-muted/30 rounded-xl">
            <input
              v-model="formData.immediateDelivery"
              type="checkbox"
              id="immediate"
              class="w-5 h-5 text-primary border-border rounded focus:ring-2 focus:ring-primary"
            />
            <label for="immediate" class="flex-1 cursor-pointer">
              <span class="font-medium">Livraison immédiate</span>
              <p class="text-sm text-muted-foreground">Le projet est déjà livré</p>
            </label>
          </div>

          <div v-if="!formData.immediateDelivery" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium mb-2">Trimestre de livraison</label>
              <select
                v-model="formData.deliveryQuarter"
                class="w-full px-4 py-3 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
              >
                <option value="">Sélectionner</option>
                <option value="Q1">1er trimestre</option>
                <option value="Q2">2e trimestre</option>
                <option value="Q3">3e trimestre</option>
                <option value="Q4">4e trimestre</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium mb-2">Année de livraison</label>
              <input
                v-model="formData.deliveryYear"
                type="number"
                min="2024"
                max="2030"
                placeholder="2025"
                class="w-full px-4 py-3 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
              />
            </div>
          </div>
        </div>

        <!-- Step 4: Médias -->
        <div v-if="currentStep === 4" class="space-y-6">
          <h2 class="text-2xl font-semibold mb-6">Photos et médias</h2>

          <div>
            <label class="block text-sm font-medium mb-2">Photos du projet</label>
            <div class="border-2 border-dashed border-border rounded-xl p-8 text-center hover:border-primary/50 transition-all cursor-pointer">
              <input
                type="file"
                multiple
                accept="image/*"
                @change="handleImageUpload"
                class="hidden"
                id="image-upload"
              />
              <label for="image-upload" class="cursor-pointer">
                <ImageIcon class="w-12 h-12 mx-auto mb-4 text-muted-foreground" />
                <p class="text-sm font-medium mb-1">Cliquez pour télécharger des photos</p>
                <p class="text-xs text-muted-foreground">PNG, JPG jusqu'à 10MB</p>
              </label>
            </div>

            <div v-if="formData.uploadedImages.length > 0" class="grid grid-cols-3 gap-4 mt-4">
              <div
                v-for="(image, index) in formData.uploadedImages"
                :key="index"
                class="relative group"
              >
                <img :src="image" class="w-full h-32 object-cover rounded-lg" />
                <button
                  @click="removeImage(index)"
                  class="absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                >
                  <X class="w-4 h-4 mx-auto" />
                </button>
              </div>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Brochure du projet (PDF)</label>
            <div class="border-2 border-dashed border-border rounded-xl p-8 text-center hover:border-primary/50 transition-all cursor-pointer">
              <input
                type="file"
                accept="application/pdf"
                @change="handleBrochureUpload"
                class="hidden"
                id="brochure-upload"
              />
              <label for="brochure-upload" class="cursor-pointer">
                <FileText class="w-12 h-12 mx-auto mb-4 text-muted-foreground" />
                <p class="text-sm font-medium mb-1">
                  <span v-if="formData.brochureUploaded">✓ Brochure téléchargée</span>
                  <span v-else>Télécharger une brochure</span>
                </p>
                <p class="text-xs text-muted-foreground">PDF jusqu'à 20MB</p>
              </label>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2">Vidéo du projet (optionnel)</label>
            <input
              v-model="formData.videoUrl"
              type="url"
              placeholder="https://youtube.com/..."
              class="w-full px-4 py-3 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
            />
          </div>
        </div>

        <!-- Step 5: Récapitulatif -->
        <div v-if="currentStep === 5" class="space-y-6">
          <h2 class="text-2xl font-semibold mb-6">Récapitulatif</h2>

          <div class="space-y-4">
            <div class="p-4 bg-muted/30 rounded-xl">
              <h3 class="font-semibold mb-2">Informations générales</h3>
              <p class="text-sm text-muted-foreground">Nom: {{ formData.projectName }}</p>
              <p class="text-sm text-muted-foreground">Localisation: {{ formData.district }}, {{ formData.city }}</p>
            </div>

            <div class="p-4 bg-muted/30 rounded-xl">
              <h3 class="font-semibold mb-2">Types de biens</h3>
              <div class="flex flex-wrap gap-2 mt-2">
                <Badge v-for="typeId in formData.selectedTypes" :key="typeId">
                  {{ propertyTypes.find(t => t.id === typeId)?.label }}
                </Badge>
              </div>
            </div>

            <div class="p-4 bg-muted/30 rounded-xl">
              <h3 class="font-semibold mb-2">Livraison</h3>
              <p class="text-sm text-muted-foreground">
                <span v-if="formData.immediateDelivery">Livraison immédiate</span>
                <span v-else>{{ formData.deliveryQuarter }} {{ formData.deliveryYear }}</span>
              </p>
            </div>

            <div class="p-4 bg-muted/30 rounded-xl">
              <h3 class="font-semibold mb-2">Médias</h3>
              <p class="text-sm text-muted-foreground">{{ formData.uploadedImages.length }} photo(s)</p>
              <p class="text-sm text-muted-foreground">
                Brochure: <span v-if="formData.brochureUploaded">Oui</span><span v-else>Non</span>
              </p>
            </div>
          </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between mt-8 pt-6 border-t border-border">
          <Button
            v-if="currentStep > 1"
            variant="outline"
            @click="currentStep--"
            class="rounded-full px-6"
          >
            Précédent
          </Button>
          <div v-else class="w-24"></div>

          <Button
            v-if="currentStep < steps.length"
            @click="handleNext"
            :disabled="!canProceed()"
            class="rounded-full px-6"
          >
            Suivant
          </Button>

          <Button
            v-else
            @click="handleSubmit"
            class="rounded-full px-6 bg-primary"
          >
            <Check class="w-4 h-4 mr-2" />
            Créer le projet
          </Button>
        </div>
      </div>
    </div>

    <!-- Project Management View -->
    <div v-if="showSuccess" class="fixed inset-0 bg-gray-50 z-50 overflow-auto">
      <div class="min-h-screen py-12 px-4">
        <div class="max-w-5xl mx-auto">
          <!-- Back Button -->
          <button @click="router.push('/dashboard')" class="flex items-center gap-2 text-muted-foreground hover:text-foreground mb-8">
            <ArrowLeft class="w-5 h-5" />
            Retour au tableau de bord
          </button>

          <!-- Project Created Card -->
          <div class="bg-white rounded-xl shadow-sm border border-border p-6 mb-8">
            <div class="flex items-start gap-6">
              <!-- Project Image -->
              <div class="w-48 h-32 bg-muted rounded-lg overflow-hidden flex-shrink-0">
                <img
                  v-if="formData.uploadedImages[0]"
                  :src="formData.uploadedImages[0]"
                  alt="Project"
                  class="w-full h-full object-cover"
                />
                <div v-else class="w-full h-full flex items-center justify-center">
                  <Building2 class="w-12 h-12 text-muted-foreground" />
                </div>
              </div>

              <!-- Project Info -->
              <div class="flex-1">
                <div class="flex items-start justify-between mb-2">
                  <div>
                    <h2 class="text-2xl font-semibold mb-1">{{ formData.projectName }}</h2>
                    <p class="text-muted-foreground">{{ formData.city }}, {{ formData.district }}</p>
                  </div>
                  <button class="text-primary hover:underline">Modifier</button>
                </div>
                <p class="text-sm text-muted-foreground line-clamp-2 mb-4">{{ formData.description }}</p>
                <div class="flex flex-wrap gap-2">
                  <Badge v-for="type in formData.selectedTypes" :key="type">{{ type }}</Badge>
                </div>
              </div>
            </div>
          </div>

          <!-- Add First Listing Prompt -->
          <div class="bg-white rounded-xl shadow-sm border border-border p-8">
            <div class="text-center max-w-2xl mx-auto">
              <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <Check class="w-8 h-8 text-green-600" />
              </div>
              <h3 class="text-2xl font-semibold mb-3">Projet créé avec succès !</h3>
              <h4 class="text-xl font-medium mb-3">Voulez-vous ajouter un premier lot ?</h4>
              <p class="text-muted-foreground mb-8">
                Créez le projet d'abord ou ajoutez les lots maintenant ou le faire plus tard depuis votre tableau de bord
              </p>

              <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <Button
                  @click="handleAddFirstListing"
                  class="rounded-full px-8 py-6 text-base"
                >
                  Ajouter un premier lot maintenant
                </Button>
                <Button
                  variant="outline"
                  @click="handleSkipToLater"
                  class="rounded-full px-8 py-6 text-base"
                >
                  Je le ferai plus tard
                </Button>
              </div>

              <p class="text-sm text-muted-foreground mt-4">
                Vous pouvez toujours ajouter des lots plus tard depuis votre
                <button @click="router.push('/dashboard')" class="text-primary hover:underline">tableau de bord</button>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch, nextTick } from 'vue'
import { ArrowLeft, Check, Building2, Home, Store, Briefcase, ImageIcon, FileText, X } from 'lucide-vue-next'
import Button from '~/components/ui/Button.vue'
import Badge from '~/components/ui/Badge.vue'
import { useUIStore } from '~/stores/ui'

const uiStore = useUIStore()
const router = useRouter()
const route = useRoute()

const currentStep = ref(1)
const showSuccess = ref(false)

const steps = [
  { number: 1, label: 'Informations' },
  { number: 2, label: 'Type de biens' },
  { number: 3, label: 'Calendrier' },
  { number: 4, label: 'Médias' },
  { number: 5, label: 'Récapitulatif' }
]

const propertyTypes = [
  { id: 'apartment', label: 'Appartements', icon: Building2 },
  { id: 'villa', label: 'Villas', icon: Home },
  { id: 'house', label: 'Maisons', icon: Home },
  { id: 'commercial', label: 'Locaux commerciaux', icon: Store },
  { id: 'office', label: 'Bureaux', icon: Briefcase }
]

const districtsByCity: Record<string, string[]> = {
  tunis: ['Centre Ville', 'La Marsa', 'Sidi Bou Said', 'Le Bardo', 'Carthage'],
  ariana: ['Ariana Ville', 'Ennasr', 'Raoued', 'Soukra'],
  'ben-arous': ['Hammam Lif', 'Rades', 'Mégrine'],
  manouba: ['Manouba Ville', 'Oued Ellil'],
  nabeul: ['Nabeul Ville', 'Hammamet', 'Kelibia'],
  sousse: ['Sousse Ville', 'Sahloul', 'Port El Kantaoui'],
  sfax: ['Sfax Ville', 'Sakiet Ezzit']
}

const formData = ref({
  projectName: '',
  city: '',
  district: '',
  address: '',
  description: '',
  selectedTypes: [] as string[],
  deliveryQuarter: '',
  deliveryYear: '',
  projectStatus: '',
  uploadedImages: [] as string[],
  brochureUploaded: false,
  videoUrl: '',
  immediateDelivery: false,
  coordinates: null as { lat: number; lng: number } | null
})

const mapContainer = ref<HTMLElement | null>(null)
let map: any = null
let marker: any = null
let L: any = null

// Initialize map
const initMap = async () => {
  if (!mapContainer.value) return

  try {
    L = await import('leaflet')

    // Fix default marker icons
    delete (L.Icon.Default.prototype as any)._getIconUrl
    L.Icon.Default.mergeOptions({
      iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
      iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
      shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
    })

    // Initialize map centered on Tunisia
    map = L.map(mapContainer.value).setView([36.8065, 10.1815], 10)

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors',
      maxZoom: 19,
    }).addTo(map)

    // Force map to resize properly
    setTimeout(() => {
      if (map) {
        map.invalidateSize()
      }
    }, 100)

    // Add click event to add marker
    map.on('click', (e: any) => {
      const { lat, lng } = e.latlng
      
      // Remove existing marker
      if (marker) {
        map.removeLayer(marker)
      }

      // Add new marker with custom icon
      const customIcon = L.icon({
        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
        iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
      })

      marker = L.marker([lat, lng], { icon: customIcon }).addTo(map)
      marker.bindPopup('📍 Emplacement du projet').openPopup()
      
      // Save coordinates
      formData.value.coordinates = { lat, lng }
    })
  } catch (error) {
    console.error('Error initializing map:', error)
  }
}

// Watch for step changes to initialize map
watch(() => currentStep.value, async (newStep) => {
  if (newStep === 1) {
    await nextTick()
    if (!map) {
      initMap()
    }
  }
})

const districts = computed(() => {
  return formData.value.city ? districtsByCity[formData.value.city] || [] : []
})

const togglePropertyType = (typeId: string) => {
  if (formData.value.selectedTypes.includes(typeId)) {
    formData.value.selectedTypes = formData.value.selectedTypes.filter(id => id !== typeId)
  } else {
    formData.value.selectedTypes.push(typeId)
  }
}

const canProceed = () => {
  switch (currentStep.value) {
    case 1:
      return formData.value.projectName && formData.value.city && formData.value.district && formData.value.coordinates
    case 2:
      return formData.value.description && formData.value.selectedTypes.length > 0
    case 3:
      return formData.value.projectStatus
    case 4:
      return true
    case 5:
      return true
    default:
      return false
  }
}

const handleNext = () => {
  if (canProceed()) {
    currentStep.value++
  }
}

const handleImageUpload = (event: Event) => {
  const target = event.target as HTMLInputElement
  const files = target.files
  if (files) {
    Array.from(files).forEach(file => {
      const reader = new FileReader()
      reader.onload = (e) => {
        if (e.target?.result) {
          formData.value.uploadedImages.push(e.target.result as string)
        }
      }
      reader.readAsDataURL(file)
    })
  }
}

const removeImage = (index: number) => {
  formData.value.uploadedImages.splice(index, 1)
}

const handleBrochureUpload = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files && target.files[0]) {
    formData.value.brochureUploaded = true
    uiStore.showSuccess('Brochure téléchargée avec succès')
  }
}

const handleSubmit = () => {
  uiStore.setLoading(true, 'Création du projet...')

  setTimeout(() => {
    uiStore.setLoading(false)
    // Store the created project ID (in real app, this would come from API response)
    createdProjectId.value = 1 // Mock ID
    showSuccess.value = true
    uiStore.showSuccess('Projet créé !', `Votre projet "${formData.value.projectName}" a été créé avec succès`)
  }, 1500)
}

// New state for listing management
const createdProjectId = ref<number | null>(null)

const handleAddFirstListing = () => {
  router.push(`/dashboard/projects/${createdProjectId.value}/listings/create`)
}

const handleSkipToLater = () => {
  router.push('/dashboard')
}

const handleBack = () => {
  router.push('/dashboard')
}

// Initialize map on mount if on step 1
onMounted(() => {
  if (currentStep.value === 1) {
    nextTick(() => initMap())
  }
})
</script>
