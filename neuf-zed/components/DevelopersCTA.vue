<template>
  <section class="py-16 bg-gradient-to-br from-primary/5 via-white to-primary/10">
    <div class="max-w-[1760px] mx-auto px-6 lg:px-20">
      <div class="relative overflow-hidden rounded-3xl bg-white/80 backdrop-blur-sm border border-white/20 shadow-lg">
        <div class="absolute inset-0 bg-gradient-to-r from-primary/10 to-transparent opacity-50"></div>
        <div class="relative p-8 lg:p-12">
          <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
            <div class="flex items-center gap-6">
              <div class="w-16 h-16 bg-primary/15 rounded-2xl flex items-center justify-center flex-shrink-0">
                <Sparkles class="w-8 h-8 text-primary" />
              </div>
              <div>
                <h2 class="text-2xl lg:text-3xl font-medium text-foreground mb-2">
                  Promoteur immobilier ?
                </h2>
                <p class="text-muted-foreground max-w-md">
                  Rejoignez notre réseau de partenaires et boostez la visibilité de vos projets neufs
                </p>
              </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
              <Button variant="ghost" size="sm" class="rounded-full text-muted-foreground hover:text-foreground">
                En savoir plus
              </Button>
              <NuxtLink to="/login">
                <Button
                  size="lg"
                  class="rounded-full bg-primary hover:bg-primary/90 px-6"
                >
                  Rejoindre neuf.tn
                  <ArrowRight class="w-4 h-4 ml-2" />
                </Button>
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ArrowRight, Sparkles } from 'lucide-vue-next'
import Button from './ui/Button.vue'
</script>
