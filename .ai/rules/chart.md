---
paths:
  - 'resources/js/components/ui/chart/**'
  - 'resources/js/components/dashboard/**'
---

# Chart

## Charts use shadcn-vue `chart` + Unovis, except floating range bars
Charts go through the shadcn-vue `ChartContainer` (`components/ui/chart`) with marks from `@unovis/vue`. That shell only does theming: it injects `--color-<key>` CSS vars from the `ChartConfig` you pass, plus tooltip/legend chrome. It draws nothing on its own.

- Always name series in `ChartConfig` and reference `var(--color-<key>)` in the marks, never a raw `--chart-N`, so light/dark both work.
- `ChartBar.vue` is the one exception and must stay hand-rolled (HTML/flexbox with `border-radius: 9999px`). Both bar charts in the design draw capsules over an arbitrary `[from, to]` range, rounded at **both** ends — Revenue Updates grows out of a zero line in two directions, Sales from Locations stacks two capsules with a gap between them. Unovis stacks from zero and `roundedCorners` only rounds the outer edge of a stack, so `VisStackedBar`/`VisGroupedBar` cannot express either. See the header comment in `paths.ts`.
- Stacking in `ChartBar` is done by the caller, by offsetting the next series' range past the previous one (`SalesFromLocationsCard`'s `SEGMENT_GAP`). The component itself only knows how to place ranges.
- `VisGroupedBar` has no `barWidth`/`barMaxWidth`. Use `groupMaxWidth` to make bars thin — `barWidth` is silently ignored.
- `ChartTooltipContent` builds its rows from the **keys of the datum** Unovis hands it, and drops any key with no `ChartConfig` entry. Its declared `nameKey` prop is dead code. To title a tooltip with a field of the datum (a year, a country), pass `labelKey: '<field>'`; to colour the swatch, put `fill` on each datum. Its `x` prop is typed `number | Date`, so label a string axis with `labelFormatter` over the index.
- `ChartStyle` scopes `--color-<key>` to `[data-chart=<id>]`. Anything outside the `ChartContainer` — a hand-built legend, a stat dot — must read `--chart-N` directly or it renders colourless.
- Series colors come from `--chart-1..5` (app.css): 1 = dark neutral, 2 = light neutral, 3 = teal, 4 = orange, 5 = blue. They invert in dark mode.
- Unovis costs ~157 kB raw / ~51 kB gzip on the chunk that imports it. It is only worth it where it is actually used.

## The reference design is React
The dashboard mirrors shadcnspace, which is Next.js + shadcn/ui + **Recharts**. Recharts has no Vue port, so pixel parity on chart internals is not achievable by swapping libraries — match it visually instead. The `.recharts-*` selectors inside `ChartContainer.vue` are dead weight carried over from the React port.
