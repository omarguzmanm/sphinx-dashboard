/**
 * Helpers for `ChartBar.vue`.
 *
 * Every other chart on this project is drawn with `@unovis/vue` through the
 * shadcn-vue `ChartContainer`. `ChartBar` is the exception: it renders floating
 * bars where each category holds two *overlapping* capsules spanning an
 * arbitrary `[from, to]` range, rounded on both ends. Unovis stacks bars from
 * zero and its `roundedCorners` only rounds the outer edge of a stack, so that
 * shape cannot be expressed with `VisStackedBar` / `VisGroupedBar`.
 */

export type ChartSeries = {
    /** Key into the `ChartConfig`; drives both the colour and the tooltip label. */
    key: string
    /** Either a plain value (drawn from the baseline) or a `[from, to]` range. */
    values: (number | [number, number])[]
    /** Bar width in pixels. */
    width?: number
}

/**
 * Normalise a series value into a `[from, to]` range.
 */
export function toRange(
    value: number | [number, number],
    baseline = 0,
): [number, number] {
    if (Array.isArray(value)) {
        return value[0] <= value[1] ? value : [value[1], value[0]]
    }

    return value >= baseline ? [baseline, value] : [value, baseline]
}

/**
 * Map a value onto a 0-100 scale where 0 is the top of the plot area.
 */
export function toOffset(value: number, min: number, max: number): number {
    if (max === min) {
        return 0
    }

    return ((max - value) / (max - min)) * 100
}
