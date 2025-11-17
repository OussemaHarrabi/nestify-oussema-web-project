<script setup lang="ts">
import { ref, computed } from "vue";
import {
    ArrowLeft,
    Plus,
    Minus,
    Check,
    Home,
    Building2,
    Store,
    Briefcase,
    Upload,
    X,
} from "lucide-vue-next";
import Button from "~/components/ui/Button.vue";
import Badge from "~/components/ui/Badge.vue";

const route = useRoute();
const projectId = computed(() => {
    const id = route?.params?.projectId
    return id ? parseInt(id as string) : 0
});

const currentStep = ref(1);
const totalSteps = 3;

// Mock project data (in real app, fetch from API)
const mockProject = {
    id: projectId.value,
    name: "Les Jardins de Carthage",
    location: "Carthage, Tunis",
};

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

const handleCancel = () => {
    window.location.href = `/dashboard/projects/${projectId.value}/manage`;
};

const handleComplete = () => {
    // In real app, submit data to API
    console.log("Creating listing for project:", projectId.value);
    window.location.href = `/dashboard/projects/${projectId.value}/manage`;
};

const toggleEquipment = (item: string) => {
    const index = selectedEquipment.value.indexOf(item);
    if (index > -1) {
        selectedEquipment.value.splice(index, 1);
    } else {
        selectedEquipment.value.push(item);
    }
};

const handleImageUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const files = target.files;
    if (files) {
        // Mock image upload (in real app, upload to server)
        Array.from(files).forEach((file) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                if (e.target?.result) {
                    uploadedImages.value.push(e.target.result as string);
                }
            };
            reader.readAsDataURL(file);
        });
    }
};

const removeImage = (index: number) => {
    uploadedImages.value.splice(index, 1);
};

const isStepValid = computed(() => {
    if (currentStep.value === 1) {
        return selectedType.value && builtSurface.value > 0 && price.value;
    }
    if (currentStep.value === 2) {
        return description.value.length > 20;
    }
    if (currentStep.value === 3) {
        return uploadedImages.value.length >= 3;
    }
    return false;
});
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white border-b border-border">
            <div class="max-w-[1760px] mx-auto px-6 lg:px-20 py-6">
                <button
                    @click="handleCancel"
                    class="flex items-center gap-2 text-muted-foreground hover:text-foreground transition-colors mb-4"
                >
                    <ArrowLeft class="w-5 h-5" />
                    <span class="font-medium">Retour au projet</span>
                </button>

                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-foreground mb-2">
                        Ajouter un bien
                    </h1>
                    <p class="text-muted-foreground">
                        Projet:
                        <span class="font-medium text-foreground">{{
                            mockProject.name
                        }}</span>
                    </p>
                </div>

                <!-- Progress Steps -->
                <div class="flex items-center gap-4">
                    <div
                        v-for="step in totalSteps"
                        :key="step"
                        class="flex items-center gap-4 flex-1"
                    >
                        <div class="flex items-center gap-3 flex-1">
                            <div
                                :class="[
                                    'w-10 h-10 rounded-full flex items-center justify-center font-semibold transition-all',
                                    step < currentStep
                                        ? 'bg-primary text-white'
                                        : step === currentStep
                                          ? 'bg-primary text-white ring-4 ring-primary/20'
                                          : 'bg-gray-200 text-muted-foreground',
                                ]"
                            >
                                <Check
                                    v-if="step < currentStep"
                                    class="w-5 h-5"
                                />
                                <span v-else>{{ step }}</span>
                            </div>
                            <div class="hidden md:block">
                                <p
                                    :class="[
                                        'text-sm font-medium',
                                        step <= currentStep
                                            ? 'text-foreground'
                                            : 'text-muted-foreground',
                                    ]"
                                >
                                    {{
                                        step === 1
                                            ? "Détails du bien"
                                            : step === 2
                                              ? "Caractéristiques"
                                              : "Photos"
                                    }}
                                </p>
                            </div>
                        </div>
                        <div
                            v-if="step < totalSteps"
                            :class="[
                                'h-1 flex-1 rounded-full transition-all',
                                step < currentStep
                                    ? 'bg-primary'
                                    : 'bg-gray-200',
                            ]"
                        ></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-4xl mx-auto px-6 lg:px-20 py-12">
            <!-- Step 1: Details -->
            <div v-if="currentStep === 1" class="space-y-8">
                <div class="bg-white rounded-xl border-2 border-border p-8">
                    <h2 class="text-2xl font-bold text-foreground mb-6">
                        Détails du bien
                    </h2>

                    <!-- Property Type -->
                    <div class="mb-8">
                        <label
                            class="block text-sm font-medium text-foreground mb-3"
                        >
                            Type de bien *
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                            <button
                                v-for="type in propertyTypes"
                                :key="type.id"
                                @click="selectedType = type.id"
                                :class="[
                                    'p-4 rounded-xl border-2 transition-all flex flex-col items-center gap-2',
                                    selectedType === type.id
                                        ? 'border-primary bg-primary/5'
                                        : 'border-border hover:border-primary/50',
                                ]"
                            >
                                <component
                                    :is="type.icon"
                                    :class="[
                                        'w-8 h-8',
                                        selectedType === type.id
                                            ? 'text-primary'
                                            : 'text-muted-foreground',
                                    ]"
                                />
                                <span
                                    :class="[
                                        'text-sm font-medium',
                                        selectedType === type.id
                                            ? 'text-primary'
                                            : 'text-foreground',
                                    ]"
                                >
                                    {{ type.label }}
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Surface -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label
                                class="block text-sm font-medium text-foreground mb-2"
                            >
                                Surface bâtie (m²) *
                            </label>
                            <input
                                v-model.number="builtSurface"
                                type="number"
                                min="0"
                                placeholder="Ex: 120"
                                class="w-full px-4 py-3 border-2 border-border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-foreground mb-2"
                            >
                                Surface terrain (m²)
                            </label>
                            <input
                                v-model.number="landSurface"
                                type="number"
                                min="0"
                                placeholder="Ex: 200"
                                class="w-full px-4 py-3 border-2 border-border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            />
                        </div>
                    </div>

                    <!-- Floor & Orientation -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label
                                class="block text-sm font-medium text-foreground mb-2"
                            >
                                Type de sol
                            </label>
                            <div class="relative">
                                <select
                                    v-model="floorType"
                                    class="w-full px-4 py-3 border-2 border-border rounded-xl appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent select-with-arrow"
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
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-foreground mb-2"
                            >
                                Étage
                            </label>
                            <input
                                v-model="floor"
                                type="text"
                                placeholder="Ex: 3ème"
                                class="w-full px-4 py-3 border-2 border-border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-foreground mb-2"
                            >
                                Orientation
                            </label>
                            <div class="relative">
                                <select
                                    v-model="orientation"
                                    class="w-full px-4 py-3 border-2 border-border rounded-xl appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent select-with-arrow"
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
                            </div>
                        </div>
                    </div>

                    <!-- Rooms Configuration -->
                    <div class="mb-6">
                        <label
                            class="block text-sm font-medium text-foreground mb-3"
                        >
                            Configuration des pièces
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-sm text-muted-foreground mb-3">
                                    Pièces
                                </p>
                                <div class="flex items-center gap-4">
                                    <button
                                        @click="rooms = Math.max(1, rooms - 1)"
                                        class="w-10 h-10 rounded-full border-2 border-border hover:border-primary hover:bg-primary/5 flex items-center justify-center transition-all"
                                    >
                                        <Minus class="w-4 h-4" />
                                    </button>
                                    <span
                                        class="text-2xl font-bold text-foreground flex-1 text-center"
                                    >
                                        {{ rooms }}
                                    </span>
                                    <button
                                        @click="rooms++"
                                        class="w-10 h-10 rounded-full border-2 border-border hover:border-primary hover:bg-primary/5 flex items-center justify-center transition-all"
                                    >
                                        <Plus class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-sm text-muted-foreground mb-3">
                                    Chambres
                                </p>
                                <div class="flex items-center gap-4">
                                    <button
                                        @click="
                                            bedrooms = Math.max(0, bedrooms - 1)
                                        "
                                        class="w-10 h-10 rounded-full border-2 border-border hover:border-primary hover:bg-primary/5 flex items-center justify-center transition-all"
                                    >
                                        <Minus class="w-4 h-4" />
                                    </button>
                                    <span
                                        class="text-2xl font-bold text-foreground flex-1 text-center"
                                    >
                                        {{ bedrooms }}
                                    </span>
                                    <button
                                        @click="bedrooms++"
                                        class="w-10 h-10 rounded-full border-2 border-border hover:border-primary hover:bg-primary/5 flex items-center justify-center transition-all"
                                    >
                                        <Plus class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-sm text-muted-foreground mb-3">
                                    Salles de bain
                                </p>
                                <div class="flex items-center gap-4">
                                    <button
                                        @click="
                                            bathrooms = Math.max(
                                                0,
                                                bathrooms - 1,
                                            )
                                        "
                                        class="w-10 h-10 rounded-full border-2 border-border hover:border-primary hover:bg-primary/5 flex items-center justify-center transition-all"
                                    >
                                        <Minus class="w-4 h-4" />
                                    </button>
                                    <span
                                        class="text-2xl font-bold text-foreground flex-1 text-center"
                                    >
                                        {{ bathrooms }}
                                    </span>
                                    <button
                                        @click="bathrooms++"
                                        class="w-10 h-10 rounded-full border-2 border-border hover:border-primary hover:bg-primary/5 flex items-center justify-center transition-all"
                                    >
                                        <Plus class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Price -->
                    <div>
                        <label
                            class="block text-sm font-medium text-foreground mb-2"
                        >
                            Prix (TND) *
                        </label>
                        <input
                            v-model="price"
                            type="text"
                            placeholder="Ex: 280 000"
                            class="w-full px-4 py-3 border-2 border-border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        />
                    </div>
                </div>
            </div>

            <!-- Step 2: Characteristics -->
            <div v-if="currentStep === 2" class="space-y-8">
                <div class="bg-white rounded-xl border-2 border-border p-8">
                    <h2 class="text-2xl font-bold text-foreground mb-6">
                        Caractéristiques
                    </h2>

                    <!-- Furnished -->
                    <div class="mb-8">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input
                                v-model="isFurnished"
                                type="checkbox"
                                class="w-5 h-5 rounded border-2 border-border text-primary focus:ring-2 focus:ring-primary"
                            />
                            <span class="text-sm font-medium text-foreground">
                                Bien meublé
                            </span>
                        </label>
                    </div>

                    <!-- Equipment -->
                    <div class="mb-8">
                        <label
                            class="block text-sm font-medium text-foreground mb-3"
                        >
                            Équipements et caractéristiques
                        </label>
                        <div
                            class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3"
                        >
                            <button
                                v-for="item in equipment"
                                :key="item"
                                @click="toggleEquipment(item)"
                                :class="[
                                    'px-4 py-2 rounded-xl border-2 text-sm font-medium transition-all text-left',
                                    selectedEquipment.includes(item)
                                        ? 'border-primary bg-primary/5 text-primary'
                                        : 'border-border text-foreground hover:border-primary/50',
                                ]"
                            >
                                {{ item }}
                            </button>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label
                            class="block text-sm font-medium text-foreground mb-2"
                        >
                            Description *
                        </label>
                        <textarea
                            v-model="description"
                            rows="6"
                            placeholder="Décrivez votre bien en détail..."
                            class="w-full px-4 py-3 border-2 border-border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                        ></textarea>
                        <p class="text-sm text-muted-foreground mt-2">
                            {{ description.length }} caractères (minimum 20)
                        </p>
                    </div>
                </div>
            </div>

            <!-- Step 3: Photos -->
            <div v-if="currentStep === 3" class="space-y-8">
                <div class="bg-white rounded-xl border-2 border-border p-8">
                    <h2 class="text-2xl font-bold text-foreground mb-2">
                        Photos
                    </h2>
                    <p class="text-muted-foreground mb-6">
                        Ajoutez au moins 3 photos de qualité (minimum
                        recommandé: 5)
                    </p>

                    <!-- Upload Area -->
                    <label
                        class="block border-2 border-dashed border-border rounded-xl p-12 text-center cursor-pointer hover:border-primary transition-all mb-6"
                    >
                        <input
                            type="file"
                            multiple
                            accept="image/*"
                            @change="handleImageUpload"
                            class="hidden"
                        />
                        <Upload
                            class="w-12 h-12 text-muted-foreground mx-auto mb-4"
                        />
                        <p class="text-lg font-semibold text-foreground mb-2">
                            Cliquez pour télécharger des photos
                        </p>
                        <p class="text-sm text-muted-foreground">
                            PNG, JPG jusqu'à 10MB chacune
                        </p>
                    </label>

                    <!-- Uploaded Images Grid -->
                    <div v-if="uploadedImages.length > 0">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div
                                v-for="(image, index) in uploadedImages"
                                :key="index"
                                class="relative aspect-video rounded-xl overflow-hidden border-2 border-border group"
                            >
                                <img
                                    :src="image"
                                    :alt="`Photo ${index + 1}`"
                                    class="w-full h-full object-cover"
                                />
                                <button
                                    @click="removeImage(index)"
                                    class="absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                                >
                                    <X class="w-4 h-4" />
                                </button>
                                <div
                                    v-if="index === 0"
                                    class="absolute bottom-2 left-2 px-2 py-1 bg-primary text-white text-xs font-medium rounded"
                                >
                                    Photo principale
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-muted-foreground mt-4">
                            {{ uploadedImages.length }} photo{{
                                uploadedImages.length > 1 ? "s" : ""
                            }}
                            téléchargée{{
                                uploadedImages.length > 1 ? "s" : ""
                            }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between mt-8">
                <Button
                    v-if="currentStep > 1"
                    variant="outline"
                    @click="handlePrevious"
                >
                    Précédent
                </Button>
                <div v-else></div>

                <div class="flex items-center gap-3">
                    <Button variant="outline" @click="handleCancel">
                        Annuler
                    </Button>
                    <Button
                        v-if="currentStep < totalSteps"
                        variant="default"
                        @click="handleNext"
                        :disabled="!isStepValid"
                    >
                        Suivant
                    </Button>
                    <Button
                        v-else
                        variant="default"
                        @click="handleComplete"
                        :disabled="!isStepValid"
                    >
                        <Check class="w-4 h-4 mr-2" />
                        Créer le bien
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.select-with-arrow {
    background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1.5L6 6.5L11 1.5' stroke='%23222222' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    padding-right: 3rem;
}
</style>
