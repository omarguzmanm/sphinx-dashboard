<script setup lang="ts">
import { Button } from '@/components/ui/button';
import UpgradeIllustration from '@/components/UpgradeIllustration.vue';
</script>

<template>
    <div
        class="mx-1 mb-1 flex flex-col items-center gap-1 rounded-xl bg-sidebar-accent/70 px-4 py-5 text-center group-data-[collapsible=icon]:hidden"
    >
        <UpgradeIllustration class="h-24 w-auto" />

        <p class="text-sm font-semibold text-sidebar-foreground">
            Grab Pro Now
        </p>
        <p class="text-xs text-muted-foreground">Customize your admin</p>

        <Button size="sm" class="mt-3 w-full rounded-md"> Get Premium </Button>
    </div>
</template>
