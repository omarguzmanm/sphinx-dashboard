<script setup lang="ts">
import { Donut } from '@unovis/ts';
import { VisDonut, VisSingleContainer } from '@unovis/vue';
import ClientOnly from '@/components/ClientOnly.vue';
import TrendBadge from '@/components/dashboard/TrendBadge.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { ChartConfig } from '@/components/ui/chart';
import {
    ChartContainer,
    ChartTooltip,
    ChartTooltipContent,
    componentToString,
} from '@/components/ui/chart';

const chartData = [
    { period: '2024', amount: 38, fill: 'var(--color-2024)' },
    { period: 'Reserved', amount: 37, fill: 'var(--color-Reserved)' },
    { period: '2025', amount: 25, fill: 'var(--color-2025)' },
];

type Data = (typeof chartData)[number];

const chartConfig = {
    amount: { label: 'Backup', color: undefined },
    '2024': { label: '2024', color: 'var(--chart-3)' },
    Reserved: { label: 'Reserved', color: 'var(--chart-1)' },
    '2025': { label: '2025', color: 'var(--chart-4)' },
} satisfies ChartConfig;

/**
 * `--color-*` is scoped to the ChartContainer by ChartStyle, so the legend
 * outside of it has to read the palette variable directly.
 */
const legend = (['2024', '2025'] as const).map((period) => ({
    period,
    color: chartConfig[period].color,
}));

const value = (d: Data) => d.amount;
const color = (d: Data) => d.fill;
</script>

<template>
    <Card class="gap-2 py-5">
        <CardHeader>
            <CardTitle class="text-lg">Yearly Backup</CardTitle>
        </CardHeader>

        <CardContent class="flex flex-1 flex-col justify-between gap-2">
            <div class="flex items-center justify-between gap-4">
                <div class="space-y-1.5">
                    <p class="text-2xl font-semibold tracking-tight">$36,358</p>
                    <div class="flex items-center gap-2">
                        <TrendBadge :value="9" />
                        <span class="text-sm text-muted-foreground">
                            last year
                        </span>
                    </div>
                </div>

                <ChartContainer
                    id="yearly-backup"
                    :config="chartConfig"
                    class="aspect-square size-25 shrink-0"
                >
                    <ClientOnly>
                        <VisSingleContainer :data="chartData">
                            <VisDonut
                                :value="value"
                                :color="color"
                                :arc-width="26"
                                :pad-angle="0.05"
                                :show-background="false"
                            />
                            <ChartTooltip
                                :triggers="{
                                    [Donut.selectors.segment]:
                                        componentToString(
                                            chartConfig,
                                            ChartTooltipContent,
                                            { labelKey: 'period' },
                                        )!,
                                }"
                            />
                        </VisSingleContainer>
                    </ClientOnly>
                </ChartContainer>
            </div>

            <div class="flex items-center gap-6">
                <div
                    v-for="item in legend"
                    :key="item.period"
                    class="flex items-center gap-2"
                >
                    <span
                        class="size-2.5 rounded-full"
                        :style="{ backgroundColor: item.color }"
                    />
                    <span class="text-sm text-muted-foreground">
                        {{ item.period }}
                    </span>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
