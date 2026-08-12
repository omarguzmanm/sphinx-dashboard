<script setup lang="ts">
import { Button } from '@/components/ui/button';
</script>

<template>
    <div
        class="mx-1 mb-1 flex flex-col items-center gap-1 rounded-xl bg-sidebar-accent/70 px-4 py-5 text-center group-data-[collapsible=icon]:hidden"
    >
        <svg
            class="h-24 w-auto"
            viewBox="0 0 120 96"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            aria-hidden="true"
        >
            <!-- Opened box flaps -->
            <path
                d="M22 50 6 40l14-9 14 10-12 9Z"
                fill="currentColor"
                class="text-muted-foreground/30"
            />
            <path
                d="M98 50l16-10-14-9-14 10 12 9Z"
                fill="currentColor"
                class="text-muted-foreground/30"
            />

            <!-- Cats peeking out -->
            <g class="text-sidebar-foreground">
                <path
                    d="M30 30l3-13 8 8-11 5Z"
                    fill="currentColor"
                    opacity="0.85"
                />
                <path
                    d="M56 30l-3-13-8 8 11 5Z"
                    fill="currentColor"
                    opacity="0.85"
                />
                <ellipse
                    cx="43"
                    cy="36"
                    rx="14"
                    ry="12"
                    fill="currentColor"
                    opacity="0.85"
                />
                <path d="M33 48h20v14H33z" fill="currentColor" opacity="0.85" />
            </g>
            <circle cx="38" cy="35" r="1.8" fill="var(--sidebar-accent)" />
            <circle cx="48" cy="35" r="1.8" fill="var(--sidebar-accent)" />
            <path
                d="M41 40h4"
                stroke="var(--sidebar-accent)"
                stroke-width="1.6"
                stroke-linecap="round"
            />

            <g class="text-muted-foreground/45">
                <path d="M64 36l1-11 7 7-8 4Z" fill="currentColor" />
                <path d="M86 36l-1-11-7 7 8 4Z" fill="currentColor" />
                <ellipse cx="75" cy="42" rx="12" ry="10" fill="currentColor" />
                <path d="M66 52h18v12H66z" fill="currentColor" />
            </g>
            <circle cx="71" cy="41" r="1.6" fill="var(--sidebar-accent)" />
            <circle cx="79" cy="41" r="1.6" fill="var(--sidebar-accent)" />

            <!-- Box front -->
            <path
                d="M20 50h80v30a4 4 0 0 1-4 4H24a4 4 0 0 1-4-4V50Z"
                fill="currentColor"
                class="text-muted-foreground/40"
            />
            <path
                d="M20 50h80v7H20z"
                fill="currentColor"
                class="text-muted-foreground/55"
            />
        </svg>

        <p class="text-sm font-semibold text-sidebar-foreground">
            Grab Pro Now
        </p>
        <p class="text-xs text-muted-foreground">Customize your admin</p>

        <Button size="sm" class="mt-3 w-full rounded-md"> Get Premium </Button>
    </div>
</template>
