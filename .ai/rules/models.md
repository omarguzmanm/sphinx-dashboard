---
paths:
  - app/Models/UserShortcut.php
---

# Models

## Overview shortcuts: aggregate on write, rank by frecency
`user_shortcuts` holds one row per (user_id, route) carrying BOTH the inferred activity (`visits`, `last_visited_at`) and the explicit pin (`pinned_at`). Deliberately not a visit log: aggregating on write bounds the table by route count, so there is nothing to prune and reading the overview is one indexed query.

- `RecordShortcutVisit` writes in `terminate()`, after the response is sent, so tracking never costs the user latency. It skips guests, non-GET, non-200, unnamed routes, Inertia partial reloads (`X-Inertia-Partial-Component`) and `UserShortcut::IGNORED_ROUTES`.
- Rank with `frecency()` (`visits * exp(-0.05 * days_since_last)`), never raw `visits` — raw counts let a burst from months ago outrank a current habit. SQLite has no `exp()`, so decay is applied in PHP over a short candidate list, not in SQL.
- Only named, parameterless routes can be tracked or pinned; `OverviewController::toPayload` drops anything `route()` cannot resolve, so renamed/removed routes disappear instead of erroring.
- The Vue side resolves icon and label by matching the URL against `navGroups` via `findNavEntry`, so `lib/navigation.ts` stays the single source of truth for presentation and PHP never ships labels.
