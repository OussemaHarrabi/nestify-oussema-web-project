<template>
  <Teleport to="body">
    <TransitionGroup
      name="toast"
      tag="div"
      class="fixed top-4 right-4 z-[9999] flex flex-col gap-2 pointer-events-none"
    >
      <div
        v-for="notification in notifications"
        :key="notification.id"
        :class="[
          'pointer-events-auto max-w-sm w-full bg-white rounded-xl shadow-lg border border-border overflow-hidden',
          'transform transition-all duration-300 ease-in-out'
        ]"
      >
        <div class="p-4">
          <div class="flex items-start gap-3">
            <!-- Icon -->
            <div
              :class="[
                'flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center',
                iconBgClasses[notification.type]
              ]"
            >
              <component
                :is="icons[notification.type]"
                :class="['w-5 h-5', iconClasses[notification.type]]"
              />
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
              <p :class="['font-semibold text-sm', titleClasses[notification.type]]">
                {{ notification.title }}
              </p>
              <p v-if="notification.message" class="text-sm text-muted-foreground mt-1">
                {{ notification.message }}
              </p>
            </div>

            <!-- Close button -->
            <button
              @click="removeNotification(notification.id)"
              class="flex-shrink-0 text-muted-foreground hover:text-foreground transition-colors"
            >
              <X class="w-4 h-4" />
            </button>
          </div>
        </div>

        <!-- Progress bar -->
        <div
          v-if="notification.duration && notification.duration > 0"
          class="h-1 bg-muted overflow-hidden"
        >
          <div
            :class="[
              'h-full',
              progressClasses[notification.type],
              'animate-progress'
            ]"
            :style="{ animationDuration: `${notification.duration}ms` }"
          ></div>
        </div>
      </div>
    </TransitionGroup>
  </Teleport>
</template>

<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { useUIStore } from '~/stores/ui'
import { CheckCircle, XCircle, AlertTriangle, Info, X } from 'lucide-vue-next'

const uiStore = useUIStore()
const { notifications } = storeToRefs(uiStore)
const { removeNotification } = uiStore

const icons = {
  success: CheckCircle,
  error: XCircle,
  warning: AlertTriangle,
  info: Info
}

const iconBgClasses = {
  success: 'bg-green-100',
  error: 'bg-red-100',
  warning: 'bg-yellow-100',
  info: 'bg-blue-100'
}

const iconClasses = {
  success: 'text-green-600',
  error: 'text-red-600',
  warning: 'text-yellow-600',
  info: 'text-blue-600'
}

const titleClasses = {
  success: 'text-green-900',
  error: 'text-red-900',
  warning: 'text-yellow-900',
  info: 'text-blue-900'
}

const progressClasses = {
  success: 'bg-green-500',
  error: 'bg-red-500',
  warning: 'bg-yellow-500',
  info: 'bg-blue-500'
}
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.toast-leave-to {
  opacity: 0;
  transform: translateX(100%) scale(0.95);
}

@keyframes progress {
  from {
    width: 100%;
  }
  to {
    width: 0%;
  }
}

.animate-progress {
  animation: progress linear forwards;
}
</style>
