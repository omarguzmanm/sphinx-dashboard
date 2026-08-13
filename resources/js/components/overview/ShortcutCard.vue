<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { Pin, PinOff, Sparkles } from '@lucide/vue';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { findNavEntry } from '@/lib/navigation';
import {
    destroy as unpinShortcut,
    store as pinShortcut,
} from '@/routes/shortcuts';
import type { Shortcut } from '@/types';

const props = defineProps<{
    shortcut: Shortcut;
}>();

const entry = computed(() => findNavEntry(props.shortcut.url));

const title = computed(() => entry.value?.title ?? props.shortcut.route);

const context = computed(() => {
    if (props.shortcut.visits > 0) {
        const visits = `${props.shortcut.visits} ${props.shortcut.visits === 1 ? 'visit' : 'visits'}`;

        return props.shortcut.lastVisitedAt
            ? `${visits} · ${relativeTime(props.shortcut.lastVisitedAt)}`
            : visits;
    }

    return entry.value?.parent ?? entry.value?.group ?? 'Suggested';
});

/**
 * Compact "2h ago" style label, using the browser's own locale rules.
 */
function relativeTime(iso: string): string {
    const elapsed = Date.parse(iso) - Date.now();

    if (Number.isNaN(elapsed)) {
        return '';
    }

    const units: [Intl.RelativeTimeFormatUnit, number][] = [
        ['year', 31536000000],
        ['month', 2592000000],
        ['day', 86400000],
        ['hour', 3600000],
        ['minute', 60000],
    ];

    const formatter = new Intl.RelativeTimeFormat(undefined, {
        numeric: 'auto',
        style: 'narrow',
    });

    for (const [unit, size] of units) {
        if (Math.abs(elapsed) >= size) {
            return formatter.format(Math.round(elapsed / size), unit);
        }
    }

    return formatter.format(0, 'minute');
}

const togglePin = () => {
    const action = props.shortcut.pinned ? unpinShortcut() : pinShortcut();

    router.visit(action.url, {
        method: action.method,
        data: { route: props.shortcut.route },
        preserveScroll: true,
    });
};
</script>

<template>
    <Card
        class="group relative gap-0 overflow-hidden p-0 transition-shadow hover:shadow-md"
    >
        <Link
            :href="shortcut.url"
            class="flex items-start gap-3 p-4 focus-visible:ring-2 focus-visible:ring-ring focus-visible:outline-none"
        >
            <span
                class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-muted text-foreground"
            >
                <component :is="entry?.icon ?? Sparkles" class="size-5" />
            </span>

            <span class="min-w-0 flex-1 pr-8">
                <span class="block truncate text-sm font-semibold">
                    {{ title }}
                </span>
                <span
                    class="mt-0.5 block truncate text-xs text-muted-foreground"
                >
                    {{ context }}
                </span>
            </span>
        </Link>

        <Button
            variant="ghost"
            size="icon-sm"
            class="absolute top-3 right-3 rounded-full text-muted-foreground opacity-0 transition-opacity group-hover:opacity-100 focus-visible:opacity-100"
            :class="{ 'opacity-100': shortcut.pinned }"
            :aria-label="shortcut.pinned ? `Unpin ${title}` : `Pin ${title}`"
            :data-test="`toggle-pin-${shortcut.route}`"
            @click="togglePin"
        >
            <PinOff v-if="shortcut.pinned" class="size-4" />
            <Pin v-else class="size-4" />
        </Button>
    </Card>
</template>
