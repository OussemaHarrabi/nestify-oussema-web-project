<template>
    <div class="w-full max-w-3xl mx-auto relative search-bar-container">
        <!-- Desktop Search Bar -->
        <div
            class="hidden md:block bg-white rounded-full border border-border shadow-lg hover:shadow-xl transition-all duration-300"
        >
            <div class="flex items-center divide-x divide-border">
                <!-- Location Dropdown -->
                <div
                    :class="[
                        'flex-1 px-4 lg:px-6 py-2 cursor-pointer rounded-l-full transition-colors relative',
                        activeTab === 'location'
                            ? 'bg-muted/50'
                            : 'hover:bg-muted/30',
                    ]"
                    @click="toggleDropdown('location')"
                >
                    <div class="flex items-center gap-2">
                        <MapPin
                            class="w-4 h-4 text-muted-foreground flex-shrink-0"
                        />
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-semibold text-foreground">
                                Où
                            </div>
                            <div class="text-sm text-muted-foreground truncate">
                                {{ selectedLocation || "Ville, région" }}
                            </div>
                        </div>
                        <ChevronDown
                            :class="[
                                'w-4 h-4 text-muted-foreground transition-transform',
                                activeTab === 'location' ? 'rotate-180' : '',
                            ]"
                        />
                    </div>

                    <!-- Location Dropdown Menu -->
                    <Transition name="dropdown">
                        <div
                            v-if="activeTab === 'location'"
                            class="absolute top-full left-0 mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-border z-[100] max-h-96 overflow-auto"
                            @click.stop
                        >
                            <div class="p-4">
                                <input
                                    v-model="locationSearch"
                                    type="text"
                                    placeholder="Rechercher un emplacement..."
                                    class="w-full px-4 py-2 border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                                />
                            </div>
                            <div class="py-2">
                                <div
                                    v-for="location in filteredLocations"
                                    :key="location"
                                    @click="selectLocation(location)"
                                    class="px-4 py-3 hover:bg-muted cursor-pointer transition-colors flex items-center gap-3"
                                >
                                    <MapPin class="w-4 h-4 text-primary" />
                                    <span class="text-sm">{{ location }}</span>
                                    <Check
                                        v-if="selectedLocation === location"
                                        class="w-4 h-4 text-primary ml-auto"
                                    />
                                </div>
                            </div>
                        </div>
                    </Transition>
                </div>

                <!-- Type Dropdown -->
                <div
                    :class="[
                        'flex-1 px-4 lg:px-6 py-2 cursor-pointer transition-colors relative',
                        activeTab === 'type'
                            ? 'bg-muted/50'
                            : 'hover:bg-muted/30',
                    ]"
                    @click="toggleDropdown('type')"
                >
                    <div class="flex items-center gap-2">
                        <Home
                            class="w-4 h-4 text-muted-foreground flex-shrink-0"
                        />
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-semibold text-foreground">
                                Type
                            </div>
                            <div class="text-sm text-muted-foreground truncate">
                                {{ selectedType || "Appartement, villa" }}
                            </div>
                        </div>
                        <ChevronDown
                            :class="[
                                'w-4 h-4 text-muted-foreground transition-transform',
                                activeTab === 'type' ? 'rotate-180' : '',
                            ]"
                        />
                    </div>

                    <!-- Type Dropdown Menu -->
                    <Transition name="dropdown">
                        <div
                            v-if="activeTab === 'type'"
                            class="absolute top-full left-0 mt-2 w-72 bg-white rounded-2xl shadow-2xl border border-border z-[100]"
                            @click.stop
                        >
                            <div class="py-2">
                                <div
                                    v-for="type in propertyTypes"
                                    :key="type.value"
                                    @click="selectType(type)"
                                    class="px-4 py-3 hover:bg-muted cursor-pointer transition-colors flex items-center gap-3"
                                >
                                    <component
                                        :is="type.icon"
                                        class="w-5 h-5 text-primary"
                                    />
                                    <span class="text-sm flex-1">{{
                                        type.label
                                    }}</span>
                                    <Check
                                        v-if="selectedType === type.label"
                                        class="w-4 h-4 text-primary"
                                    />
                                </div>
                            </div>
                        </div>
                    </Transition>
                </div>

                <!-- Budget Slider -->
                <div
                    :class="[
                        'flex-1 px-4 lg:px-6 py-2 cursor-pointer transition-colors relative',
                        activeTab === 'budget'
                            ? 'bg-muted/50'
                            : 'hover:bg-muted/30',
                    ]"
                    @click="toggleDropdown('budget')"
                >
                    <div class="flex items-center gap-2">
                        <DollarSign
                            class="w-4 h-4 text-muted-foreground flex-shrink-0"
                        />
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-semibold text-foreground">
                                Budget
                            </div>
                            <div class="text-sm text-muted-foreground truncate">
                                {{ budgetDisplay }}
                            </div>
                        </div>
                        <ChevronDown
                            :class="[
                                'w-4 h-4 text-muted-foreground transition-transform',
                                activeTab === 'budget' ? 'rotate-180' : '',
                            ]"
                        />
                    </div>

                    <!-- Budget Slider Menu -->
                    <Transition name="dropdown">
                        <div
                            v-if="activeTab === 'budget'"
                            class="absolute top-full right-0 mt-2 w-80 lg:w-96 bg-white rounded-2xl shadow-2xl border border-border z-[100] p-4 lg:p-6"
                            @click.stop
                        >
                            <div class="space-y-4 lg:space-y-6">
                                <div>
                                    <div class="flex justify-between items-center mb-4 lg:mb-6">
                                        <span class="text-sm font-semibold">Fourchette de prix</span>
                                        <span class="text-xs lg:text-sm text-primary font-semibold">
                                            {{ formatPrice(minPrice) }} - {{ formatPrice(maxPrice) }}
                                        </span>
                                    </div>
                                    
                                    <!-- Dual Range Slider -->
                                    <div class="relative h-2 bg-muted rounded-lg mb-8">
                                        <!-- Track highlight -->
                                        <div 
                                            class="absolute h-full bg-primary rounded-lg"
                                            :style="{
                                                left: `${(minPrice / 3000000) * 100}%`,
                                                right: `${100 - (maxPrice / 3000000) * 100}%`
                                            }"
                                        ></div>
                                        
                                        <!-- Min slider -->
                                        <input
                                            v-model.number="minPrice"
                                            type="range"
                                            min="0"
                                            max="3000000"
                                            step="50000"
                                            class="absolute w-full h-2 bg-transparent appearance-none cursor-pointer range-slider"
                                            style="pointer-events: none;"
                                            @input="handleMinChange"
                                        />
                                        
                                        <!-- Max slider -->
                                        <input
                                            v-model.number="maxPrice"
                                            type="range"
                                            min="0"
                                            max="3000000"
                                            step="50000"
                                            class="absolute w-full h-2 bg-transparent appearance-none cursor-pointer range-slider"
                                            style="pointer-events: none;"
                                            @input="handleMaxChange"
                                        />
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-border flex gap-2">
                                    <button
                                        @click="resetBudget"
                                        class="flex-1 px-3 lg:px-4 py-2 text-sm font-medium text-muted-foreground hover:text-foreground border border-border rounded-lg transition-colors"
                                    >
                                        Réinitialiser
                                    </button>
                                    <button
                                        @click="applyBudget"
                                        class="flex-1 px-3 lg:px-4 py-2 text-sm font-medium bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors"
                                    >
                                        Appliquer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </Transition>
                </div>

                <!-- Search Button -->
                <div class="p-1">
                    <button
                        @click="handleSearch"
                        class="bg-primary hover:bg-primary/90 rounded-full w-12 h-12 flex items-center justify-center shadow-md transition-all hover:scale-105"
                    >
                        <Search class="w-5 h-5 text-white" />
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Search Bar -->
        <div class="md:hidden bg-white rounded-2xl border border-border shadow-lg">
            <div class="p-4 space-y-3">
                <!-- Location Button -->
                <button
                    @click="toggleDropdown('location')"
                    :class="[
                        'w-full flex items-center justify-between p-4 rounded-xl border border-border transition-all',
                        activeTab === 'location' 
                            ? 'bg-primary/5 border-primary' 
                            : 'bg-white hover:bg-muted/30'
                    ]"
                >
                    <div class="flex items-center gap-3">
                        <MapPin class="w-5 h-5 text-primary flex-shrink-0" />
                        <div class="text-left">
                            <div class="text-xs font-semibold text-muted-foreground">Où</div>
                            <div class="text-sm font-medium text-foreground">
                                {{ selectedLocation || "Ville, région" }}
                            </div>
                        </div>
                    </div>
                    <ChevronDown 
                        :class="[
                            'w-5 h-5 text-muted-foreground transition-transform',
                            activeTab === 'location' ? 'rotate-180' : ''
                        ]"
                    />
                </button>

                <!-- Type Button -->
                <button
                    @click="toggleDropdown('type')"
                    :class="[
                        'w-full flex items-center justify-between p-4 rounded-xl border border-border transition-all',
                        activeTab === 'type' 
                            ? 'bg-primary/5 border-primary' 
                            : 'bg-white hover:bg-muted/30'
                    ]"
                >
                    <div class="flex items-center gap-3">
                        <Home class="w-5 h-5 text-primary flex-shrink-0" />
                        <div class="text-left">
                            <div class="text-xs font-semibold text-muted-foreground">Type</div>
                            <div class="text-sm font-medium text-foreground">
                                {{ selectedType || "Appartement, villa" }}
                            </div>
                        </div>
                    </div>
                    <ChevronDown 
                        :class="[
                            'w-5 h-5 text-muted-foreground transition-transform',
                            activeTab === 'type' ? 'rotate-180' : ''
                        ]"
                    />
                </button>

                <!-- Budget Button -->
                <button
                    @click="toggleDropdown('budget')"
                    :class="[
                        'w-full flex items-center justify-between p-4 rounded-xl border border-border transition-all',
                        activeTab === 'budget' 
                            ? 'bg-primary/5 border-primary' 
                            : 'bg-white hover:bg-muted/30'
                    ]"
                >
                    <div class="flex items-center gap-3">
                        <DollarSign class="w-5 h-5 text-primary flex-shrink-0" />
                        <div class="text-left">
                            <div class="text-xs font-semibold text-muted-foreground">Budget</div>
                            <div class="text-sm font-medium text-foreground">
                                {{ budgetDisplay }}
                            </div>
                        </div>
                    </div>
                    <ChevronDown 
                        :class="[
                            'w-5 h-5 text-muted-foreground transition-transform',
                            activeTab === 'budget' ? 'rotate-180' : ''
                        ]"
                    />
                </button>

                <!-- Search Button -->
                <button
                    @click="handleSearch"
                    class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-4 px-6 rounded-xl shadow-md transition-all hover:scale-[1.02] flex items-center justify-center gap-2"
                >
                    <Search class="w-5 h-5" />
                    <span>Rechercher</span>
                </button>
            </div>
        </div>

        <!-- Mobile Dropdown Overlays -->
        <Transition name="modal">
            <div
                v-if="activeTab && isMobile"
                class="fixed inset-0 z-[200] flex items-end md:hidden"
                @click="closeDropdowns"
            >
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

                <!-- Modal Content -->
                <div
                    class="relative w-full bg-white rounded-t-3xl max-h-[80vh] overflow-hidden"
                    @click.stop
                >
                    <!-- Location Dropdown -->
                    <div v-if="activeTab === 'location'" class="flex flex-col h-full">
                        <div class="sticky top-0 bg-white border-b border-border p-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold">Localisation</h3>
                                <button @click="closeDropdowns" class="p-2 hover:bg-muted rounded-full">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <input
                                v-model="locationSearch"
                                type="text"
                                placeholder="Rechercher un emplacement..."
                                class="w-full px-4 py-3 border border-border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                            />
                        </div>
                        <div class="flex-1 overflow-y-auto py-2">
                            <div
                                v-for="location in filteredLocations"
                                :key="location"
                                @click="selectLocation(location)"
                                class="px-4 py-4 hover:bg-muted active:bg-muted/80 cursor-pointer transition-colors flex items-center gap-3"
                            >
                                <MapPin class="w-5 h-5 text-primary flex-shrink-0" />
                                <span class="text-sm flex-1">{{ location }}</span>
                                <Check
                                    v-if="selectedLocation === location"
                                    class="w-5 h-5 text-primary flex-shrink-0"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Type Dropdown -->
                    <div v-if="activeTab === 'type'" class="flex flex-col h-full">
                        <div class="sticky top-0 bg-white border-b border-border p-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold">Type de bien</h3>
                                <button @click="closeDropdowns" class="p-2 hover:bg-muted rounded-full">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="flex-1 overflow-y-auto py-2">
                            <div
                                v-for="type in propertyTypes"
                                :key="type.value"
                                @click="selectType(type)"
                                class="px-4 py-4 hover:bg-muted active:bg-muted/80 cursor-pointer transition-colors flex items-center gap-3"
                            >
                                <component
                                    :is="type.icon"
                                    class="w-6 h-6 text-primary flex-shrink-0"
                                />
                                <span class="text-sm flex-1 font-medium">{{ type.label }}</span>
                                <Check
                                    v-if="selectedType === type.label"
                                    class="w-5 h-5 text-primary flex-shrink-0"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Budget Dropdown -->
                    <div v-if="activeTab === 'budget'" class="flex flex-col h-full">
                        <div class="sticky top-0 bg-white border-b border-border p-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold">Budget</h3>
                                <button @click="closeDropdowns" class="p-2 hover:bg-muted rounded-full">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="flex-1 overflow-y-auto p-6">
                            <div class="space-y-6">
                                <div>
                                    <div class="flex justify-between items-center mb-6">
                                        <span class="text-sm font-semibold">Fourchette de prix</span>
                                        <span class="text-sm text-primary font-semibold">
                                            {{ formatPrice(minPrice) }} - {{ formatPrice(maxPrice) }}
                                        </span>
                                    </div>
                                    
                                    <!-- Dual Range Slider -->
                                    <div class="relative h-2 bg-muted rounded-lg mb-12">
                                        <!-- Track highlight -->
                                        <div 
                                            class="absolute h-full bg-primary rounded-lg"
                                            :style="{
                                                left: `${(minPrice / 3000000) * 100}%`,
                                                right: `${100 - (maxPrice / 3000000) * 100}%`
                                            }"
                                        ></div>
                                        
                                        <!-- Min slider -->
                                        <input
                                            v-model.number="minPrice"
                                            type="range"
                                            min="0"
                                            max="3000000"
                                            step="50000"
                                            class="absolute w-full h-2 bg-transparent appearance-none cursor-pointer range-slider"
                                            style="pointer-events: none;"
                                            @input="handleMinChange"
                                        />
                                        
                                        <!-- Max slider -->
                                        <input
                                            v-model.number="maxPrice"
                                            type="range"
                                            min="0"
                                            max="3000000"
                                            step="50000"
                                            class="absolute w-full h-2 bg-transparent appearance-none cursor-pointer range-slider"
                                            style="pointer-events: none;"
                                            @input="handleMaxChange"
                                        />
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <button
                                        @click="resetBudget"
                                        class="flex-1 px-4 py-3 text-sm font-medium text-muted-foreground hover:text-foreground border border-border rounded-xl transition-colors"
                                    >
                                        Réinitialiser
                                    </button>
                                    <button
                                        @click="applyBudget"
                                        class="flex-1 px-4 py-3 text-sm font-medium bg-primary text-white rounded-xl hover:bg-primary/90 transition-colors"
                                    >
                                        Appliquer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- Desktop Backdrop -->
        <div
            v-if="activeTab && !isMobile"
            @click="closeDropdowns"
            class="fixed inset-0 z-[90]"
        ></div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted, onUnmounted } from "vue";
import {
    Search,
    MapPin,
    Home,
    DollarSign,
    ChevronDown,
    Check,
    Building2,
    HomeIcon,
} from "lucide-vue-next";

const activeTab = ref<"location" | "type" | "budget" | null>(null);
const locationSearch = ref("");
const selectedLocation = ref("");
const selectedType = ref("");
const minPrice = ref(0);
const maxPrice = ref(3000000);
const appliedMinPrice = ref(0);
const appliedMaxPrice = ref(3000000);
const isMobile = ref(false);

// Detect mobile screen size
const checkMobile = () => {
    if (typeof window !== 'undefined') {
        isMobile.value = window.innerWidth < 768; // md breakpoint
    }
};

onMounted(() => {
    checkMobile();
    window.addEventListener('resize', checkMobile);
});

onUnmounted(() => {
    if (typeof window !== 'undefined') {
        window.removeEventListener('resize', checkMobile);
    }
});

// Popular Tunisia locations
const locations = [
    "Sidi Bou Said",
    "Les Berges du Lac 1",
    "Les Berges du Lac 2",
    "Jardins de Carthage",
    "La Marsa",
    "Gammarth",
    "El Menzah 5",
    "El Menzah 6",
    "El Menzah 7",
    "El Menzah 8",
    "El Menzah 9",
    "Ennasr 1",
    "Ennasr 2",
    "Ain Zaghouan Nord",
    "Ariana Ville",
    "Cité Ennasr",
    "El Manar 1",
    "El Manar 2",
    "El Manar 3",
    "Soukra",
    "Chotrana",
    "Riadh al Andalous",
    "Les Jardins d'El Menzah",
    "Mutuelleville",
    "El Aouina",
    "Carthage",
    "Tunis Centre Ville",
    "Lafayette",
    "Montplaisir",
    "Bardo",
    "Manouba",
    "Ben Arous",
    "Hammamet",
    "Nabeul",
    "Sousse",
    "Monastir",
    "Sfax",
];

const propertyTypes = [
    { value: "apartment", label: "Appartements", icon: Building2 },
    { value: "villa", label: "Villas", icon: HomeIcon },
    { value: "house", label: "Maisons", icon: Home },
    { value: "penthouse", label: "Penthouses", icon: Building2 },
    { value: "duplex", label: "Duplex", icon: Building2 },
    { value: "studio", label: "Studios", icon: Building2 },
];

const filteredLocations = computed(() => {
    if (!locationSearch.value) return locations;
    return locations.filter((loc) =>
        loc.toLowerCase().includes(locationSearch.value.toLowerCase()),
    );
});

const budgetDisplay = computed(() => {
    if (appliedMinPrice.value === 0 && appliedMaxPrice.value === 3000000) {
        return "Min - Max";
    }
    if (appliedMinPrice.value === 0) {
        return `Jusqu'à ${formatPrice(appliedMaxPrice.value)}`;
    }
    if (appliedMaxPrice.value === 3000000) {
        return `À partir de ${formatPrice(appliedMinPrice.value)}`;
    }
    return `${formatPrice(appliedMinPrice.value)} - ${formatPrice(appliedMaxPrice.value)}`;
});

const toggleDropdown = (tab: "location" | "type" | "budget") => {
    const isOpening = activeTab.value !== tab;
    activeTab.value = activeTab.value === tab ? null : tab;
    
    // Scroll search bar into view when opening a dropdown
    if (isOpening && typeof window !== 'undefined') {
        setTimeout(() => {
            const searchBar = document.querySelector('.search-bar-container');
            if (searchBar) {
                searchBar.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center'
                });
            }
        }, 50);
    }
};

const closeDropdowns = () => {
    activeTab.value = null;
};

const selectLocation = (location: string) => {
    selectedLocation.value = location;
    activeTab.value = null;
    emit("update:location", location);
};

const selectType = (type: { value: string; label: string }) => {
    selectedType.value = type.label;
    activeTab.value = null;
    emit("update:type", type.value);
};

const applyBudget = () => {
    appliedMinPrice.value = minPrice.value;
    appliedMaxPrice.value = maxPrice.value;
    activeTab.value = null;
    emit("update:budget", { min: minPrice.value, max: maxPrice.value });
};

const resetBudget = () => {
    minPrice.value = 0;
    maxPrice.value = 3000000;
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

const handleSearch = () => {
    emit("search", {
        location: selectedLocation.value,
        type: selectedType.value,
        minPrice: appliedMinPrice.value,
        maxPrice: appliedMaxPrice.value,
    });
};

const handleMinChange = () => {
    if (minPrice.value > maxPrice.value) {
        minPrice.value = maxPrice.value;
    }
};

const handleMaxChange = () => {
    if (maxPrice.value < minPrice.value) {
        maxPrice.value = minPrice.value;
    }
};

const emit = defineEmits<{
    "update:location": [location: string];
    "update:type": [type: string];
    "update:budget": [budget: { min: number; max: number }];
    search: [
        filters: {
            location: string;
            type: string;
            minPrice: number;
            maxPrice: number;
        },
    ];
}>();

// Watch for min/max validation
watch([minPrice, maxPrice], () => {
    if (minPrice.value > maxPrice.value) {
        const temp = minPrice.value;
        minPrice.value = maxPrice.value;
        maxPrice.value = temp;
    }
});
</script>

<style scoped>
.dropdown-enter-active,
.dropdown-leave-active {
    transition: all 0.2s ease;
}

.dropdown-enter-from {
    opacity: 0;
    transform: translateY(-10px);
}

.dropdown-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

/* Modal Animation for Mobile */
.modal-enter-active {
    transition: all 0.3s ease-out;
}

.modal-leave-active {
    transition: all 0.25s ease-in;
}

.modal-enter-from {
    opacity: 0;
}

.modal-enter-from > div:last-child {
    transform: translateY(100%);
}

.modal-leave-to {
    opacity: 0;
}

.modal-leave-to > div:last-child {
    transform: translateY(100%);
}

/* Dual Range Slider Styling */
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
    position: relative;
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

/* Prevent body scroll when modal is open */
body:has(.modal-enter-active) {
    overflow: hidden;
}
</style>
