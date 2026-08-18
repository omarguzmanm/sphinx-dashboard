<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { Compass, Pin } from '@lucide/vue';
import { computed } from 'vue';
import ShortcutCard from '@/components/overview/ShortcutCard.vue';
import type { Shortcut } from '@/types';

const props = defineProps<{
    pinned: Shortcut[];
    suggested: Shortcut[];
    fallback: Shortcut[];
}>();

const user = computed(() => usePage().props.auth.user);

const firstName = computed(() => user.value.name.split(' ')[0]);

const hasActivity = computed(() => props.suggested.length > 0);
</script>

<template>
    <Head title="Overview" />

    <div class="flex flex-1 flex-col gap-8 p-4 md:p-6">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">
                Welcome back, {{ firstName }}
            </h1>
            <p class="mt-1 text-sm text-muted-foreground">
                Jump straight back into what you were working on.
            </p>
        </div>

        <section v-if="pinned.length > 0" class="space-y-3">
            <div class="flex items-center gap-2">
                <Pin class="size-4 text-muted-foreground" />
                <h2 class="text-base font-semibold">Pinned</h2>
            </div>

            <div
                class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
            >
                <ShortcutCard
                    v-for="shortcut in pinned"
                    :key="shortcut.route"
                    :shortcut="shortcut"
                />
            </div>
        </section>

        <section class="space-y-3">
            <div class="flex items-center gap-2">
                <Compass class="size-4 text-muted-foreground" />
                <h2 class="text-base font-semibold">
                    {{ hasActivity ? 'Frequently used' : 'Get started' }}
                </h2>
            </div>

            <p v-if="!hasActivity" class="text-sm text-muted-foreground">
                As you move around the app, the places you return to most will
                show up here. Pin anything to keep it at the top.
            </p>

            <div
                class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
            >
                <ShortcutCard
                    v-for="shortcut in hasActivity ? suggested : fallback"
                    :key="shortcut.route"
                    :shortcut="shortcut"
                />
            </div>
        </section>
    </div>
</template>
