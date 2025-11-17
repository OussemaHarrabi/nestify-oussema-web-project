<template>
  <section class="py-20 lg:py-32 bg-white">
    <div class="max-w-[1760px] mx-auto px-6 lg:px-20">
      <div class="text-center mb-20">
        <h2 class="text-4xl lg:text-5xl mb-8 text-foreground">
          Les avantages de l'immobilier neuf
        </h2>
        <p class="text-xl text-muted-foreground max-w-3xl mx-auto leading-relaxed">
          L'immobilier neuf offre bien plus qu'un simple logement : il garantit confort, modernité
          et sérénité. Découvrez les principaux atouts qui font du neuf un choix sûr et durable.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 lg:gap-16">
        <div
          v-for="(benefit, index) in benefits"
          :key="index"
          class="text-center group"
        >
          <div class="inline-flex items-center justify-center w-20 h-20 bg-primary/10 rounded-full mb-8 group-hover:bg-primary/20 transition-all duration-300 group-hover:scale-110">
            <component :is="benefit.icon" class="w-10 h-10 text-primary" />
          </div>
          <h3 class="text-2xl mb-6 text-foreground font-semibold">
            {{ benefit.title }}
          </h3>
          <p class="text-muted-foreground leading-relaxed text-lg">
            {{ benefit.description }}
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { Shield, Award, Zap, TrendingUp, Calculator, Home } from 'lucide-vue-next'

const benefits = [
  {
    icon: Shield,
    title: "Sécurité garantie",
    description: "Tous nos projets sont sélectionnés et vérifiés. Promoteurs certifiés et projets conformes aux normes."
  },
  {
    icon: Award,
    title: "Qualité premium",
    description: "Matériaux de qualité, finitions soignées et architectures modernes pour votre confort."
  },
  {
    icon: Zap,
    title: "Économie d'énergie",
    description: "Les constructions récentes respectent les normes énergétiques et offrent une meilleure performance, ce qui réduit vos factures."
  },
  {
    icon: TrendingUp,
    title: "Investissement rentable",
    description: "Projets situés dans des zones en développement avec un fort potentiel de valorisation."
  },
  {
    icon: Calculator,
    title: "Des frais de notaire réduits",
    description: "En achetant dans le neuf, les frais de notaire sont réduits, ce qui rend votre acquisition plus avantageuse et économique dès le départ."
  },
  {
    icon: Home,
    title: "Nouvelles constructions",
    description: "Bénéficiez des dernières normes de construction et des équipements les plus modernes."
  }
]
</script>
