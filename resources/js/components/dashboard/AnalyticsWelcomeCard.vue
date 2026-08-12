<script setup lang="ts">
import AnalyticsIllustration from '@/components/dashboard/AnalyticsIllustration.vue';
import TrendBadge from '@/components/dashboard/TrendBadge.vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';

type Props = {
    earnings: string;
    earningsTrend: number;
    expense: string;
    expenseTrend: number;
};

defineProps<Props>();
</script>

<template>
    <Card class="relative gap-0 overflow-hidden py-5">
        <CardHeader class="gap-0.5 sm:pr-44">
            <CardTitle class="text-lg">Analytics Dashboard</CardTitle>
            <CardDescription>Check all the statistics</CardDescription>
        </CardHeader>

        <CardContent class="mt-auto pt-7">
            <div class="flex items-stretch gap-6">
                <div class="space-y-1">
                    <p class="text-sm text-muted-foreground">Earnings</p>
                    <div class="flex items-center gap-2">
                        <span class="text-2xl font-semibold tracking-tight">
                            {{ earnings }}
                        </span>
                        <TrendBadge :value="earningsTrend" />
                    </div>
                </div>

                <Separator orientation="vertical" class="h-auto!" />

                <div class="space-y-1">
                    <p class="text-sm text-muted-foreground">Expense</p>
                    <div class="flex items-center gap-2">
                        <span class="text-2xl font-semibold tracking-tight">
                            {{ expense }}
                        </span>
                        <TrendBadge :value="expenseTrend" />
                    </div>
                </div>
            </div>
        </CardContent>

        <AnalyticsIllustration
            class="pointer-events-none absolute right-3 bottom-0 hidden h-[168px] w-auto sm:block"
        />
    </Card>
</template>
