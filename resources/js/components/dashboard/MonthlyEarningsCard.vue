<script setup lang="ts">
import { CircleDollarSign } from '@lucide/vue';
import CardIconBadge from '@/components/dashboard/CardIconBadge.vue';
import SparklineAreaChart from '@/components/dashboard/SparklineAreaChart.vue';
import TrendBadge from '@/components/dashboard/TrendBadge.vue';
import {
    Card,
    CardAction,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';

const earnings = [38, 26, 44, 30, 22, 34, 27, 41, 33, 52, 46, 58];

const months = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December',
];
</script>

<template>
    <Card class="gap-3 overflow-hidden py-5">
        <CardHeader>
            <CardTitle class="text-lg">Monthly earnings</CardTitle>

            <CardAction>
                <CardIconBadge :icon="CircleDollarSign" />
            </CardAction>
        </CardHeader>

        <CardContent class="space-y-1.5">
            <p class="text-2xl font-semibold tracking-tight">$6,820</p>
            <div class="flex items-center gap-2">
                <TrendBadge :value="-9" />
                <span class="text-sm text-muted-foreground"
                    >than last year</span
                >
            </div>
        </CardContent>

        <div class="mt-auto px-6 pt-2">
            <SparklineAreaChart
                id="monthly-earnings"
                class="h-[72px]"
                :data="earnings"
                :labels="months"
                label="Earnings"
                color="var(--chart-1)"
            />
        </div>
    </Card>
</template>
