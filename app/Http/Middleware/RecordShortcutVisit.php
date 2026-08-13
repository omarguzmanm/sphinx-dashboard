<?php

namespace App\Http\Middleware;

use App\Models\UserShortcut;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecordShortcutVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * Recorded after the response has been sent, so the write never adds
     * latency to the page the user is waiting for.
     */
    public function terminate(Request $request, Response $response): void
    {
        if (! $this->shouldRecord($request, $response)) {
            return;
        }

        UserShortcut::record(
            $request->user()->id,
            (string) $request->route()?->getName(),
        );
    }

    private function shouldRecord(Request $request, Response $response): bool
    {
        if (! $request->user() || ! $request->isMethod('GET')) {
            return false;
        }

        // Inertia partial reloads re-fetch props for a page the user is
        // already on; counting them would inflate whatever they linger on.
        if ($request->headers->has('X-Inertia-Partial-Component')) {
            return false;
        }

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        return (bool) $request->route()?->getName();
    }
}
