<template>
    <component
        :is="as"
        :class="cn(buttonVariants({ variant, size }), props.class)"
        v-bind="$attrs"
    >
        <slot />
    </component>
</template>

<script setup lang="ts">
import { cva } from "class-variance-authority";
import { cn } from "~/utils/cn";

const buttonVariants = cva(
    "inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]",
    {
        variants: {
            variant: {
                default:
                    "bg-primary text-primary-foreground hover:bg-primary/90",
                destructive:
                    "bg-destructive text-white hover:bg-destructive/90",
                outline:
                    "border bg-background text-foreground hover:bg-accent hover:text-accent-foreground",
                secondary:
                    "bg-secondary text-secondary-foreground hover:bg-secondary/80",
                ghost: "hover:bg-accent hover:text-accent-foreground",
                link: "text-primary underline-offset-4 hover:underline",
            },
            size: {
                default: "h-9 px-4 py-2",
                sm: "h-8 rounded-md gap-1.5 px-3",
                lg: "h-10 rounded-md px-6",
                icon: "size-9 rounded-md",
            },
        },
        defaultVariants: {
            variant: "default",
            size: "default",
        },
    },
);

interface Props {
    as?: string;
    variant?:
        | "default"
        | "destructive"
        | "outline"
        | "secondary"
        | "ghost"
        | "link";
    size?: "default" | "sm" | "lg" | "icon";
    class?: string;
}

const props = withDefaults(defineProps<Props>(), {
    as: "button",
    variant: "default",
    size: "default",
});
</script>
