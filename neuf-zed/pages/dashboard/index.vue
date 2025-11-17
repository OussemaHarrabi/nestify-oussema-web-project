<script setup lang="ts">
import { ref } from "vue";
import {
    Building,
    Home,
    Users,
    Plus,
    Eye,
    Settings,
    MessageSquare,
    TrendingUp,
    ArrowRight,
    Menu,
    User,
    ChevronDown,
    ChevronUp,
} from "lucide-vue-next";
import Button from "~/components/ui/Button.vue";
import Badge from "~/components/ui/Badge.vue";

const stats = ref({
    totalProjects: 3,
    totalListings: 12,
    totalLeads: 24,
    totalViews: 590,
});

const expandedProjects = ref<number[]>([]);

const projects = ref([
    {
        id: 1,
        name: "Les Jardins de Carthage",
        location: "Carthage, Tunis",
        image: "https://images.unsplash.com/photo-1664892798972-079f15663b16?w=200",
        status: "active",
        listingsCount: 5,
        leadsCount: 12,
        views: 245,
        listings: [
            {
                id: 1,
                type: "Appartement 2 pièces",
                surface: 85,
                price: "280 000",
                status: "available",
            },
            {
                id: 2,
                type: "Appartement 3 pièces",
                surface: 120,
                price: "340 000",
                status: "available",
            },
            {
                id: 3,
                type: "Appartement 4 pièces",
                surface: 155,
                price: "420 000",
                status: "reserved",
            },
            {
                id: 4,
                type: "Villa 5 pièces",
                surface: 220,
                price: "680 000",
                status: "sold",
            },
        ],
    },
    {
        id: 2,
        name: "Marina Bay Residence",
        location: "Hammamet, Nabeul",
        image: "https://images.unsplash.com/photo-1670352702722-058a93937fc1?w=200",
        status: "active",
        listingsCount: 4,
        leadsCount: 8,
        views: 189,
        listings: [
            {
                id: 5,
                type: "Appartement 2 pièces",
                surface: 90,
                price: "295 000",
                status: "available",
            },
            {
                id: 6,
                type: "Appartement 3 pièces",
                surface: 130,
                price: "380 000",
                status: "available",
            },
        ],
    },
    {
        id: 3,
        name: "The Village",
        location: "Soukra, Chotrana 2",
        image: "https://images.unsplash.com/photo-1677553512940-f79af72efd1b?w=200",
        status: "active",
        listingsCount: 3,
        leadsCount: 4,
        views: 156,
        listings: [
            {
                id: 7,
                type: "Appartement 3 pièces",
                surface: 115,
                price: "320 000",
                status: "available",
            },
            {
                id: 8,
                type: "Penthouse 5 pièces",
                surface: 200,
                price: "650 000",
                status: "reserved",
            },
        ],
    },
]);

const toggleProjectListings = (projectId: number) => {
    const index = expandedProjects.value.indexOf(projectId);
    if (index > -1) {
        expandedProjects.value.splice(index, 1);
    } else {
        expandedProjects.value.push(projectId);
    }
};

const isProjectExpanded = (projectId: number) => {
    return expandedProjects.value.includes(projectId);
};

const handleManageProject = (id: number) => {
    window.location.href = `/dashboard/projects/${id}/manage`;
};

const handleViewProject = (id: number) => {
    window.location.href = `/project/${id}`;
};

const handleCreateListing = (projectId: number) => {
    window.location.href = `/dashboard/projects/${projectId}/listings/create`;
};

const handleContactsClick = () => {
    window.location.href = "/dashboard/contacts";
};

const goToCreateProject = () => {
    window.location.href = "/dashboard/create-project";
};

const handleListingClick = (projectId: number, listingId: number) => {
    window.location.href = `/dashboard/projects/${projectId}/listings/${listingId}`;
};

const updateListingStatus = (
    projectId: number,
    listingId: number,
    status: string,
) => {
    const project = projects.value.find((p) => p.id === projectId);
    if (project) {
        const listing = project.listings.find((l) => l.id === listingId);
        if (listing) {
            listing.status = status;
        }
    }
};

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
            return "text-green-700";
        case "reserved":
            return "text-amber-700";
        case "sold":
            return "text-gray-700";
        default:
            return "text-gray-700";
    }
};

const goHome = () => {
    window.location.href = "/";
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header with Profile Dropdown -->
        <Header />

        <!-- Main Content -->
        <div class="max-w-[1760px] mx-auto px-6 lg:px-20 py-8">
            <!-- Page Title -->
            <div class="mb-8">
                <h1 class="text-3xl lg:text-4xl font-bold text-foreground mb-2">
                    Tableau de bord
                </h1>
                <p class="text-muted-foreground">
                    Gérez vos projets immobiliers en toute simplicité
                </p>
            </div>

            <!-- Stats Overview -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"
            >
                <!-- Total Projects -->
                <div
                    class="bg-white rounded-xl p-6 border-2 border-border hover:border-primary transition-all"
                >
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-muted-foreground">Projets</p>
                        <div
                            class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center"
                        >
                            <Building class="w-5 h-5 text-primary" />
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-foreground mb-1">
                        {{ stats.totalProjects }}
                    </p>
                    <p class="text-sm text-muted-foreground">Projets actifs</p>
                </div>

                <!-- Total Listings -->
                <div
                    class="bg-white rounded-xl p-6 border-2 border-border hover:border-primary transition-all"
                >
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-muted-foreground">Biens</p>
                        <div
                            class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center"
                        >
                            <Home class="w-5 h-5 text-primary" />
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-foreground mb-1">
                        {{ stats.totalListings }}
                    </p>
                    <p class="text-sm text-muted-foreground">
                        Annonces publiées
                    </p>
                </div>

                <!-- Total Leads -->
                <div
                    class="bg-white rounded-xl p-6 border-2 border-border hover:border-primary transition-all"
                >
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-muted-foreground">Demandes</p>
                        <div
                            class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center"
                        >
                            <Users class="w-5 h-5 text-primary" />
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-foreground mb-1">
                        {{ stats.totalLeads }}
                    </p>
                    <p class="text-sm text-muted-foreground">Contacts reçus</p>
                </div>

                <!-- Total Views -->
                <div
                    class="bg-white rounded-xl p-6 border-2 border-border hover:border-primary transition-all"
                >
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-muted-foreground">Vues</p>
                        <div
                            class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center"
                        >
                            <Eye class="w-5 h-5 text-primary" />
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-foreground mb-1">
                        {{ stats.totalViews }}
                    </p>
                    <p class="text-sm text-muted-foreground">Vues totales</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl border-2 border-border p-6 mb-8">
                <h2 class="text-xl font-bold text-foreground mb-4">
                    Actions rapides
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button
                        @click="goToCreateProject"
                        class="flex items-center gap-4 p-4 rounded-xl border-2 border-border hover:border-primary hover:bg-primary/5 transition-all text-left group"
                    >
                        <div
                            class="w-12 h-12 rounded-full bg-primary/10 group-hover:bg-primary/20 flex items-center justify-center transition-colors flex-shrink-0"
                        >
                            <Plus class="w-6 h-6 text-primary" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-foreground mb-1">
                                Créer un projet
                            </p>
                            <p class="text-sm text-muted-foreground">
                                Ajoutez un nouveau projet immobilier
                            </p>
                        </div>
                        <ArrowRight
                            class="w-5 h-5 text-muted-foreground group-hover:text-primary transition-colors flex-shrink-0"
                        />
                    </button>

                    <button
                        @click="handleContactsClick"
                        class="flex items-center gap-4 p-4 rounded-xl border-2 border-border hover:border-primary hover:bg-primary/5 transition-all text-left group"
                    >
                        <div
                            class="w-12 h-12 rounded-full bg-primary/10 group-hover:bg-primary/20 flex items-center justify-center transition-colors flex-shrink-0"
                        >
                            <MessageSquare class="w-6 h-6 text-primary" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-foreground mb-1">
                                Voir les contacts
                            </p>
                            <p class="text-sm text-muted-foreground">
                                Gérez vos demandes de contact
                            </p>
                        </div>
                        <ArrowRight
                            class="w-5 h-5 text-muted-foreground group-hover:text-primary transition-colors flex-shrink-0"
                        />
                    </button>

                    <button
                        class="flex items-center gap-4 p-4 rounded-xl border-2 border-border hover:border-primary hover:bg-primary/5 transition-all text-left group"
                    >
                        <div
                            class="w-12 h-12 rounded-full bg-primary/10 group-hover:bg-primary/20 flex items-center justify-center transition-colors flex-shrink-0"
                        >
                            <TrendingUp class="w-6 h-6 text-primary" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-foreground mb-1">
                                Voir les statistiques
                            </p>
                            <p class="text-sm text-muted-foreground">
                                Analysez vos performances
                            </p>
                        </div>
                        <ArrowRight
                            class="w-5 h-5 text-muted-foreground group-hover:text-primary transition-colors flex-shrink-0"
                        />
                    </button>
                </div>
            </div>

            <!-- Projects List -->
            <div class="bg-white rounded-xl border-2 border-border p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-foreground mb-1">
                            Mes projets
                        </h2>
                        <p class="text-sm text-muted-foreground">
                            {{ projects.length }} projet{{
                                projects.length > 1 ? "s" : ""
                            }}
                            actif{{ projects.length > 1 ? "s" : "" }}
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div
                        v-for="project in projects"
                        :key="project.id"
                        class="rounded-xl border-2 border-border hover:border-primary transition-all"
                    >
                        <!-- Project Header -->
                        <div
                            class="flex flex-col md:flex-row items-start md:items-center gap-6 p-6"
                        >
                            <!-- Project Image & Info -->
                            <div class="flex items-start gap-4 flex-1 min-w-0">
                                <img
                                    :src="project.image"
                                    :alt="project.name"
                                    class="w-20 h-20 rounded-xl object-cover border-2 border-border flex-shrink-0 cursor-pointer hover:opacity-80 transition-opacity"
                                    @click="handleViewProject(project.id)"
                                />
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start gap-3 mb-2">
                                        <h3
                                            class="text-lg font-semibold text-foreground cursor-pointer hover:text-primary transition-colors"
                                            @click="
                                                handleViewProject(project.id)
                                            "
                                        >
                                            {{ project.name }}
                                        </h3>
                                        <Badge variant="default">Actif</Badge>
                                    </div>
                                    <p
                                        class="text-sm text-muted-foreground mb-3"
                                    >
                                        {{ project.location }}
                                    </p>
                                    <div
                                        class="flex items-center gap-4 text-sm text-muted-foreground"
                                    >
                                        <div class="flex items-center gap-1">
                                            <Home class="w-4 h-4" />
                                            <span
                                                >{{
                                                    project.listingsCount
                                                }}
                                                biens</span
                                            >
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <MessageSquare class="w-4 h-4" />
                                            <span
                                                >{{
                                                    project.leadsCount
                                                }}
                                                demandes</span
                                            >
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <Eye class="w-4 h-4" />
                                            <span
                                                >{{ project.views }} vues</span
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-3 flex-shrink-0">
                                <Button
                                    variant="outline"
                                    size="sm"
                                    @click="toggleProjectListings(project.id)"
                                >
                                    <Home class="w-4 h-4 mr-2" />
                                    Voir les biens
                                    <ChevronDown
                                        v-if="!isProjectExpanded(project.id)"
                                        class="w-4 h-4 ml-2"
                                    />
                                    <ChevronUp v-else class="w-4 h-4 ml-2" />
                                </Button>
                                <Button
                                    variant="default"
                                    size="sm"
                                    @click="handleManageProject(project.id)"
                                >
                                    <Settings class="w-4 h-4 mr-2" />
                                    Gérer
                                </Button>
                            </div>
                        </div>

                        <!-- Expanded Listings -->
                        <div
                            v-if="isProjectExpanded(project.id)"
                            class="border-t border-border bg-gray-50 p-4"
                        >
                            <div class="space-y-2">
                                <div
                                    v-for="listing in project.listings"
                                    :key="listing.id"
                                    class="flex items-center justify-between p-4 bg-white rounded-lg border border-border hover:border-primary transition-all cursor-pointer"
                                    @click="
                                        handleListingClick(
                                            project.id,
                                            listing.id,
                                        )
                                    "
                                >
                                    <div class="flex items-center gap-3">
                                        <Home class="w-5 h-5 text-primary" />
                                        <div>
                                            <p
                                                class="font-semibold text-foreground"
                                            >
                                                {{ listing.type }}
                                            </p>
                                            <p
                                                class="text-sm text-muted-foreground"
                                            >
                                                {{ listing.surface }} m² •
                                                {{ listing.price }} TND
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <select
                                            :value="listing.status"
                                            @change="
                                                updateListingStatus(
                                                    project.id,
                                                    listing.id,
                                                    (
                                                        $event.target as HTMLSelectElement
                                                    ).value,
                                                )
                                            "
                                            @click.stop
                                            :class="[
                                                'px-3 py-1.5 rounded-lg text-sm font-medium border-2 cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary',
                                                getStatusColor(listing.status),
                                            ]"
                                        >
                                            <option value="available">
                                                Disponible
                                            </option>
                                            <option value="reserved">
                                                Réservé
                                            </option>
                                            <option value="sold">Vendu</option>
                                        </select>
                                        <ChevronDown
                                            class="w-5 h-5 text-muted-foreground"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="projects.length === 0" class="text-center py-12">
                    <Building
                        class="w-16 h-16 text-muted-foreground mx-auto mb-4"
                    />
                    <h3 class="text-xl font-semibold text-foreground mb-2">
                        Aucun projet pour le moment
                    </h3>
                    <p class="text-muted-foreground mb-6">
                        Créez votre premier projet pour commencer
                    </p>
                    <Button variant="default" @click="goToCreateProject">
                        <Plus class="w-4 h-4 mr-2" />
                        Créer un projet
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>
