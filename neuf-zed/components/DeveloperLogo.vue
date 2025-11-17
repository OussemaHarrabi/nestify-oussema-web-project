<template>
  <div
    :class="cn('w-10 h-10 rounded-lg flex items-center justify-center', getColor(developer), props.class)"
  >
    <span class="text-white text-sm font-semibold">
      {{ getInitials(developer) }}
    </span>
  </div>
</template>

<script setup lang="ts">
import { cn } from '~/utils/cn'

interface Props {
  developer: string
  class?: string
}

const props = defineProps<Props>()

const getInitials = (name: string): string => {
  return name
    .split(' ')
    .map(word => word[0])
    .join('')
    .toUpperCase()
    .substring(0, 2)
}

const getColor = (name: string): string => {
  const colors = [
    'bg-blue-600',
    'bg-green-600',
    'bg-purple-600',
    'bg-orange-600',
    'bg-red-600',
    'bg-indigo-600'
  ]
  const index = name.length % colors.length
  return colors[index]
}
</script>
