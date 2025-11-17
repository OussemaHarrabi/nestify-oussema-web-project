<script setup lang="ts">
import { ref, computed } from "vue";
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
    Edit,
    Trash2,
    Eye,
    MessageSquare,
    Phone,
    Mail,
} from "lucide-vue-next";
import Button from "~/components/ui/Button.vue";
import Badge from "~/components/ui/Badge.vue";

const route = useRoute();
const projectId = computed(() => {
    const id = route?.params?.projectId
    return id ? parseInt(id as string) : 0
});
const listingId = computed(() => {
    const id = route?.params?.id
    return id ? parseInt(id as string) : 0
});

const selectedImageIndex = ref(0);
const showContactForm = ref(false);

// Mock listing data (in real app, fetch from API based on projectId and listingId)
const listing = {
    id: listingId.value,
    projectId: projectId.value,
    type: "Appartement S+3",
    surface: 120,
    landSurface: 0,
    price: "340 000",
    rooms: 3,
    bedrooms: 3,
    bathrooms: 2,
    floor: "3ème étage",
    orientation: "Nord-Est",
    floorType: "Carrelage premium",
    status: "available",
    isFurnished: false,
    description:
        "Magnifique appartement S+3 de 120m² situé au 3ème étage, offrant une orientation nord-est idéale. Cet appartement moderne dispose de 3 chambres spacieuses, 2 salles de bains élégamment aménagées, d'un salon lumineux et d'une cuisine équipée. Les finitions sont de haute qualité avec du carrelage premium dans toutes les pièces. L'appartement bénéficie également d'un balcon avec une vue dégagée. Idéal pour une famille recherchant confort et modernité dans un quartier prisé.",
    equipment: [
        "Balcon",
        "Parking",
        "Ascenseur",
        "Cuisine équipée",
        "Climatisation",
        "Double vitrage",
        "Sécurité 24h/24",
        "Vue dégagée",
    ],
    images: [
        "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800&h=600&fit=crop",
        "https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&h=600&fit=crop",
        "https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800&h=600&fit=crop",
        "https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=800&h=600&fit=crop",
        "https://images.unsplash.com/photo-1600210492493-0946911123ea?w=800&h=600&fit=crop",
    ],
    views: 156,
    contacts: 8,
};

const project = {
    id: projectId.value,
    name: "Les Jardins de Carthage",
    location: "Carthage, Tunis",
    address: "Avenue de la République, Carthage",
    developer: "SPIV Promotion",
    developerLogo: "SPIV",
    deliveryDate: "Q2 2025",
    image: "https://images.unsplash.com/photo-1664892798972-079f15663b16?w=200",
};

const formData = ref({
    firstName: "",
    lastName: "",
    email: "",
    phone: "",
    message: "",
});

const getStatusBadge = (status: string) => {
    switch (status) {
        case "available":
            return {
                text: "Disponible",
                class: "bg-green-100 text-green-800 border-green-200",
            };
        case "reserved":
            return {
                text: "Réservé",
                class: "bg-amber-100 text-amber-800 border-amber-200",
            };
        case "sold":
            return {
                text: "Vendu",
                class: "bg-gray-100 text-gray-800 border-gray-200",
            };
        default:
            return {
                text: status,
                class: "bg-gray-100 text-gray-800 border-gray-200",
            };
    }
};

const statusBadge = computed(() => getStatusBadge(listing.status));

const goBack = () => {
    window.location.href = `/dashboard/projects/${projectId.value}/manage`;
};

const handleEdit = () => {
    // Navigate to edit page (can be implemented later)
    console.log("Edit listing:", listingId.value);
};

const handleDelete = () => {
    if (
        confirm(
            "Êtes-vous sûr de vouloir supprimer ce bien ? Cette action est irréversible.",
        )
    ) {
        console.log("Listing deleted:", listingId.value);
        window.location.href = `/dashboard/projects/${projectId.value}/manage`;
    }
};

const handleShare = () => {
    if (navigator.share) {
        navigator.share({
            title: `${listing.type} - ${project.name}`,
            text: `Découvrez ce ${listing.type} à ${project.location}`,
            url: window.location.href,
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href);
        alert("Lien copié dans le presse-papiers!");
    }
};

const handleSubmitContact = () => {
    console.log("Contact form submitted:", formData.value);
    showContactForm.value = false;
    // In real app, send to API
    alert("Message envoyé avec succès!");
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white border-b border-border sticky top-0 z-40">
            <div class="max-w-[1760px] mx-auto px-6 lg:px-20 py-4">
                <div class="flex items-center justify-between">
                    <button
                        @click="goBack"
                        class="flex items-center gap-2 text-muted-foreground hover:text-foreground transition-colors"
                    >
                        <ArrowLeft class="w-5 h-5" />
                        <span class="font-medium">Retour au projet</span>
                    </button>

                    <div class="flex items-center gap-3">
                        <Button
                            variant="outline"
                            size="sm"
                            @click="handleShare"
                        >
                            <Share class="w-4 h-4 mr-2" />
                            Partager
                        </Button>
                        <Button variant="outline" size="sm" @click="handleEdit">
                            <Edit class="w-4 h-4 mr-2" />
                            Modifier
                        </Button>
                        <Button
                            variant="outline"
                            size="sm"
                            @click="handleDelete"
                            class="text-red-600 border-red-300 hover:bg-red-50"
                        >
                            <Trash2 class="w-4 h-4 mr-2" />
                            Supprimer
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-[1760px] mx-auto px-6 lg:px-20 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Images & Details -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Image Gallery -->
                    <div
                        class="bg-white rounded-xl border-2 border-border overflow-hidden"
                    >
                        <!-- Main Image -->
                        <div class="relative aspect-video bg-gray-100">
                            <img
                                :src="listing.images[selectedImageIndex]"
                                :alt="`${listing.type} - Image ${selectedImageIndex + 1}`"
                                class="w-full h-full object-cover"
                            />
                            <div class="absolute top-4 left-4">
                                <span
                                    :class="[
                                        'px-4 py-2 rounded-full text-sm font-medium border-2',
                                        statusBadge.class,
                                    ]"
                                >
                                    {{ statusBadge.text }}
                                </span>
                            </div>
                        </div>

                        <!-- Thumbnail Gallery -->
                        <div class="p-4 flex gap-3 overflow-x-auto">
                            <button
                                v-for="(image, index) in listing.images"
                                :key="index"
                                @click="selectedImageIndex = index"
                                :class="[
                                    'relative flex-shrink-0 w-24 h-20 rounded-lg overflow-hidden border-2 transition-all',
                                    selectedImageIndex === index
                                        ? 'border-primary ring-2 ring-primary/20'
                                        : 'border-border hover:border-primary/50',
                                ]"
                            >
                                <img
                                    :src="image"
                                    :alt="`Thumbnail ${index + 1}`"
                                    class="w-full h-full object-cover"
                                />
                            </button>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="bg-white rounded-xl border-2 border-border p-8">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <h1
                                    class="text-3xl font-bold text-foreground mb-2"
                                >
                                    {{ listing.type }}
                                </h1>
                                <div
                                    class="flex items-center gap-4 text-muted-foreground"
                                >
                                    <div class="flex items-center gap-1">
                                        <Building2 class="w-4 h-4" />
                                        <span>{{ project.name }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <MapPin class="w-4 h-4" />
                                        <span>{{ project.location }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-3xl font-bold text-primary">
                                    {{ listing.price }} TND
                                </p>
                                <p class="text-sm text-muted-foreground mt-1">
                                    {{
                                        Math.round(
                                            parseInt(
                                                listing.price.replace(
                                                    /\s/g,
                                                    "",
                                                ),
                                            ) / listing.surface,
                                        )
                                    }}
                                    TND/m²
                                </p>
                            </div>
                        </div>

                        <!-- Key Features Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                            <div class="bg-gray-50 rounded-xl p-4">
                                <div
                                    class="flex items-center gap-2 text-muted-foreground mb-1"
                                >
                                    <Maximize class="w-4 h-4" />
                                    <span class="text-sm">Surface</span>
                                </div>
                                <p class="text-xl font-bold text-foreground">
                                    {{ listing.surface }} m²
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-4">
                                <div
                                    class="flex items-center gap-2 text-muted-foreground mb-1"
                                >
                                    <BedDouble class="w-4 h-4" />
                                    <span class="text-sm">Chambres</span>
                                </div>
                                <p class="text-xl font-bold text-foreground">
                                    {{ listing.bedrooms }}
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-4">
                                <div
                                    class="flex items-center gap-2 text-muted-foreground mb-1"
                                >
                                    <Bath class="w-4 h-4" />
                                    <span class="text-sm">Salles de bain</span>
                                </div>
                                <p class="text-xl font-bold text-foreground">
                                    {{ listing.bathrooms }}
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-4">
                                <div
                                    class="flex items-center gap-2 text-muted-foreground mb-1"
                                >
                                    <Compass class="w-4 h-4" />
                                    <span class="text-sm">Orientation</span>
                                </div>
                                <p class="text-lg font-bold text-foreground">
                                    {{ listing.orientation }}
                                </p>
                            </div>
                        </div>

                        <!-- Additional Details -->
                        <div class="space-y-4 mb-8">
                            <div
                                class="flex items-center justify-between py-3 border-b border-border"
                            >
                                <span class="text-muted-foreground"
                                    >Type de bien</span
                                >
                                <span class="font-medium text-foreground">{{
                                    listing.type
                                }}</span>
                            </div>
                            <div
                                class="flex items-center justify-between py-3 border-b border-border"
                            >
                                <span class="text-muted-foreground">Étage</span>
                                <span class="font-medium text-foreground">{{
                                    listing.floor
                                }}</span>
                            </div>
                            <div
                                class="flex items-center justify-between py-3 border-b border-border"
                            >
                                <span class="text-muted-foreground"
                                    >Type de sol</span
                                >
                                <span class="font-medium text-foreground">{{
                                    listing.floorType
                                }}</span>
                            </div>
                            <div
                                class="flex items-center justify-between py-3 border-b border-border"
                            >
                                <span class="text-muted-foreground"
                                    >Meublé</span
                                >
                                <span class="font-medium text-foreground">
                                    {{ listing.isFurnished ? "Oui" : "Non" }}
                                </span>
                            </div>
                            <div
                                class="flex items-center justify-between py-3 border-b border-border"
                            >
                                <span class="text-muted-foreground"
                                    >Nombre de pièces</span
                                >
                                <span class="font-medium text-foreground">{{
                                    listing.rooms
                                }}</span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-8">
                            <h2 class="text-xl font-bold text-foreground mb-4">
                                Description
                            </h2>
                            <p class="text-foreground leading-relaxed">
                                {{ listing.description }}
                            </p>
                        </div>

                        <!-- Equipment -->
                        <div>
                            <h2 class="text-xl font-bold text-foreground mb-4">
                                Équipements et caractéristiques
                            </h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                <div
                                    v-for="item in listing.equipment"
                                    :key="item"
                                    class="flex items-center gap-2 bg-gray-50 rounded-lg px-4 py-3"
                                >
                                    <div
                                        class="w-2 h-2 rounded-full bg-primary"
                                    ></div>
                                    <span class="text-sm text-foreground">{{
                                        item
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Project Info -->
                    <div class="bg-white rounded-xl border-2 border-border p-8">
                        <h2 class="text-xl font-bold text-foreground mb-6">
                            À propos du projet
                        </h2>
                        <div class="flex items-start gap-6">
                            <img
                                :src="project.image"
                                :alt="project.name"
                                class="w-24 h-24 rounded-xl object-cover border-2 border-border"
                            />
                            <div class="flex-1">
                                <h3
                                    class="text-lg font-semibold text-foreground mb-2"
                                >
                                    {{ project.name }}
                                </h3>
                                <div
                                    class="space-y-2 text-sm text-muted-foreground"
                                >
                                    <div class="flex items-center gap-2">
                                        <MapPin class="w-4 h-4" />
                                        <span>{{ project.address }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <Building2 class="w-4 h-4" />
                                        <span
                                            >Promoteur:
                                            {{ project.developer }}</span
                                        >
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <Calendar class="w-4 h-4" />
                                        <span
                                            >Livraison:
                                            {{ project.deliveryDate }}</span
                                        >
                                    </div>
                                </div>
                                <Button
                                    variant="outline"
                                    size="sm"
                                    class="mt-4"
                                    @click="goBack"
                                >
                                    Voir tous les biens du projet
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Stats & Actions -->
                <div class="space-y-6">
                    <!-- Stats Card -->
                    <div
                        class="bg-white rounded-xl border-2 border-border p-6 sticky top-24"
                    >
                        <h3 class="text-lg font-semibold text-foreground mb-4">
                            Statistiques
                        </h3>
                        <div class="space-y-4">
                            <div
                                class="flex items-center justify-between p-4 bg-gray-50 rounded-xl"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center"
                                    >
                                        <Eye class="w-5 h-5 text-primary" />
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm text-muted-foreground"
                                        >
                                            Vues
                                        </p>
                                        <p
                                            class="text-xl font-bold text-foreground"
                                        >
                                            {{ listing.views }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="flex items-center justify-between p-4 bg-gray-50 rounded-xl"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center"
                                    >
                                        <MessageSquare
                                            class="w-5 h-5 text-primary"
                                        />
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm text-muted-foreground"
                                        >
                                            Contacts
                                        </p>
                                        <p
                                            class="text-xl font-bold text-foreground"
                                        >
                                            {{ listing.contacts }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-border">
                            <h4
                                class="text-sm font-medium text-foreground mb-3"
                            >
                                Actions rapides
                            </h4>
                            <div class="space-y-3">
                                <Button
                                    variant="default"
                                    class="w-full"
                                    @click="showContactForm = !showContactForm"
                                >
                                    <MessageSquare class="w-4 h-4 mr-2" />
                                    Voir les contacts
                                </Button>
                                <Button
                                    variant="outline"
                                    class="w-full"
                                    @click="handleEdit"
                                >
                                    <Edit class="w-4 h-4 mr-2" />
                                    Modifier le bien
                                </Button>
                                <Button
                                    variant="outline"
                                    class="w-full"
                                    @click="handleShare"
                                >
                                    <Share class="w-4 h-4 mr-2" />
                                    Partager
                                </Button>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div
                        v-if="showContactForm"
                        class="bg-white rounded-xl border-2 border-border p-6"
                    >
                        <h3 class="text-lg font-semibold text-foreground mb-4">
                            Demandes de contact
                        </h3>
                        <p class="text-sm text-muted-foreground mb-4">
                            {{ listing.contacts }} personne{{
                                listing.contacts > 1 ? "s ont" : " a"
                            }}
                            manifesté leur intérêt pour ce bien.
                        </p>
                        <Button
                            variant="default"
                            class="w-full"
                            @click="
                                window.location.href = '/dashboard/contacts'
                            "
                        >
                            Voir tous les contacts
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
