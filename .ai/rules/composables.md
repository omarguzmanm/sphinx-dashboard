---
paths:
  - resources/js/composables/useCustomizer.ts
---

# Composables

## Customizer state lives on &lt;html&gt; and must round-trip through a cookie
The theme customizer (direction / layout / container / card style / colours) is mirrored onto `<html>` as `dir` + `data-layout` + `data-boxed-layout` + `data-card-style`, so the CSS in `app.css` reacts with plain attribute selectors and needs no JS.

- Those rules sit in `@layer utilities`, NOT components — otherwise Tailwind utilities already on the element (`max-w-*`, `shadow-sm`) win and boxed/card-style silently do nothing.
- Persist to BOTH localStorage and the `customizer` cookie. The cookie is what `HandleCustomizer` reads so the SSR render picks the same layout the client hydrates; localStorage alone causes a hydration mismatch. The cookie is in `encryptCookies(except:)`.
- `<Customizer />` is mounted in `AppLayout`, deliberately outside the `<component :is="layout">` switch. Put it inside a layout and changing Layout Type unmounts the open panel.
- Charts that need the DOM (Unovis) must stay wrapped in `<ClientOnly>`, and every `ChartContainer` needs an explicit `id`. Without the stable id, reka's `useId` counter drifts between server and client and every id after it mismatches.
