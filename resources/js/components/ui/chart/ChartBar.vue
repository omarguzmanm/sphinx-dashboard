<script setup lang="ts">
import type { HTMLAttributes } from "vue"
import { computed, ref } from "vue"
import { cn } from "@/lib/utils"
import type { ChartConfig } from "."
import ChartContainer from "./ChartContainer.vue"
import ClientOnly from "@/components/ClientOnly.vue"
import ChartLegendContent from "./ChartLegendContent.vue"
import ChartTooltipContent from "./ChartTooltipContent.vue"
import type { ChartSeries } from "./paths"
import { toOffset, toRange } from "./paths"

const props = withDefaults(defineProps<{
  /** Stable id for the ChartContainer, so SSR and client agree. */
  id: string
  categories: string[]
  config: ChartConfig
  series: ChartSeries[]
  min: number
  max: number
  ticks?: number[]
  baseline?: number
  barWidth?: number
  radius?: string
  showGrid?: boolean
  /** Draw the grid lines but drop the value column on the left. */
  hideTickLabels?: boolean
  hideTooltipLabel?: boolean
  showLegend?: boolean
  formatTick?: (value: number) => string
  class?: HTMLAttributes["class"]
}>(), {
  baseline: 0,
  barWidth: 10,
  radius: "9999px",
  showGrid: true,
  formatTick: (value: number) => String(value),
})

const plot = ref<HTMLElement | null>(null)
const hovered = ref<number | null>(null)
const pointerY = ref(0)

const axisTicks = computed(() => props.ticks ?? [props.max, props.min])

const columns = computed(() =>
  props.categories.map((label, index) => ({
    label,
    bars: props.series.map((serie) => {
      const [from, to] = toRange(serie.values[index] ?? 0, props.baseline)

      return {
        key: serie.key,
        width: serie.width ?? props.barWidth,
        top: toOffset(to, props.min, props.max),
        height: Math.abs(
          toOffset(to, props.min, props.max) - toOffset(from, props.min, props.max),
        ),
      }
    }),
  })),
)

/** The value each series reports for the hovered category. */
const tooltipPayload = computed(() => {
  if (hovered.value === null) {
    return {}
  }

  return Object.fromEntries(
    props.series.map((serie) => {
      const value = serie.values[hovered.value!] ?? 0

      if (!Array.isArray(value)) {
        return [serie.key, value]
      }

      // A range carries its own span; report that rather than the offset.
      const [from, to] = toRange(value, props.baseline)

      return [serie.key, from === props.baseline ? to : to - from]
    }),
  )
})

function trackPointer(event: MouseEvent) {
  const rect = plot.value?.getBoundingClientRect()

  if (rect) {
    pointerY.value = event.clientY - rect.top
  }
}

/** Follow the pointer vertically, anchored beside the hovered column. */
const tooltipStyle = computed(() => {
  const total = props.categories.length
  const index = hovered.value ?? 0
  const flip = index > total - 3

  return {
    left: `${((index + 0.5) / total) * 100}%`,
    top: `${pointerY.value}px`,
    transform: `translate(${flip ? "calc(-100% - 10px)" : "10px"}, -50%)`,
  }
})
</script>

<template>
  <ChartContainer
    :id="id"
    :config="config"
    :class="cn('aspect-auto', props.class)"
  >
    <div class="flex h-full w-full gap-4">
      <div
        v-if="axisTicks.length > 0 && !hideTickLabels"
        class="text-muted-foreground flex flex-col justify-between pb-7 text-right text-xs tabular-nums"
      >
        <span v-for="tick in axisTicks" :key="tick" class="leading-none">
          {{ formatTick(tick) }}
        </span>
      </div>

      <div class="flex min-w-0 flex-1 flex-col">
        <div
          ref="plot"
          class="relative flex-1"
          @mousemove="trackPointer"
          @mouseleave="hovered = null"
        >
          <div v-if="showGrid" class="absolute inset-0">
            <div
              v-for="tick in axisTicks"
              :key="`grid-${tick}`"
              class="bg-border/60 absolute inset-x-0 h-px"
              :style="{ top: `${toOffset(tick, min, max)}%` }"
            />
          </div>

          <div class="absolute inset-0 flex items-stretch">
            <div
              v-for="(column, index) in columns"
              :key="column.label"
              class="relative flex min-w-0 flex-1 items-start justify-center"
              @mouseenter="hovered = index"
            >
              <div
                v-if="hovered === index"
                class="bg-muted/70 absolute inset-0"
              />

              <div
                v-for="bar in column.bars"
                :key="`${column.label}-${bar.key}`"
                class="absolute"
                :style="{
                  top: `${bar.top}%`,
                  height: `${bar.height}%`,
                  width: `${bar.width}px`,
                  backgroundColor: `var(--color-${bar.key})`,
                  borderRadius: radius,
                }"
              />
            </div>
          </div>

          <div
            v-if="hovered !== null"
            class="pointer-events-none absolute z-10"
            :style="tooltipStyle"
          >
            <ChartTooltipContent
              :payload="tooltipPayload"
              :config="config"
              :hide-label="hideTooltipLabel"
              :x="hovered"
              :label-formatter="(index) => categories[Number(index)] ?? ''"
            />
          </div>
        </div>

        <div class="text-muted-foreground mt-3 flex text-xs tabular-nums">
          <span
            v-for="column in columns"
            :key="`label-${column.label}`"
            class="min-w-0 flex-1 truncate text-center"
          >
            {{ column.label }}
          </span>
        </div>

        <ClientOnly v-if="showLegend">
          <ChartLegendContent />
        </ClientOnly>
      </div>
    </div>
  </ChartContainer>
</template>
