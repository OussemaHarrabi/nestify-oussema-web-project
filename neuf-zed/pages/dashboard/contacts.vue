<script setup lang="ts">
import { ref, computed } from "vue";
import {
    Mail,
    Phone,
    Calendar,
    Building2,
    Home,
    MessageSquare,
    ArrowLeft,
    Filter,
} from "lucide-vue-next";
import Button from "~/components/ui/Button.vue";
import Badge from "~/components/ui/Badge.vue";

const filter = ref<"all" | "new" | "replied">("all");
const projectFilter = ref<string>("all");

const mockContacts = [
    {
        id: 1,
        name: "Ahmed Ben Ali",
        email: "ahmed.benali@email.com",
        phone: "+216 98 765 432",
        project: "Les Jardins de Carthage",
        projectId: 1,
        listing: "Appartement 3 pièces",
        date: "10 Oct 2024",
        time: "14:30",
        status: "new",
        message:
            "Je suis intéressé par cet appartement. Pouvons-nous organiser une visite ce weekend?",
    },
    {
        id: 2,
        name: "Leila Mansour",
        email: "leila.m@email.com",
        phone: "+216 22 123 456",
        project: "Marina Bay Residence",
        projectId: 2,
        listing: "Villa 4 pièces",
        date: "09 Oct 2024",
        time: "10:15",
        status: "replied",
        message:
            "Quelles sont les options de financement disponibles pour cette propriété?",
    },
    {
        id: 3,
        name: "Mohamed Trabelsi",
        email: "m.trabelsi@email.com",
        phone: "+216 55 987 654",
        project: "Les Jardins de Carthage",
        projectId: 1,
        listing: "Penthouse 5 pièces",
        date: "08 Oct 2024",
        time: "16:45",
        status: "new",
        message: "Est-ce que le prix inclut le parking et la cave?",
    },
    {
        id: 4,
        name: "Fatma Zaidi",
        email: "fatma.z@email.com",
        phone: "+216 23 456 789",
        project: "Marina Bay Residence",
        projectId: 2,
        listing: "Appartement 2 pièces",
        date: "07 Oct 2024",
        time: "11:20",
        status: "replied",
        message: "Y a-t-il des frais supplémentaires à prévoir?",
    },
    {
        id: 5,
        name: "Karim Khaled",
        email: "k.khaled@email.com",
        phone: "+216 98 111 222",
        project: "The Village",
        projectId: 3,
        listing: "Villa 5 pièces",
        date: "06 Oct 2024",
        time: "09:00",
        status: "new",
        message: "Pouvez-vous m'envoyer plus de photos et les plans?",
    },
];

const uniqueProjects = computed(() => {
    const projects = new Set(mockContacts.map((c) => c.project));
    return ["all", ...Array.from(projects)];
});

const filteredContacts = computed(() => {
    let filtered = mockContacts;

    if (filter.value !== "all") {
        filtered = filtered.filter((c) => c.status === filter.value);
    }

    if (projectFilter.value !== "all") {
        filtered = filtered.filter((c) => c.project === projectFilter.value);
    }

    return filtered;
});

const newContactsCount = computed(() => {
    return mockContacts.filter((c) => c.status === "new").length;
});

const repliedContactsCount = computed(() => {
    return mockContacts.filter((c) => c.status === "replied").length;
});

const handleStatusChange = (contactId: number, newStatus: string) => {
    const contact = mockContacts.find((c) => c.id === contactId);
    if (contact) {
        contact.status = newStatus;
    }
};

const goBack = () => {
    window.location.href = "/dashboard";
};
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header with Profile Dropdown -->
        <Header />

        <!-- Page Header -->
        <div class="bg-white border-b border-border">
            <div class="max-w-[1760px] mx-auto px-6 lg:px-20 py-6">
                <div class="flex items-center gap-4 mb-6">
                    <Button variant="outline" size="sm" @click="goBack">
                        <ArrowLeft class="w-4 h-4" />
                    </Button>
                    <div>
                        <h1 class="text-3xl font-bold text-foreground">
                            Contacts & Demandes
                        </h1>
                        <p class="text-muted-foreground mt-1">
                            Gérez toutes vos demandes de contact
                        </p>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        class="bg-gray-50 rounded-xl p-6 border-2 border-transparent hover:border-primary transition-all"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    Total
                                </p>
                                <p
                                    class="text-3xl font-bold text-foreground mt-1"
                                >
                                    {{ mockContacts.length }}
                                </p>
                            </div>
                            <div
                                class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center"
                            >
                                <MessageSquare class="w-6 h-6 text-primary" />
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-gray-50 rounded-xl p-6 border-2 border-transparent hover:border-primary transition-all"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    Nouveaux
                                </p>
                                <p
                                    class="text-3xl font-bold text-foreground mt-1"
                                >
                                    {{ newContactsCount }}
                                </p>
                            </div>
                            <div
                                class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center"
                            >
                                <Mail class="w-6 h-6 text-amber-600" />
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-gray-50 rounded-xl p-6 border-2 border-transparent hover:border-primary transition-all"
                    >
                        <div>
                            <p class="text-sm text-muted-foreground">
                                Répondus
                            </p>
                            <p class="text-3xl font-bold text-foreground mt-1">
                                {{ repliedContactsCount }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Content -->
        <div class="max-w-[1760px] mx-auto px-6 lg:px-20 py-8">
            <!-- Filters -->
            <div class="bg-white rounded-xl border-2 border-border p-6 mb-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label
                            class="block text-sm font-medium text-foreground mb-2"
                        >
                            Statut
                        </label>
                        <div class="flex gap-2 flex-wrap">
                            <Button
                                :variant="
                                    filter === 'all' ? 'default' : 'outline'
                                "
                                size="sm"
                                @click="filter = 'all'"
                            >
                                Tous ({{ mockContacts.length }})
                            </Button>
                            <Button
                                :variant="
                                    filter === 'new' ? 'default' : 'outline'
                                "
                                size="sm"
                                @click="filter = 'new'"
                            >
                                Nouveaux ({{ newContactsCount }})
                            </Button>
                            <Button
                                :variant="
                                    filter === 'replied' ? 'default' : 'outline'
                                "
                                size="sm"
                                @click="filter = 'replied'"
                            >
                                Répondus ({{ repliedContactsCount }})
                            </Button>
                        </div>
                    </div>

                    <div class="flex-1">
                        <label
                            class="block text-sm font-medium text-foreground mb-2"
                        >
                            Projet
                        </label>
                        <div class="relative">
                            <select
                                v-model="projectFilter"
                                class="w-full px-4 py-2.5 border-2 border-border rounded-xl bg-white text-foreground appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all select-with-arrow"
                            >
                                <option value="all">Tous les projets</option>
                                <option
                                    v-for="project in uniqueProjects.filter(
                                        (p) => p !== 'all',
                                    )"
                                    :key="project"
                                    :value="project"
                                >
                                    {{ project }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contacts List -->
            <div class="space-y-4">
                <div
                    v-for="contact in filteredContacts"
                    :key="contact.id"
                    class="bg-white rounded-xl border-2 border-border p-6 hover:border-primary transition-all"
                >
                    <div class="flex flex-col lg:flex-row gap-6">
                        <!-- Contact Info -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3
                                        class="text-lg font-semibold text-foreground"
                                    >
                                        {{ contact.name }}
                                    </h3>
                                    <div
                                        class="flex items-center gap-4 mt-2 text-sm text-muted-foreground"
                                    >
                                        <div class="flex items-center gap-1">
                                            <Mail class="w-4 h-4" />
                                            {{ contact.email }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <Phone class="w-4 h-4" />
                                            {{ contact.phone }}
                                        </div>
                                    </div>
                                </div>
                                <Badge
                                    :variant="
                                        contact.status === 'new'
                                            ? 'default'
                                            : 'secondary'
                                    "
                                >
                                    {{
                                        contact.status === "new"
                                            ? "Nouveau"
                                            : "Répondu"
                                    }}
                                </Badge>
                            </div>

                            <!-- Project & Listing Info -->
                            <div class="flex flex-wrap gap-4 mb-4 text-sm">
                                <div
                                    class="flex items-center gap-2 text-muted-foreground"
                                >
                                    <Building2 class="w-4 h-4" />
                                    <span class="font-medium">{{
                                        contact.project
                                    }}</span>
                                </div>
                                <div
                                    class="flex items-center gap-2 text-muted-foreground"
                                >
                                    <Home class="w-4 h-4" />
                                    <span>{{ contact.listing }}</span>
                                </div>
                                <div
                                    class="flex items-center gap-2 text-muted-foreground"
                                >
                                    <Calendar class="w-4 h-4" />
                                    <span
                                        >{{ contact.date }} à
                                        {{ contact.time }}</span
                                    >
                                </div>
                            </div>

                            <!-- Message -->
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <p class="text-sm text-foreground">
                                    {{ contact.message }}
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-3">
                                <Button size="sm" variant="default">
                                    <Mail class="w-4 h-4 mr-2" />
                                    Répondre
                                </Button>
                                <Button size="sm" variant="outline">
                                    <Phone class="w-4 h-4 mr-2" />
                                    Appeler
                                </Button>
                                <Button
                                    v-if="contact.status === 'new'"
                                    size="sm"
                                    variant="outline"
                                    @click="
                                        handleStatusChange(
                                            contact.id,
                                            'replied',
                                        )
                                    "
                                >
                                    Marquer comme répondu
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    v-if="filteredContacts.length === 0"
                    class="bg-white rounded-xl border-2 border-dashed border-border p-12 text-center"
                >
                    <MessageSquare
                        class="w-12 h-12 text-muted-foreground mx-auto mb-4"
                    />
                    <h3 class="text-lg font-semibold text-foreground mb-2">
                        Aucun contact trouvé
                    </h3>
                    <p class="text-muted-foreground">
                        Aucun contact ne correspond à vos filtres
                    </p>
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
