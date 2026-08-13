<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserShortcut;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class OverviewController extends Controller
{
    /**
     * How many inferred shortcuts to surface.
     */
    private const SUGGESTION_LIMIT = 6;

    /**
     * Shown while a user has no history worth ranking.
     *
     * @var list<string>
     */
    private const FALLBACK_ROUTES = [
        'dashboard',
        'profile.edit',
        'security.edit',
    ];

    public function __invoke(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        $shortcuts = UserShortcut::query()
            ->where('user_id', $user->id)
            ->get();

        $pinned = $shortcuts
            ->filter(fn (UserShortcut $shortcut): bool => $shortcut->pinned_at !== null)
            ->sortBy('pinned_at')
            ->values();

        $suggested = UserShortcut::rankByFrecency(
            $shortcuts->filter(
                fn (UserShortcut $shortcut): bool => $shortcut->pinned_at === null
                    && $shortcut->visits > 0
            )->values()
        )->take(self::SUGGESTION_LIMIT);

        return Inertia::render('Overview', [
            'pinned' => $this->present($pinned),
            'suggested' => $this->present($suggested),
            'fallback' => $this->fallback($pinned, $suggested),
        ]);
    }

    /**
     * @param  Collection<int, UserShortcut>  $shortcuts
     * @return list<array{route: string, url: string, visits: int, lastVisitedAt: string|null, pinned: bool}>
     */
    private function present(Collection $shortcuts): array
    {
        return $shortcuts
            ->map(fn (UserShortcut $shortcut): ?array => $this->toPayload(
                $shortcut->route,
                $shortcut->visits,
                $shortcut->last_visited_at?->toIso8601String(),
                $shortcut->pinned_at !== null,
            ))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Destinations to show when there is not enough activity to rank yet.
     *
     * @param  Collection<int, UserShortcut>  $pinned
     * @param  Collection<int, UserShortcut>  $suggested
     * @return list<array{route: string, url: string, visits: int, lastVisitedAt: string|null, pinned: bool}>
     */
    private function fallback(Collection $pinned, Collection $suggested): array
    {
        if ($suggested->isNotEmpty()) {
            return [];
        }

        $taken = $pinned->pluck('route')->all();

        return collect(self::FALLBACK_ROUTES)
            ->reject(fn (string $route): bool => in_array($route, $taken, true))
            ->map(fn (string $route): ?array => $this->toPayload($route, 0, null, false))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Resolve a route name to a link, dropping anything that no longer exists
     * or needs parameters we cannot supply.
     *
     * @return array{route: string, url: string, visits: int, lastVisitedAt: string|null, pinned: bool}|null
     */
    private function toPayload(string $route, int $visits, ?string $lastVisitedAt, bool $pinned): ?array
    {
        try {
            $url = route($route, absolute: false);
        } catch (\Throwable) {
            return null;
        }

        return [
            'route' => $route,
            'url' => $url,
            'visits' => $visits,
            'lastVisitedAt' => $lastVisitedAt,
            'pinned' => $pinned,
        ];
    }
}
