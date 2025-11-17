<template>
  <div class="min-h-screen bg-white">
    <Header />
    <main>
      <HeroSection @search-click="handleSearchClick" />
      <ProjectsSection
        @project-click="handleProjectClick"
        @developer-click="handleDeveloperClick"
      />
      <AboutSection />
      <BenefitsSection />
      <DevelopersCTA />
    </main>
    <Footer />
  </div>
</template>

<script setup lang="ts">
import Header from '~/components/Header.vue'
import HeroSection from '~/components/HeroSection.vue'
import ProjectsSection from '~/components/ProjectsSection.vue'
import AboutSection from '~/components/AboutSection.vue'
import BenefitsSection from '~/components/BenefitsSection.vue'
import DevelopersCTA from '~/components/DevelopersCTA.vue'
import Footer from '~/components/Footer.vue'
import { useProjectsStore } from '~/stores/projects'

// Initialize projects store and fetch data
const projectsStore = useProjectsStore()

// Fetch projects on page load
onMounted(async () => {
  if (projectsStore.projects.length === 0) {
    await projectsStore.fetchProjects()
  }
})

// Navigation handlers
const handleProjectClick = (project: any) => {
  navigateTo('/project/' + project.id)
}

const handleDeveloperClick = (developer: any) => {
  navigateTo('/developer/' + developer.name)
}

const handleSearchClick = () => {
  navigateTo('/search')
}
</script>
