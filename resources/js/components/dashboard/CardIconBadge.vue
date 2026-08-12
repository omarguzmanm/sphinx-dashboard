<script setup lang="ts">
import type { LucideIcon } from '@lucide/vue';

type Props = {
    icon: LucideIcon;
};

defineProps<Props>();
</script>

<template>
    <span
        class="flex size-11 items-center justify-center rounded-full border border-neutral-200 text-foreground dark:border-neutral-700"
    >
        <component :is="icon" class="size-5" :stroke-width="2" />
    </span>
</template>
