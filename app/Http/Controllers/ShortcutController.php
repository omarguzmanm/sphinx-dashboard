<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserShortcut;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Validation\Rule;

class ShortcutController extends Controller
{
    /**
     * Pin a destination to the overview.
     */
    public function store(Request $request): RedirectResponse
    {
        $route = $this->validatedRoute($request);

        /** @var User $user */
        $user = $request->user();

        UserShortcut::query()->updateOrCreate(
            ['user_id' => $user->id, 'route' => $route],
            ['pinned_at' => now()],
        );

        return back();
    }

    /**
     * Unpin a destination, keeping whatever activity it has accumulated.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $route = $this->validatedRoute($request);

        /** @var User $user */
        $user = $request->user();

        UserShortcut::query()
            ->where('user_id', $user->id)
            ->where('route', $route)
            ->update(['pinned_at' => null]);

        return back();
    }

    /**
     * Only real, parameterless, non-auth routes can be pinned.
     */
    private function validatedRoute(Request $request): string
    {
        /** @var array{route: string} $validated */
        $validated = $request->validate([
            'route' => [
                'required',
                'string',
                'max:255',
                Rule::notIn(UserShortcut::IGNORED_ROUTES),
                function (string $attribute, mixed $value, callable $fail): void {
                    if (! RouteFacade::has((string) $value)) {
                        $fail('That destination does not exist.');
                    }
                },
            ],
        ]);

        return $validated['route'];
    }
}
