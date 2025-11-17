<template>
    <div class="min-h-screen bg-white flex flex-col">
        <!-- Unified Header with Auth -->
        <Header />

        <!-- Search Bar Section -->
        <div class="bg-white border-b border-border py-4 sticky top-20 z-40">
            <div class="max-w-full mx-auto px-6 lg:px-10">
                <SearchBar @search="handleSearch" />
            </div>
        </div>

        <!-- Filters Bar -->
        <div class="bg-white border-b border-border py-4 sticky top-36 z-30">
            <div class="max-w-full mx-auto px-6 lg:px-10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4 flex-wrap">
                        <Button
                            variant="outline"
                            size="sm"
                            class="rounded-full"
                            @click="showFiltersModal = true"
                        >
                            <SlidersHorizontal class="w-4 h-4 mr-2" />
                            Filtres
                            <span
                                v-if="activeFiltersCount > 0"
                                class="ml-2 bg-primary text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"
                            >
                                {{ activeFiltersCount }}
                            </span>
                        </Button>

                        <!-- Active Filters Display -->
                        <div class="flex items-center gap-2 flex-wrap">
                            <Badge
                                v-for="(filter, index) in activeFilters"
                                :key="index"
                                variant="outline"
                                class="px-4 py-2 text-sm rounded-full flex items-center gap-2"
                            >
                                {{ filter.label }}
                                <X
                                    class="w-3 h-3 cursor-pointer hover:text-primary"
                                    @click="removeFilter(filter.key)"
                                />
                            </Badge>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-sm text-muted-foreground"
                            >{{ filteredProjects.length }} projets</span
                        >

                        <div
                            class="flex items-center gap-2 border border-border rounded-full p-1"
                        >
                            <button
                                :class="[
                                    'px-3 py-1.5 rounded-full text-sm font-medium transition-colors',
                                    viewMode === 'list'
                                        ? 'bg-primary text-white'
                                        : 'text-muted-foreground hover:text-foreground',
                                ]"
                                @click="viewMode = 'list'"
                            >
                                <List class="w-4 h-4" />
                            </button>
                            <button
                                :class="[
                                    'px-3 py-1.5 rounded-full text-sm font-medium transition-colors',
                                    viewMode === 'map'
                                        ? 'bg-primary text-white'
                                        : 'text-muted-foreground hover:text-foreground',
                                ]"
                                @click="viewMode = 'map'"
                            >
                                <MapIcon class="w-4 h-4" />
                            </button>
                            <button
                                :class="[
                                    'px-3 py-1.5 rounded-full text-sm font-medium transition-colors',
                                    viewMode === 'split'
                                        ? 'bg-primary text-white'
                                        : 'text-muted-foreground hover:text-foreground',
                                ]"
                                @click="viewMode = 'split'"
                            >
                                <LayoutGrid class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex overflow-hidden">
            <!-- List View Only -->
            <div v-if="viewMode === 'list'" class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto px-6 lg:px-10 py-8">
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                    >
                        <ProjectCard
                            v-for="project in paginatedProjects"
                            :key="project.id"
                            :project="project"
                            @click="handleProjectClick(project)"
                        />
                    </div>

                    <!-- Pagination -->
                    <div v-if="totalPages > 1" class="flex justify-center items-center gap-2 mt-12">
                        <button
                            @click="goToPage(currentPage - 1)"
                            :disabled="currentPage === 1"
                            :class="[
                                'px-3 py-2 rounded-lg transition-colors',
                                currentPage === 1
                                    ? 'text-muted-foreground cursor-not-allowed'
                                    : 'text-foreground hover:bg-muted'
                            ]"
                        >
                            <ChevronLeft class="w-5 h-5" />
                        </button>

                        <button
                            v-for="page in totalPages"
                            :key="page"
                            @click="goToPage(page)"
                            :class="[
                                'px-4 py-2 rounded-lg transition-colors font-medium',
                                currentPage === page
                                    ? 'bg-primary text-white'
                                    : 'text-foreground hover:bg-muted'
                            ]"
                        >
                            {{ page }}
                        </button>

                        <button
                            @click="goToPage(currentPage + 1)"
                            :disabled="currentPage === totalPages"
                            :class="[
                                'px-3 py-2 rounded-lg transition-colors',
                                currentPage === totalPages
                                    ? 'text-muted-foreground cursor-not-allowed'
                                    : 'text-foreground hover:bg-muted'
                            ]"
                        >
                            <ChevronRight class="w-5 h-5" />
                        </button>
                    </div>
                </div>

                <!-- Footer -->
                <Footer />
            </div>

            <!-- Map View Only -->
            <div v-else-if="viewMode === 'map'" class="flex-1">
                <MapView
                    :projects="filteredProjects"
                    :selected-project-id="selectedProjectId"
                    @project-click="handleProjectClick"
                />
            </div>

            <!-- Split View -->
            <div v-else class="flex-1 flex flex-col">
                <!-- Split View Container -->
                <div class="flex-1 flex relative h-[calc(100vh-220px)]">
                    <!-- Left: Projects List -->
                    <div
                        class="w-full lg:w-1/2 overflow-y-auto border-r border-border h-full projects-list"
                    >
                        <div class="p-6 space-y-4">
                            <ProjectCard
                                v-for="project in paginatedProjects"
                                :key="project.id"
                                :project="project"
                                :is-selected="selectedProjectId === project.id"
                                @click="handleProjectClick(project)"
                                class="cursor-pointer"
                            />
                        </div>

                        <!-- Pagination -->
                        <div v-if="totalPages > 1" class="flex justify-center items-center gap-2 p-6">
                            <button
                                @click="goToPage(currentPage - 1)"
                                :disabled="currentPage === 1"
                                :class="[
                                    'px-3 py-2 rounded-lg transition-colors',
                                    currentPage === 1
                                        ? 'text-muted-foreground cursor-not-allowed'
                                        : 'text-foreground hover:bg-muted'
                                ]"
                            >
                                <ChevronLeft class="w-5 h-5" />
                            </button>

                            <button
                                v-for="page in totalPages"
                                :key="page"
                                @click="goToPage(page)"
                                :class="[
                                    'px-4 py-2 rounded-lg transition-colors font-medium',
                                    currentPage === page
                                        ? 'bg-primary text-white'
                                        : 'text-foreground hover:bg-muted'
                                ]"
                            >
                                {{ page }}
                            </button>

                            <button
                                @click="goToPage(currentPage + 1)"
                                :disabled="currentPage === totalPages"
                                :class="[
                                    'px-3 py-2 rounded-lg transition-colors',
                                    currentPage === totalPages
                                        ? 'text-muted-foreground cursor-not-allowed'
                                        : 'text-foreground hover:bg-muted'
                                ]"
                            >
                                <ChevronRight class="w-5 h-5" />
                            </button>
                        </div>
                    </div>

                    <!-- Right: Map -->
                    <div
                        class="hidden lg:block w-1/2 h-full sticky top-0"
                    >
                        <MapView
                            :projects="filteredProjects"
                            :selected-project-id="selectedProjectId"
                            @project-click="handleProjectClick"
                        />
                    </div>
                </div>

                <!-- Footer - Full Width -->
                <Footer />
            </div>
        </div>

        <!-- Filters Modal -->
        <Teleport to="body">
            <Transition name="modal">
                <div
                    v-if="showFiltersModal"
                    class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] flex items-center justify-center p-4"
                    @click="showFiltersModal = false"
                >
                    <div
                        class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden"
                        @click.stop
                    >
                        <!-- Modal Header -->
                        <div
                            class="flex items-center justify-between p-6 border-b border-border"
                        >
                            <h2 class="text-2xl font-semibold">
                                Filtres avancés
                            </h2>
                            <button
                                @click="showFiltersModal = false"
                                class="w-10 h-10 flex items-center justify-center hover:bg-muted rounded-full transition-colors"
                            >
                                <X class="w-5 h-5" />
                            </button>
                        </div>

                        <!-- Modal Content -->
                        <div
                            class="overflow-y-auto max-h-[calc(90vh-140px)] p-6"
                        >
                            <div class="space-y-8">
                                <!-- Price Range -->
                                <div>
                                    <h3
                                        class="font-semibold mb-4 flex items-center gap-2"
                                    >
                                        <DollarSign
                                            class="w-5 h-5 text-primary"
                                        />
                                        Fourchette de prix
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center mb-6">
                                            <span class="text-sm font-semibold">Prix</span>
                                            <span class="text-sm text-primary font-semibold">
                                                {{ formatPrice(filters.minPrice) }} - {{ formatPrice(filters.maxPrice) }}
                                            </span>
                                        </div>
                                        
                                        <!-- Dual Range Slider -->
                                        <div class="relative h-2 bg-muted rounded-lg mb-8">
                                            <!-- Track highlight -->
                                            <div 
                                                class="absolute h-full bg-primary rounded-lg"
                                                :style="{
                                                    left: `${(filters.minPrice / 3000000) * 100}%`,
                                                    right: `${100 - (filters.maxPrice / 3000000) * 100}%`
                                                }"
                                            ></div>
                                            
                                            <!-- Min slider -->
                                            <input
                                                v-model.number="filters.minPrice"
                                                type="range"
                                                min="0"
                                                max="3000000"
                                                step="50000"
                                                class="absolute w-full h-2 bg-transparent appearance-none cursor-pointer range-slider"
                                            />
                                            
                                            <!-- Max slider -->
                                            <input
                                                v-model.number="filters.maxPrice"
                                                type="range"
                                                min="0"
                                                max="3000000"
                                                step="50000"
                                                class="absolute w-full h-2 bg-transparent appearance-none cursor-pointer range-slider"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <!-- Area Range -->
                                <div>
                                    <h3
                                        class="font-semibold mb-4 flex items-center gap-2"
                                    >
                                        <Maximize2
                                            class="w-5 h-5 text-primary"
                                        />
                                        Surface (m²)
                                    </h3>
                                    <div class="flex items-center gap-4">
                                        <input
                                            v-model.number="filters.minArea"
                                            type="number"
                                            placeholder="Min m²"
                                            class="flex-1 px-4 py-3 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                        />
                                        <span class="text-muted-foreground"
                                            >-</span
                                        >
                                        <input
                                            v-model.number="filters.maxArea"
                                            type="number"
                                            placeholder="Max m²"
                                            class="flex-1 px-4 py-3 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                        />
                                    </div>
                                </div>

                                <!-- Rooms -->
                                <div>
                                    <h3
                                        class="font-semibold mb-4 flex items-center gap-2"
                                    >
                                        <BedDouble
                                            class="w-5 h-5 text-primary"
                                        />
                                        Nombre de pièces
                                    </h3>
                                    <div class="flex flex-wrap gap-2">
                                        <button
                                            v-for="room in [1, 2, 3, 4, 5, 6]"
                                            :key="room"
                                            @click="toggleRoom(room)"
                                            :class="[
                                                'px-6 py-3 border-2 rounded-lg font-medium transition-all',
                                                filters.rooms.includes(room)
                                                    ? 'border-primary bg-primary/10 text-primary'
                                                    : 'border-border hover:border-primary/50',
                                            ]"
                                        >
                                            {{ room
                                            }}{{ room === 6 ? "+" : "" }}
                                        </button>
                                    </div>
                                </div>

                                <!-- Bathrooms -->
                                <div>
                                    <h3
                                        class="font-semibold mb-4 flex items-center gap-2"
                                    >
                                        <Bath class="w-5 h-5 text-primary" />
                                        Salles de bain
                                    </h3>
                                    <div class="flex flex-wrap gap-2">
                                        <button
                                            v-for="bath in [1, 2, 3, 4]"
                                            :key="bath"
                                            @click="toggleBathroom(bath)"
                                            :class="[
                                                'px-6 py-3 border-2 rounded-lg font-medium transition-all',
                                                filters.bathrooms.includes(bath)
                                                    ? 'border-primary bg-primary/10 text-primary'
                                                    : 'border-border hover:border-primary/50',
                                            ]"
                                        >
                                            {{ bath
                                            }}{{ bath === 4 ? "+" : "" }}
                                        </button>
                                    </div>
                                </div>

                                <!-- Amenities -->
                                <div>
                                    <h3
                                        class="font-semibold mb-4 flex items-center gap-2"
                                    >
                                        <Star class="w-5 h-5 text-primary" />
                                        Caractéristiques
                                    </h3>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label
                                            v-for="amenity in amenities"
                                            :key="amenity.value"
                                            class="flex items-center gap-3 p-3 border border-border rounded-lg hover:border-primary/50 cursor-pointer transition-all"
                                        >
                                            <input
                                                v-model="filters.amenities"
                                                type="checkbox"
                                                :value="amenity.value"
                                                class="w-5 h-5 text-primary border-border rounded focus:ring-2 focus:ring-primary"
                                            />
                                            <span class="text-sm">{{
                                                amenity.label
                                            }}</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Promoter Name -->
                                <div>
                                    <h3
                                        class="font-semibold mb-4 flex items-center gap-2"
                                    >
                                        <Building2
                                            class="w-5 h-5 text-primary"
                                        />
                                        Nom du promoteur
                                    </h3>
                                    <input
                                        v-model="filters.promoterName"
                                        type="text"
                                        placeholder="Rechercher un promoteur..."
                                        class="w-full px-4 py-3 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                    />
                                </div>

                                <!-- Construction Status Slider -->
                                <div>
                                    <h3
                                        class="font-semibold mb-4 flex items-center gap-2"
                                    >
                                        <Calendar
                                            class="w-5 h-5 text-primary"
                                        />
                                        État de construction
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="relative h-2 bg-muted rounded-lg mb-8">
                                            <!-- Track highlight -->
                                            <div 
                                                class="absolute h-full bg-primary rounded-lg transition-all"
                                                :style="{
                                                    left: '0%',
                                                    right: `${100 - (filters.constructionStatus / 5) * 100}%`
                                                }"
                                            ></div>
                                            
                                            <input
                                                v-model.number="filters.constructionStatus"
                                                type="range"
                                                min="0"
                                                max="5"
                                                step="1"
                                                class="absolute w-full h-2 bg-transparent appearance-none cursor-pointer range-slider"
                                            />
                                        </div>
                                        
                                        <div class="flex items-center justify-between text-xs text-muted-foreground px-1">
                                            <span class="text-center">Peu<br/>importe</span>
                                            <span class="text-center">Prêt<br/>maintenant</span>
                                            <span class="text-center">3<br/>mois</span>
                                            <span class="text-center">6<br/>mois</span>
                                            <span class="text-center">9<br/>mois</span>
                                            <span class="text-center">1<br/>an</span>
                                        </div>
                                        
                                        <div class="text-center mt-4">
                                            <span
                                                class="inline-block px-4 py-2 bg-primary/10 text-primary rounded-full text-sm font-medium"
                                            >
                                                {{ constructionStatusLabel }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div
                            class="flex items-center justify-between p-6 border-t border-border bg-white"
                        >
                            <button
                                @click="resetFilters"
                                class="px-6 py-3 text-sm font-medium text-muted-foreground hover:text-foreground border border-border rounded-lg transition-colors"
                            >
                                Réinitialiser
                            </button>
                            <div class="flex gap-3">
                                <Button
                                    variant="outline"
                                    @click="showFiltersModal = false"
                                    class="px-6 py-3"
                                >
                                    Annuler
                                </Button>
                                <Button 
                                    @click="applyFilters"
                                    class="px-6 py-3"
                                >
                                    Appliquer les filtres ({{
                                        filteredProjects.length
                                    }})
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import {
    Menu,
    User,
    SlidersHorizontal,
    List,
    MapIcon,
    LayoutGrid,
    X,
    DollarSign,
    Maximize2,
    BedDouble,
    Bath,
    Star,
    Building2,
    Calendar,
    ChevronLeft,
    ChevronRight,
} from "lucide-vue-next";
import Button from "~/components/ui/Button.vue";
import Badge from "~/components/ui/Badge.vue";
import SearchBar from "~/components/SearchBar.vue";
import MapView from "~/components/MapView.vue";
import ProjectCard from "~/components/ProjectCard.vue";
import Footer from "~/components/Footer.vue";
import { useProjectsStore } from "~/stores/projects";

const route = useRoute();
const projectsStore = useProjectsStore();
const viewMode = ref<"list" | "map" | "split">("split");
const showFiltersModal = ref(false);
const selectedProjectId = ref<number | null>(null);

// Pagination
const currentPage = ref(1);
const itemsPerPage = 10;

// Fetch projects on page load
onMounted(async () => {
  if (projectsStore.projects.length === 0) {
    await projectsStore.fetchProjects()
  }
})

// Initialize filters from route query or defaults
const filters = ref({
    minPrice: Number(route.query?.minPrice) || 0,
    maxPrice: Number(route.query?.maxPrice) || 3000000,
    minArea: 0,
    maxArea: 1000,
    rooms: [] as number[],
    bathrooms: [] as number[],
    amenities: [] as string[],
    promoterName: "",
    constructionStatus: 0, // 0: any, 1: ready, 2: 3mo, 3: 6mo, 4: 9mo, 5: 1yr
    location: (route.query?.location as string) || "",
    type: (route.query?.type as string) || "",
});

const amenities = [
    { value: "piscine", label: "Piscine" },
    { value: "garage", label: "Garage" },
    { value: "parking", label: "Parking" },
    { value: "wifi", label: "Wi-Fi" },
    { value: "gym", label: "Salle de sport" },
    { value: "garden", label: "Jardin" },
    { value: "terrace", label: "Terrasse" },
    { value: "elevator", label: "Ascenseur" },
];

const projects = computed(() => projectsStore.allProjects);

const filteredProjects = computed(() => {
    let result = [...projects.value];

    // Price filter
    if (filters.value.minPrice > 0 || filters.value.maxPrice < 3000000) {
        result = result.filter((p) => {
            const price = parseInt(p.price.replace(/\s/g, ""));
            return (
                price >= filters.value.minPrice &&
                price <= filters.value.maxPrice
            );
        });
    }

    // Location filter
    if (filters.value.location) {
        result = result.filter((p) =>
            p.location
                .toLowerCase()
                .includes(filters.value.location.toLowerCase()),
        );
    }

    // Type filter
    if (filters.value.type) {
        result = result.filter((p) =>
            p.type.toLowerCase().includes(filters.value.type.toLowerCase()),
        );
    }

    // Promoter filter
    if (filters.value.promoterName) {
        result = result.filter((p) =>
            p.developer
                .toLowerCase()
                .includes(filters.value.promoterName.toLowerCase()),
        );
    }

    return result;
});

const totalPages = computed(() => 
    Math.ceil(filteredProjects.value.length / itemsPerPage)
);

const paginatedProjects = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredProjects.value.slice(start, end);
});

const activeFilters = computed(() => {
    const active: Array<{ key: string; label: string }> = [];

    if (filters.value.minPrice > 0 || filters.value.maxPrice < 3000000) {
        active.push({
            key: "price",
            label: `${filters.value.minPrice / 1000}K - ${filters.value.maxPrice / 1000}K TND`,
        });
    }

    if (filters.value.rooms.length > 0) {
        active.push({
            key: "rooms",
            label: `${filters.value.rooms.length} pièce(s)`,
        });
    }

    if (filters.value.bathrooms.length > 0) {
        active.push({
            key: "bathrooms",
            label: `${filters.value.bathrooms.length} SDB`,
        });
    }

    if (filters.value.amenities.length > 0) {
        active.push({
            key: "amenities",
            label: `${filters.value.amenities.length} caractéristique(s)`,
        });
    }

    if (filters.value.promoterName) {
        active.push({ key: "promoterName", label: filters.value.promoterName });
    }

    if (filters.value.constructionStatus > 0) {
        active.push({
            key: "constructionStatus",
            label: constructionStatusLabel.value,
        });
    }

    return active;
});

const activeFiltersCount = computed(() => activeFilters.value.length);

const constructionStatusLabel = computed(() => {
    const labels = [
        "Peu importe",
        "Prêt maintenant",
        "Dans 3 mois",
        "Dans 6 mois",
        "Dans 9 mois",
        "Dans 1 an",
    ];
    const status = filters.value?.constructionStatus ?? 0;
    return labels[status] || labels[0];
});

const handleSearch = (searchFilters: any) => {
    filters.value.location = searchFilters.location;
    filters.value.type = searchFilters.type;
    filters.value.minPrice = searchFilters.minPrice;
    filters.value.maxPrice = searchFilters.maxPrice;
};

const handleProjectClick = (project: any) => {
    selectedProjectId.value = project.id;
    navigateTo(`/project/${project.id}`);
};

const toggleRoom = (room: number) => {
    const index = filters.value.rooms.indexOf(room);
    if (index > -1) {
        filters.value.rooms.splice(index, 1);
    } else {
        filters.value.rooms.push(room);
    }
};

const toggleBathroom = (bath: number) => {
    const index = filters.value.bathrooms.indexOf(bath);
    if (index > -1) {
        filters.value.bathrooms.splice(index, 1);
    } else {
        filters.value.bathrooms.push(bath);
    }
};

const removeFilter = (key: string) => {
    switch (key) {
        case "price":
            filters.value.minPrice = 0;
            filters.value.maxPrice = 3000000;
            break;
        case "rooms":
            filters.value.rooms = [];
            break;
        case "bathrooms":
            filters.value.bathrooms = [];
            break;
        case "amenities":
            filters.value.amenities = [];
            break;
        case "promoterName":
            filters.value.promoterName = "";
            break;
        case "constructionStatus":
            filters.value.constructionStatus = 0;
            break;
    }
};

const resetFilters = () => {
    filters.value = {
        minPrice: 0,
        maxPrice: 3000000,
        minArea: 0,
        maxArea: 1000,
        rooms: [],
        bathrooms: [],
        amenities: [],
        promoterName: "",
        constructionStatus: 0,
        location: "",
        type: "",
    };
};

const formatPrice = (price: number): string => {
    if (price === 0) return "0 TND";
    if (price >= 1000000) {
        return `${(price / 1000000).toFixed(1)}M TND`;
    }
    if (price >= 1000) {
        return `${(price / 1000).toFixed(0)}K TND`;
    }
    return `${price} TND`;
};

const applyFilters = () => {
    showFiltersModal.value = false;
    currentPage.value = 1; // Reset to first page when filters change
};

const goToPage = (page: number) => {
    currentPage.value = page;
    // Scroll to top of projects list
    const projectsList = document.querySelector('.projects-list');
    if (projectsList) {
        projectsList.scrollTop = 0;
    }
};
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.modal-enter-active .bg-white,
.modal-leave-active .bg-white {
    transition: transform 0.3s ease;
}

.modal-enter-from .bg-white {
    transform: scale(0.95);
}

.modal-leave-to .bg-white {
    transform: scale(0.95);
}

/* Dual Range Slider */
.range-slider {
    pointer-events: all !important;
}

.range-slider::-webkit-slider-thumb {
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #ff385c;
    cursor: pointer;
    border: 3px solid white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    pointer-events: all;
    z-index: 10;
}

.range-slider::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #ff385c;
    cursor: pointer;
    border: 3px solid white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.range-slider::-webkit-slider-track {
    background: transparent;
    height: 8px;
}

.range-slider::-moz-range-track {
    background: transparent;
    height: 8px;
}
</style>
