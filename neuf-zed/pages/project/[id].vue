<template>
    <div class="min-h-screen bg-white">
        <!-- Loading State -->
        <div
            v-if="isLoading"
            class="flex items-center justify-center min-h-screen"
        >
            <LoadingSpinner size="lg" text="Chargement du projet..." />
        </div>

        <!-- Error State -->
        <div
            v-else-if="error"
            class="flex items-center justify-center min-h-screen"
        >
            <div class="text-center max-w-md px-6">
                <div
                    class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4"
                >
                    <AlertCircle class="w-8 h-8 text-red-600" />
                </div>
                <h2 class="text-2xl font-semibold mb-2">Projet introuvable</h2>
                <p class="text-muted-foreground mb-6">
                    Ce projet n'existe pas ou a été supprimé.
                </p>
                <Button @click="navigateTo('/search')" class="rounded-full">
                    Retour aux projets
                </Button>
            </div>
        </div>

        <!-- Project Content -->
        <div v-else-if="project">
            <!-- Unified Header with Auth -->
            <Header :show-search-bar="false" />

            <!-- Navigation -->
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-4">
                <Button
                    variant="ghost"
                    size="sm"
                    @click="navigateTo('/search')"
                    class="rounded-full hover:bg-muted/50 transition-colors"
                >
                    <ArrowLeft class="w-4 h-4 mr-2" />
                    Retour aux projets
                </Button>
            </div>

            <div class="max-w-7xl mx-auto px-6 lg:px-8 pb-16">
                <!-- Titre du projet -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-semibold mb-2">
                            {{ project.title }}
                        </h1>
                        <div class="flex items-center text-sm">
                            <span class="underline text-[14px]">{{
                                project.location
                            }}</span>
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
                        <Button
                            variant="ghost"
                            size="sm"
                            class="rounded-lg underline"
                            @click="handleSave"
                        >
                            <Heart
                                :class="[
                                    'w-4 h-4 mr-2',
                                    isSaved ? 'fill-current text-primary' : '',
                                ]"
                            />
                            Save
                        </Button>
                    </div>
                </div>

                <!-- Galerie d'images -->
                <div class="relative mb-8">
                    <div class="grid grid-cols-4 gap-2 h-[700px]">
                        <div
                            class="col-span-2 relative rounded-l-xl overflow-hidden"
                        >
                            <img
                                :src="displayImages[0]"
                                :alt="project.title"
                                class="w-full h-full object-cover hover:brightness-110 transition-all duration-200 cursor-pointer"
                                @click="openGallery(0)"
                            />
                        </div>

                        <div class="col-span-2 grid grid-cols-2 gap-2">
                            <div
                                v-for="(image, index) in displayImages.slice(
                                    1,
                                    5,
                                )"
                                :key="index"
                                :class="[
                                    'relative overflow-hidden cursor-pointer',
                                    {
                                        'rounded-tr-xl': index === 0,
                                        'rounded-br-xl': index === 3,
                                    },
                                ]"
                                @click="openGallery(index + 1)"
                            >
                                <img
                                    :src="image"
                                    :alt="`${project.title} - Photo ${index + 2}`"
                                    class="w-full h-full object-cover hover:brightness-110 transition-all duration-200"
                                />
                                <div
                                    v-if="
                                        index === 3 && displayImages.length > 5
                                    "
                                    class="absolute inset-0 bg-black/60 flex items-center justify-center"
                                >
                                    <span class="text-white font-medium"
                                        >+{{
                                            displayImages.length - 5
                                        }}
                                        photos</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <Button
                        variant="outline"
                        class="absolute bottom-4 right-4 bg-white hover:bg-white border border-foreground rounded-lg px-4 py-2 text-foreground font-medium"
                        @click="openGallery(0)"
                    >
                        Show all photos
                    </Button>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Contenu principal -->
                    <div class="lg:col-span-2 space-y-8">
                        <div>
                            <p class="text-xl text-[rgb(0,0,0)] mb-4 font-bold">
                                {{ project.type }}
                            </p>

                            <div
                                class="flex items-center text-muted-foreground mb-6"
                            >
                                <MapPin class="w-5 h-5 mr-2" />
                                <span>{{ project.location }}</span>
                            </div>

                            <p class="text-muted-foreground">
                                Proposé par
                                <span
                                    class="underline font-medium cursor-pointer hover:text-primary transition-colors"
                                    @click="
                                        navigateTo(
                                            `/developer/${encodeURIComponent(project.developer)}`,
                                        )
                                    "
                                    >{{ project.developer }}</span
                                >, publié le
                                {{ project.publishDate || "Récemment" }}
                            </p>
                        </div>

                        <div class="border-t border-border pt-8">
                            <h2 class="text-2xl font-semibold mb-4">
                                Description
                            </h2>
                            <p class="text-muted-foreground leading-relaxed">
                                {{
                                    project.description ||
                                    "Découvrez ce magnifique projet immobilier qui allie confort moderne et qualité de vie exceptionnelle. Situé dans un quartier prisé, il offre des espaces de vie généreux et des finitions soignées."
                                }}
                            </p>
                        </div>

                        <div
                            v-if="
                                project.amenities &&
                                project.amenities.length > 0
                            "
                            class="border-t border-border pt-8"
                        >
                            <h2 class="text-2xl font-semibold mb-6">
                                Équipements
                            </h2>
                            <div class="grid grid-cols-2 gap-4">
                                <div
                                    v-for="amenity in project.amenities"
                                    :key="amenity.name"
                                    class="flex items-center gap-3"
                                >
                                    <component
                                        :is="amenity.icon"
                                        class="w-6 h-6 text-muted-foreground"
                                    />
                                    <span class="text-foreground">{{
                                        amenity.name
                                    }}</span>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="project.units && project.units.length > 0"
                            class="border-t border-border pt-8"
                        >
                            <h2 class="text-2xl font-semibold mb-6">
                                Logements disponibles
                            </h2>
                            
                            <div class="space-y-8">
                                <div
                                    v-for="(unit, index) in project.units"
                                    :key="index"
                                >
                                    <!-- Unit Group Header -->
                                    <div class="flex items-baseline justify-between mb-4">
                                        <h3 class="text-lg font-semibold">{{ unit.type }}</h3>
                                        <button
                                            @click="expandedUnit = expandedUnit === index ? null : index"
                                            class="text-sm text-foreground hover:text-primary transition-colors flex items-center gap-1"
                                        >
                                            {{ unit.count }}
                                            <svg class="w-4 h-4" :class="{ 'rotate-180': expandedUnit === index }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <div class="flex items-center justify-between mb-4">
                                        <p class="text-sm text-muted-foreground">{{ unit.priceRange }}</p>
                                    </div>

                                    <!-- Unit Details List -->
                                    <div v-if="expandedUnit === index" class="space-y-3">
                                        <div
                                            v-for="(detail, detailIndex) in unit.details"
                                            :key="detailIndex"
                                            class="flex items-center justify-between py-4 border-b border-border last:border-0 hover:bg-muted/30 -mx-4 px-4 rounded-lg transition-colors"
                                        >
                                            <!-- Left: Title and Details -->
                                            <div>
                                                <p class="font-medium mb-1">{{ detail.type }}</p>
                                                <p class="text-sm text-muted-foreground">
                                                    {{ detail.surface }} · {{ detail.rooms }} · {{ detail.bathrooms }}
                                                </p>
                                            </div>

                                            <!-- Right: Price and Actions -->
                                            <div class="flex items-center gap-4">
                                                <p class="text-primary font-semibold text-lg">{{ detail.price }}</p>
                                                <div class="flex gap-2">
                                                    <Button
                                                        variant="outline"
                                                        size="sm"
                                                        class="rounded-full border border-border hover:border-primary hover:text-primary transition-colors px-4"
                                                        @click="navigateTo(`/project/${projectId}/listing/${index * 10 + detailIndex + 1}`)"
                                                    >
                                                        Voir l'annonce
                                                    </Button>
                                                    <Button
                                                        size="sm"
                                                        class="bg-primary hover:bg-primary/90 text-white rounded-full px-6"
                                                        @click.stop="handleContactUnit(detail)"
                                                    >
                                                        Demander le plan
                                                    </Button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar contact -->
                    <div class="lg:col-span-1">
                        <div class="sticky top-32 self-start">
                            <div class="shadow-xl border border-border/50 rounded-2xl">
                                <!-- Developer Info & Contact Form -->
                                <div class="p-6">
                                    <div class="mb-6">
                                        <p class="text-sm text-muted-foreground mb-2">À partir de</p>
                                        <p class="text-3xl font-semibold text-primary">
                                            {{ project.price }} TND
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-4 mb-6">
                                        <div
                                            class="cursor-pointer"
                                            @click="navigateTo(`/developer/${encodeURIComponent(project.developer)}`)"
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
                                                @click="navigateTo(`/developer/${encodeURIComponent(project.developer)}`)"
                                            >
                                                {{ project.developer }}
                                            </h3>
                                        </div>
                                    </div>

                                    <form @submit.prevent="handleSubmit" class="space-y-4">
                                        <div class="grid grid-cols-2 gap-3">
                                            <input
                                                v-model="formData.firstName"
                                                placeholder="Prénom"
                                                required
                                                class="h-12 rounded-lg border border-border focus:border-foreground transition-colors px-4 outline-none"
                                                :class="{ 'border-red-500': formErrors.firstName }"
                                            />
                                            <input
                                                v-model="formData.lastName"
                                                placeholder="Nom"
                                                required
                                                class="h-12 rounded-lg border border-border focus:border-foreground transition-colors px-4 outline-none"
                                                :class="{ 'border-red-500': formErrors.lastName }"
                                            />
                                        </div>

                                        <input
                                            v-model="formData.email"
                                            type="email"
                                            placeholder="Adresse e-mail"
                                            required
                                            class="w-full h-12 rounded-lg border border-border focus:border-foreground transition-colors px-4 outline-none"
                                            :class="{ 'border-red-500': formErrors.email }"
                                        />

                                        <input
                                            v-model="formData.phone"
                                            placeholder="Numéro de téléphone"
                                            required
                                            class="w-full h-12 rounded-lg border border-border focus:border-foreground transition-colors px-4 outline-none"
                                            :class="{ 'border-red-500': formErrors.phone }"
                                        />

                                        <Button
                                            v-if="!showMessageField"
                                            variant="ghost"
                                            size="sm"
                                            type="button"
                                            @click="showMessageField = true"
                                            class="h-auto p-0 text-foreground hover:bg-transparent text-sm justify-start"
                                        >
                                            <MessageSquare class="w-4 h-4 mr-2" />
                                            Ajouter un message (facultatif)
                                        </Button>

                                        <textarea
                                            v-if="showMessageField"
                                            v-model="formData.message"
                                            placeholder="Bonjour, je suis intéressé(e) par ce projet. Pouvez-vous me contacter ?"
                                            rows="3"
                                            class="w-full rounded-lg border border-border focus:border-foreground resize-none transition-colors px-4 py-3 outline-none"
                                        ></textarea>

                                        <Button
                                            type="submit"
                                            :disabled="isSubmitting"
                                            class="w-full bg-primary hover:bg-primary/90 rounded-lg h-12 font-semibold text-base shadow-sm"
                                        >
                                            <LoadingSpinner
                                                v-if="isSubmitting"
                                                size="sm"
                                                variant="white"
                                                class="mr-2"
                                            />
                                            {{
                                                isSubmitting
                                                    ? "Envoi..."
                                                    : `Contacter ${project.developer}`
                                            }}
                                        </Button>

                                        <p class="text-xs text-muted-foreground text-center leading-relaxed px-2">
                                            En contactant ce promoteur, vous acceptez de recevoir des informations sur ce projet.
                                        </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Section -->
            <div class="mt-16 max-w-7xl mx-auto px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-2">
                        <hr class="border-border mb-8" />
                        <h2 class="text-2xl font-semibold mb-6">Localisation</h2>
                        <div class="h-96 bg-muted rounded-2xl overflow-hidden relative z-10">
                            <MapView
                                v-if="project.coordinates"
                                :projects="[{ ...project, image: project.images[0] }]"
                                :selected-project-id="project.id"
                            />
                            <div v-else class="flex items-center justify-center h-full">
                                <p class="text-muted-foreground">Carte non disponible</p>
                            </div>
                        </div>
                    </div>
                    <!-- Empty space for alignment -->
                    <div class="lg:col-span-1"></div>
                </div>
            </div>

            <!-- Other Projects from Same Developer -->
            <div class="mt-16 max-w-7xl mx-auto px-6 lg:px-8 pb-16">
                <hr class="border-border mb-8" />
                <h2 class="text-2xl font-semibold mb-8">
                    Autres projets de {{ project.developer }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Mock other projects -->
                    <div 
                        v-for="otherProject in developerProjects"
                        :key="otherProject.id"
                        class="overflow-hidden group cursor-pointer hover:shadow-xl transition-all duration-300 border border-border/50 rounded-xl"
                        @click="navigateTo(`/project/${otherProject.id}`)"
                    >
                        <div class="relative aspect-[4/3]">
                            <img
                                :src="otherProject.image"
                                :alt="otherProject.title"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                            <div class="absolute top-4 left-4">
                                <Badge class="bg-white text-foreground shadow-md rounded-full">
                                    {{ otherProject.status }}
                                </Badge>
                            </div>
                            <div class="absolute bottom-4 right-4">
                                <div class="w-10 h-10 bg-foreground rounded-full flex items-center justify-center shadow-md">
                                    <span class="text-white font-semibold text-xs">
                                        {{ otherProject.developerLogo }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="font-semibold text-lg mb-1">{{ otherProject.title }}</h3>
                            <div class="flex items-center text-muted-foreground mb-3">
                                <MapPin class="w-4 h-4 mr-1" />
                                <span class="text-sm">{{ otherProject.location }}</span>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-sm text-muted-foreground">À partir de</span>
                                <span class="text-foreground font-semibold">{{ otherProject.price }} TND</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <Footer />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useProjectsStore } from "~/stores/projects";
import { useUIStore } from "~/stores/ui";
import {
    ArrowLeft,
    MapPin,
    Share,
    Heart,
    Menu,
    User,
    Phone,
    Mail,
    Home,
    Car,
    Dumbbell,
    Waves,
    Wifi,
    AlertCircle,
    MessageSquare,
} from "lucide-vue-next";
import Button from "~/components/ui/Button.vue";
import SearchBar from "~/components/SearchBar.vue";
import LoadingSpinner from "~/components/ui/LoadingSpinner.vue";
import Badge from "~/components/ui/Badge.vue";
import MapView from "~/components/MapView.vue";
import Footer from "~/components/Footer.vue";

const route = useRoute();
const projectsStore = useProjectsStore();
const uiStore = useUIStore();

const projectId = computed(() => {
    const id = route?.params?.id;
    return id ? parseInt(id as string) : 0;
});
const expandedUnit = ref<number | null>(null);
const isLoading = ref(true);
const error = ref(false);
const isSaved = ref(false);
const isSubmitting = ref(false);
const showMessageField = ref(false);

const formData = ref({
    firstName: "",
    lastName: "",
    email: "",
    phone: "",
    message: "",
});

const formErrors = ref({
    firstName: "",
    lastName: "",
    email: "",
    phone: "",
});

const project = ref({
    id: projectId.value,
    title: "The Village",
    location: "Soukra, Chotrana 2",
    price: "435 000",
    type: "Appartements neufs 3 à 5 pièces",
    status: "Haut standing, finalisé",
    developer: "SPIV Promotion",
    developerLogo: "SPIV",
    publishDate: "06/10/2024",
    coordinates: { lat: 36.8665, lng: 10.1815 },
    description:
        "La société SPIV vous invite à découvrir son projet « The Village » idéalement situé à Soukra, Chotrana 2 déployant ses neuf blocs en R+2. Cette résidence vous propose des appartements et des duplex d'exception, la résidence The Village offre à ses habitants un cadre luxueux et moderne où il fait bon vivre toute l'année.",
    amenities: [
        { icon: Car, name: "Parking privé" },
        { icon: Dumbbell, name: "Salle de sport" },
        { icon: Waves, name: "Piscine" },
        { icon: Wifi, name: "Wifi gratuit" },
    ],
    images: [
        "https://images.unsplash.com/photo-1677553512940-f79af72efd1b?w=800",
        "https://images.unsplash.com/photo-1664892798972-079f15663b16?w=400",
        "https://images.unsplash.com/photo-1670352702722-058a93937fc1?w=400",
        "https://images.unsplash.com/photo-1757264119016-7e6b568b810d?w=400",
        "https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=400",
    ],
    units: [
        {
            type: "Appartements 2 pièces",
            priceRange: "De 370 000 TND à 499 000 TND",
            count: "3 biens",
            details: [
                {
                    type: "Appartement 2 pièces",
                    surface: "45 m²",
                    rooms: "1 Ch.",
                    bathrooms: "1 Sdb.",
                    price: "370 000 TND",
                },
                {
                    type: "Appartement 2 pièces",
                    surface: "50 m²",
                    rooms: "1 Ch.",
                    bathrooms: "1 Sdb.",
                    price: "420 000 TND",
                },
                {
                    type: "Appartement 2 pièces",
                    surface: "55 m²",
                    rooms: "1 Ch.",
                    bathrooms: "1 Sdb.",
                    price: "499 000 TND",
                },
            ],
        },
        {
            type: "Appartements 3 pièces",
            priceRange: "De 550 000 TND à 650 000 TND",
            count: "5 biens",
            details: [
                {
                    type: "Appartement 3 pièces",
                    surface: "75 m²",
                    rooms: "2 Ch.",
                    bathrooms: "1 Sdb.",
                    price: "550 000 TND",
                },
                {
                    type: "Appartement 3 pièces",
                    surface: "80 m²",
                    rooms: "2 Ch.",
                    bathrooms: "2 Sdb.",
                    price: "620 000 TND",
                },
            ],
        },
    ],
});

// Mock other projects from same developer
const developerProjects = ref([
    {
        id: 2,
        title: "Les Jardins de Carthage",
        location: "Carthage, Tunis",
        price: "520 000",
        status: "En construction",
        developer: "SPIV Promotion",
        developerLogo: "SPIV",
        image: "https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800",
    },
    {
        id: 3,
        title: "Résidence Azur",
        location: "La Marsa, Tunis",
        price: "680 000",
        status: "Bientôt disponible",
        developer: "SPIV Promotion",
        developerLogo: "SPIV",
        image: "https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=800",
    },
    {
        id: 4,
        title: "Palais des Roses",
        location: "Gammarth, Tunis",
        price: "890 000",
        status: "Livraison 2025",
        developer: "SPIV Promotion",
        developerLogo: "SPIV",
        image: "https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800",
    },
]);

const displayImages = computed(() => project.value?.images || []);

onMounted(async () => {
    try {
        isLoading.value = true;
        
        // Fetch from API instead of using mock data
        const foundProject = await projectsStore.fetchProjectById(projectId.value);
        
        if (foundProject) {
            project.value = {
                ...project.value,
                ...foundProject,
                amenities: project.value.amenities, // Keep static amenities for now
                units: project.value.units, // Keep static units for now
            };
        } else {
            error.value = true;
        }

        isLoading.value = false;
    } catch (e) {
        error.value = true;
        isLoading.value = false;
        uiStore.showError("Erreur", "Impossible de charger le projet");
    }
});

const validateForm = () => {
    formErrors.value = {
        firstName: "",
        lastName: "",
        email: "",
        phone: "",
    };

    let isValid = true;

    if (!formData.value.firstName.trim()) {
        formErrors.value.firstName = "Prénom requis";
        isValid = false;
    }

    if (!formData.value.lastName.trim()) {
        formErrors.value.lastName = "Nom requis";
        isValid = false;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(formData.value.email)) {
        formErrors.value.email = "Email invalide";
        isValid = false;
    }

    const phoneRegex = /^[\d\s\+\-\(\)]{8,}$/;
    if (!phoneRegex.test(formData.value.phone)) {
        formErrors.value.phone = "Téléphone invalide";
        isValid = false;
    }

    return isValid;
};

const handleSubmit = async () => {
    if (!validateForm()) {
        uiStore.showError(
            "Erreur",
            "Veuillez corriger les erreurs du formulaire",
        );
        return;
    }

    isSubmitting.value = true;

    try {
        await new Promise((resolve) => setTimeout(resolve, 1500)); // Simulate API call

        uiStore.showSuccess(
            "Demande envoyée !",
            `Nous vous contacterons bientôt concernant ${project.value.title}`,
        );

        // Reset form
        formData.value = {
            firstName: "",
            lastName: "",
            email: "",
            phone: "",
            message: "",
        };
    } catch (e) {
        uiStore.showError(
            "Erreur",
            "Impossible d'envoyer votre demande. Veuillez réessayer.",
        );
    } finally {
        isSubmitting.value = false;
    }
};

const handleContactUnit = (detail: any) => {
    formData.value.message = `Bonjour, je suis intéressé(e) par ${detail.type} de ${detail.surface}. Pouvez-vous me contacter ?`;
    showMessageField.value = true;
    uiStore.showInfo("Message pré-rempli", "Vous pouvez maintenant envoyer votre demande");
};

const handleShare = () => {
    if (navigator.share) {
        navigator.share({
            title: project.value.title,
            text: `Découvrez ${project.value.title} sur neuf.tn`,
            url: window.location.href,
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
        uiStore.showSuccess(
            "Lien copié !",
            "Le lien a été copié dans votre presse-papiers",
        );
    }
};

const handleSave = () => {
    isSaved.value = !isSaved.value;
    if (isSaved.value) {
        uiStore.showSuccess(
            "Projet sauvegardé",
            `${project.value.title} a été ajouté à vos favoris`,
        );
    } else {
        uiStore.showInfo(
            "Projet retiré",
            `${project.value.title} a été retiré de vos favoris`,
        );
    }
};

const openGallery = (index: number) => {
    console.log("Open gallery at index:", index);
    // Implement gallery modal here
};
</script>
