<script setup lang="ts">
import { ref, computed } from "vue";
import {
    Mail,
    Phone,
    Calendar,
    Building2,
    Home,
    MessageSquare,
} from "lucide-vue-next";

const filter = ref<"all" | "new" | "replied">("all");
const projectFilter = ref<string>("all");

const mockContacts = [
    {
        id: 1,
        name: "Ahmed Ben Ali",
        email: "ahmed.benali@email.com",
        phone: "+216 98 765 432",
        project: "Les Jardins de Carthage",
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
        listing: "Penthouse 5 pièces",
        date: "08 Oct 2024",
        time: "16:45",
        status: "replied",
        message:
            "Le prix est-il négociable? Je souhaite avoir plus d'informations sur les équipements.",
    },
    {
        id: 4,
        name: "Sami Gharbi",
        email: "sami.g@email.com",
        phone: "+216 29 456 789",
        project: "Marina Bay Residence",
        listing: "Villa 5 pièces",
        date: "10 Oct 2024",
        time: "09:20",
        status: "new",
        message:
            "Bonjour, je souhaite visiter cette propriété. Êtes-vous disponible demain?",
    },
    {
        id: 5,
        name: "Nadia Khelifi",
        email: "nadia.k@email.com",
        phone: "+216 25 789 123",
        project: "Les Jardins de Carthage",
        listing: "Appartement 4 pièces",
        date: "07 Oct 2024",
        time: "11:30",
        status: "replied",
        message:
            "Je voudrais connaître les frais de notaire et les délais de livraison.",
    },
];

const uniqueProjects = computed(() => {
    return Array.from(new Set(mockContacts.map((c) => c.project)));
});

const filteredContacts = computed(() => {
    return mockContacts.filter((contact) => {
        if (filter.value !== "all" && contact.status !== filter.value)
            return false;
        if (
            projectFilter.value !== "all" &&
            contact.project !== projectFilter.value
        )
            return false;
        return true;
    });
});

const newContactsCount = computed(
    () => mockContacts.filter((c) => c.status === "new").length,
);
const repliedContactsCount = computed(
    () => mockContacts.filter((c) => c.status === "replied").length,
);

const handleStatusChange = (contactId: number, newStatus: string) => {
    console.log(`Status changed for contact ${contactId}: ${newStatus}`);
    // TODO: Update in store/database
};
</script>

<template>
    <div class="space-y-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div
                class="group bg-white p-6 rounded-2xl border border-border/60 hover:shadow-lg hover:shadow-black/5 transition-all duration-300 hover:border-border"
            >
                <div class="text-4xl mb-2">{{ mockContacts.length }}</div>
                <div class="text-sm text-muted-foreground">Total contacts</div>
            </div>

            <div
                class="group bg-white p-6 rounded-2xl border border-border/60 hover:shadow-lg hover:shadow-black/5 transition-all duration-300 hover:border-border"
            >
                <div class="text-4xl mb-2">{{ newContactsCount }}</div>
                <div class="text-sm text-muted-foreground">Nouveaux</div>
            </div>

            <div
                class="group bg-white p-6 rounded-2xl border border-border/60 hover:shadow-lg hover:shadow-black/5 transition-all duration-300 hover:border-border"
            >
                <div class="text-4xl mb-2">{{ repliedContactsCount }}</div>
                <div class="text-sm text-muted-foreground">Traités</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap items-center gap-3">
            <!-- Status Filters -->
            <button
                @click="filter = 'all'"
                :class="[
                    'px-5 py-2.5 rounded-full transition-all duration-200',
                    filter === 'all'
                        ? 'bg-foreground text-white shadow-md shadow-foreground/20'
                        : 'bg-white border border-border text-foreground hover:border-foreground/40 hover:shadow-sm',
                ]"
            >
                Tous ({{ mockContacts.length }})
            </button>
            <button
                @click="filter = 'new'"
                :class="[
                    'px-5 py-2.5 rounded-full transition-all duration-200',
                    filter === 'new'
                        ? 'bg-primary text-white shadow-md shadow-primary/30'
                        : 'bg-white border border-border text-foreground hover:border-foreground/40 hover:shadow-sm',
                ]"
            >
                Nouveaux ({{ newContactsCount }})
            </button>
            <button
                @click="filter = 'replied'"
                :class="[
                    'px-5 py-2.5 rounded-full transition-all duration-200',
                    filter === 'replied'
                        ? 'bg-foreground text-white shadow-md shadow-foreground/20'
                        : 'bg-white border border-border text-foreground hover:border-foreground/40 hover:shadow-sm',
                ]"
            >
                Traités ({{ repliedContactsCount }})
            </button>

            <!-- Separator -->
            <div class="h-8 w-px bg-border/50"></div>

            <!-- Project Filter -->
            <select
                v-model="projectFilter"
                class="px-5 py-2.5 rounded-full bg-white border border-border text-foreground hover:border-foreground/40 hover:shadow-sm transition-all duration-200 outline-none pr-10 cursor-pointer select-arrow"
            >
                <option value="all">Tous les projets</option>
                <option
                    v-for="project in uniqueProjects"
                    :key="project"
                    :value="project"
                >
                    {{ project }}
                </option>
            </select>
        </div>

        <!-- Contacts List -->
        <div class="space-y-4">
            <div
                v-for="contact in filteredContacts"
                :key="contact.id"
                class="group bg-white border border-border/60 hover:border-border hover:shadow-lg hover:shadow-black/5 rounded-2xl overflow-hidden transition-all duration-300"
            >
                <div class="p-7">
                    <div class="flex items-start gap-4">
                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <!-- Header -->
                            <div
                                class="flex items-start justify-between gap-4 mb-6"
                            >
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg">
                                            {{ contact.name }}
                                        </h3>
                                    </div>
                                    <div
                                        class="flex items-center gap-2 text-sm text-muted-foreground"
                                    >
                                        <Calendar class="w-4 h-4" />
                                        <span
                                            >{{ contact.date }} à
                                            {{ contact.time }}</span
                                        >
                                    </div>
                                </div>

                                <!-- Status Selector -->
                                <select
                                    :value="contact.status"
                                    @change="
                                        (e) =>
                                            handleStatusChange(
                                                contact.id,
                                                (e.target as HTMLSelectElement)
                                                    .value,
                                            )
                                    "
                                    :class="[
                                        'px-4 py-2.5 rounded-xl border transition-all duration-200 outline-none pr-10 cursor-pointer flex-shrink-0 shadow-sm select-arrow',
                                        'bg-white border-border text-foreground hover:border-foreground/40 hover:shadow-md',
                                    ]"
                                >
                                    <option value="new">Nouveau</option>
                                    <option value="replied">Traité</option>
                                </select>
                            </div>

                            <!-- Contact Info -->
                            <div
                                class="flex flex-wrap gap-x-8 gap-y-3 mb-6 pb-6 border-b border-border/50"
                            >
                                <div
                                    class="flex items-center gap-2.5 group/item"
                                >
                                    <div
                                        class="p-1.5 rounded-lg bg-muted/50 group-hover/item:bg-muted transition-colors"
                                    >
                                        <Mail
                                            class="w-4 h-4 text-muted-foreground"
                                        />
                                    </div>
                                    <span class="text-sm text-foreground">{{
                                        contact.email
                                    }}</span>
                                </div>
                                <div
                                    class="flex items-center gap-2.5 group/item"
                                >
                                    <div
                                        class="p-1.5 rounded-lg bg-muted/50 group-hover/item:bg-muted transition-colors"
                                    >
                                        <Phone
                                            class="w-4 h-4 text-muted-foreground"
                                        />
                                    </div>
                                    <span class="text-sm text-foreground">{{
                                        contact.phone
                                    }}</span>
                                </div>
                            </div>

                            <!-- Project Info -->
                            <div class="mb-6">
                                <p
                                    class="text-xs text-muted-foreground mb-3 uppercase tracking-wider"
                                >
                                    Projet concerné
                                </p>
                                <div
                                    class="flex items-center gap-4 p-5 bg-gradient-to-br from-muted/30 to-muted/10 rounded-2xl border border-border/30 group-hover:border-border/50 transition-colors"
                                >
                                    <div
                                        class="w-11 h-11 bg-white rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm"
                                    >
                                        <Building2
                                            class="w-5 h-5 text-primary"
                                        />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="mb-1.5">
                                            {{ contact.project }}
                                        </p>
                                        <div
                                            class="flex items-center gap-2 text-sm text-muted-foreground"
                                        >
                                            <Home
                                                class="w-3.5 h-3.5 flex-shrink-0"
                                            />
                                            <span class="truncate">{{
                                                contact.listing
                                            }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Message -->
                            <div>
                                <p
                                    class="text-xs text-muted-foreground mb-3 uppercase tracking-wider"
                                >
                                    Message
                                </p>
                                <div
                                    class="p-5 bg-gradient-to-br from-muted/20 to-muted/5 rounded-2xl border border-border/30"
                                >
                                    <p
                                        class="text-sm leading-relaxed text-foreground/90"
                                    >
                                        {{ contact.message }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div
                v-if="filteredContacts.length === 0"
                class="bg-white border border-border rounded-2xl p-12"
            >
                <div class="text-center">
                    <div
                        class="w-20 h-20 bg-muted rounded-full flex items-center justify-center mx-auto mb-6"
                    >
                        <MessageSquare
                            class="w-10 h-10 text-muted-foreground"
                        />
                    </div>
                    <h3 class="text-xl mb-2">
                        <template v-if="filter === 'all'"
                            >Aucun contact pour le moment</template
                        >
                        <template v-else-if="filter === 'new'"
                            >Aucun nouveau contact</template
                        >
                        <template v-else>Aucun contact traité</template>
                    </h3>
                    <p class="text-muted-foreground">
                        <template v-if="filter === 'all'"
                            >Les demandes de contact de vos projets apparaîtront
                            ici</template
                        >
                        <template v-else-if="filter === 'new'"
                            >Tous vos contacts ont été traités</template
                        >
                        <template v-else
                            >Vous n'avez pas encore répondu à des
                            contacts</template
                        >
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.select-arrow {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23717171' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.875rem center;
    background-size: 16px;
}
</style>
