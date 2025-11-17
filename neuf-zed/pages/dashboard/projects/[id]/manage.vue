<script setup lang="ts">
import { ref, computed } from "vue";
import {
    ArrowLeft,
    Plus,
    Home,
    Eye,
    FileText,
    Settings,
    MapPin,
    Calendar,
    Building2,
    Edit,
    Trash2,
    AlertTriangle,
    Users,
    MessageSquare,
} from "lucide-vue-next";
import Button from "~/components/ui/Button.vue";
import Badge from "~/components/ui/Badge.vue";

const route = useRoute();
const projectId = computed(() => {
    const id = route?.params?.id
    return id ? parseInt(id as string) : 0
});

const selectedTab = ref("listings");
const isPublished = ref(true);
const filterStatus = ref<string>("all");

const mockProject = {
    id: projectId.value,
    name: "Les Jardins de Carthage",
    location: "Carthage, Tunis",
    address: "Avenue de la République, Carthage",
    status: "construction",
    deliveryDate: "Q2 2025",
    description:
        "Résidence de standing située au cœur de Carthage, offrant un cadre de vie exceptionnel avec vue mer et prestations haut de gamme.",
    image: "https://images.unsplash.com/photo-1664892798972-079f15663b16?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtb2Rlcm4lMjBsdXh1cnklMjBhcGFydG1lbnQlMjBidWlsZGluZ3xlbnwxfHx8fDE3NTk3NDg0NTd8MA&ixlib=rb-4.1.0&q=80&w=1080",
    isPublished: true,
    types: ["Appartements", "Penthouses"],
    totalListings: 15,
    availableListings: 8,
    reservedListings: 4,
    soldListings: 3,
    totalViews: 2456,
    totalContacts: 89,
};

const mockListings = [
    {
        id: 1,
        type: "Appartement S+2",
        surface: 85,
        price: "280 000",
        bedrooms: 2,
        bathrooms: 1,
        floor: "2",
        orientation: "Sud",
        status: "available",
        image: "https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=400&h=300&fit=crop",
    },
    {
        id: 2,
        type: "Appartement S+3",
        surface: 120,
        price: "340 000",
        bedrooms: 3,
        bathrooms: 2,
        floor: "3",
        orientation: "Nord-Est",
        status: "available",
        image: "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=400&h=300&fit=crop",
    },
    {
        id: 3,
        type: "Appartement S+4",
        surface: 155,
        price: "420 000",
        bedrooms: 4,
        bathrooms: 2,
        floor: "4",
        orientation: "Sud",
        status: "sold",
        image: "https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=400&h=300&fit=crop",
    },
    {
        id: 4,
        type: "Penthouse S+5",
        surface: 220,
        price: "680 000",
        bedrooms: 5,
        bathrooms: 3,
        floor: "Dernier",
        orientation: "Panoramique",
        status: "reserved",
        image: "https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?w=400&h=300&fit=crop",
    },
    {
        id: 5,
        type: "Appartement S+2",
        surface: 90,
        price: "295 000",
        bedrooms: 2,
        bathrooms: 1,
        floor: "1",
        orientation: "Ouest",
        status: "available",
        image: "https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=400&h=300&fit=crop",
    },
];

const getStatusLabel = (status: string) => {
    switch (status) {
        case "available":
            return "Disponible";
        case "reserved":
            return "Réservé";
        case "sold":
            return "Vendu";
        default:
            return status;
    }
};

const getStatusColor = (status: string) => {
    switch (status) {
        case "available":
            return "bg-green-100 text-green-700 border-green-200";
        case "reserved":
            return "bg-amber-100 text-amber-700 border-amber-200";
        case "sold":
            return "bg-gray-100 text-gray-700 border-gray-200";
        default:
            return "bg-gray-100 text-gray-700 border-gray-200";
    }
};

const filteredListings = computed(() => {
    if (filterStatus.value === "all") return mockListings;
    return mockListings.filter((l) => l.status === filterStatus.value);
});

const handleDeleteProject = () => {
    if (
        confirm(
            "Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.",
        )
    ) {
        console.log("Projet supprimé");
        window.location.href = "/dashboard";
    }
};

const goBack = () => {
    window.location.href = "/dashboard";
};

const handleAddListing = () => {
    window.location.href = `/dashboard/projects/${projectId.value}/listings/create`;
};

const handleEditProject = () => {
    // Navigate to edit project page (can be implemented later)
    console.log("Edit project:", projectId.value);
};

const handleListingClick = (listing: any) => {
    window.location.href = `/dashboard/projects/${projectId.value}/listings/${listing.id}`;
};

const handleContactsClick = () => {
    window.location.href = "/dashboard/contacts";
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="sticky top-0 z-50 bg-white border-b border-border">
            <div class="max-w-[1760px] mx-auto px-6 lg:px-20">
                <div class="py-6">
                    <button
                        @click="goBack"
                        class="flex items-center gap-2 text-muted-foreground hover:text-foreground transition-colors mb-4"
                    >
                        <ArrowLeft class="w-5 h-5" />
                        <span class="font-medium"
                            >Retour au tableau de bord</span
                        >
                    </button>

                    <div
                        class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6"
                    >
                        <div class="flex items-start gap-6">
                            <img
                                :src="mockProject.image"
                                :alt="mockProject.name"
                                class="w-24 h-24 rounded-xl object-cover border-2 border-border"
                            />
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <h1
                                        class="text-2xl lg:text-3xl font-bold text-foreground"
                                    >
                                        {{ mockProject.name }}
                                    </h1>
                                    <Badge
                                        :variant="
                                            mockProject.isPublished
                                                ? 'default'
                                                : 'secondary'
                                        "
                                    >
                                        {{
                                            mockProject.isPublished
                                                ? "Publié"
                                                : "Brouillon"
                                        }}
                                    </Badge>
                                </div>
                                <div
                                    class="flex items-center gap-4 text-sm text-muted-foreground"
                                >
                                    <div class="flex items-center gap-1">
                                        <MapPin class="w-4 h-4" />
                                        <span>{{ mockProject.location }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <Calendar class="w-4 h-4" />
                                        <span
                                            >Livraison:
                                            {{ mockProject.deliveryDate }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <Button
                                variant="outline"
                                size="sm"
                                @click="handleEditProject"
                            >
                                <Edit class="w-4 h-4 mr-2" />
                                Modifier
                            </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                @click="handleContactsClick"
                            >
                                <MessageSquare class="w-4 h-4 mr-2" />
                                Contacts
                            </Button>
                            <Button
                                variant="default"
                                size="sm"
                                @click="handleAddListing"
                            >
                                <Plus class="w-4 h-4 mr-2" />
                                Ajouter un bien
                            </Button>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="flex gap-6 border-t border-border">
                    <button
                        @click="selectedTab = 'listings'"
                        :class="[
                            'py-4 font-medium border-b-2 transition-colors',
                            selectedTab === 'listings'
                                ? 'border-primary text-primary'
                                : 'border-transparent text-muted-foreground hover:text-foreground',
                        ]"
                    >
                        <div class="flex items-center gap-2">
                            <Home class="w-4 h-4" />
                            <span>Biens ({{ mockProject.totalListings }})</span>
                        </div>
                    </button>
                    <button
                        @click="selectedTab = 'stats'"
                        :class="[
                            'py-4 font-medium border-b-2 transition-colors',
                            selectedTab === 'stats'
                                ? 'border-primary text-primary'
                                : 'border-transparent text-muted-foreground hover:text-foreground',
                        ]"
                    >
                        <div class="flex items-center gap-2">
                            <Eye class="w-4 h-4" />
                            <span>Statistiques</span>
                        </div>
                    </button>
                    <button
                        @click="selectedTab = 'settings'"
                        :class="[
                            'py-4 font-medium border-b-2 transition-colors',
                            selectedTab === 'settings'
                                ? 'border-primary text-primary'
                                : 'border-transparent text-muted-foreground hover:text-foreground',
                        ]"
                    >
                        <div class="flex items-center gap-2">
                            <Settings class="w-4 h-4" />
                            <span>Paramètres</span>
                        </div>
                    </button>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="max-w-[1760px] mx-auto px-6 lg:px-20 py-8">
            <!-- Listings Tab -->
            <div v-if="selectedTab === 'listings'">
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl p-6 border-2 border-border">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm text-muted-foreground">Total</p>
                            <Building2 class="w-5 h-5 text-primary" />
                        </div>
                        <p class="text-3xl font-bold text-foreground">
                            {{ mockProject.totalListings }}
                        </p>
                    </div>

                    <div class="bg-white rounded-xl p-6 border-2 border-border">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm text-muted-foreground">
                                Disponibles
                            </p>
                            <div
                                class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center"
                            >
                                <div
                                    class="w-2 h-2 rounded-full bg-green-500"
                                ></div>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-foreground">
                            {{ mockProject.availableListings }}
                        </p>
                    </div>

                    <div class="bg-white rounded-xl p-6 border-2 border-border">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm text-muted-foreground">
                                Réservés
                            </p>
                            <div
                                class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center"
                            >
                                <div
                                    class="w-2 h-2 rounded-full bg-amber-500"
                                ></div>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-foreground">
                            {{ mockProject.reservedListings }}
                        </p>
                    </div>

                    <div class="bg-white rounded-xl p-6 border-2 border-border">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm text-muted-foreground">Vendus</p>
                            <div
                                class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center"
                            >
                                <div
                                    class="w-2 h-2 rounded-full bg-gray-500"
                                ></div>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-foreground">
                            {{ mockProject.soldListings }}
                        </p>
                    </div>
                </div>

                <!-- Filters -->
                <div
                    class="bg-white rounded-xl border-2 border-border p-6 mb-6"
                >
                    <div
                        class="flex items-center justify-between flex-wrap gap-4"
                    >
                        <div>
                            <h2
                                class="text-lg font-semibold text-foreground mb-1"
                            >
                                Liste des biens
                            </h2>
                            <p class="text-sm text-muted-foreground">
                                Gérez tous les biens de ce projet
                            </p>
                        </div>

                        <div class="flex gap-2">
                            <Button
                                :variant="
                                    filterStatus === 'all'
                                        ? 'default'
                                        : 'outline'
                                "
                                size="sm"
                                @click="filterStatus = 'all'"
                            >
                                Tous ({{ mockListings.length }})
                            </Button>
                            <Button
                                :variant="
                                    filterStatus === 'available'
                                        ? 'default'
                                        : 'outline'
                                "
                                size="sm"
                                @click="filterStatus = 'available'"
                            >
                                Disponibles ({{
                                    mockProject.availableListings
                                }})
                            </Button>
                            <Button
                                :variant="
                                    filterStatus === 'reserved'
                                        ? 'default'
                                        : 'outline'
                                "
                                size="sm"
                                @click="filterStatus = 'reserved'"
                            >
                                Réservés ({{ mockProject.reservedListings }})
                            </Button>
                            <Button
                                :variant="
                                    filterStatus === 'sold'
                                        ? 'default'
                                        : 'outline'
                                "
                                size="sm"
                                @click="filterStatus = 'sold'"
                            >
                                Vendus ({{ mockProject.soldListings }})
                            </Button>
                        </div>
                    </div>
                </div>

                <!-- Listings Grid -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <div
                        v-for="listing in filteredListings"
                        :key="listing.id"
                        @click="handleListingClick(listing)"
                        class="bg-white rounded-xl border-2 border-border overflow-hidden hover:border-primary transition-all cursor-pointer group"
                    >
                        <div class="relative h-48 overflow-hidden">
                            <img
                                :src="listing.image"
                                :alt="listing.type"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            />
                            <div class="absolute top-3 right-3">
                                <span
                                    :class="[
                                        'px-3 py-1 rounded-full text-xs font-medium border',
                                        getStatusColor(listing.status),
                                    ]"
                                >
                                    {{ getStatusLabel(listing.status) }}
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            <h3
                                class="text-lg font-semibold text-foreground mb-2"
                            >
                                {{ listing.type }}
                            </h3>

                            <div class="space-y-2 mb-4">
                                <div
                                    class="flex items-center justify-between text-sm"
                                >
                                    <span class="text-muted-foreground"
                                        >Surface</span
                                    >
                                    <span class="font-medium text-foreground"
                                        >{{ listing.surface }} m²</span
                                    >
                                </div>
                                <div
                                    class="flex items-center justify-between text-sm"
                                >
                                    <span class="text-muted-foreground"
                                        >Étage</span
                                    >
                                    <span class="font-medium text-foreground">{{
                                        listing.floor
                                    }}</span>
                                </div>
                                <div
                                    class="flex items-center justify-between text-sm"
                                >
                                    <span class="text-muted-foreground"
                                        >Orientation</span
                                    >
                                    <span class="font-medium text-foreground">{{
                                        listing.orientation
                                    }}</span>
                                </div>
                            </div>

                            <div
                                class="flex items-center justify-between pt-4 border-t border-border"
                            >
                                <div
                                    class="flex items-center gap-3 text-sm text-muted-foreground"
                                >
                                    <div class="flex items-center gap-1">
                                        <Home class="w-4 h-4" />
                                        <span>{{ listing.bedrooms }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span>🚿</span>
                                        <span>{{ listing.bathrooms }}</span>
                                    </div>
                                </div>
                                <p class="text-lg font-bold text-primary">
                                    {{ listing.price }} TND
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Add New Listing Card -->
                    <button
                        @click="handleAddListing"
                        class="bg-white rounded-xl border-2 border-dashed border-border hover:border-primary transition-all p-12 flex flex-col items-center justify-center gap-4 group"
                    >
                        <div
                            class="w-16 h-16 rounded-full bg-gray-100 group-hover:bg-primary/10 flex items-center justify-center transition-colors"
                        >
                            <Plus
                                class="w-8 h-8 text-muted-foreground group-hover:text-primary transition-colors"
                            />
                        </div>
                        <div class="text-center">
                            <p class="font-semibold text-foreground mb-1">
                                Ajouter un bien
                            </p>
                            <p class="text-sm text-muted-foreground">
                                Créez une nouvelle annonce
                            </p>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Stats Tab -->
            <div v-if="selectedTab === 'stats'" class="space-y-6">
                <div class="bg-white rounded-xl border-2 border-border p-8">
                    <h2 class="text-2xl font-bold text-foreground mb-6">
                        Statistiques du projet
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gray-50 rounded-xl p-6">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm text-muted-foreground">
                                    Vues totales
                                </p>
                                <Eye class="w-5 h-5 text-primary" />
                            </div>
                            <p class="text-3xl font-bold text-foreground">
                                {{ mockProject.totalViews }}
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-6">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm text-muted-foreground">
                                    Contacts reçus
                                </p>
                                <Users class="w-5 h-5 text-primary" />
                            </div>
                            <p class="text-3xl font-bold text-foreground">
                                {{ mockProject.totalContacts }}
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-6">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm text-muted-foreground">
                                    Taux de conversion
                                </p>
                                <MessageSquare class="w-5 h-5 text-primary" />
                            </div>
                            <p class="text-3xl font-bold text-foreground">
                                {{
                                    (
                                        (mockProject.totalContacts /
                                            mockProject.totalViews) *
                                        100
                                    ).toFixed(1)
                                }}%
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border-2 border-border p-8">
                    <h3 class="text-xl font-semibold text-foreground mb-4">
                        Détails supplémentaires
                    </h3>
                    <p class="text-muted-foreground">
                        Les statistiques détaillées et graphiques seront
                        disponibles prochainement.
                    </p>
                </div>
            </div>

            <!-- Settings Tab -->
            <div v-if="selectedTab === 'settings'" class="space-y-6">
                <div class="bg-white rounded-xl border-2 border-border p-8">
                    <h2 class="text-2xl font-bold text-foreground mb-6">
                        Paramètres du projet
                    </h2>

                    <div class="space-y-6">
                        <div>
                            <h3
                                class="text-lg font-semibold text-foreground mb-4"
                            >
                                Visibilité
                            </h3>
                            <div
                                class="flex items-center justify-between p-4 bg-gray-50 rounded-xl"
                            >
                                <div>
                                    <p class="font-medium text-foreground">
                                        Projet publié
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        Le projet est visible sur la plateforme
                                    </p>
                                </div>
                                <label
                                    class="relative inline-flex items-center cursor-pointer"
                                >
                                    <input
                                        v-model="isPublished"
                                        type="checkbox"
                                        class="sr-only peer"
                                    />
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"
                                    ></div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <h3
                                class="text-lg font-semibold text-foreground mb-4"
                            >
                                Zone de danger
                            </h3>
                            <div
                                class="border-2 border-red-200 rounded-xl p-6 bg-red-50"
                            >
                                <div class="flex items-start gap-4">
                                    <AlertTriangle
                                        class="w-6 h-6 text-red-600 flex-shrink-0 mt-1"
                                    />
                                    <div class="flex-1">
                                        <h4
                                            class="font-semibold text-foreground mb-2"
                                        >
                                            Supprimer ce projet
                                        </h4>
                                        <p
                                            class="text-sm text-muted-foreground mb-4"
                                        >
                                            Cette action est irréversible. Tous
                                            les biens associés seront également
                                            supprimés.
                                        </p>
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            @click="handleDeleteProject"
                                            class="border-red-300 text-red-600 hover:bg-red-100"
                                        >
                                            <Trash2 class="w-4 h-4 mr-2" />
                                            Supprimer le projet
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
