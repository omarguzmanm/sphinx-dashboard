<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Customizer from '@/components/Customizer.vue';
import { hydrateCustomizer, useCustomizer } from '@/composables/useCustomizer';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';

const { breadcrumbs = [] } = defineProps<{
    breadcrumbs?: BreadcrumbItem[];
}>();

// On the server there is no localStorage, so seed from the shared cookie value
// to render the same layout the client will hydrate. On the client the state
// has already been restored by `initializeCustomizer` in app.ts.
if (typeof window === 'undefined') {
    hydrateCustomizer(usePage().props.customizer);
}

const { settings } = useCustomizer();

const layout = computed(() =>
    settings.value.layout === 'horizontal' ? AppHeaderLayout : AppSidebarLayout,
);
</script>

<template>
    <component :is="layout" :breadcrumbs="breadcrumbs">
        <slot />
    </component>

    <!-- Kept outside the layout switch so changing Layout Type does not
         unmount the panel while it is open. -->
    <Customizer />
</template>
