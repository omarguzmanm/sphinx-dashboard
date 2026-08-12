<script setup lang="ts">
import { VisArea, VisLine, VisXYContainer } from '@unovis/vue';
import type { HTMLAttributes } from 'vue';
import { computed } from 'vue';
import ClientOnly from '@/components/ClientOnly.vue';
import type { ChartConfig } from '@/components/ui/chart';
import {
    ChartContainer,
    ChartCrosshair,
    ChartTooltip,
    ChartTooltipContent,
    componentToString,
} from '@/components/ui/chart';

type Props = {
    /** Stable id for the ChartContainer and the gradient, so SSR and client agree. */
    id: string;
    data: number[];
    /** Human readable labels shown in the tooltip, one per data point. */
    labels?: string[];
    label: string;
    color?: string;
    class?: HTMLAttributes['class'];
};

const props = withDefaults(defineProps<Props>(), {
    color: 'var(--chart-1)',
});

const gradientId = computed(() => `sparkline-${props.id}`);

const chartConfig = computed<ChartConfig>(() => ({
    value: { label: props.label, color: props.color },
}));

const chartData = computed(() =>
    props.data.map((value, index) => ({ index, value })),
);

const svgDefs = computed(
    () => `
  <linearGradient id="${gradientId.value}" x1="0" y1="0" x2="0" y2="1">
    <stop offset="0%" stop-color="var(--color-value)" stop-opacity="0.3" />
    <stop offset="100%" stop-color="var(--color-value)" stop-opacity="0" />
  </linearGradient>
`,
);

type Point = { index: number; value: number };

const x = (d: Point) => d.index;
const y = (d: Point) => d.value;
</script>

<template>
    <ChartContainer
        :id="id"
        :config="chartConfig"
        :class="['aspect-auto', props.class]"
    >
        <ClientOnly>
            <VisXYContainer
                :data="chartData"
                :svg-defs="svgDefs"
                :y-domain="[0, undefined]"
                :margin="{ top: 4, right: 0, bottom: 0, left: 0 }"
                :padding="{ top: 0, right: 0, bottom: 0, left: 0 }"
            >
                <VisArea :x="x" :y="y" :color="`url(#${gradientId})`" />
                <VisLine
                    :x="x"
                    :y="y"
                    color="var(--color-value)"
                    :line-width="2"
                />
                <ChartTooltip />
                <ChartCrosshair
                    :template="
                        componentToString(chartConfig, ChartTooltipContent, {
                            labelFormatter: (index: number | Date) =>
                                labels?.[Number(index)] ?? '',
                            hideLabel: !labels,
                        })
                    "
                    color="var(--color-value)"
                />
            </VisXYContainer>
        </ClientOnly>
    </ChartContainer>
</template>
