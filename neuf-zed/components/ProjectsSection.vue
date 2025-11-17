<template>
    <section class="py-20 lg:py-32 bg-muted">
        <div class="max-w-[1760px] mx-auto px-6 lg:px-20">
            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between mb-16"
            >
                <div>
                    <h2 class="text-4xl lg:text-5xl mb-6 text-foreground">
                        Les derniers projets immobiliers
                    </h2>
                    <p class="text-xl text-muted-foreground">
                        Découvrez nos nouveaux projets immobiliers neufs
                        récemment ajoutés à notre plateforme
                    </p>
                </div>
                <Button
                    variant="outline"
                    class="mt-8 md:mt-0 rounded-full px-8 py-3 border-border hover:bg-white"
                    @click="navigateTo('/search')"
                >
                    Voir tous les projets
                </Button>
            </div>

            <!-- Loading State -->
            <div v-if="isLoading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="i in 3" :key="i" class="bg-white rounded-3xl overflow-hidden border border-border animate-pulse">
                    <div class="h-72 bg-gray-200"></div>
                    <div class="p-6 space-y-4">
                        <div class="h-6 bg-gray-200 rounded w-3/4"></div>
                        <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                        <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                    </div>
                </div>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="text-center py-12">
                <p class="text-red-500 mb-4">{{ error }}</p>
                <Button @click="$router.go(0)" variant="outline">Réessayer</Button>
            </div>

            <!-- Empty State -->
            <div v-else-if="projects.length === 0" class="text-center py-12">
                <p class="text-muted-foreground text-lg">Aucun projet disponible pour le moment</p>
            </div>

            <!-- Projects Grid -->
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                    v-for="project in projects"
                    :key="project.id"
                    @click="$emit('project-click', project)"
                    class="bg-white rounded-3xl overflow-hidden group cursor-pointer hover:shadow-2xl transition-all duration-300 border border-border"
                >
                    <!-- Image avec overlay -->
                    <div class="relative h-72">
                        <img
                            :src="project.image"
                            :alt="project.title"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        />

                        <!-- Badge statut -->
                        <div class="absolute top-6 left-6">
                            <div
                                class="bg-white/95 backdrop-blur-sm rounded-full px-5 py-2.5 shadow-lg"
                            >
                                <span
                                    class="text-sm font-medium text-foreground"
                                    >{{ project.status }}</span
                                >
                            </div>
                        </div>

                        <!-- Badge promoteur -->
                        <div class="absolute bottom-6 right-6">
                            <div
                                class="bg-white/95 backdrop-blur-sm rounded-xl px-4 py-3 shadow-lg cursor-pointer hover:bg-white transition-colors"
                                @click.stop="
                                    $emit('developer-click', {
                                        name: project.developer,
                                        logo: project.developerLogo,
                                    })
                                "
                            >
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-12 h-12 bg-foreground rounded-xl flex items-center justify-center shadow-lg"
                                    >
                                        <span
                                            class="text-primary text-xs font-bold"
                                        >
                                            {{ project.developerLogo }}
                                        </span>
                                    </div>
                                    <div>
                                        <p
                                            class="text-xs text-muted-foreground uppercase tracking-wide"
                                        >
                                            Promoteur
                                        </p>
                                        <p
                                            class="text-sm font-semibold text-foreground hover:text-primary transition-colors"
                                        >
                                            {{ project.developer }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contenu -->
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2 text-foreground">
                            {{ project.title }}
                        </h3>

                        <p class="text-muted-foreground mb-4 text-base">
                            {{ project.type }}
                        </p>

                        <div
                            class="flex items-center text-muted-foreground mb-6"
                        >
                            <MapPin class="w-4 h-4 mr-2" />
                            <span class="text-sm">{{ project.location }}</span>
                        </div>

                        <div class="text-lg">
                            <span class="text-muted-foreground text-sm"
                                >À partir de
                            </span>
                            <span class="text-primary font-semibold"
                                >{{ project.price }} TND</span
                            >
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-16">
                <Button
                    size="lg"
                    class="bg-primary hover:bg-primary/90 rounded-full px-12 py-4 text-lg"
                    @click="navigateTo('/search')"
                >
                    Explorer tous les projets
                </Button>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { MapPin } from "lucide-vue-next";
import Button from "./ui/Button.vue";

const { apiFetch } = useApi();
const getImageUrl = useImageUrl;

const projects = ref<any[]>([]);
const isLoading = ref(true);
const error = ref<string | null>(null);

// Fetch projects from API
onMounted(async () => {
    try {
        isLoading.value = true;
        const response = await apiFetch('/projects', {
            params: {
                per_page: 6, // Fetch only 6 projects for the homepage
                sort: 'created_at',
                order: 'desc'
            }
        });
        
        // Transform API data to match component expectations
        projects.value = response.data.map((project: any) => ({
            id: project.id,
            title: project.name,
            location: `${project.city}${project.district ? ', ' + project.district : ''}`,
            image: getImageUrl(project.main_image) || 'https://images.unsplash.com/photo-1664892798972-079f15663b16?w=800',
            price: project.starting_price ? new Intl.NumberFormat('fr-TN').format(project.starting_price) : '0',
            type: project.description?.substring(0, 50) || 'Projet immobilier',
            status: project.status || 'Disponible',
            developer: project.promoter?.company_name || 'Promoteur',
            developerLogo: project.promoter?.company_name?.substring(0, 2).toUpperCase() || 'PR',
        }));
    } catch (err: any) {
        console.error('Error fetching projects:', err);
        error.value = 'Impossible de charger les projets';
        // Fallback to empty array
        projects.value = [];
    } finally {
        isLoading.value = false;
    }
});

defineEmits<{
    "project-click": [project: any];
    "developer-click": [developer: { name: string; logo: string }];
}>();
</script>
