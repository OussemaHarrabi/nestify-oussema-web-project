<script setup lang="ts">
import { ref } from "vue";
import {
    Plus,
    Minus,
    Check,
    Home,
    Building2,
    Store,
    Briefcase,
} from "lucide-vue-next";

interface Props {
    projectName: string;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    back: [];
    complete: [];
    cancel: [];
}>();

const currentStep = ref(1);
const totalSteps = 3;

// Step 1: Details
const selectedType = ref("");
const builtSurface = ref(0);
const landSurface = ref(0);
const floorType = ref("");
const floor = ref("");
const orientation = ref("");
const rooms = ref(1);
const bedrooms = ref(1);
const bathrooms = ref(1);
const price = ref("");

// Step 2: Characteristics
const isFurnished = ref(false);
const selectedEquipment = ref<string[]>([]);
const description = ref("");

// Step 3: Photos
const uploadedImages = ref<string[]>([]);

const propertyTypes = [
    { id: "apartment", label: "Appartement", icon: Building2 },
    { id: "house", label: "Maison", icon: Home },
    { id: "villa", label: "Villa", icon: Home },
    { id: "commercial", label: "Local commercial", icon: Store },
    { id: "office", label: "Bureau", icon: Briefcase },
];

const floorTypes = [
    "Carrelage",
    "Marbre",
    "Parquet",
    "Béton ciré",
    "Pierre naturelle",
    "Autre",
];

const orientations = [
    "Nord",
    "Sud",
    "Est",
    "Ouest",
    "Nord-Est",
    "Nord-Ouest",
    "Sud-Est",
    "Sud-Ouest",
];

const equipment = [
    "Terrasse",
    "Balcon",
    "Jardin",
    "Piscine privée",
    "Parking",
    "Cave",
    "Garage",
    "Cuisine équipée",
    "Dressing",
    "Climatisation",
    "Ascenseur",
    "Chauffage central",
    "Double vitrage",
    "Cheminée",
    "Vue sur mer",
    "Vue panoramique",
    "Sécurité 24h/24",
];

const handleNext = () => {
    if (currentStep.value < totalSteps) currentStep.value++;
};

const handlePrevious = () => {
    if (currentStep.value > 1) currentStep.value--;
};

const handleSubmit = () => {
    const listingData = {
        type: selectedType.value,
        builtSurface: builtSurface.value,
        landSurface: landSurface.value,
        floorType: floorType.value,
        floor: floor.value,
        orientation: orientation.value,
        rooms: rooms.value,
        bedrooms: bedrooms.value,
        bathrooms: bathrooms.value,
        price: price.value,
        isFurnished: isFurnished.value,
        equipment: selectedEquipment.value,
        description: description.value,
        images: uploadedImages.value,
    };

    console.log("Listing created:", listingData);
    emit("complete");
};

const handleImageUpload = () => {
    const mockImages = [
        "https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&h=600&fit=crop",
        "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800&h=600&fit=crop",
        "https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800&h=600&fit=crop",
    ];
    uploadedImages.value = [
        ...uploadedImages.value,
        ...mockImages.slice(0, 3 - uploadedImages.value.length),
    ];
};

const removeImage = (index: number) => {
    uploadedImages.value = uploadedImages.value.filter((_, i) => i !== index);
};

const toggleEquipment = (item: string) => {
    const index = selectedEquipment.value.indexOf(item);
    if (index > -1) {
        selectedEquipment.value = selectedEquipment.value.filter(
            (e) => e !== item,
        );
    } else {
        selectedEquipment.value = [...selectedEquipment.value, item];
    }
};
</script>

<template>
    <div class="min-h-screen bg-white flex flex-col">
        <!-- Header -->
        <header class="border-b border-border">
            <div class="max-w-screen-lg mx-auto px-6 lg:px-20 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="bg-primary rounded-lg p-2">
                            <div
                                class="w-6 h-6 bg-white rounded-sm flex items-center justify-center"
                            >
                                <span class="text-primary text-xs font-bold"
                                    >N</span
                                >
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <button
                            class="text-foreground hover:bg-muted px-4 py-2 rounded-full transition-colors"
                        >
                            Questions?
                        </button>
                        <button
                            @click="emit('cancel')"
                            class="text-foreground hover:bg-muted px-4 py-2 rounded-full transition-colors"
                        >
                            Sauvegarder & quitter
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Progress Bar -->
        <div class="bg-gray-100">
            <div class="max-w-screen-lg mx-auto px-6 lg:px-20">
                <div class="h-1 bg-gray-200">
                    <div
                        class="h-full bg-foreground transition-all duration-300"
                        :style="{
                            width: `${(currentStep / totalSteps) * 100}%`,
                        }"
                    />
                </div>
            </div>
        </div>

        <!-- Step Indicator -->
        <div class="bg-white border-b border-border">
            <div class="max-w-screen-lg mx-auto px-6 lg:px-20 py-6">
                <div class="relative flex items-center justify-center">
                    <div
                        class="absolute top-5 left-1/2 -translate-x-1/2 h-[1px] w-[360px] bg-gray-200 -z-10"
                    >
                        <div
                            class="h-full bg-primary transition-all duration-500"
                            :style="{
                                width: `${((currentStep - 1) / 2) * 100}%`,
                            }"
                        />
                    </div>

                    <div class="flex items-center">
                        <div
                            v-for="step in [1, 2, 3]"
                            :key="step"
                            class="flex items-center"
                        >
                            <button
                                @click="currentStep = step"
                                class="flex flex-col items-center gap-2.5 px-12 transition-all cursor-pointer group"
                            >
                                <div
                                    :class="[
                                        'w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300',
                                        step <= currentStep
                                            ? 'bg-primary text-white'
                                            : 'bg-white text-gray-400 border border-gray-300 group-hover:border-gray-400',
                                    ]"
                                >
                                    <Check
                                        v-if="step < currentStep"
                                        class="w-5 h-5"
                                        stroke-width="2.5"
                                    />
                                    <span v-else class="text-sm">{{
                                        step
                                    }}</span>
                                </div>

                                <span
                                    :class="[
                                        'text-xs whitespace-nowrap transition-colors',
                                        step === currentStep
                                            ? 'text-gray-800'
                                            : step < currentStep
                                              ? 'text-gray-500'
                                              : 'text-gray-400',
                                    ]"
                                >
                                    {{
                                        [
                                            "Détails",
                                            "Caractéristiques",
                                            "Photos",
                                        ][step - 1]
                                    }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 max-w-screen-md mx-auto px-6 py-12 w-full">
            <!-- Step 1: Details -->
            <div v-if="currentStep === 1" class="space-y-8">
                <div>
                    <h1 class="text-3xl lg:text-4xl mb-3">Détails du bien</h1>
                    <p class="text-muted-foreground text-lg">
                        Renseignez les informations principales du lot
                    </p>
                </div>

                <div class="pt-6">
                    <label class="block text-foreground mb-4"
                        >Type de bien</label
                    >
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <button
                            v-for="type in propertyTypes"
                            :key="type.id"
                            type="button"
                            @click="selectedType = type.id"
                            :class="[
                                'p-4 rounded-xl border-2 transition-all text-left',
                                selectedType === type.id
                                    ? 'border-primary bg-primary/5'
                                    : 'border-border hover:border-primary/40',
                            ]"
                        >
                            <component
                                :is="type.icon"
                                :class="[
                                    'w-6 h-6 mb-2',
                                    selectedType === type.id
                                        ? 'text-primary'
                                        : 'text-foreground',
                                ]"
                            />
                            <p
                                :class="[
                                    'text-sm',
                                    selectedType === type.id
                                        ? 'text-primary font-medium'
                                        : 'text-foreground',
                                ]"
                            >
                                {{ type.label }}
                            </p>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-foreground mb-3"
                            >Surface construite (m²)</label
                        >
                        <input
                            v-model.number="builtSurface"
                            type="number"
                            placeholder="120"
                            class="w-full text-lg py-3 px-4 border-2 border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                        />
                    </div>
                    <div>
                        <label class="block text-foreground mb-3"
                            >Surface de la parcelle (m²)</label
                        >
                        <input
                            v-model.number="landSurface"
                            type="number"
                            placeholder="150"
                            class="w-full text-lg py-3 px-4 border-2 border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                        />
                        <p class="text-sm text-muted-foreground mt-2">
                            Optionnel
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-foreground mb-3"
                            >Type de sol</label
                        >
                        <div class="relative">
                            <select
                                v-model="floorType"
                                class="w-full text-lg py-3 px-4 pr-10 border-2 border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors appearance-none bg-white cursor-pointer"
                            >
                                <option value="">Sélectionner</option>
                                <option
                                    v-for="type in floorTypes"
                                    :key="type"
                                    :value="type"
                                >
                                    {{ type }}
                                </option>
                            </select>
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none"
                            >
                                <svg
                                    class="w-5 h-5 text-muted-foreground"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-foreground mb-3"
                            >Étage du bien</label
                        >
                        <div class="relative">
                            <select
                                v-model="floor"
                                class="w-full text-lg py-3 px-4 pr-10 border-2 border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors appearance-none bg-white cursor-pointer"
                            >
                                <option value="">Sélectionner</option>
                                <option value="RDC">Rez-de-chaussée</option>
                                <option value="1">1er étage</option>
                                <option value="2">2ème étage</option>
                                <option value="3">3ème étage</option>
                                <option value="4">4ème étage</option>
                                <option value="5">5ème étage</option>
                                <option value="6+">6ème étage et +</option>
                                <option value="dernier">Dernier étage</option>
                            </select>
                            <div
                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none"
                            >
                                <svg
                                    class="w-5 h-5 text-muted-foreground"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-foreground mb-3"
                        >Orientation</label
                    >
                    <div class="relative">
                        <select
                            v-model="orientation"
                            class="w-full text-lg py-3 px-4 pr-10 border-2 border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors appearance-none bg-white cursor-pointer"
                        >
                            <option value="">Sélectionner</option>
                            <option
                                v-for="orient in orientations"
                                :key="orient"
                                :value="orient"
                            >
                                {{ orient }}
                            </option>
                        </select>
                        <div
                            class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none"
                        >
                            <svg
                                class="w-5 h-5 text-muted-foreground"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="border-2 border-border rounded-xl p-4">
                    <div
                        class="flex items-center justify-between py-6 border-b border-border"
                    >
                        <span class="text-foreground">Pièces</span>
                        <div class="flex items-center gap-4">
                            <button
                                type="button"
                                @click="rooms = Math.max(1, rooms - 1)"
                                class="w-8 h-8 rounded-full border-2 border-border hover:border-primary disabled:opacity-25 transition-colors flex items-center justify-center"
                            >
                                <Minus class="w-4 h-4" />
                            </button>
                            <span class="w-8 text-center text-foreground">{{
                                rooms
                            }}</span>
                            <button
                                type="button"
                                @click="rooms++"
                                class="w-8 h-8 rounded-full border-2 border-border hover:border-primary transition-colors flex items-center justify-center"
                            >
                                <Plus class="w-4 h-4" />
                            </button>
                        </div>
                    </div>

                    <div
                        class="flex items-center justify-between py-6 border-b border-border"
                    >
                        <span class="text-foreground">Chambres</span>
                        <div class="flex items-center gap-4">
                            <button
                                type="button"
                                @click="bedrooms = Math.max(0, bedrooms - 1)"
                                class="w-8 h-8 rounded-full border-2 border-border hover:border-primary transition-colors flex items-center justify-center"
                            >
                                <Minus class="w-4 h-4" />
                            </button>
                            <span class="w-8 text-center text-foreground">{{
                                bedrooms
                            }}</span>
                            <button
                                type="button"
                                @click="bedrooms++"
                                class="w-8 h-8 rounded-full border-2 border-border hover:border-primary transition-colors flex items-center justify-center"
                            >
                                <Plus class="w-4 h-4" />
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between py-6">
                        <span class="text-foreground">Salles de bain</span>
                        <div class="flex items-center gap-4">
                            <button
                                type="button"
                                @click="bathrooms = Math.max(1, bathrooms - 1)"
                                class="w-8 h-8 rounded-full border-2 border-border hover:border-primary transition-colors flex items-center justify-center"
                            >
                                <Minus class="w-4 h-4" />
                            </button>
                            <span class="w-8 text-center text-foreground">{{
                                bathrooms
                            }}</span>
                            <button
                                type="button"
                                @click="bathrooms++"
                                class="w-8 h-8 rounded-full border-2 border-border hover:border-primary transition-colors flex items-center justify-center"
                            >
                                <Plus class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-foreground mb-3">Prix (TND)</label>
                    <input
                        v-model="price"
                        type="text"
                        placeholder="350 000"
                        class="w-full text-lg py-3 px-4 border-2 border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                    />
                </div>
            </div>

            <!-- Step 2: Characteristics -->
            <div v-if="currentStep === 2" class="space-y-8">
                <div>
                    <h1 class="text-3xl lg:text-4xl mb-3">
                        Caractéristiques du bien
                    </h1>
                    <p class="text-muted-foreground text-lg">
                        Ajoutez les équipements et options disponibles
                    </p>
                </div>

                <div class="pt-6">
                    <div
                        class="flex items-center gap-4 p-4 bg-muted/30 rounded-xl cursor-pointer"
                        @click="isFurnished = !isFurnished"
                    >
                        <input
                            type="checkbox"
                            v-model="isFurnished"
                            class="w-5 h-5 rounded border-2 border-border text-primary focus:ring-2 focus:ring-primary accent-primary"
                        />
                        <label class="flex-1 cursor-pointer">
                            <span class="font-medium">Bien meublé</span>
                            <p class="text-sm text-muted-foreground">
                                Cochez si le bien est livré avec meubles
                            </p>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-foreground mb-4"
                        >Équipements et options</label
                    >
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <label
                            v-for="item in equipment"
                            :key="item"
                            :class="[
                                'py-3 px-4 rounded-xl border-2 transition-all text-sm cursor-pointer',
                                selectedEquipment.includes(item)
                                    ? 'border-primary bg-primary/5'
                                    : 'border-border hover:border-primary/40',
                            ]"
                        >
                            <input
                                type="checkbox"
                                :value="item"
                                :checked="selectedEquipment.includes(item)"
                                @change="toggleEquipment(item)"
                                class="mr-2 rounded border-2 border-border text-primary focus:ring-2 focus:ring-primary accent-primary"
                            />
                            <span
                                :class="
                                    selectedEquipment.includes(item)
                                        ? 'text-primary font-medium'
                                        : 'text-foreground'
                                "
                                >{{ item }}</span
                            >
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-foreground mb-3"
                        >Description</label
                    >
                    <textarea
                        v-model="description"
                        placeholder="Décrivez le bien en détail : avantages, finitions, particularités..."
                        rows="6"
                        class="w-full text-base py-3 px-4 border-2 border-border rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                    ></textarea>
                    <div class="flex items-center justify-between mt-2">
                        <p class="text-sm text-muted-foreground">
                            Une description détaillée attire plus d'acheteurs
                        </p>
                        <span class="text-sm text-muted-foreground"
                            >{{ description.length }} / 1000</span
                        >
                    </div>
                </div>
            </div>

            <!-- Step 3: Photos -->
            <div v-if="currentStep === 3" class="space-y-8">
                <div>
                    <h1 class="text-3xl lg:text-4xl mb-3">
                        Ajoutez des photos
                    </h1>
                    <p class="text-muted-foreground text-lg">
                        Vous aurez besoin de 3 photos minimum. Vous pourrez en
                        ajouter plus tard.
                    </p>
                </div>

                <div v-if="uploadedImages.length === 0" class="pt-6">
                    <button
                        type="button"
                        @click="handleImageUpload"
                        class="w-full max-w-xl mx-auto aspect-[4/3] border-2 border-dashed border-border hover:border-primary rounded-2xl flex flex-col items-center justify-center transition-colors group"
                    >
                        <div class="w-16 h-16 mb-4">
                            <Plus
                                class="w-full h-full text-muted-foreground group-hover:text-primary transition-colors"
                                stroke-width="1.5"
                            />
                        </div>
                        <p class="text-foreground text-lg">
                            Télécharger à partir de votre appareil
                        </p>
                    </button>
                </div>

                <div v-else class="space-y-4 pt-6">
                    <!-- Main image -->
                    <div
                        v-if="uploadedImages[0]"
                        class="relative aspect-[4/3] rounded-2xl overflow-hidden border border-border"
                    >
                        <img
                            :src="uploadedImages[0]"
                            alt="Photo principale"
                            class="w-full h-full object-cover"
                        />
                        <div class="absolute top-4 left-4">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white text-foreground shadow-sm"
                            >
                                Photo de couverture
                            </span>
                        </div>
                        <button
                            type="button"
                            @click="removeImage(0)"
                            class="absolute top-4 right-4 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-100 transition-colors"
                        >
                            <span class="text-xl leading-none">&times;</span>
                        </button>
                    </div>

                    <!-- Grid of images -->
                    <div class="grid grid-cols-2 gap-4">
                        <div
                            v-for="(img, index) in uploadedImages.slice(1)"
                            :key="index + 1"
                            class="relative aspect-[4/3] rounded-xl overflow-hidden border border-border bg-gray-50"
                        >
                            <img
                                :src="img"
                                :alt="`Photo ${index + 2}`"
                                class="w-full h-full object-cover"
                            />
                            <button
                                type="button"
                                @click="removeImage(index + 1)"
                                class="absolute top-2 right-2 w-7 h-7 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-gray-100 transition-colors"
                            >
                                <span class="text-lg leading-none"
                                    >&times;</span
                                >
                            </button>
                        </div>

                        <!-- Empty slots -->
                        <div
                            v-for="index in Math.max(
                                0,
                                5 - uploadedImages.length,
                            )"
                            :key="`empty-${index}`"
                            class="aspect-[4/3] rounded-xl border-2 border-dashed border-border bg-gray-50 flex items-center justify-center"
                        >
                            <Plus
                                class="w-12 h-12 text-muted-foreground"
                                stroke-width="1"
                            />
                        </div>

                        <!-- Add more button -->
                        <button
                            v-if="
                                uploadedImages.length >= 3 &&
                                uploadedImages.length < 10
                            "
                            type="button"
                            @click="handleImageUpload"
                            class="aspect-[4/3] rounded-xl border-2 border-dashed border-border hover:border-foreground bg-white flex flex-col items-center justify-center transition-colors group"
                        >
                            <Plus
                                class="w-8 h-8 text-muted-foreground group-hover:text-foreground transition-colors mb-2"
                            />
                            <span
                                class="text-sm text-muted-foreground group-hover:text-foreground transition-colors"
                                >Ajouter plus</span
                            >
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="border-t border-border bg-white">
            <div class="max-w-screen-lg mx-auto px-6 lg:px-20 py-6">
                <div class="flex items-center justify-between">
                    <button
                        type="button"
                        @click="
                            currentStep === 1 ? emit('cancel') : handlePrevious
                        "
                        class="text-foreground hover:bg-muted px-4 py-2 rounded-lg font-medium underline transition-colors"
                    >
                        {{ currentStep === 1 ? "Annuler" : "Retour" }}
                    </button>

                    <button
                        v-if="currentStep < totalSteps"
                        @click="handleNext"
                        class="bg-foreground hover:bg-foreground/90 text-white px-8 py-3 rounded-lg transition-colors"
                    >
                        Suivant
                    </button>
                    <button
                        v-else
                        @click="handleSubmit"
                        class="bg-foreground hover:bg-foreground/90 text-white px-8 py-3 rounded-lg transition-colors"
                    >
                        Confirmer le lot
                    </button>
                </div>
            </div>
        </footer>
    </div>
</template>
