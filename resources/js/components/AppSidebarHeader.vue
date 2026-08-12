<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Bell, Mail, Moon, Search, ShoppingCart, Sun } from '@lucide/vue';
import { computed } from 'vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Separator } from '@/components/ui/separator';
import { SidebarTrigger } from '@/components/ui/sidebar';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { useAppearance } from '@/composables/useAppearance';
import { useInitials } from '@/composables/useInitials';
import type { BreadcrumbItem } from '@/types';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const page = usePage();
const user = computed(() => page.props.auth.user);

const { getInitials } = useInitials();
const { resolvedAppearance, updateAppearance } = useAppearance();

const toggleAppearance = () => {
    updateAppearance(resolvedAppearance.value === 'dark' ? 'light' : 'dark');
};
</script>

<template>
    <header
        class="sticky top-0 z-30 flex h-16 shrink-0 items-center gap-2 bg-background px-4 md:px-6"
    >
        <SidebarTrigger class="-ml-1 text-muted-foreground" />

        <Separator orientation="vertical" class="mx-2 hidden h-6! sm:block" />

        <div class="relative hidden w-full max-w-xs sm:block">
            <Search
                class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground"
            />
            <Input
                type="search"
                placeholder="Search...."
                aria-label="Search"
                class="h-10 rounded-full bg-background pl-9 shadow-none"
            />
        </div>

        <Breadcrumbs
            v-if="breadcrumbs.length > 0"
            class="ml-2 hidden lg:flex"
            :breadcrumbs="breadcrumbs"
        />

        <div class="ml-auto flex items-center gap-1 sm:gap-2">
            <Button
                variant="ghost"
                size="icon"
                class="rounded-full text-muted-foreground"
                :aria-label="`Switch to ${resolvedAppearance === 'dark' ? 'light' : 'dark'} mode`"
                @click="toggleAppearance"
            >
                <Sun v-if="resolvedAppearance === 'dark'" class="size-5" />
                <Moon v-else class="size-5" />
            </Button>

            <Button
                variant="ghost"
                size="icon"
                class="hidden rounded-full sm:inline-flex"
                aria-label="Change language"
            >
                <svg
                    class="size-5 rounded-xs"
                    viewBox="0 0 60 30"
                    aria-hidden="true"
                >
                    <clipPath id="header-flag-clip">
                        <path d="M0 0v30h60V0z" />
                    </clipPath>
                    <g clip-path="url(#header-flag-clip)">
                        <path d="M0 0v30h60V0z" fill="#012169" />
                        <path
                            d="M0 0l60 30m0-30L0 30"
                            stroke="#fff"
                            stroke-width="6"
                        />
                        <path
                            d="M0 0l60 30m0-30L0 30"
                            stroke="#C8102E"
                            stroke-width="4"
                        />
                        <path
                            d="M30 0v30M0 15h60"
                            stroke="#fff"
                            stroke-width="10"
                        />
                        <path
                            d="M30 0v30M0 15h60"
                            stroke="#C8102E"
                            stroke-width="6"
                        />
                    </g>
                </svg>
            </Button>

            <Button
                variant="ghost"
                size="icon"
                class="relative rounded-full text-muted-foreground"
                aria-label="Cart, 11 items"
            >
                <ShoppingCart class="size-5" />
                <span
                    class="absolute top-0 right-0 flex size-4 items-center justify-center rounded-full bg-destructive text-[10px] leading-none font-medium text-destructive-foreground"
                >
                    11
                </span>
            </Button>

            <Button
                variant="ghost"
                size="icon"
                class="relative rounded-full text-muted-foreground"
                aria-label="Notifications, unread"
            >
                <Bell class="size-5" />
                <span
                    class="absolute top-1.5 right-1.5 size-2 rounded-full bg-destructive"
                />
            </Button>

            <Button
                variant="ghost"
                size="icon"
                class="hidden rounded-full text-muted-foreground sm:inline-flex"
                aria-label="Messages"
            >
                <Mail class="size-5" />
            </Button>

            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <button
                        type="button"
                        class="ml-1 rounded-full focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                        data-test="header-user-menu"
                    >
                        <Avatar class="size-9">
                            <AvatarImage
                                v-if="user.avatar"
                                :src="user.avatar"
                                :alt="user.name"
                            />
                            <AvatarFallback class="text-xs">
                                {{ getInitials(user.name) }}
                            </AvatarFallback>
                        </Avatar>
                    </button>
                </DropdownMenuTrigger>
                <DropdownMenuContent class="w-56 rounded-lg" align="end">
                    <UserMenuContent :user="user" />
                </DropdownMenuContent>
            </DropdownMenu>
        </div>
    </header>
</template>
