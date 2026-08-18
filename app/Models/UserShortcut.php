<?php

namespace App\Models;

use Database\Factories\UserShortcutFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * A user shortcut is a destination the user has visited, with a count of
 * visits and a timestamp of the last visit. It is used to suggest shortcuts
 * on the overview page.
 *
 * @property int $id
 * @property int $user_id
 * @property string $route
 * @property int $visits
 * @property Carbon|null $last_visited_at
 * @property Carbon|null $pinned_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder<UserShortcut> query()
 */
class UserShortcut extends Model
{
    /** @use HasFactory<UserShortcutFactory> */
    use HasFactory;

    /**
     * How quickly an unused destination loses ground, per day. At 0.05 a
     * shortcut keeps roughly half its weight after two weeks untouched.
     */
    public const float DECAY_PER_DAY = 0.05;

    /**
     * Routes that say nothing about what a user actually works on.
     *
     * @var list<string>
     */
    public const array IGNORED_ROUTES = [
        'overview',
        'home',
        'login',
        'register',
        'logout',
        'password.request',
        'password.reset',
        'password.confirm',
        'verification.notice',
        'verification.verify',
        'two-factor.login',
    ];

    protected $fillable = [
        'user_id',
        'route',
        'visits',
        'last_visited_at',
        'pinned_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'visits' => 'integer',
            'last_visited_at' => 'datetime',
            'pinned_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record one visit, creating the row on first sight.
     */
    public static function record(int $userId, string $route, ?Carbon $at = null): void
    {
        if (in_array($route, self::IGNORED_ROUTES, true)) {
            return;
        }

        $timestamp = ($at ?? now())->toDateTimeString();

        static::query()->upsert(
            [[
                'user_id' => $userId,
                'route' => $route,
                'visits' => 1,
                'last_visited_at' => $timestamp,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]],
            ['user_id', 'route'],
            [
                'visits' => DB::raw('user_shortcuts.visits + 1'),
                'last_visited_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        );
    }

    /**
     * @param  Builder<UserShortcut>  $query
     * @return Builder<UserShortcut>
     */
    public function scopePinned(Builder $query): Builder
    {
        return $query->whereNotNull('pinned_at');
    }

    /**
     * Rank by frecency: visits weighted down by how long ago the destination
     * was last opened, so a burst of use months ago does not outrank a habit
     * formed this week.
     *
     * SQLite has no `exp()`, so the decay is applied in PHP over a short
     * candidate list rather than in SQL.
     *
     * @param  Collection<int, UserShortcut>  $shortcuts
     * @return Collection<int, UserShortcut>
     */
    public static function rankByFrecency(Collection $shortcuts): Collection
    {
        return $shortcuts
            ->sortByDesc(fn (UserShortcut $shortcut): float => $shortcut->frecency())
            ->values();
    }

    public function frecency(): float
    {
        if ($this->visits < 1) {
            return 0.0;
        }

        $days = $this->last_visited_at?->diffInDays(now()) ?? 0;

        return $this->visits * exp(-self::DECAY_PER_DAY * max($days, 0));
    }
}
