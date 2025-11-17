<template>
  <section class="relative bg-white py-20 lg:py-32">
    <!-- Background Image -->
    <div class="absolute inset-0">
      <img 
        src="~/assets/images/6030773312612846140.jpg" 
        alt="Background" 
        class="w-full h-full object-cover"
      />
    </div>

    <div class="relative max-w-[1760px] mx-auto px-6 lg:px-20">
      <div class="text-center mb-16">
        <h1 class="text-5xl lg:text-7xl mb-8 text-white max-w-5xl mx-auto leading-tight font-bold drop-shadow-[0_4px_12px_rgba(0,0,0,0.8)]">
          Trouvez votre projet neuf idéal
        </h1>
        <p class="text-xl text-white max-w-3xl mx-auto leading-relaxed drop-shadow-md">
          Découvrez les meilleurs projets immobiliers neufs en Tunisie. Appartements,
          villas et résidences de standing.
        </p>
      </div>

      <!-- Barre de recherche -->
      <div class="max-w-4xl mx-auto relative z-10">
        <SearchBar @search="handleSearch" />
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { Search, MapPin, Home, DollarSign } from 'lucide-vue-next'
import Button from './ui/Button.vue'
import Badge from './ui/Badge.vue'
import SearchBar from './SearchBar.vue'

const handleSearch = (filters: any) => {
  navigateTo({
    path: '/search',
    query: {
      location: filters.location,
      type: filters.type,
      minPrice: filters.minPrice,
      maxPrice: filters.maxPrice
    }
  })
}
</script>
