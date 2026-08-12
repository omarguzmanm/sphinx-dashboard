<script setup lang="ts">
import { ArrowRight } from '@lucide/vue';
import type { LucideIcon } from '@lucide/vue';
import CardIconBadge from '@/components/dashboard/CardIconBadge.vue';
import TrendBadge from '@/components/dashboard/TrendBadge.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardAction,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';

type Props = {
    title: string;
    value: string;
    trend: number;
    icon: LucideIcon;
};

defineProps<Props>();
</script>

<template>
    <Card class="gap-3 py-5">
        <CardHeader>
            <CardTitle class="text-lg">{{ title }}</CardTitle>

            <CardAction>
                <CardIconBadge :icon="icon" />
            </CardAction>
        </CardHeader>

        <CardContent class="space-y-5">
            <div class="flex items-center gap-2">
                <span class="text-2xl font-semibold tracking-tight">
                    {{ value }}
                </span>
                <TrendBadge :value="trend" />
            </div>

            <Button variant="outline" size="sm" class="rounded-lg">
                See Report
                <ArrowRight />
            </Button>
        </CardContent>
    </Card>
</template>
