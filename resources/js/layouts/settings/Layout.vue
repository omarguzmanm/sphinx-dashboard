<script setup lang="ts">
import SettingsHero from '@/components/SettingsHero.vue';
</script>

<template>
    <div class="flex flex-1 flex-col gap-6 p-4 md:p-6">
        <SettingsHero />

        <slot />
    </div>
</template>
