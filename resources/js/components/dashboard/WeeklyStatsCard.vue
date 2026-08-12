<script setup lang="ts">
import SparklineAreaChart from '@/components/dashboard/SparklineAreaChart.vue';
import TrendBadge from '@/components/dashboard/TrendBadge.vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';

const sales = [12, 18, 14, 46, 30, 22, 41, 26, 20, 34, 24, 30];

const weeks = sales.map((_, index) => `Week ${index + 1}`);

const topProducts = [
    { name: 'Top Sales', description: 'Johnathan Doe', trend: 23 },
    { name: 'Best Seller', description: 'Footwear', trend: 15 },
    { name: 'Most Commented', description: 'Ashley Olsen', trend: -12 },
];
</script>

<template>
    <Card class="gap-4 py-5">
        <CardHeader class="gap-0.5">
            <CardTitle class="text-lg">Weekly Stats</CardTitle>
            <CardDescription>Average sales</CardDescription>
        </CardHeader>

        <div class="px-6 pt-2">
            <SparklineAreaChart
                id="weekly-stats"
                class="h-36"
                :data="sales"
                :labels="weeks"
                label="Sales"
                color="var(--chart-1)"
            />
        </div>

        <CardContent class="mt-auto space-y-5">
            <div
                v-for="product in topProducts"
                :key="product.name"
                class="flex items-center gap-3"
            >
                <span
                    class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-muted text-xs font-semibold"
                >
                    {{ product.name.charAt(0) }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold">
                        {{ product.name }}
                    </p>
                    <p class="truncate text-xs text-muted-foreground">
                        {{ product.description }}
                    </p>
                </div>
                <TrendBadge :value="product.trend" />
            </div>
        </CardContent>
    </Card>
</template>
