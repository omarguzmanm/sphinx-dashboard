<script setup lang="ts">
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import type { ChartConfig, ChartSeries } from '@/components/ui/chart';
import { ChartBar } from '@/components/ui/chart';

const categories = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];

const usa = [186, 305, 237, 73, 209, 214];
const india = [80, 200, 120, 190, 130, 140];

/** Value-space gap that keeps the two stacked capsules visually separated. */
const SEGMENT_GAP = 16;

const chartConfig = {
    usa: { label: 'USA', color: 'var(--chart-1)' },
    india: { label: 'India', color: 'var(--chart-2)' },
} satisfies ChartConfig;

const series: ChartSeries[] = [
    {
        key: 'usa',
        width: 11,
        values: usa.map((value): [number, number] => [0, value]),
    },
    {
        key: 'india',
        width: 11,
        values: india.map((value, index): [number, number] => [
            usa[index] + SEGMENT_GAP,
            usa[index] + SEGMENT_GAP + value,
        ]),
    },
];

const ticks = [600, 450, 300, 150, 0];
</script>

<template>
    <Card class="gap-4 py-5">
        <CardHeader class="gap-0.5">
            <CardTitle class="text-lg">Sales from Locations</CardTitle>
            <CardDescription>This Year</CardDescription>
        </CardHeader>

        <CardContent class="flex-1 pt-2">
            <ChartBar
                id="sales-from-locations"
                class="h-full min-h-56"
                :categories="categories"
                :config="chartConfig"
                :series="series"
                :min="0"
                :max="600"
                :ticks="ticks"
                hide-tick-labels
                hide-tooltip-label
                show-legend
            />
        </CardContent>
    </Card>
</template>
