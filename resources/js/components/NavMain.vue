<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronRight } from '@lucide/vue';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { toUrl } from '@/lib/utils';
import type { NavGroup, NavItem } from '@/types';

defineProps<{
    groups: NavGroup[];
}>();

const { isCurrentUrl } = useCurrentUrl();

const hasActiveChild = (item: NavItem): boolean =>
    (item.items ?? []).some((child) => isCurrentUrl(child.href));

/**
 * Placeholder entries (`#`) must not trigger an Inertia visit.
 */
const linkFor = (item: NavItem) => (toUrl(item.href) === '#' ? 'a' : Link);
</script>

<template>
    <SidebarGroup
        v-for="group in groups"
        :key="group.title"
        class="px-3 py-0 group-data-[collapsible=icon]:px-2"
    >
        <SidebarGroupLabel
            class="mt-3 text-[11px] font-bold tracking-[0.08em] text-sidebar-foreground/60 uppercase group-data-[collapsible=icon]:-mt-8"
        >
            {{ group.title }}
        </SidebarGroupLabel>

        <SidebarMenu class="gap-1">
            <template v-for="item in group.items" :key="item.title">
                <Collapsible
                    v-if="item.items?.length"
                    as-child
                    :default-open="hasActiveChild(item)"
                    class="group/collapsible"
                >
                    <SidebarMenuItem>
                        <CollapsibleTrigger as-child>
                            <SidebarMenuButton
                                class="h-9 gap-3 rounded-lg px-3 transition-all duration-200 ease-in-out hover:translate-x-1 [&>svg]:size-5"
                                :tooltip="item.title"
                            >
                                <component :is="item.icon" />
                                <span>{{ item.title }}</span>
                                <ChevronRight
                                    class="ml-auto size-4! transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                                />
                            </SidebarMenuButton>
                        </CollapsibleTrigger>

                        <CollapsibleContent>
                            <SidebarMenuSub>
                                <SidebarMenuSubItem
                                    v-for="child in item.items"
                                    :key="child.title"
                                >
                                    <SidebarMenuSubButton
                                        as-child
                                        :is-active="isCurrentUrl(child.href)"
                                    >
                                        <component
                                            :is="linkFor(child)"
                                            :href="child.href"
                                        >
                                            <span>{{ child.title }}</span>
                                        </component>
                                    </SidebarMenuSubButton>
                                </SidebarMenuSubItem>
                            </SidebarMenuSub>
                        </CollapsibleContent>
                    </SidebarMenuItem>
                </Collapsible>

                <SidebarMenuItem v-else>
                    <SidebarMenuButton
                        as-child
                        class="h-9 gap-3 rounded-lg px-3 transition-all duration-200 ease-in-out hover:translate-x-1 data-[active=true]:bg-primary data-[active=true]:text-primary-foreground data-[active=true]:hover:bg-primary data-[active=true]:hover:text-primary-foreground [&>svg]:size-5"
                        :is-active="item.isActive ?? isCurrentUrl(item.href)"
                        :tooltip="item.title"
                    >
                        <component :is="linkFor(item)" :href="item.href">
                            <component :is="item.icon" />
                            <span>{{ item.title }}</span>
                        </component>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </template>
        </SidebarMenu>
    </SidebarGroup>
</template>
