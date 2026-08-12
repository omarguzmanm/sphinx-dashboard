<script setup lang="ts">
import { Grip } from '@lucide/vue';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardAction,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { ChartBar } from '@/components/ui/chart';
import type { ChartConfig, ChartSeries } from '@/components/ui/chart';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

const year = ref('2026');

const categories = [
    '16/08',
    '17/08',
    '18/08',
    '19/08',
    '20/08',
    '21/08',
    '22/08',
];

const chartConfig = {
    earnings: { label: 'Earnings', color: 'var(--chart-2)' },
    expense: { label: 'Expense', color: 'var(--chart-1)' },
} satisfies ChartConfig;

const series: ChartSeries[] = [
    {
        key: 'earnings',
        width: 11,
        values: [1650, 2050, 2200, 3000, 1100, 950, 1250],
    },
    {
        key: 'expense',
        width: 11,
        values: [-1900, -1150, -2500, -1550, -600, -1800, -1350],
    },
];

const ticks = [3000, 2000, 1000, 0, -1000, -2000, -3000];

const formatTick = (value: number): string => `${value / 1000}k`;
</script>

<template>
    <Card class="py-5">
        <CardHeader class="gap-0.5">
            <CardTitle class="text-lg">Revenue Updates</CardTitle>
            <CardDescription>Overview of profit</CardDescription>

            <CardAction>
                <Select v-model="year">
                    <SelectTrigger size="sm" class="w-[128px] rounded-lg">
                        <SelectValue :placeholder="`Year ${year}`">
                            Year {{ year }}
                        </SelectValue>
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="2026">Year 2026</SelectItem>
                        <SelectItem value="2025">Year 2025</SelectItem>
                        <SelectItem value="2024">Year 2024</SelectItem>
                    </SelectContent>
                </Select>
            </CardAction>
        </CardHeader>

        <CardContent class="grid flex-1 gap-8 pt-3 lg:grid-cols-[1fr_300px]">
            <ChartBar
                id="revenue-updates"
                class="h-[310px]"
                :categories="categories"
                :config="chartConfig"
                :series="series"
                :min="-3000"
                :max="3000"
                :ticks="ticks"
                :format-tick="formatTick"
            />

            <div class="flex flex-col">
                <div class="flex items-center gap-3">
                    <span
                        class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-muted text-muted-foreground"
                    >
                        <Grip class="size-5" />
                    </span>
                    <div>
                        <p class="text-2xl font-semibold tracking-tight">
                            $63,489.50
                        </p>
                        <p class="text-sm text-muted-foreground">
                            Total Earnings
                        </p>
                    </div>
                </div>

                <div class="mt-12 space-y-8">
                    <div class="flex items-start gap-3">
                        <span
                            class="mt-1.5 size-2.5 shrink-0 rounded-full"
                            :style="{
                                backgroundColor: chartConfig.earnings.color,
                            }"
                        />
                        <div>
                            <p class="text-sm text-muted-foreground">
                                Earnings this month
                            </p>
                            <p class="text-lg font-semibold">$48,820</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <span
                            class="mt-1.5 size-2.5 shrink-0 rounded-full"
                            :style="{
                                backgroundColor: chartConfig.expense.color,
                            }"
                        />
                        <div>
                            <p class="text-sm text-muted-foreground">
                                Expense this month
                            </p>
                            <p class="text-lg font-semibold">$26,498</p>
                        </div>
                    </div>
                </div>

                <Button size="sm" class="mt-auto w-full rounded-lg">
                    View Full Report
                </Button>
            </div>
        </CardContent>
    </Card>
</template>
