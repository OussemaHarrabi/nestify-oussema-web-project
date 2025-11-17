<template>
  <footer class="bg-muted border-t border-border">
    <div class="max-w-[1760px] mx-auto px-6 lg:px-20 py-16 lg:py-20">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
        <!-- Logo et description -->
        <div class="lg:col-span-1">
          <div class="flex items-center space-x-3 mb-6">
            <div class="bg-primary rounded-lg p-2">
              <div class="w-7 h-7 bg-white rounded-sm flex items-center justify-center">
                <span class="text-primary text-sm font-bold">N</span>
              </div>
            </div>
            <span class="text-2xl font-semibold text-foreground">neuf.tn</span>
          </div>
          <p class="text-muted-foreground mb-6 text-lg leading-relaxed">
            La plateforme de référence pour l'immobilier neuf en Tunisie.
            Trouvez votre nouveau chez-vous parmi nos projets sélectionnés.
          </p>
          <div class="flex space-x-4">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-primary hover:text-white cursor-pointer transition-all duration-300">
              <Facebook class="w-5 h-5" />
            </div>
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-primary hover:text-white cursor-pointer transition-all duration-300">
              <Instagram class="w-5 h-5" />
            </div>
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-primary hover:text-white cursor-pointer transition-all duration-300">
              <Linkedin class="w-5 h-5" />
            </div>
          </div>
        </div>

        <!-- Navigation -->
        <div>
          <h3 class="font-semibold mb-6 text-foreground text-lg">Découvrir</h3>
          <ul class="space-y-3 text-muted-foreground">
            <li><a href="#" class="hover:text-foreground transition-colors">Tous les projets</a></li>
            <li><a href="#" class="hover:text-foreground transition-colors">Appartements</a></li>
            <li><a href="#" class="hover:text-foreground transition-colors">Villas</a></li>
            <li><a href="#" class="hover:text-foreground transition-colors">Projets de luxe</a></li>
            <li><a href="#" class="hover:text-foreground transition-colors">Investissement</a></li>
          </ul>
        </div>

        <!-- Support -->
        <div>
          <h3 class="font-semibold mb-6 text-foreground text-lg">Support</h3>
          <ul class="space-y-3 text-muted-foreground">
            <li><a href="#" class="hover:text-foreground transition-colors">Centre d'aide</a></li>
            <li><a href="#" class="hover:text-foreground transition-colors">Guide d'achat</a></li>
            <li><a href="#" class="hover:text-foreground transition-colors">Financement</a></li>
            <li><a href="#" class="hover:text-foreground transition-colors">Conseils juridiques</a></li>
            <li><a href="#" class="hover:text-foreground transition-colors">FAQ</a></li>
          </ul>
        </div>

        <!-- Contact -->
        <div>
          <h3 class="font-semibold mb-6 text-foreground text-lg">Contact</h3>
          <div class="space-y-4 text-muted-foreground">
            <div class="flex items-center space-x-3">
              <Phone class="w-5 h-5" />
              <span>+216 70 123 456</span>
            </div>
            <div class="flex items-center space-x-3">
              <Mail class="w-5 h-5" />
              <span>contact@neuf.tn</span>
            </div>
            <div class="flex items-center space-x-3">
              <MapPin class="w-5 h-5" />
              <span>Tunis, Tunisie</span>
            </div>
          </div>
        </div>
      </div>

      <div class="border-t border-border mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
        <p class="text-muted-foreground">
          © 2024 neuf.tn. Tous droits réservés.
        </p>
        <div class="flex space-x-8 mt-4 md:mt-0">
          <a href="#" class="text-muted-foreground hover:text-foreground transition-colors">Mentions légales</a>
          <a href="#" class="text-muted-foreground hover:text-foreground transition-colors">Confidentialité</a>
          <a href="#" class="text-muted-foreground hover:text-foreground transition-colors">Conditions</a>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
import { Facebook, Instagram, Linkedin, Mail, Phone, MapPin } from 'lucide-vue-next'
</script>
