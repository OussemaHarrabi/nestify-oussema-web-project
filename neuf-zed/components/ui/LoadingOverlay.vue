<template>
  <Teleport to="body">
    <Transition name="fade">
      <div
        v-if="isLoading"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] flex items-center justify-center"
      >
        <div class="bg-white rounded-2xl p-8 shadow-2xl max-w-sm w-full mx-4">
          <div class="flex flex-col items-center">
            <div class="relative">
              <div class="w-16 h-16 border-4 border-primary/20 rounded-full"></div>
              <div class="absolute inset-0 w-16 h-16 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
            </div>

            <h3 v-if="loadingMessage" class="mt-6 text-lg font-semibold text-foreground text-center">
              {{ loadingMessage }}
            </h3>

            <p v-else class="mt-6 text-lg font-semibold text-foreground">
              Chargement...
            </p>

            <div class="mt-4 w-full bg-muted rounded-full h-2 overflow-hidden">
              <div class="h-full bg-primary animate-progress-bar"></div>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { useUIStore } from '~/stores/ui'

const uiStore = useUIStore()
const { isLoading, loadingMessage } = storeToRefs(uiStore)
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

@keyframes progress-bar {
  0% {
    width: 0%;
    margin-left: 0%;
  }
  50% {
    width: 50%;
    margin-left: 25%;
  }
  100% {
    width: 0%;
    margin-left: 100%;
  }
}

.animate-progress-bar {
  animation: progress-bar 1.5s ease-in-out infinite;
}
</style>
