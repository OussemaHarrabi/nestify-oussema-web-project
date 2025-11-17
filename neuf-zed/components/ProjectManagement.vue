<script setup lang="ts">
import { ref, computed } from 'vue'
import { ArrowLeft, Plus, Home, Eye, FileText, Settings, MapPin, Calendar, Building2, Edit, Trash2, AlertTriangle } from 'lucide-vue-next'

interface Props {
  projectId: number
}

const props = defineProps<Props>()

const emit = defineEmits<{
  back: []
  addListing: [projectId: number]
  editProject: []
  contactsClick: []
  listingClick: [listing: any]
}>()

const selectedTab = ref('listings')
const isPublished = ref(true)
const filterStatus = ref<string>('all')

const mockProject = {
  id: props.projectId,
  name: "Les Jardins de Carthage",
  location: "Carthage, Tunis",
  address: "Avenue de la République, Carthage",
  status: "construction",
  deliveryDate: "Q2 2025",
  description: "Résidence de standing située au cœur de Carthage, offrant un cadre de vie exceptionnel avec vue mer et prestations haut de gamme.",
  image: "https://images.unsplash.com/photo-1664892798972-079f15663b16?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtb2Rlcm4lMjBsdXh1cnklMjBhcGFydG1lbnQlMjBidWlsZGluZ3xlbnwxfHx8fDE3NTk3NDg0NTd8MA&ixlib=rb-4.1.0&q=80&w=1080",
  isPublished: true,
  types: ['Appartements', 'Penthouses'],
  totalListings: 15,
  availableListings: 8,
  reservedListings: 4,
  soldListings: 3,
  totalViews: 2456,
  totalContacts: 89,
}

const mockListings = [
  { id: 1, type: "Appartement S+2", surface: 85, price: "280 000", bedrooms: 2, bathrooms: 1, floor: "2", orientation: "Sud", status: "available", image: "https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=400&h=300&fit=crop" },
  { id: 2, type: "Appartement S+3", surface: 120, price: "340 000", bedrooms: 3, bathrooms: 2, floor: "3", orientation: "Nord-Est", status: "available", image: "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=400&h=300&fit=crop" },
  { id: 3, type: "Appartement S+4", surface: 155, price: "420 000", bedrooms: 4, bathrooms: 2, floor: "4", orientation: "Sud", status: "sold", image: "https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=400&h=300&fit=crop" },
  { id: 4, type: "Penthouse S+5", surface: 220, price: "680 000", bedrooms: 5, bathrooms: 3, floor: "Dernier", orientation: "Panoramique", status: "reserved", image: "https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=400&h=300&fit=crop" },
  { id: 5, type: "Appartement S+2", surface: 90, price: "295 000", bedrooms: 2, bathrooms: 1, floor: "1", orientation: "Ouest", status: "available", image: "https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=400&h=300&fit=crop" },
]

const getStatusLabel = (status: string) => {
  switch (status) {
    case 'available': return 'Disponible'
    case 'reserved': return 'Réservé'
    case 'sold': return 'Vendu'
    default: return status
  }
}

const filteredListings = computed(() => {
  if (filterStatus.value === 'all') return mockListings
  return mockListings.filter(l => l.status === filterStatus.value)
})

const handleDeleteProject = () => {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.')) {
    console.log('Projet supprimé')
    emit('back')
  }
}
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white border-b border-border">
      <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="py-6">
          <button
            @click="emit('back')"
            class="flex items-center gap-2 text-muted-foreground hover:text-foreground transition-colors mb-4"
          >
            <ArrowLeft class="w-5 h-5" />
            <span>Retour au dashboard</span>
          </button>

          <div class="flex items-start justify-between">
            <div class="flex gap-6">
              <!-- Project Image -->
              <div class="w-32 h-32 rounded-xl overflow-hidden border border-border flex-shrink-0">
                <img
                  :src="mockProject.image"
                  :alt="mockProject.name"
                  class="w-full h-full object-cover"
                />
              </div>

              <!-- Project Info -->
              <div>
                <h1 class="text-2xl font-semibold text-foreground mb-2">
                  {{ mockProject.name }}
                </h1>
                <div class="flex items-center gap-4 text-sm text-muted-foreground mb-3">
                  <div class="flex items-center gap-2">
                    <MapPin class="w-4 h-4" />
                    <span>{{ mockProject.location }}</span>
                  </div>
                  <div class="flex items-center gap-2">
                    <Calendar class="w-4 h-4" />
                    <span>Livraison {{ mockProject.deliveryDate }}</span>
                  </div>
                </div>
                <div class="flex items-center gap-3">
                  <span
                    :class="[
                      'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium',
                      isPublished ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700'
                    ]"
                  >
                    {{ isPublished ? 'Publié' : 'Brouillon' }}
                  </span>
                  <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-gray-100 text-gray-600">
                    {{ mockProject.types.join(', ') }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col items-end gap-4">
              <div class="flex items-center gap-2">
                <button class="flex items-center gap-2 px-4 py-2 text-sm bg-white border border-border rounded-full hover:bg-gray-50 transition-colors">
                  <Eye class="w-4 h-4" />
                  Aperçu public
                </button>
                <button
                  @click="emit('editProject')"
                  class="flex items-center gap-2 px-4 py-2 text-sm bg-white border border-border rounded-full hover:bg-gray-50 transition-colors"
                >
                  <Edit class="w-4 h-4" />
                  Modifier le projet
                </button>
              </div>
              <button
                @click="emit('contactsClick')"
                class="flex items-center gap-2 px-4 py-2 text-sm bg-primary text-white rounded-full hover:bg-primary/90 transition-colors"
              >
                <FileText class="w-4 h-4" />
                Contacts ({{ mockProject.totalContacts }})
              </button>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Stats Cards -->
    <div class="bg-white border-b border-border">
      <div class="max-w-7xl mx-auto px-6 lg:px-10 py-6">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
          <div class="group bg-white p-6 rounded-2xl border border-border/60 hover:shadow-lg hover:shadow-black/5 transition-all duration-300 hover:border-border">
            <div class="text-4xl mb-2">{{ mockProject.totalListings }}</div>
            <div class="text-sm text-muted-foreground">Lots totaux</div>
          </div>
          <div class="group bg-white p-6 rounded-2xl border border-border/60 hover:shadow-lg hover:shadow-black/5 transition-all duration-300 hover:border-border">
            <div class="text-4xl mb-2">{{ mockProject.availableListings }}</div>
            <div class="text-sm text-muted-foreground">Disponibles</div>
          </div>
          <div class="group bg-white p-6 rounded-2xl border border-border/60 hover:shadow-lg hover:shadow-black/5 transition-all duration-300 hover:border-border">
            <div class="text-4xl mb-2">{{ mockProject.reservedListings }}</div>
            <div class="text-sm text-muted-foreground">Réservés</div>
          </div>
          <div class="group bg-white p-6 rounded-2xl border border-border/60 hover:shadow-lg hover:shadow-black/5 transition-all duration-300 hover:border-border">
            <div class="text-4xl mb-2">{{ mockProject.soldListings }}</div>
            <div class="text-sm text-muted-foreground">Vendus</div>
          </div>
          <div class="group bg-white p-6 rounded-2xl border border-border/60 hover:shadow-lg hover:shadow-black/5 transition-all duration-300 hover:border-border">
            <div class="text-4xl mb-2">{{ mockProject.totalContacts }}</div>
            <div class="text-sm text-muted-foreground">Contacts</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-8">
      <!-- Tabs -->
      <div class="bg-white border border-border rounded-xl p-1 mb-6 inline-flex">
        <button
          @click="selectedTab = 'listings'"
          :class="[
            'flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-colors',
            selectedTab === 'listings' ? 'bg-gray-100 text-foreground' : 'text-muted-foreground hover:text-foreground'
          ]"
        >
          <Home class="w-4 h-4" />
          Lots ({{ mockProject.totalListings }})
        </button>
        <button
          @click="selectedTab = 'settings'"
          :class="[
            'flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-colors',
            selectedTab === 'settings' ? 'bg-gray-100 text-foreground' : 'text-muted-foreground hover:text-foreground'
          ]"
        >
          <Settings class="w-4 h-4" />
          Paramètres
        </button>
      </div>

      <!-- Listings Tab -->
      <div v-if="selectedTab === 'listings'" class="space-y-6">
        <!-- Filters -->
        <div class="p-4 bg-white border border-border rounded-2xl">
          <div class="flex items-center justify-between">
            <div class="flex gap-2">
              <button
                @click="filterStatus = 'all'"
                :class="[
                  'px-4 py-2 text-sm rounded-full transition-colors',
                  filterStatus === 'all' ? 'bg-foreground text-white' : 'bg-white border border-border hover:bg-gray-50'
                ]"
              >
                Tous ({{ mockListings.length }})
              </button>
              <button
                @click="filterStatus = 'available'"
                :class="[
                  'px-4 py-2 text-sm rounded-full transition-colors',
                  filterStatus === 'available' ? 'bg-foreground text-white' : 'bg-white border border-border hover:bg-gray-50'
                ]"
              >
                Disponibles ({{ mockProject.availableListings }})
              </button>
              <button
                @click="filterStatus = 'reserved'"
                :class="[
                  'px-4 py-2 text-sm rounded-full transition-colors',
                  filterStatus === 'reserved' ? 'bg-foreground text-white' : 'bg-white border border-border hover:bg-gray-50'
                ]"
              >
                Réservés ({{ mockProject.reservedListings }})
              </button>
              <button
                @click="filterStatus = 'sold'"
                :class="[
                  'px-4 py-2 text-sm rounded-full transition-colors',
                  filterStatus === 'sold' ? 'bg-foreground text-white' : 'bg-white border border-border hover:bg-gray-50'
                ]"
              >
                Vendus ({{ mockProject.soldListings }})
              </button>
            </div>

            <button
              @click="emit('addListing', props.projectId)"
              class="flex items-center gap-2 px-4 py-2 text-sm bg-white border border-border rounded-full hover:bg-gray-50 transition-colors"
            >
              <Plus class="w-4 h-4" />
              Nouveau lot
            </button>
          </div>
        </div>

        <!-- Listings Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="listing in filteredListings"
            :key="listing.id"
            class="group cursor-pointer"
            @click="emit('listingClick', listing)"
          >
            <div class="relative aspect-square mb-3 rounded-xl overflow-hidden">
              <img
                :src="listing.image"
                :alt="listing.type"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              />
              <div class="absolute top-4 left-4">
                <div :class="[
                  'px-3 py-1.5 rounded-md shadow-md backdrop-blur-sm',
                  listing.status === 'available' ? 'bg-white' :
                  listing.status === 'reserved' ? 'bg-yellow-50 border border-yellow-200' :
                  'bg-red-50 border border-red-200'
                ]">
                  <span :class="[
                    'text-sm',
                    listing.status === 'available' ? 'text-foreground' :
                    listing.status === 'reserved' ? 'text-yellow-700' :
                    'text-red-700'
                  ]">
                    {{ getStatusLabel(listing.status) }}
                  </span>
                </div>
              </div>

              <!-- Pagination dots -->
              <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-1.5">
                <div
                  v-for="i in 3"
                  :key="i"
                  :class="[
                    'rounded-full transition-all h-1.5',
                    i === 1 ? 'bg-white w-2' : 'bg-white/60 w-1.5'
                  ]"
                />
              </div>
            </div>

            <div class="space-y-2">
              <div class="flex-1 min-w-0">
                <p class="text-foreground truncate">{{ listing.type }}</p>
                <p class="text-muted-foreground text-sm truncate">{{ listing.surface }} m² · {{ listing.bedrooms }} chambres</p>
              </div>

              <div class="flex items-baseline gap-1">
                <span class="text-foreground font-bold">{{ parseInt(listing.price.replace(/\s/g, '')).toLocaleString() }} TND</span>
              </div>

              <div class="flex gap-2 pt-2">
                <button
                  class="flex-1 px-4 py-2 text-sm bg-white border border-border rounded-lg hover:border-foreground/40 hover:bg-gray-50 transition-all"
                  @click.stop="console.log('Modifier le lot', listing.id)"
                >
                  <Edit class="w-4 h-4 inline mr-2" />
                  Modifier
                </button>
                <button
                  class="px-4 py-2 text-sm bg-white border border-border rounded-lg hover:border-red-300 hover:bg-red-50 hover:text-red-600 transition-all"
                  @click.stop="console.log('Supprimer le lot', listing.id)"
                >
                  <Trash2 class="w-4 h-4" />
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="filteredListings.length === 0" class="p-12 bg-white border border-border rounded-2xl">
          <div class="text-center">
            <Home class="w-16 h-16 text-muted-foreground mx-auto mb-4" />
            <h3 class="font-semibold text-foreground mb-2">Aucun lot trouvé</h3>
            <p class="text-muted-foreground mb-6">Aucun lot ne correspond à ce filtre</p>
            <button
              @click="filterStatus = 'all'"
              class="px-4 py-2 bg-white border border-border rounded-full hover:bg-gray-50 transition-colors"
            >
              Voir tous les lots
            </button>
          </div>
        </div>
      </div>

      <!-- Settings Tab -->
      <div v-if="selectedTab === 'settings'" class="space-y-6">
        <!-- Publication -->
        <div class="p-6 bg-white border border-border rounded-2xl">
          <h3 class="font-semibold text-foreground mb-4">Publication</h3>
          <div class="flex items-center justify-between p-4 bg-muted/50 rounded-lg">
            <div>
              <p class="font-medium text-foreground mb-1">Rendre le projet visible au public</p>
              <p class="text-sm text-muted-foreground">Les acheteurs pourront voir ce projet et ses lots sur neuf.tn</p>
            </div>
            <button
              @click="isPublished = !isPublished"
              :class="[
                'relative inline-flex h-6 w-11 items-center rounded-full transition-colors',
                isPublished ? 'bg-primary' : 'bg-gray-200'
              ]"
            >
              <span
                :class="[
                  'inline-block h-4 w-4 transform rounded-full bg-white transition-transform',
                  isPublished ? 'translate-x-6' : 'translate-x-1'
                ]"
              />
            </button>
          </div>
        </div>

        <!-- Project Info -->
        <div class="p-6 bg-white border border-border rounded-2xl">
          <h3 class="font-semibold text-foreground mb-4">Informations du projet</h3>
          <div class="space-y-4">
            <div>
              <div class="text-sm text-muted-foreground mb-1">Nom du projet</div>
              <p class="font-medium text-foreground">{{ mockProject.name }}</p>
            </div>
            <div>
              <div class="text-sm text-muted-foreground mb-1">Localisation</div>
              <p class="font-medium text-foreground">{{ mockProject.location }}</p>
            </div>
            <div>
              <div class="text-sm text-muted-foreground mb-1">Adresse</div>
              <p class="font-medium text-foreground">{{ mockProject.address }}</p>
            </div>
            <div>
              <div class="text-sm text-muted-foreground mb-1">Date de livraison</div>
              <p class="font-medium text-foreground">{{ mockProject.deliveryDate }}</p>
            </div>
            <div>
              <div class="text-sm text-muted-foreground mb-1">Types de logements</div>
              <p class="font-medium text-foreground">{{ mockProject.types.join(', ') }}</p>
            </div>
            <div>
              <div class="text-sm text-muted-foreground mb-1">Nombre de logements</div>
              <p class="font-medium text-foreground">{{ mockProject.totalListings }} logements ({{ mockProject.availableListings }} disponibles, {{ mockProject.reservedListings }} réservés, {{ mockProject.soldListings }} vendus)</p>
            </div>
            <div>
              <div class="text-sm text-muted-foreground mb-1">Description</div>
              <p class="text-foreground">{{ mockProject.description }}</p>
            </div>
            <button
              @click="emit('editProject')"
              class="flex items-center gap-2 px-4 py-2 bg-white border border-border rounded-full hover:bg-gray-50 transition-colors"
            >
              <Edit class="w-4 h-4" />
              Modifier les informations
            </button>
          </div>
        </div>

        <!-- Danger Zone -->
        <div class="p-6 bg-white border border-red-100 rounded-2xl shadow-sm">
          <div class="flex items-start gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
              <AlertTriangle class="w-5 h-5 text-red-600" />
            </div>
            <div class="flex-1">
              <h3 class="font-semibold text-foreground mb-1">Supprimer le projet</h3>
              <p class="text-sm text-muted-foreground">
                Cette action supprimera définitivement le projet, tous les lots et l'historique des contacts. Cette opération est irréversible.
              </p>
            </div>
          </div>

          <button
            @click="handleDeleteProject"
            class="w-full flex items-center justify-center gap-2 px-4 py-2 text-red-600 bg-white border border-red-300 rounded-full hover:bg-red-600 hover:text-white transition-all"
          >
            <Trash2 class="w-4 h-4" />
            Supprimer définitivement
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
