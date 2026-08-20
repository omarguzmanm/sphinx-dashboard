<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import {
    FileText,
    House,
    Mail,
    Settings,
    Sparkles,
    UserRound,
    X,
} from '@lucide/vue';
import type { LucideIcon } from '@lucide/vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Sheet,
    SheetClose,
    SheetContent,
    SheetDescription,
    SheetTitle,
} from '@/components/ui/sheet';
import UpgradeIllustration from '@/components/UpgradeIllustration.vue';
import { useInitials } from '@/composables/useInitials';
import { toUrl } from '@/lib/utils';
import { logout, overview } from '@/routes';
import { edit as editProfile } from '@/routes/profile';
import { edit as editSecurity } from '@/routes/security';
import type { User } from '@/types';

type PanelLink = {
    title: string;
    href: string;
    icon: LucideIcon;
    badge?: number;
};

const open = defineModel<boolean>('open', { default: false });

defineProps<{
    user: User;
}>();

const { getInitials } = useInitials();

const links: PanelLink[] = [
    { title: 'Home', href: toUrl(overview()), icon: House },
    { title: 'Profile', href: toUrl(editProfile()), icon: UserRound },
    { title: 'Invoice', href: '#', icon: FileText, badge: 4 },
    { title: 'Subscription', href: '#', icon: Sparkles },
    { title: 'Account Settings', href: toUrl(editSecurity()), icon: Settings },
];

/** Placeholder entries must not trigger an Inertia visit. */
const componentFor = (href: string) => (href === '#' ? 'a' : Link);

const handleLogout = () => {
    router.flushAll();
};
</script>

<template>
    <Sheet v-model:open="open">
        <SheetContent
            side="right"
            class="w-full gap-0 p-0 sm:max-w-sm [&>button:last-of-type]:hidden"
            data-test="user-panel"
        >
            <SheetClose
                class="absolute top-5 right-5 flex size-9 items-center justify-center rounded-full border border-border text-foreground transition-colors hover:bg-accent focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                aria-label="Close account panel"
            >
                <X class="size-4" />
            </SheetClose>

            <SheetTitle class="sr-only">Account</SheetTitle>
            <SheetDescription class="sr-only">
                Your account details and shortcuts.
            </SheetDescription>

            <div class="flex flex-col items-center gap-3 px-6 pt-14 pb-8">
                <Avatar class="size-20">
                    <AvatarImage
                        v-if="user.avatar"
                        :src="user.avatar"
                        :alt="user.name"
                    />
                    <AvatarFallback class="text-xl font-medium">
                        {{ getInitials(user.name) }}
                    </AvatarFallback>
                </Avatar>

                <div class="space-y-1 text-center">
                    <p class="text-lg font-semibold">{{ user.name }}</p>
                    <p
                        class="flex items-center justify-center gap-2 text-sm text-muted-foreground"
                    >
                        <Mail class="size-4 shrink-0" />
                        <span class="truncate">{{ user.email }}</span>
                    </p>
                </div>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto border-t px-4 py-6">
                <component
                    :is="componentFor(link.href)"
                    v-for="link in links"
                    :key="link.title"
                    :href="link.href"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200 ease-in-out hover:translate-x-1 hover:bg-accent focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
                    @click="open = false"
                >
                    <component
                        :is="link.icon"
                        class="size-5 shrink-0 text-muted-foreground"
                    />
                    <span class="flex-1">{{ link.title }}</span>
                    <Badge
                        v-if="link.badge"
                        variant="secondary"
                        class="rounded-md px-1.5 font-mono text-xs"
                    >
                        {{ link.badge }}
                    </Badge>
                </component>
            </nav>

            <div
                class="flex flex-col items-center gap-1 border-t px-6 pt-6 pb-8 text-center"
            >
                <UpgradeIllustration class="h-20 w-auto" />
                <p class="mt-1 text-base font-semibold">
                    Grab ShadcnSpace Admin
                </p>
                <p class="text-sm text-muted-foreground">
                    Customize your dashboard
                </p>
                <Button
                    variant="outline"
                    size="sm"
                    class="mt-4 rounded-lg"
                    as-child
                >
                    <Link
                        :href="logout()"
                        as="button"
                        data-test="logout-button"
                        @click="handleLogout"
                    >
                        Log Out
                    </Link>
                </Button>
            </div>
        </SheetContent>
    </Sheet>
</template>
