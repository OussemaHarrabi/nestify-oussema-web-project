<script setup lang="ts">
import { ref, computed } from 'vue'
import {
    ArrowLeft,
    MapPin,
    Calendar,
    Building2,
    BedDouble,
    Bath,
    Maximize,
    Compass,
    Share,
    Heart,
    Menu,
    User,
    MessageSquare
} from 'lucide-vue-next'
import MapView from '~/components/MapView.vue'

const route = useRoute()
const listingId = computed(() => {
    const id = route?.params?.id
    return id ? parseInt(id as string) : 0
})

// Mock listing data (in production, this would come from API)
const listing = ref({
    id: listingId.value || 1,
    type: 'Appartement S+3',
    surface: 120,
    price: '340 000',
    bedrooms: 3,
    bathrooms: 2,
    floor: '3',
    orientation: 'Nord-Est',
    status: 'available',
    image: 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800&h=600&fit=crop'
})

const project = ref({
    name: 'Les Jardins de Carthage',
    location: 'Carthage, Tunis',
    developer: 'SPIV Promotion',
    developerLogo: 'SPIV',
    deliveryDate: 'Q2 2025',
    coordinates: { lat: 36.8525, lng: 10.3233 }
})

// Images for gallery
const listingImages = ref([
    listing.value.image,
    'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800&h=600&fit=crop',
    'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800&h=600&fit=crop',
    'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=800&h=600&fit=crop',
    'https://images.unsplash.com/photo-1600210492493-0946911123ea?w=800&h=600&fit=crop'
])

// Contact form
const formData = ref({
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    message: ''
})

const showMessage = ref(false)

const getStatusBadge = (status: string) => {
    switch (status) {
        case 'available':
            return { text: 'Disponible', class: 'bg-green-100 text-green-800 hover:bg-green-100 border-green-200 border' }
        case 'reserved':
            return { text: 'Réservé', class: 'bg-yellow-100 text-yellow-800 hover:bg-yellow-100 border-yellow-200 border' }
        case 'sold':
            return { text: 'Vendu', class: 'bg-red-100 text-red-800 hover:bg-red-100 border-red-200 border' }
        default:
            return { text: status, class: 'bg-gray-100 text-gray-800 hover:bg-gray-100 border-gray-200 border' }
    }
}

const statusBadge = computed(() => getStatusBadge(listing.value.status))

const handleBack = () => {
    navigateTo(-1)
}

const handleDeveloperClick = () => {
    navigateTo(`/developer/${encodeURIComponent(project.value.developer)}`)
}

const handleProjectClick = () => {
    navigateTo('/search')
}

const handleShare = async () => {
    if (navigator.share) {
        try {
            await navigator.share({
                title: `${listing.value.type} - ${project.value.name}`,
                text: `Découvrez ce ${listing.value.type} à ${project.value.location}`,
                url: window.location.href
            })
        } catch (err) {
            console.log('Share cancelled')
        }
    } else {
        // Fallback: copy to clipboard
        await navigator.clipboard.writeText(window.location.href)
        const uiStore = useUiStore()
        uiStore.showSuccess('Lien copié!', 'Le lien a été copié dans le presse-papiers')
    }
}

const handleSubmitContact = () => {
    console.log('Contact form submitted:', formData.value)
    const uiStore = useUiStore()
    uiStore.showSuccess('Message envoyé!', 'Nous vous contacterons bientôt.')
    // Reset form
    formData.value = {
        firstName: '',
        lastName: '',
        email: '',
        phone: '',
        message: ''
    }
    showMessage.value = false
}
</script>

<template>
    <div class="min-h-screen bg-white">
        <!-- Unified Header with Auth and Search Bar -->
        <Header :show-search-bar="true" />

        <!-- Navigation -->
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4">
            <Button
                variant="ghost"
                size="sm"
                @click="handleBack"
                class="rounded-full hover:bg-muted/50 transition-colors"
            >
                <ArrowLeft class="w-4 h-4 mr-2" />
                Retour au projet
            </Button>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 pb-16">
            <!-- Title and Status -->
            <div class="flex items-start justify-between mb-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-2xl lg:text-3xl font-semibold">{{ listing.type }}</h1>
                        <Badge :class="statusBadge.class">
                            {{ statusBadge.text }}
                        </Badge>
                    </div>
                    <div class="flex items-center text-sm">
                        <span
                            class="underline text-[14px] cursor-pointer hover:text-primary transition-colors"
                            @click="handleProjectClick"
                        >
                            {{ project.name }} · {{ project.location }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <Button
                        variant="ghost"
                        size="sm"
                        class="rounded-lg underline"
                        @click="handleShare"
                    >
                        <Share class="w-4 h-4 mr-2" />
                        Share
                    </Button>
                    <Button variant="ghost" size="sm" class="rounded-lg underline">
                        <Heart class="w-4 h-4 mr-2" />
                        Save
                    </Button>
                </div>
            </div>

            <!-- Image Gallery - Airbnb Style -->
            <div class="relative mb-12">
                <div class="grid grid-cols-4 gap-2 h-96">
                    <!-- Main Image - Left Half -->
                    <div class="col-span-2 relative rounded-l-xl overflow-hidden">
                        <img
                            :src="listingImages[0]"
                            :alt="listing.type"
                            class="w-full h-full object-cover hover:brightness-110 transition-all duration-200 cursor-pointer"
                        />
                    </div>

                    <!-- Secondary Images - Right Grid 2x2 -->
                    <div class="col-span-2 grid grid-cols-2 gap-2">
                        <div
                            v-for="(image, index) in listingImages.slice(1, 5)"
                            :key="index"
                            :class="[
                                'relative overflow-hidden cursor-pointer',
                                index === 0 ? 'rounded-tr-xl' : '',
                                index === 3 ? 'rounded-br-xl' : ''
                            ]"
                        >
                            <img
                                :src="image"
                                :alt="`${listing.type} - Photo ${index + 2}`"
                                class="w-full h-full object-cover hover:brightness-110 transition-all duration-200"
                            />
                            <div
                                v-if="index === 3 && listingImages.length > 5"
                                class="absolute inset-0 bg-black/60 flex items-center justify-center"
                            >
                                <span class="text-white font-medium">+{{ listingImages.length - 5 }} photos</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Show All Photos Button -->
                <Button
                    variant="outline"
                    class="absolute bottom-4 right-4 bg-white hover:bg-white border border-foreground rounded-lg px-4 py-2 text-foreground font-medium"
                >
                    <div class="w-4 h-4 grid grid-cols-2 gap-0.5 mr-2">
                        <div class="bg-current rounded-sm"></div>
                        <div class="bg-current rounded-sm"></div>
                        <div class="bg-current rounded-sm"></div>
                        <div class="bg-current rounded-sm"></div>
                    </div>
                    Show all photos
                </Button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Main Info -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">{{ listing.type }} dans {{ project.name }}</h2>

                        <div class="flex items-center text-muted-foreground mb-6">
                            <MapPin class="w-5 h-5 mr-2" />
                            <span>{{ project.location }}</span>
                        </div>

                        <p class="text-muted-foreground">
                            Proposé par
                            <span
                                class="underline font-medium cursor-pointer hover:text-primary transition-colors"
                                @click="handleDeveloperClick"
                            >
                                {{ project.developer }}
                            </span>
                        </p>
                    </div>

                    <hr class="border-border" />

                    <!-- Characteristics -->
                    <div>
                        <h2 class="text-2xl font-semibold mb-6">Caractéristiques</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-muted/50 flex items-center justify-center flex-shrink-0">
                                    <Maximize class="w-6 h-6 text-foreground" />
                                </div>
                                <div>
                                    <p class="font-medium text-foreground">{{ listing.surface }} m²</p>
                                    <p class="text-sm text-muted-foreground">Surface</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-muted/50 flex items-center justify-center flex-shrink-0">
                                    <BedDouble class="w-6 h-6 text-foreground" />
                                </div>
                                <div>
                                    <p class="font-medium text-foreground">{{ listing.bedrooms }} chambres</p>
                                    <p class="text-sm text-muted-foreground">Chambres à coucher</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-muted/50 flex items-center justify-center flex-shrink-0">
                                    <Bath class="w-6 h-6 text-foreground" />
                                </div>
                                <div>
                                    <p class="font-medium text-foreground">{{ listing.bathrooms }} salle{{ listing.bathrooms > 1 ? 's' : '' }} de bain</p>
                                    <p class="text-sm text-muted-foreground">Salles de bain</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-muted/50 flex items-center justify-center flex-shrink-0">
                                    <Building2 class="w-6 h-6 text-foreground" />
                                </div>
                                <div>
                                    <p class="font-medium text-foreground">Étage {{ listing.floor }}</p>
                                    <p class="text-sm text-muted-foreground">Niveau</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-muted/50 flex items-center justify-center flex-shrink-0">
                                    <Compass class="w-6 h-6 text-foreground" />
                                </div>
                                <div>
                                    <p class="font-medium text-foreground">{{ listing.orientation }}</p>
                                    <p class="text-sm text-muted-foreground">Orientation</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-muted/50 flex items-center justify-center flex-shrink-0">
                                    <Calendar class="w-6 h-6 text-foreground" />
                                </div>
                                <div>
                                    <p class="font-medium text-foreground">{{ project.deliveryDate }}</p>
                                    <p class="text-sm text-muted-foreground">Livraison prévue</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-border" />

                    <!-- What Makes It Unique -->
                    <div>
                        <h2 class="text-2xl font-semibold mb-4">Ce qui rend ce bien unique</h2>
                        <p class="text-muted-foreground leading-relaxed mb-4">
                            Cet appartement {{ listing.type.toLowerCase() }} offre un cadre de vie exceptionnel avec {{ listing.bedrooms }} chambres spacieuses et {{ listing.bathrooms }} salle{{ listing.bathrooms > 1 ? 's' : '' }} de bain moderne{{ listing.bathrooms > 1 ? 's' : '' }}. Situé à l'étage {{ listing.floor }} avec une orientation {{ listing.orientation.toLowerCase() }}, il bénéficie d'une luminosité optimale tout au long de la journée.
                        </p>
                        <p class="text-muted-foreground leading-relaxed">
                            Faisant partie du projet {{ project.name }}, cette résidence de standing offre des prestations haut de gamme et un environnement privilégié à {{ project.location }}.
                        </p>
                    </div>

                    <hr class="border-border" />

                    <!-- About The Project -->
                    <div>
                        <h2 class="text-2xl font-semibold mb-6">À propos du projet</h2>
                        <div
                            class="p-6 hover:shadow-lg transition-all duration-300 cursor-pointer border border-border/50 rounded-xl"
                            @click="handleProjectClick"
                        >
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg mb-2">{{ project.name }}</h3>
                                    <div class="flex items-center text-muted-foreground mb-3">
                                        <MapPin class="w-4 h-4 mr-2" />
                                        <span class="text-sm">{{ project.location }}</span>
                                    </div>
                                    <div class="flex items-center text-muted-foreground mb-4">
                                        <Calendar class="w-4 h-4 mr-2" />
                                        <span class="text-sm">Livraison prévue : {{ project.deliveryDate }}</span>
                                    </div>
                                </div>
                            </div>
                            <Button variant="outline" class="w-full rounded-full">
                                Voir tous les lots du projet
                            </Button>
                        </div>
                    </div>

                    <hr class="border-border" />

                    <!-- Location -->
                    <div>
                        <h2 class="text-2xl font-semibold mb-6">Localisation</h2>
                        <div class="h-96 bg-muted rounded-2xl overflow-hidden relative z-10">
                            <MapView
                                v-if="project.coordinates"
                                :projects="[{ 
                                    id: listing.id,
                                    title: listing.type,
                                    location: project.location,
                                    image: listing.image,
                                    price: listing.price,
                                    type: listing.type,
                                    status: listing.status,
                                    developer: project.developer,
                                    developerLogo: project.developerLogo,
                                    coordinates: project.coordinates
                                }]"
                                :selected-project-id="listing.id"
                            />
                            <div v-else class="flex items-center justify-center h-full">
                                <p class="text-muted-foreground">Carte non disponible</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-8 self-start shadow-xl border border-border/50 rounded-2xl">
                        <!-- Header -->
                        <div class="p-6">
                            <div class="flex items-center gap-4 mb-6">
                                <div
                                    class="cursor-pointer"
                                    @click="handleDeveloperClick"
                                >
                                    <div class="w-12 h-12 bg-foreground rounded-full flex items-center justify-center shadow-sm">
                                        <span class="text-white font-semibold text-sm">
                                            {{ project.developerLogo }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3
                                        class="font-semibold text-lg cursor-pointer hover:text-primary transition-colors"
                                        @click="handleDeveloperClick"
                                    >
                                        {{ project.developer }}
                                    </h3>
                                </div>
                            </div>

                            <!-- Contact Form -->
                            <form @submit.prevent="handleSubmitContact" class="space-y-4">
                                <div class="grid grid-cols-2 gap-3">
                                    <input
                                        v-model="formData.firstName"
                                        placeholder="Prénom"
                                        required
                                        class="h-12 rounded-lg border border-border focus:border-foreground transition-colors px-4 outline-none"
                                    />
                                    <input
                                        v-model="formData.lastName"
                                        placeholder="Nom"
                                        required
                                        class="h-12 rounded-lg border border-border focus:border-foreground transition-colors px-4 outline-none"
                                    />
                                </div>

                                <input
                                    v-model="formData.email"
                                    type="email"
                                    placeholder="Adresse e-mail"
                                    required
                                    class="w-full h-12 rounded-lg border border-border focus:border-foreground transition-colors px-4 outline-none"
                                />

                                <input
                                    v-model="formData.phone"
                                    placeholder="Numéro de téléphone"
                                    required
                                    class="w-full h-12 rounded-lg border border-border focus:border-foreground transition-colors px-4 outline-none"
                                />

                                <Button
                                    v-if="!showMessage"
                                    variant="ghost"
                                    size="sm"
                                    type="button"
                                    @click="showMessage = true"
                                    class="h-auto p-0 text-foreground hover:bg-transparent text-sm justify-start"
                                >
                                    <MessageSquare class="w-4 h-4 mr-2" />
                                    Ajouter un message (facultatif)
                                </Button>

                                <textarea
                                    v-if="showMessage"
                                    v-model="formData.message"
                                    placeholder="Bonjour, je suis intéressé(e) par cet appartement. Pouvez-vous me contacter ?"
                                    rows="3"
                                    class="w-full rounded-lg border border-border focus:border-foreground resize-none transition-colors px-4 py-3 outline-none"
                                ></textarea>

                                <Button
                                    type="submit"
                                    class="w-full bg-primary hover:bg-primary/90 rounded-lg h-12 font-semibold text-base shadow-sm"
                                >
                                    Contacter {{ project.developer }}
                                </Button>

                                <p class="text-xs text-muted-foreground text-center leading-relaxed px-2">
                                    En contactant ce promoteur, vous acceptez de recevoir des informations sur ce bien.
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Similar Listings -->
            <div class="mt-16">
                <hr class="border-border mb-8" />
                <h2 class="text-2xl font-semibold mb-8">Autres lots disponibles dans ce projet</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Mock similar listings -->
                    <div class="overflow-hidden group cursor-pointer hover:shadow-xl transition-all duration-300 border border-border/50 rounded-xl">
                        <div class="relative aspect-square">
                            <img
                                src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=400&h=400&fit=crop"
                                alt="Autre lot"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                            <div class="absolute top-4 left-4">
                                <Badge class="bg-white text-foreground shadow-md">
                                    Disponible
                                </Badge>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium mb-1">Appartement S+3</h3>
                            <p class="text-sm text-muted-foreground mb-3">120 m² · 3 chambres</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-foreground font-semibold">340 000 TND</span>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-hidden group cursor-pointer hover:shadow-xl transition-all duration-300 border border-border/50 rounded-xl">
                        <div class="relative aspect-square">
                            <img
                                src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=400&h=400&fit=crop"
                                alt="Autre lot"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                            <div class="absolute top-4 left-4">
                                <Badge class="bg-white text-foreground shadow-md">
                                    Disponible
                                </Badge>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium mb-1">Appartement S+2</h3>
                            <p class="text-sm text-muted-foreground mb-3">85 m² · 2 chambres</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-foreground font-semibold">280 000 TND</span>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-hidden group cursor-pointer hover:shadow-xl transition-all duration-300 border border-border/50 rounded-xl">
                        <div class="relative aspect-square">
                            <img
                                src="https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=400&h=400&fit=crop"
                                alt="Autre lot"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                            <div class="absolute top-4 left-4">
                                <Badge class="bg-yellow-100 text-yellow-800 shadow-md border border-yellow-200">
                                    Réservé
                                </Badge>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium mb-1">Penthouse S+5</h3>
                            <p class="text-sm text-muted-foreground mb-3">220 m² · 5 chambres</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-foreground font-semibold">680 000 TND</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
