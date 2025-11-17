<template>
    <div>
        <NuxtPage />
        <Toast />
        <LoadingOverlay />
    </div>
</template>

<script setup lang="ts">
import Toast from "~/components/ui/Toast.vue";
import LoadingOverlay from "~/components/ui/LoadingOverlay.vue";

useHead({
    htmlAttrs: {
        lang: "fr",
    },
});
</script>
