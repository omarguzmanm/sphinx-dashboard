<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import NavMain from '@/components/NavMain.vue';
import NavUpgradeCard from '@/components/NavUpgradeCard.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { useCustomizer } from '@/composables/useCustomizer';
import { navGroups } from '@/lib/navigation';
import { dashboard } from '@/routes';

const { settings } = useCustomizer();

const side = computed(() =>
    settings.value.direction === 'rtl' ? 'right' : 'left',
);
</script>

<template>
    <Sidebar collapsible="icon" variant="inset" :side="side">
        <SidebarHeader class="px-2 py-4">
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton
                        size="lg"
                        as-child
                        class="hover:bg-transparent"
                    >
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent class="scrollbar-on-hover gap-0">
            <NavMain :groups="navGroups" />
        </SidebarContent>

        <SidebarFooter>
            <NavUpgradeCard />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
