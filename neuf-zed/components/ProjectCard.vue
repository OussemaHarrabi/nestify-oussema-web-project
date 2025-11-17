<template>
    <div
        :class="[
            'bg-white rounded-xl overflow-hidden group cursor-pointer transition-all duration-300 border border-border',
            isSelected
                ? 'ring-2 ring-primary shadow-xl'
                : 'hover:shadow-xl',
        ]"
        @click="$emit('click', project)"
    >
        <div class="flex h-48">
            <!-- Image - Left Side (Half width) -->
            <div class="relative w-1/2 overflow-hidden flex-shrink-0">
                <img
                    :src="project.image"
                    :alt="project.title"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />

                <!-- Status Badge -->
                <div class="absolute top-3 left-3">
                    <div
                        class="bg-white/95 backdrop-blur-sm rounded-full px-3 py-1.5 shadow-lg"
                    >
                        <span class="text-xs font-medium text-foreground">{{
                            project.status
                        }}</span>
                    </div>
                </div>

                <!-- Developer Badge - Bottom Right of Image -->
                <div class="absolute bottom-3 right-3">
                    <div
                        class="bg-white/95 backdrop-blur-sm rounded-lg px-2 py-1.5 shadow-lg hover:bg-white transition-colors"
                        @click.stop="$emit('developer-click', project)"
                    >
                        <div class="flex items-center gap-1.5">
                            <div
                                class="w-5 h-5 bg-foreground rounded-md flex items-center justify-center"
                            >
                                <span class="text-primary text-[9px] font-bold">
                                    {{ project.developerLogo }}
                                </span>
                            </div>
                            <span class="text-[10px] font-medium text-foreground">{{
                                project.developer
                            }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content - Right Side (Half width) -->
            <div class="w-1/2 p-4 flex flex-col">
                <h3 class="text-lg font-semibold mb-1 text-foreground line-clamp-1">
                    {{ project.title }}
                </h3>

                <p class="text-sm text-muted-foreground mb-2 line-clamp-1">
                    {{ project.type }}
                </p>

                <div class="flex items-center text-muted-foreground mb-3">
                    <MapPin class="w-4 h-4 mr-1 flex-shrink-0" />
                    <span class="text-sm line-clamp-1">{{ project.location }}</span>
                </div>

                <div class="mt-auto">
                    <div>
                        <span class="text-muted-foreground text-xs">À partir de </span>
                        <span class="text-primary font-semibold text-base"
                            >{{ project.price }} TND</span
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { MapPin } from "lucide-vue-next";

interface Props {
    project: {
        id: number;
        title: string;
        location: string;
        image: string;
        price: string;
        type: string;
        status: string;
        developer: string;
        developerLogo: string;
    };
    isSelected?: boolean;
}

defineProps<Props>();

defineEmits<{
    click: [project: any];
    "developer-click": [project: any];
}>();
</script>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
