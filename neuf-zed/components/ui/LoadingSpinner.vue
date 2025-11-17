<template>
    <div :class="cn('flex items-center justify-center', props.class)">
        <div
            :class="[
                'animate-spin rounded-full border-b-2',
                sizeClasses[size],
                colorClasses[variant],
            ]"
        ></div>
        <span v-if="text" :class="['ml-3', textSizeClasses[size]]">{{
            text
        }}</span>
    </div>
</template>

<script setup lang="ts">
import { cn } from "~/utils/cn";

interface Props {
    size?: "sm" | "md" | "lg" | "xl";
    variant?: "primary" | "secondary" | "white";
    text?: string;
    class?: string;
}

const props = withDefaults(defineProps<Props>(), {
    size: "md",
    variant: "primary",
});

const sizeClasses = {
    sm: "w-4 h-4 border-2",
    md: "w-8 h-8 border-2",
    lg: "w-12 h-12 border-3",
    xl: "w-16 h-16 border-4",
};

const colorClasses = {
    primary: "border-primary",
    secondary: "border-muted-foreground",
    white: "border-white",
};

const textSizeClasses = {
    sm: "text-sm",
    md: "text-base",
    lg: "text-lg",
    xl: "text-xl",
};
</script>
