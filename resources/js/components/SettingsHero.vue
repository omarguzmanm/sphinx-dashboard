<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { BadgeCheck, Palette, ShieldCheck, UserRound } from '@lucide/vue';
import type { LucideIcon } from '@lucide/vue';
import { computed } from 'vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Card } from '@/components/ui/card';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { useInitials } from '@/composables/useInitials';
import { toUrl } from '@/lib/utils';
import { edit as editAppearance } from '@/routes/appearance';
import { edit as editProfile } from '@/routes/profile';
import { edit as editSecurity } from '@/routes/security';
import type { NavItem } from '@/types';

type SettingsTab = NavItem & { icon: LucideIcon };

const tabs: SettingsTab[] = [
    { title: 'Profile', href: editProfile(), icon: UserRound },
    { title: 'Security', href: editSecurity(), icon: ShieldCheck },
    { title: 'Appearance', href: editAppearance(), icon: Palette },
];

const { isCurrentOrParentUrl } = useCurrentUrl();
const { getInitials } = useInitials();

const user = computed(() => usePage().props.auth.user);

const memberSince = computed(() => {
    const date = new Date(user.value.created_at);

    return Number.isNaN(date.getTime())
        ? null
        : new Intl.DateTimeFormat(undefined, {
              month: 'long',
              year: 'numeric',
          }).format(date);
});
</script>

<template>
    <Card class="gap-0 overflow-hidden p-0">
        <!-- Cover, built from the theme palette so it follows a custom
             primary colour instead of shipping an image asset. -->
        <div
            class="relative h-36 sm:h-44"
            :style="{
                backgroundImage: [
                    'radial-gradient(at 10% 20%, color-mix(in oklab, var(--chart-3) 45%, var(--card)) 0px, transparent 60%)',
                    'radial-gradient(at 75% 10%, color-mix(in oklab, var(--chart-5) 40%, var(--card)) 0px, transparent 55%)',
                    'radial-gradient(at 60% 95%, color-mix(in oklab, var(--chart-4) 40%, var(--card)) 0px, transparent 60%)',
                    'radial-gradient(at 95% 70%, color-mix(in oklab, var(--chart-3) 35%, var(--card)) 0px, transparent 50%)',
                ].join(','),
                backgroundColor:
                    'color-mix(in oklab, var(--chart-5) 22%, var(--card))',
            }"
        ></div>

        <div class="px-6 pb-5">
            <div
                class="-mt-12 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"
            >
                <div class="flex items-end gap-4">
                    <Avatar
                        class="size-24 shrink-0 ring-4 ring-card"
                        data-test="settings-avatar"
                    >
                        <AvatarImage
                            v-if="user.avatar"
                            :src="user.avatar"
                            :alt="user.name"
                        />
                        <AvatarFallback class="bg-muted text-2xl font-semibold">
                            {{ getInitials(user.name) }}
                        </AvatarFallback>
                    </Avatar>

                    <div class="min-w-0 pb-1">
                        <div class="flex items-center gap-2">
                            <h2 class="truncate text-xl font-semibold">
                                {{ user.name }}
                            </h2>
                            <BadgeCheck
                                v-if="user.email_verified_at"
                                class="size-5 shrink-0 text-[var(--chart-3)]"
                                aria-label="Verified email"
                            />
                        </div>
                        <p class="truncate text-sm text-muted-foreground">
                            {{ user.email }}
                        </p>
                    </div>
                </div>

                <Badge
                    v-if="memberSince"
                    variant="secondary"
                    class="w-fit rounded-md px-2 py-1 text-xs font-medium"
                >
                    Member since {{ memberSince }}
                </Badge>
            </div>
        </div>

        <nav
            class="flex gap-1 overflow-x-auto border-t px-4 sm:justify-end sm:px-6"
            aria-label="Settings"
        >
            <Link
                v-for="tab in tabs"
                :key="toUrl(tab.href)"
                :href="tab.href"
                class="-mb-px flex shrink-0 items-center gap-2 border-b-2 px-3 py-3 text-sm font-medium transition-colors focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                :class="
                    isCurrentOrParentUrl(tab.href)
                        ? 'border-foreground text-foreground'
                        : 'border-transparent text-muted-foreground hover:text-foreground'
                "
                :aria-current="
                    isCurrentOrParentUrl(tab.href) ? 'page' : undefined
                "
            >
                <component :is="tab.icon" class="size-4" />
                {{ tab.title }}
            </Link>
        </nav>
    </Card>
</template>
