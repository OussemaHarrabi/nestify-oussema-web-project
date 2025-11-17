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
    MessageSquare,
    ChevronLeft,
    ChevronRight,
    X
} from 'lucide-vue-next'

const route = useRoute()
const projectId = computed(() => {
    const id = route?.params?.projectId
    return id ? parseInt(id as string) : 0
})
const listingId = computed(() => {
    const id = route?.params?.id
    return id ? parseInt(id as string) : 0
})

// Gallery modal state
const showGallery = ref(false)
const currentImageIndex = ref(0)

// Mock listing data (in production, this would come from API)
const listing = ref({
    id: listingId.value,
    projectId: projectId.value,
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
    id: projectId.value,
    name: 'Les Jardins de Carthage',
    location: 'Carthage, Tunis',
    developer: 'SPIV Promotion',
    developerLogo: 'SPIV',
    deliveryDate: 'Q2 2025',
    coverImage: 'https://images.unsplash.com/photo-1677553512940-f79af72efd1b?w=400&h=300&fit=crop'
})

// Images for gallery
const listingImages = ref([
    listing.value.image,
    'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800&h=600&fit=crop',
    'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800&h=600&fit=crop',
    'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=800&h=600&fit=crop',
    'https://images.unsplash.com/photo-1600210492493-0946911123ea?w=800&h=600&fit=crop',
    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&h=600&fit=crop'
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
            return { text: 'Disponible', class: 'bg-green-100 text-green-800 hover:bg-green-100 border-green-200 border rounded-full' }
        case 'reserved':
            return { text: 'Réservé', class: 'bg-yellow-100 text-yellow-800 hover:bg-yellow-100 border-yellow-200 border rounded-full' }
        case 'sold':
            return { text: 'Vendu', class: 'bg-red-100 text-red-800 hover:bg-red-100 border-red-200 border rounded-full' }
        default:
            return { text: status, class: 'bg-gray-100 text-gray-800 hover:bg-gray-100 border-gray-200 border rounded-full' }
    }
}

const statusBadge = computed(() => getStatusBadge(listing.value.status))

const handleBack = () => {
    navigateTo(`/project/${projectId.value}`)
}

const handleDeveloperClick = () => {
    navigateTo(`/developer/${encodeURIComponent(project.value.developer)}`)
}

const handleProjectClick = () => {
    navigateTo(`/project/${projectId.value}`)
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

// Gallery functions
const openGallery = (index: number) => {
    currentImageIndex.value = index
    showGallery.value = true
}

const closeGallery = () => {
    showGallery.value = false
}

const nextImage = () => {
    currentImageIndex.value = (currentImageIndex.value + 1) % listingImages.value.length
}

const prevImage = () => {
    currentImageIndex.value = (currentImageIndex.value - 1 + listingImages.value.length) % listingImages.value.length
}

// Keyboard navigation for gallery
const handleKeydown = (e: KeyboardEvent) => {
    if (!showGallery.value) return
    
    if (e.key === 'ArrowRight') {
        nextImage()
    } else if (e.key === 'ArrowLeft') {
        prevImage()
    } else if (e.key === 'Escape') {
        closeGallery()
    }
}

// Add keyboard listener
if (process.client) {
    window.addEventListener('keydown', handleKeydown)
}
</script>

<template>
    <div class="min-h-screen bg-white">
        <!-- Header -->
        <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-border/50">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <!-- Logo -->
                    <NuxtLink to="/" class="flex items-center space-x-3">
                        <div class="bg-primary rounded-lg p-2">
                            <div class="w-7 h-7 bg-white rounded-sm flex items-center justify-center">
                                <span class="text-primary text-sm font-bold">N</span>
                            </div>
                        </div>
                        <span class="text-2xl font-semibold text-primary">neuf.tn</span>
                    </NuxtLink>

                    <!-- Search Bar -->
                    <div class="hidden lg:block flex-1 max-w-lg mx-8">
                        <SearchBar />
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-4">
                        <Button
                            variant="ghost"
                            size="sm"
                            class="hidden lg:flex text-foreground hover:bg-muted rounded-full px-4"
                            @click="navigateTo('/dashboard')"
                        >
                            Annoncer votre projet
                        </Button>

                        <div class="flex items-center border border-border rounded-full p-1 hover:shadow-md transition-all cursor-pointer bg-white">
                            <div class="p-2.5">
                                <Menu class="w-4 h-4 text-muted-foreground" />
                            </div>
                            <div class="p-2 bg-muted-foreground rounded-full">
                                <User class="w-5 h-5 text-white" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

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
                        <MapPin class="w-4 h-4 mr-1" />
                        <span class="text-muted-foreground">{{ project.location }}</span>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Image Gallery - Airbnb Style -->
                    <div class="relative">
                        <div class="grid grid-cols-4 gap-2 h-96">
                            <!-- Main Image - Left Half -->
                            <div class="col-span-2 relative rounded-l-xl overflow-hidden">
                                <img
                                    :src="listingImages[0]"
                                    :alt="listing.type"
                                    class="w-full h-full object-cover hover:brightness-110 transition-all duration-200 cursor-pointer"
                                    @click="openGallery(0)"
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
                                    @click="openGallery(index + 1)"
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
                            @click="openGallery(0)"
                            class="absolute bottom-4 right-4 bg-white hover:bg-white border border-foreground rounded-lg px-3 py-1.5 text-foreground text-xs font-medium whitespace-nowrap shadow-md"
                        >
                            <div class="w-3 h-3 grid grid-cols-2 gap-0.5 mr-1.5 flex-shrink-0">
                                <div class="bg-current rounded-sm"></div>
                                <div class="bg-current rounded-sm"></div>
                                <div class="bg-current rounded-sm"></div>
                                <div class="bg-current rounded-sm"></div>
                            </div>
                            Afficher toutes les photos
                        </Button>
                    </div>

                    <!-- Price Section - Right under images -->
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-foreground">{{ listing.price }} TND</span>
                    </div>

                    <!-- Proposed By -->
                    <div class="text-sm text-muted-foreground">
                        Proposé par
                        <span
                            class="underline font-medium cursor-pointer hover:text-primary transition-colors"
                            @click="handleDeveloperClick"
                        >
                            {{ project.developer }}
                        </span>
                    </div>

                    <hr class="border-border" />

                    <!-- Description du bien -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Description du bien</h2>
                        <p class="text-muted-foreground leading-relaxed">
                            Cet appartement {{ listing.type.toLowerCase() }} offre un cadre de vie exceptionnel avec {{ listing.bedrooms }} chambres spacieuses et {{ listing.bathrooms }} salle{{ listing.bathrooms > 1 ? 's' : '' }} de bain moderne{{ listing.bathrooms > 1 ? 's' : '' }}. Situé à l'étage {{ listing.floor }} avec une orientation {{ listing.orientation.toLowerCase() }}, il bénéficie d'une luminosité optimale tout au long de la journée.
                        </p>
                    </div>

                    <hr class="border-border" />

                    <!-- Détails du bien -->
                    <div>
                        <h2 class="text-xl font-semibold mb-6">Détails du bien</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                            <!-- Surface -->
                            <div class="flex items-start gap-3">
                                <Maximize class="w-5 h-5 text-foreground mt-1" />
                                <div>
                                    <p class="font-medium text-foreground">{{ listing.surface }} m²</p>
                                    <p class="text-sm text-muted-foreground">Surface construite</p>
                                </div>
                            </div>

                            <!-- Étage -->
                            <div class="flex items-start gap-3">
                                <Building2 class="w-5 h-5 text-foreground mt-1" />
                                <div>
                                    <p class="font-medium text-foreground">{{ listing.floor }}</p>
                                    <p class="text-sm text-muted-foreground">Étage</p>
                                </div>
                            </div>

                            <!-- Sud -->
                            <div class="flex items-start gap-3">
                                <Compass class="w-5 h-5 text-foreground mt-1" />
                                <div>
                                    <p class="font-medium text-foreground">{{ listing.orientation }}</p>
                                    <p class="text-sm text-muted-foreground">Orientation</p>
                                </div>
                            </div>

                            <!-- Chambres à coucher -->
                            <div class="flex items-start gap-3">
                                <BedDouble class="w-5 h-5 text-foreground mt-1" />
                                <div>
                                    <p class="font-medium text-foreground">{{ listing.bedrooms }} chambres</p>
                                    <p class="text-sm text-muted-foreground">à coucher</p>
                                </div>
                            </div>

                            <!-- Salle de bain -->
                            <div class="flex items-start gap-3">
                                <Bath class="w-5 h-5 text-foreground mt-1" />
                                <div>
                                    <p class="font-medium text-foreground">{{ listing.bathrooms }} salle{{ listing.bathrooms > 1 ? 's' : '' }} de bain</p>
                                    <p class="text-sm text-muted-foreground">de bain</p>
                                </div>
                            </div>

                            <!-- Livraison prévue -->
                            <div class="flex items-start gap-3">
                                <Calendar class="w-5 h-5 text-foreground mt-1" />
                                <div>
                                    <p class="font-medium text-foreground">{{ project.deliveryDate }}</p>
                                    <p class="text-sm text-muted-foreground">Livraison prévue</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-border" />

                    <!-- Caractéristiques -->
                    <div>
                        <h2 class="text-xl font-semibold mb-6">Caractéristiques</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <!-- Feature items with icons -->
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 flex items-center justify-center">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" />
                                        <path d="M3 9h18M9 3v18" />
                                    </svg>
                                </div>
                                <span class="text-sm">Terrasse</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 flex items-center justify-center">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 12h18M3 6h18M3 18h18" />
                                    </svg>
                                </div>
                                <span class="text-sm">Balcon</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 flex items-center justify-center">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="8" width="18" height="13" rx="2" />
                                        <path d="M7 12v4M17 12v4" />
                                    </svg>
                                </div>
                                <span class="text-sm">Parking</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 flex items-center justify-center">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 2v20M2 12h20" />
                                    </svg>
                                </div>
                                <span class="text-sm">Cuisine équipée</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 flex items-center justify-center">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 6v6l4 2" />
                                    </svg>
                                </div>
                                <span class="text-sm">Climatisation</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 flex items-center justify-center">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                    </svg>
                                </div>
                                <span class="text-sm">Double vitrage</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 flex items-center justify-center">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="3" />
                                        <path d="M12 1v6m0 6v6m9.66-9H14.5m-5 0H1.34" />
                                    </svg>
                                </div>
                                <span class="text-sm">Ascenseur</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 flex items-center justify-center">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="5" y="11" width="14" height="10" rx="2" />
                                        <circle cx="12" cy="16" r="1" />
                                    </svg>
                                </div>
                                <span class="text-sm">Vue panoramique</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 flex items-center justify-center">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                                    </svg>
                                </div>
                                <span class="text-sm">Sécurité 24h/24</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 flex items-center justify-center">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                </div>
                                <span class="text-sm">Espace vert</span>
                            </div>
                        </div>
                    </div>

                    <hr class="border-border" />

                    <!-- About The Project - With Cover Image -->
                    <div>
                        <h2 class="text-2xl font-semibold mb-6">À propos du projet</h2>
                        <div
                            class="p-6 hover:shadow-lg transition-all duration-300 cursor-pointer border border-border/50 rounded-xl"
                            @click="handleProjectClick"
                        >
                            <div class="flex items-start gap-6">
                                <!-- Project Cover Image -->
                                <div class="flex-shrink-0">
                                    <img
                                        :src="project.coverImage"
                                        :alt="project.name"
                                        class="w-32 h-24 object-cover rounded-lg"
                                    />
                                </div>

                                <!-- Project Info -->
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
                            <Button variant="outline" class="w-full rounded-full mt-4">
                                Voir tous les lots du projet
                            </Button>
                        </div>
                    </div>
                </div>

                <!-- Contact Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24 shadow-xl border border-border/50 rounded-2xl">
                        <!-- Developer Info & Contact Form -->
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
                    <div 
                        class="overflow-hidden group cursor-pointer hover:shadow-xl transition-all duration-300 border border-border/50 rounded-xl"
                        @click="navigateTo(`/project/${projectId}/listing/${listingId + 1}`)"
                    >
                        <div class="relative aspect-square">
                            <img
                                src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=400&h=400&fit=crop"
                                alt="Autre lot"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                            <div class="absolute top-4 left-4">
                                <Badge class="bg-white text-foreground shadow-md rounded-full">
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

                    <div 
                        class="overflow-hidden group cursor-pointer hover:shadow-xl transition-all duration-300 border border-border/50 rounded-xl"
                        @click="navigateTo(`/project/${projectId}/listing/${listingId + 2}`)"
                    >
                        <div class="relative aspect-square">
                            <img
                                src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=400&h=400&fit=crop"
                                alt="Autre lot"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                            <div class="absolute top-4 left-4">
                                <Badge class="bg-white text-foreground shadow-md rounded-full">
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

                    <div 
                        class="overflow-hidden group cursor-pointer hover:shadow-xl transition-all duration-300 border border-border/50 rounded-xl"
                    >
                        <div class="relative aspect-square">
                            <img
                                src="https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=400&h=400&fit=crop"
                                alt="Autre lot"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                            <div class="absolute top-4 left-4">
                                <Badge class="bg-yellow-100 text-yellow-800 shadow-md border border-yellow-200 rounded-full">
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

        <!-- Image Gallery Modal -->
        <Teleport to="body">
            <Transition name="fade">
                <div
                    v-if="showGallery"
                    class="fixed inset-0 bg-black/95 z-[100] flex items-center justify-center"
                    @click="closeGallery"
                >
                    <!-- Close Button -->
                    <button
                        class="absolute top-4 right-4 z-10 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 transition-colors flex items-center justify-center"
                        @click.stop="closeGallery"
                    >
                        <X class="w-6 h-6 text-white" />
                    </button>

                    <!-- Image Counter -->
                    <div class="absolute top-4 left-1/2 -translate-x-1/2 z-10 px-4 py-2 rounded-full bg-black/50 text-white text-sm">
                        {{ currentImageIndex + 1 }} / {{ listingImages.length }}
                    </div>

                    <!-- Previous Button -->
                    <button
                        class="absolute left-4 z-10 w-12 h-12 rounded-full bg-white hover:bg-white/90 transition-colors flex items-center justify-center shadow-xl"
                        @click.stop="prevImage"
                    >
                        <ChevronLeft class="w-6 h-6 text-foreground" />
                    </button>

                    <!-- Next Button -->
                    <button
                        class="absolute right-4 z-10 w-12 h-12 rounded-full bg-white hover:bg-white/90 transition-colors flex items-center justify-center shadow-xl"
                        @click.stop="nextImage"
                    >
                        <ChevronRight class="w-6 h-6 text-foreground" />
                    </button>

                    <!-- Current Image -->
                    <div class="max-w-7xl max-h-[90vh] w-full px-16" @click.stop>
                        <Transition name="slide" mode="out-in">
                            <img
                                :key="currentImageIndex"
                                :src="listingImages[currentImageIndex]"
                                :alt="`Photo ${currentImageIndex + 1}`"
                                class="w-full h-full object-contain"
                            />
                        </Transition>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.slide-enter-active,
.slide-leave-active {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.slide-enter-from {
    opacity: 0;
    transform: translateX(50px);
}

.slide-leave-to {
    opacity: 0;
    transform: translateX(-50px);
}
</style>
