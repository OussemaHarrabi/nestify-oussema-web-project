<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white border-b border-border">
      <div class="max-w-7xl mx-auto px-6 lg:px-10 py-6">
        <div class="flex items-center justify-between">
          <button
            @click="router.back()"
            class="flex items-center gap-2 text-muted-foreground hover:text-foreground transition-colors"
          >
            <ArrowLeft class="w-5 h-5" />
            <span>Retour</span>
          </button>
          <h1 class="text-2xl font-semibold">Créer un lot</h1>
          <div class="w-20"></div>
        </div>
      </div>
    </header>

    <!-- Form Content -->
    <div class="max-w-4xl mx-auto px-6 lg:px-10 py-12">
      <div class="bg-white rounded-2xl shadow-sm border border-border p-8">
        <h2 class="text-2xl font-semibold mb-6">Détails du lot</h2>

        <div class="space-y-6">
          <!-- Listing Type -->
          <div>
            <label class="block text-sm font-medium mb-2">Type de bien *</label>
            <select
              v-model="listingData.type"
              class="w-full px-4 py-3 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
              required
            >
              <option value="">Sélectionner un type</option>
              <option value="apartment">Appartement</option>
              <option value="villa">Villa</option>
              <option value="house">Maison</option>
              <option value="commercial">Local commercial</option>
              <option value="office">Bureau</option>
            </select>
          </div>

          <!-- Surface -->
          <div>
            <label class="block text-sm font-medium mb-2">Surface (m²) *</label>
            <input
              v-model.number="listingData.surface"
              type="number"
              placeholder="Ex: 120"
              class="w-full px-4 py-3 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
              required
            />
          </div>

          <!-- Rooms Grid -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
              <label class="block text-sm font-medium mb-2">Chambres *</label>
              <input
                v-model.number="listingData.bedrooms"
                type="number"
                min="0"
                placeholder="Ex: 3"
                class="w-full px-4 py-3 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                required
              />
            </div>

            <div>
              <label class="block text-sm font-medium mb-2">Salles de bain *</label>
              <input
                v-model.number="listingData.bathrooms"
                type="number"
                min="0"
                placeholder="Ex: 2"
                class="w-full px-4 py-3 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                required
              />
            </div>

            <div>
              <label class="block text-sm font-medium mb-2">Étage</label>
              <input
                v-model="listingData.floor"
                type="text"
                placeholder="Ex: 1er étage"
                class="w-full px-4 py-3 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
              />
            </div>
          </div>

          <!-- Price -->
          <div>
            <label class="block text-sm font-medium mb-2">Prix (TND) *</label>
            <input
              v-model.number="listingData.price"
              type="number"
              placeholder="Ex: 450000"
              class="w-full px-4 py-3 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
              required
            />
          </div>

          <!-- Description -->
          <div>
            <label class="block text-sm font-medium mb-2">Description</label>
            <textarea
              v-model="listingData.description"
              rows="4"
              placeholder="Décrivez les caractéristiques de ce lot..."
              class="w-full px-4 py-3 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary resize-none"
            ></textarea>
          </div>

          <!-- Photos -->
          <div>
            <label class="block text-sm font-medium mb-2">Photos du lot</label>
            <div class="border-2 border-dashed border-border rounded-xl p-8 text-center hover:border-primary/50 transition-colors cursor-pointer">
              <input
                type="file"
                accept="image/*"
                multiple
                @change="handleImageUpload"
                class="hidden"
                id="listing-images"
              />
              <label for="listing-images" class="cursor-pointer">
                <ImageIcon class="w-12 h-12 text-muted-foreground mx-auto mb-3" />
                <p class="text-muted-foreground mb-1">Cliquez pour télécharger des photos</p>
                <p class="text-sm text-muted-foreground">PNG, JPG jusqu'à 10MB</p>
              </label>
            </div>

            <!-- Image Preview -->
            <div v-if="listingData.images.length > 0" class="grid grid-cols-3 gap-4 mt-4">
              <div
                v-for="(image, index) in listingData.images"
                :key="index"
                class="relative aspect-video rounded-lg overflow-hidden group"
              >
                <img :src="image" alt="Preview" class="w-full h-full object-cover" />
                <button
                  @click="removeImage(index)"
                  class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                >
                  <X class="w-4 h-4 text-red-600" />
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 mt-8">
          <Button
            variant="outline"
            @click="router.back()"
            class="flex-1 rounded-full py-6"
          >
            Annuler
          </Button>
          <Button
            @click="handleSubmit"
            :disabled="!isFormValid"
            class="flex-1 rounded-full py-6"
          >
            <Check class="w-4 h-4 mr-2" />
            Créer le lot
          </Button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { ArrowLeft, Check, ImageIcon, X } from 'lucide-vue-next'
import Button from '~/components/ui/Button.vue'
import { useUIStore } from '~/stores/ui'

const router = useRouter()
const route = useRoute()
const uiStore = useUIStore()

const projectId = computed(() => (route?.query?.projectId as string) || '')

const listingData = ref({
  type: '',
  surface: null as number | null,
  bedrooms: null as number | null,
  bathrooms: null as number | null,
  floor: '',
  price: null as number | null,
  description: '',
  images: [] as string[]
})

const isFormValid = computed(() => {
  return (
    listingData.value.type &&
    listingData.value.surface &&
    listingData.value.bedrooms !== null &&
    listingData.value.bathrooms !== null &&
    listingData.value.price
  )
})

const handleImageUpload = (event: Event) => {
  const target = event.target as HTMLInputElement
  const files = target.files
  if (files) {
    Array.from(files).forEach(file => {
      const reader = new FileReader()
      reader.onload = (e) => {
        if (e.target?.result) {
          listingData.value.images.push(e.target.result as string)
        }
      }
      reader.readAsDataURL(file)
    })
  }
}

const removeImage = (index: number) => {
  listingData.value.images.splice(index, 1)
}

const handleSubmit = () => {
  uiStore.setLoading(true, 'Création du lot...')

  setTimeout(() => {
    uiStore.setLoading(false)
    uiStore.showSuccess('Lot créé !', 'Votre lot a été créé avec succès')
    
    // Redirect back to project creation with flag
    setTimeout(() => {
      router.push('/dashboard/create-project?listingAdded=true')
    }, 1000)
  }, 1500)
}
</script>
