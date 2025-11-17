<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Unified Header with Auth -->
    <Header />

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-primary/5 via-white to-primary/10 py-20">
      <div class="max-w-[1760px] mx-auto px-6 lg:px-20">
        <div class="text-center max-w-4xl mx-auto">
          <h1 class="text-5xl lg:text-6xl font-bold text-foreground mb-6">
            Promoteurs Immobiliers de Confiance
          </h1>
          <p class="text-xl text-muted-foreground mb-8">
            Découvrez les meilleurs promoteurs immobiliers en Tunisie. Des partenaires fiables pour votre projet de vie.
          </p>
          <div class="flex items-center justify-center gap-4">
            <Badge class="px-6 py-2 text-base bg-primary/10 text-primary hover:bg-primary/20 hover:-translate-y-1 transition-all cursor-default">{{ developers.length }} Promoteurs</Badge>
            <Badge class="px-6 py-2 text-base bg-primary/10 text-primary hover:bg-primary/20 hover:-translate-y-1 transition-all cursor-default">{{ totalProjects }} Projets Réalisés</Badge>
            <Badge class="px-6 py-2 text-base bg-primary/10 text-primary hover:bg-primary/20 hover:-translate-y-1 transition-all cursor-default">15+ Ans d'Expérience</Badge>
          </div>
        </div>
      </div>
    </section>

    <!-- Developers Grid -->
    <section class="py-16">
      <div class="max-w-[1760px] mx-auto px-6 lg:px-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <NuxtLink
            v-for="developer in developers"
            :key="developer.name"
            :to="`/developer/${encodeURIComponent(developer.name)}`"
            class="group bg-white rounded-2xl border-2 border-border hover:border-primary transition-all duration-300 overflow-hidden cursor-pointer hover:shadow-2xl block"
          >
            <!-- Logo Section -->
            <div class="p-8 bg-gradient-to-br from-muted/30 to-muted/10 flex items-center justify-center border-b border-border">
              <div class="w-32 h-32 bg-white rounded-2xl shadow-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <img :src="developer.logo" :alt="developer.name" class="w-24 h-24 object-contain" />
              </div>
            </div>

            <!-- Info Section -->
            <div class="p-6">
              <h3 class="text-2xl font-bold text-foreground mb-2 group-hover:text-primary transition-colors">
                {{ developer.name }}
              </h3>
              <p class="text-muted-foreground mb-4 line-clamp-2">
                {{ developer.description }}
              </p>

              <!-- Stats -->
              <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="text-center">
                  <div class="text-2xl font-bold text-primary">{{ developer.projectsCount }}</div>
                  <div class="text-xs text-muted-foreground">Projets</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-primary">{{ developer.unitsDelivered }}+</div>
                  <div class="text-xs text-muted-foreground">Unités</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-primary">{{ developer.yearsExperience }}+</div>
                  <div class="text-xs text-muted-foreground">Ans</div>
                </div>
              </div>

              <!-- Specialties -->
              <div class="flex flex-wrap gap-2 mb-4">
                <Badge v-for="specialty in developer.specialties.slice(0, 3)" :key="specialty" variant="outline" class="text-xs">
                  {{ specialty }}
                </Badge>
              </div>

              <!-- Achievements Badge -->
              <div v-if="developer.certified" class="flex items-center gap-2 text-sm text-primary font-semibold">
                <Award class="w-4 h-4" />
                <span>Promoteur Certifié</span>
              </div>
            </div>

            <!-- CTA Footer -->
            <div class="px-6 pb-6">
              <div class="w-full py-3 bg-primary/5 group-hover:bg-primary text-foreground group-hover:text-white rounded-xl text-center font-semibold transition-all duration-300">
                Voir les projets
              </div>
            </div>
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- Why Choose Section -->
    <section class="py-20 bg-white">
      <div class="max-w-[1760px] mx-auto px-6 lg:px-20">
        <div class="text-center mb-16">
          <h2 class="text-4xl font-bold text-foreground mb-4">
            Pourquoi Choisir nos Promoteurs?
          </h2>
          <p class="text-xl text-muted-foreground">
            Des garanties solides pour votre investissement immobilier
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div class="text-center p-6">
            <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
              <Shield class="w-8 h-8 text-primary" />
            </div>
            <h3 class="text-xl font-bold mb-2">Garantie Qualité</h3>
            <p class="text-muted-foreground">Tous nos promoteurs sont certifiés et respectent les normes de construction</p>
          </div>

          <div class="text-center p-6">
            <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
              <TrendingUp class="w-8 h-8 text-primary" />
            </div>
            <h3 class="text-xl font-bold mb-2">Track Record Prouvé</h3>
            <p class="text-muted-foreground">Des années d'expérience et des centaines de projets livrés avec succès</p>
          </div>

          <div class="text-center p-6">
            <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
              <Users class="w-8 h-8 text-primary" />
            </div>
            <h3 class="text-xl font-bold mb-2">Support Client</h3>
            <p class="text-muted-foreground">Accompagnement personnalisé de l'achat jusqu'à la livraison</p>
          </div>

          <div class="text-center p-6">
            <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
              <CheckCircle class="w-8 h-8 text-primary" />
            </div>
            <h3 class="text-xl font-bold mb-2">Transparence Totale</h3>
            <p class="text-muted-foreground">Informations claires sur les prix, délais et conditions</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<script setup lang="ts">
import { Menu, User, Award, Shield, TrendingUp, Users, CheckCircle } from 'lucide-vue-next'
import Badge from '~/components/ui/Badge.vue'
import Footer from '~/components/Footer.vue'

const developers = [
  {
    name: 'Tunisie Immobilière',
    logo: 'https://via.placeholder.com/150x150/FF385C/FFFFFF?text=TI',
    description: 'Leader du marché tunisien avec plus de 25 ans d\'expérience dans la promotion immobilière haut de gamme.',
    projectsCount: 45,
    unitsDelivered: 3200,
    yearsExperience: 25,
    specialties: ['Résidences de luxe', 'Projets intégrés', 'Vue mer'],
    certified: true
  },
  {
    name: 'Carthage Promotions',
    logo: 'https://via.placeholder.com/150x150/4A90E2/FFFFFF?text=CP',
    description: 'Spécialiste des projets résidentiels dans les zones prisées de la côte tunisienne.',
    projectsCount: 32,
    unitsDelivered: 2400,
    yearsExperience: 18,
    specialties: ['Appartements standing', 'Villas modernes', 'Résidences sécurisées'],
    certified: true
  },
  {
    name: 'Mediterranean Developers',
    logo: 'https://via.placeholder.com/150x150/50C878/FFFFFF?text=MD',
    description: 'Innovation et qualité au service de votre projet immobilier avec des designs contemporains.',
    projectsCount: 28,
    unitsDelivered: 1800,
    yearsExperience: 15,
    specialties: ['Éco-construction', 'Smart homes', 'Design moderne'],
    certified: true
  },
  {
    name: 'Prestige Immobilier',
    logo: 'https://via.placeholder.com/150x150/FFD700/333333?text=PI',
    description: 'Créateur de résidences d\'exception alliant luxe, confort et emplacement privilégié.',
    projectsCount: 22,
    unitsDelivered: 1500,
    yearsExperience: 20,
    specialties: ['Penthouse', 'Résidences golf', 'Concierge service'],
    certified: true
  },
  {
    name: 'Horizon Development',
    logo: 'https://via.placeholder.com/150x150/9C27B0/FFFFFF?text=HD',
    description: 'Construisons ensemble votre avenir avec des projets innovants et accessibles.',
    projectsCount: 35,
    unitsDelivered: 2600,
    yearsExperience: 16,
    specialties: ['Projets abordables', 'Facilités de paiement', 'Livraison rapide'],
    certified: true
  },
  {
    name: 'Elite Properties Group',
    logo: 'https://via.placeholder.com/150x150/FF6B6B/FFFFFF?text=EPG',
    description: 'Excellence et raffinement dans chaque détail pour une vie de standing.',
    projectsCount: 19,
    unitsDelivered: 1200,
    yearsExperience: 12,
    specialties: ['Résidences fermées', 'Piscine privée', 'Jardins paysagers'],
    certified: true
  }
]

const totalProjects = developers.reduce((sum, dev) => sum + dev.projectsCount, 0)
</script>
