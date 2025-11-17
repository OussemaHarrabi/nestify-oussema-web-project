<template>
  <div class="min-h-screen bg-white">
    <!-- Unified Header with Auth -->
    <Header />

    <div class="max-w-6xl mx-auto px-6 lg:px-10 py-6">
      <button
        @click="navigateTo('/search')"
        class="flex items-center gap-3 text-gray-600 hover:text-gray-900 transition-colors mb-6"
      >
        <div class="w-8 h-8 rounded-full border border-gray-300 hover:border-gray-400 flex items-center justify-center transition-colors">
          <ArrowLeft class="w-4 h-4" />
        </div>
        <span class="hidden sm:block">Retour aux projets</span>
      </button>
    </div>

    <div class="max-w-6xl mx-auto px-6 lg:px-10 pb-12">
      <!-- En-tête promoteur -->
      <div class="flex flex-col md:flex-row gap-8 mb-12">
        <div class="w-32 h-32 bg-foreground rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
          <span class="text-primary text-4xl font-bold">{{ developer.logo }}</span>
        </div>

        <div class="flex-1">
          <h1 class="text-4xl font-semibold mb-4">{{ developer.name }}</h1>

          <div class="flex flex-wrap gap-4 mb-6">
            <div class="flex items-center gap-2 text-muted-foreground">
              <MapPin class="w-5 h-5" />
              <span>{{ developer.location }}</span>
            </div>
            <div class="flex items-center gap-2 text-muted-foreground">
              <Building2 class="w-5 h-5" />
              <span>{{ developer.projectsCount }} projets</span>
            </div>
          </div>

          <div class="flex flex-wrap gap-3">
            <Button variant="default" size="lg" class="rounded-full">
              <Phone class="w-4 h-4 mr-2" />
              Appeler
            </Button>
            <Button variant="outline" size="lg" class="rounded-full">
              <Mail class="w-4 h-4 mr-2" />
              Envoyer un email
            </Button>
          </div>
        </div>
      </div>

      <!-- À propos -->
      <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-6">À propos de {{ developer.name }}</h2>
        <p class="text-muted-foreground leading-relaxed text-lg">
          {{ developer.description }}
        </p>
      </section>

      <!-- Certifications -->
      <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-6">Certifications et labels</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div
            v-for="cert in developer.certifications"
            :key="cert.name"
            class="bg-muted/30 rounded-2xl p-6 border border-border hover:border-primary/50 transition-all"
          >
            <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center mb-4 shadow-sm">
              <Award class="w-8 h-8 text-primary" />
            </div>
            <h3 class="font-semibold mb-2">{{ cert.name }}</h3>
            <p class="text-sm text-muted-foreground">{{ cert.description }}</p>
          </div>
        </div>
      </section>

      <!-- Points forts -->
      <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-6">Nos points forts</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="flex gap-4">
            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
              <Shield class="w-6 h-6 text-primary" />
            </div>
            <div>
              <h3 class="font-semibold mb-2">Qualité garantie</h3>
              <p class="text-muted-foreground">Des matériaux de première qualité et des finitions soignées dans tous nos projets.</p>
            </div>
          </div>

          <div class="flex gap-4">
            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
              <Award class="w-6 h-6 text-primary" />
            </div>
            <div>
              <h3 class="font-semibold mb-2">Expertise reconnue</h3>
              <p class="text-muted-foreground">Plus de 15 ans d'expérience dans l'immobilier neuf en Tunisie.</p>
            </div>
          </div>

          <div class="flex gap-4">
            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
              <Building2 class="w-6 h-6 text-primary" />
            </div>
            <div>
              <h3 class="font-semibold mb-2">Projets innovants</h3>
              <p class="text-muted-foreground">Architectures modernes et conceptions respectueuses de l'environnement.</p>
            </div>
          </div>

          <div class="flex gap-4">
            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
              <Star class="w-6 h-6 text-primary" />
            </div>
            <div>
              <h3 class="font-semibold mb-2">Satisfaction client</h3>
              <p class="text-muted-foreground">Un accompagnement personnalisé du début à la livraison de votre bien.</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Projets du promoteur -->
      <section>
        <h2 class="text-2xl font-semibold mb-6">Nos projets</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="project in developer.projects"
            :key="project.id"
            @click="navigateTo(`/project/${project.id}`)"
            class="bg-white rounded-2xl overflow-hidden border border-border hover:shadow-xl transition-all cursor-pointer group"
          >
            <div class="relative h-48">
              <img
                :src="project.image"
                :alt="project.name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              />
              <div class="absolute top-4 left-4">
                <Badge class="bg-white/95 text-foreground">{{ project.status }}</Badge>
              </div>
            </div>

            <div class="p-5">
              <h3 class="text-lg font-semibold mb-2">{{ project.name }}</h3>
              <p class="text-sm text-muted-foreground mb-3">{{ project.type }}</p>

              <div class="flex items-center text-muted-foreground text-sm mb-4">
                <MapPin class="w-4 h-4 mr-1" />
                <span>{{ project.location }}</span>
              </div>

              <div class="text-lg">
                <span class="text-muted-foreground text-sm">À partir de </span>
                <span class="text-primary font-semibold">{{ project.priceFrom }} TND</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Contact Card -->
      <section class="mt-12">
        <div class="bg-gradient-to-br from-primary/5 to-primary/10 rounded-3xl p-8 border border-primary/20">
          <div class="max-w-2xl mx-auto text-center">
            <h2 class="text-2xl font-semibold mb-4">Intéressé par nos projets ?</h2>
            <p class="text-muted-foreground mb-6">
              Contactez-nous pour plus d'informations sur nos projets immobiliers
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
              <Button size="lg" class="rounded-full">
                <Phone class="w-4 h-4 mr-2" />
                {{ developer.phone }}
              </Button>
              <Button variant="outline" size="lg" class="rounded-full">
                <Mail class="w-4 h-4 mr-2" />
                {{ developer.email }}
              </Button>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { ArrowLeft, MapPin, Phone, Mail, Building2, Shield, Award, Star } from 'lucide-vue-next'
import Button from '~/components/ui/Button.vue'
import Badge from '~/components/ui/Badge.vue'

const route = useRoute()
const developerName = computed(() => (route?.params?.name as string) || '')

const developer = ref({
  id: 1,
  name: "SPIV Promotion",
  logo: "SPIV",
  location: "Hammamet, El Mrezga",
  phone: "+216 70 123 456",
  email: "contact@spivpromotion.tn",
  projectsCount: 3,
  description: "SPIV Promotion est une société spécialisée dans le développement immobilier neuf en Tunisie. Nous nous engageons à créer des espaces de vie modernes et confortables, alliant qualité architecturale et innovations durables. Notre expertise s'étend sur différents types de projets résidentiels, des appartements haut de gamme aux villas de luxe.",
  certifications: [
    {
      name: "RE 2020",
      description: "Réglementation Environnementale 2020"
    },
    {
      name: "RT 2012",
      description: "Réglementation Thermique 2012"
    },
    {
      name: "ISO 9001",
      description: "Gestion de la qualité certifiée"
    }
  ],
  projects: [
    {
      id: 1,
      name: "The Village",
      type: "Appartements neufs 3 à 5 pièces",
      location: "Soukra, Chotrana 2",
      priceFrom: "435 000",
      status: "Haut standing, finalisé",
      image: "https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=400&h=300&fit=crop"
    },
    {
      id: 2,
      name: "Marina Bay",
      type: "Appartements de luxe 2 à 4 pièces",
      location: "Gammarth, Zone touristique",
      priceFrom: "520 000",
      status: "En construction",
      image: "https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=400&h=300&fit=crop"
    },
    {
      id: 3,
      name: "Green Residence",
      type: "Villas modernes",
      location: "La Marsa, Centre ville",
      priceFrom: "750 000",
      status: "Livraison 2024",
      image: "https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=400&h=300&fit=crop"
    }
  ]
})
</script>
