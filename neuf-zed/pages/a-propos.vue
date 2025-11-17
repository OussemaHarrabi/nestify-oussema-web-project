<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Unified Header with Auth -->
    <Header />

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-primary/5 via-white to-primary/10 py-20">
      <div class="max-w-[1200px] mx-auto px-6 lg:px-20 text-center">
        <h1 class="text-5xl lg:text-6xl font-bold text-foreground mb-6">
          À Propos de Neuf.tn
        </h1>
        <p class="text-xl text-muted-foreground max-w-3xl mx-auto">
          La plateforme n°1 en Tunisie pour découvrir et acheter des biens immobiliers neufs. Nous connectons les acheteurs aux meilleurs promoteurs du pays.
        </p>
      </div>
    </section>

    <!-- Mission Section -->
    <section class="py-20 bg-white">
      <div class="max-w-[1200px] mx-auto px-6 lg:px-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <div>
            <h2 class="text-4xl font-bold text-foreground mb-6">Notre Mission</h2>
            <p class="text-lg text-muted-foreground mb-6">
              Simplifier l'accès à l'immobilier neuf en Tunisie en offrant une plateforme transparente, moderne et accessible à tous. Nous croyons que chacun mérite de trouver le logement de ses rêves facilement.
            </p>
            <p class="text-lg text-muted-foreground mb-6">
              Depuis notre lancement, nous avons aidé des milliers de familles tunisiennes à concrétiser leur projet immobilier en les mettant en relation avec des promoteurs de confiance.
            </p>
            <div class="flex gap-4">
              <Badge class="px-6 py-3 text-base bg-primary/10 text-primary">Innovation</Badge>
              <Badge class="px-6 py-3 text-base bg-primary/10 text-primary">Transparence</Badge>
              <Badge class="px-6 py-3 text-base bg-primary/10 text-primary">Excellence</Badge>
            </div>
          </div>
          <div class="bg-gradient-to-br from-primary/10 to-primary/5 rounded-3xl p-12 text-center">
            <div class="grid grid-cols-2 gap-8">
              <div>
                <div class="text-5xl font-bold text-primary mb-2">500+</div>
                <div class="text-muted-foreground">Projets</div>
              </div>
              <div>
                <div class="text-5xl font-bold text-primary mb-2">50+</div>
                <div class="text-muted-foreground">Promoteurs</div>
              </div>
              <div>
                <div class="text-5xl font-bold text-primary mb-2">10K+</div>
                <div class="text-muted-foreground">Acheteurs</div>
              </div>
              <div>
                <div class="text-5xl font-bold text-primary mb-2">24/7</div>
                <div class="text-muted-foreground">Support</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Timeline Section -->
    <section class="py-20 bg-gray-50">
      <div class="max-w-[1200px] mx-auto px-6 lg:px-20">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-bold text-foreground mb-4">
            Notre Parcours
          </h2>
          <p class="text-xl text-muted-foreground">
            L'évolution de Neuf.tn au fil des années
          </p>
        </div>

        <div class="relative">
          <!-- Timeline Line -->
          <div class="hidden lg:block absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-primary/20"></div>

          <!-- Timeline Items -->
          <div class="space-y-12">
            <div v-for="(milestone, index) in milestones" :key="index" class="relative">
              <div class="lg:flex lg:items-center lg:justify-between">
                <!-- Left Side (odd items) -->
                <div :class="['lg:w-5/12', index % 2 === 0 ? 'lg:text-right' : 'lg:order-2']">
                  <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="text-primary font-bold text-lg mb-2">{{ milestone.year }}</div>
                    <h3 class="text-xl font-bold mb-2">{{ milestone.title }}</h3>
                    <p class="text-muted-foreground">{{ milestone.description }}</p>
                  </div>
                </div>

                <!-- Center Icon -->
                <div class="hidden lg:flex absolute left-1/2 transform -translate-x-1/2 w-12 h-12 bg-primary rounded-full items-center justify-center shadow-lg">
                  <Zap class="w-6 h-6 text-white" />
                </div>

                <!-- Right Side (even items) -->
                <div class="lg:w-5/12"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Values Section -->
    <section class="py-20 bg-white">
      <div class="max-w-[1760px] mx-auto px-6 lg:px-20">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-bold text-foreground mb-4">
            Nos Valeurs
          </h2>
          <p class="text-xl text-muted-foreground">
            Les principes qui guident notre travail au quotidien
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="value in values" :key="value.title" class="bg-gradient-to-br from-muted/20 to-white rounded-2xl p-8 hover:shadow-xl transition-shadow">
            <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mb-6">
              <component :is="value.icon" class="w-8 h-8 text-primary" />
            </div>
            <h3 class="text-2xl font-bold mb-4">{{ value.title }}</h3>
            <p class="text-muted-foreground">{{ value.description }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Team Section -->
    <section class="py-20 bg-gray-50">
      <div class="max-w-[1200px] mx-auto px-6 lg:px-20">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-bold text-foreground mb-4">
            Notre Équipe
          </h2>
          <p class="text-xl text-muted-foreground">
            Des experts passionnés par l'immobilier
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div v-for="member in team" :key="member.name" class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-shadow">
            <div class="h-48 bg-gradient-to-br from-primary/20 to-primary/10 flex items-center justify-center">
              <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center shadow-lg">
                <User class="w-16 h-16 text-primary" />
              </div>
            </div>
            <div class="p-6 text-center">
              <h3 class="text-xl font-bold mb-1">{{ member.name }}</h3>
              <p class="text-primary font-semibold mb-3">{{ member.role }}</p>
              <p class="text-sm text-muted-foreground">{{ member.bio }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact CTA -->
    <section class="py-20 bg-white">
      <div class="max-w-[1200px] mx-auto px-6 lg:px-20">
        <div class="bg-gradient-to-br from-primary to-primary/80 rounded-3xl p-12 lg:p-16 text-center text-white">
          <h2 class="text-4xl font-bold mb-6">Prêt à Commencer?</h2>
          <p class="text-xl mb-8 opacity-90">
            Rejoignez des milliers de Tunisiens qui ont trouvé leur logement idéal sur Neuf.tn
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <NuxtLink to="/search">
              <Button size="lg" class="bg-white text-primary hover:bg-gray-100 px-8">
                <Search class="w-5 h-5 mr-2" />
                Découvrir les projets
              </Button>
            </NuxtLink>
            <NuxtLink to="/dashboard" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all h-10 px-8 border-2 border-white text-white hover:bg-white hover:text-primary">
              <Building class="w-5 h-5" />
              Annoncer un projet
            </NuxtLink>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Info -->
    <section class="py-16 bg-gray-50">
      <div class="max-w-[1200px] mx-auto px-6 lg:px-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center p-6">
            <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
              <Mail class="w-8 h-8 text-primary" />
            </div>
            <h3 class="font-bold mb-2">Email</h3>
            <p class="text-muted-foreground">contact@neuf.tn</p>
          </div>

          <div class="text-center p-6">
            <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
              <Phone class="w-8 h-8 text-primary" />
            </div>
            <h3 class="font-bold mb-2">Téléphone</h3>
            <p class="text-muted-foreground">+216 71 123 456</p>
          </div>

          <div class="text-center p-6">
            <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
              <MapPin class="w-8 h-8 text-primary" />
            </div>
            <h3 class="font-bold mb-2">Adresse</h3>
            <p class="text-muted-foreground">Les Berges du Lac, Tunis</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<script setup lang="ts">
import { Menu, User, Zap, Target, Heart, Shield, Users, Lightbulb, Search, Building, Mail, Phone, MapPin } from 'lucide-vue-next'
import Button from '~/components/ui/Button.vue'
import Badge from '~/components/ui/Badge.vue'
import Footer from '~/components/Footer.vue'

const milestones = [
  {
    year: '2025',
    title: 'Lancement de Neuf.tn',
    description: 'Début de l\'aventure avec 5 promoteurs partenaires et 50 projets référencés.'
  },
  {
    year: '2026',
    title: 'Expansion Nationale',
    description: 'Couverture de toutes les régions de Tunisie avec plus de 200 projets.'
  },
  {
    year: '2027',
    title: 'Innovation Digitale',
    description: 'Lancement de visites virtuelles 3D et de l\'application mobile.'
  },
  {
    year: '2028',
    title: 'Partenariats Bancaires',
    description: 'Accords avec les principales banques pour faciliter le financement.'
  },
  {
    year: '2029',
    title: 'Leader du Marché',
    description: 'Plus de 50 promoteurs partenaires et 10 000 acheteurs satisfaits.'
  },
  {
    year: '2030',
    title: 'Nouvelle Ère',
    description: 'Intelligence artificielle pour recommandations personnalisées.'
  }
]

const values = [
  {
    icon: Target,
    title: 'Excellence',
    description: 'Nous visons l\'excellence dans chaque aspect de notre service pour offrir la meilleure expérience.'
  },
  {
    icon: Heart,
    title: 'Passion',
    description: 'Notre passion pour l\'immobilier nous pousse à innover constamment pour vous servir mieux.'
  },
  {
    icon: Shield,
    title: 'Confiance',
    description: 'La confiance est au cœur de nos relations avec nos clients et partenaires.'
  },
  {
    icon: Users,
    title: 'Collaboration',
    description: 'Nous travaillons en étroite collaboration avec tous nos partenaires pour votre réussite.'
  },
  {
    icon: Lightbulb,
    title: 'Innovation',
    description: 'L\'innovation technologique pour simplifier votre recherche immobilière.'
  },
  {
    icon: Zap,
    title: 'Réactivité',
    description: 'Une équipe réactive et à votre écoute pour répondre rapidement à vos besoins.'
  }
]

const team = [
  {
    name: 'Mohamed Ben Ali',
    role: 'CEO & Fondateur',
    bio: '15 ans d\'expérience dans l\'immobilier et la tech. Passionné par l\'innovation.'
  },
  {
    name: 'Leila Trabelsi',
    role: 'Directrice Marketing',
    bio: 'Experte en marketing digital avec une vision stratégique pour la croissance.'
  },
  {
    name: 'Karim Jebali',
    role: 'CTO',
    bio: 'Architecte technique avec 12 ans d\'expérience dans les plateformes web.'
  }
]
</script>
