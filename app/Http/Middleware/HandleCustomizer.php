<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class HandleCustomizer
{
    /**
     * Defaults mirrored from `resources/js/composables/useCustomizer.ts`.
     *
     * @var array{direction: string, layout: string, container: string, cardStyle: string, primaryColor: string, secondaryColor: string}
     */
    public const DEFAULTS = [
        'direction' => 'ltr',
        'layout' => 'vertical',
        'container' => 'full',
        'cardStyle' => 'border',
        'primaryColor' => '#171717',
        'secondaryColor' => '#ebebeb',
    ];

    /**
     * Share the customizer preferences so the server renders the same layout
     * the client is about to hydrate, avoiding a hydration mismatch.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        View::share('customizer', $this->resolve($request));

        return $next($request);
    }

    /**
     * @return array<string, string>
     */
    public function resolve(Request $request): array
    {
        $decoded = json_decode((string) $request->cookie('customizer'), true);

        if (! is_array($decoded)) {
            return self::DEFAULTS;
        }

        return array_map(
            fn (mixed $value): string => is_string($value) ? $value : '',
            array_merge(self::DEFAULTS, array_intersect_key($decoded, self::DEFAULTS)),
        );
    }
}
