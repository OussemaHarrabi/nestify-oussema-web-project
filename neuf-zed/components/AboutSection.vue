<template>
  <section class="py-20 bg-white">
    <div class="max-w-[1760px] mx-auto px-6 lg:px-20">
      <div class="grid lg:grid-cols-2 gap-16 items-center">
        <div>
          <!-- Badge Notre mission -->
          <div class="inline-flex items-center px-4 py-2 bg-primary/10 rounded-full mb-8">
            <span class="text-primary text-sm font-medium">Notre mission</span>
          </div>

          <!-- Titre principal -->
          <h2 class="text-4xl lg:text-5xl mb-6 text-foreground">
            Pourquoi choisir <span class="text-primary">neuf.tn</span> ?
          </h2>

          <!-- Description principale -->
          <p class="text-lg text-muted-foreground leading-relaxed mb-8">
            Chez neuf.tn, nous simplifions votre recherche de logement neuf en Tunisie. Notre
            plateforme réunit les meilleurs projets immobiliers, proposés par des promoteurs
            vérifiés. Vous gagnez du temps, évitez les mauvaises surprises et trouvez plus
            facilement le bien qui correspond à vos besoins.
          </p>

          <!-- Liste des avantages -->
          <div class="space-y-6 mb-8">
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center flex-shrink-0">
                <Shield class="w-6 h-6 text-primary" />
              </div>
              <div>
                <h4 class="text-lg mb-2 text-foreground font-medium">Sélection premium</h4>
                <p class="text-muted-foreground">
                  Tous les projets sont proposés par des promoteurs certifiés et conformes aux normes en vigueur.
                </p>
              </div>
            </div>

            <div class="flex items-start gap-4">
              <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center flex-shrink-0">
                <TrendingUp class="w-6 h-6 text-primary" />
              </div>
              <div>
                <h4 class="text-lg mb-2 text-foreground font-medium">Expérience fluide</h4>
                <p class="text-muted-foreground">
                  Une recherche intuitive pour trouver le bien qui correspond à vos exigences.
                </p>
              </div>
            </div>

            <div class="flex items-start gap-4">
              <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center flex-shrink-0">
                <Eye class="w-6 h-6 text-primary" />
              </div>
              <div>
                <h4 class="text-lg mb-2 text-foreground font-medium">Transparence totale</h4>
                <p class="text-muted-foreground">
                  Des informations fiables et mises à jour pour une décision en toute sérénité.
                </p>
              </div>
            </div>
          </div>

          <!-- Bouton CTA -->
          <NuxtLink to="/search" class="inline-flex items-center gap-2 bg-primary text-primary-foreground px-6 py-3 rounded-full hover:bg-primary/90 transition-colors">
            <span>Trouvez un projet neuf</span>
            <ArrowRight class="w-4 h-4" />
          </NuxtLink>
        </div>

        <!-- Image à droite -->
        <div class="rounded-3xl overflow-hidden shadow-lg">
          <img
            src="https://images.unsplash.com/photo-1751054566137-3cd270219647?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtb2Rlcm4lMjBhcGFydG1lbnQlMjBidWlsZGluZyUyMGFyY2hpdGVjdHVyZSUyMHR1bmlzaWF8ZW58MXx8fHwxNzU5ODUxNzY2fDA&ixlib=rb-4.1.0&q=80&w=1080"
            alt="Architecture moderne neuf.tn"
            class="w-full h-[500px] object-cover"
          />
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { Shield, TrendingUp, Eye, ArrowRight } from 'lucide-vue-next'
</script>
