<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserShortcut;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserShortcut>
 */
class UserShortcutFactory extends Factory
{
    protected $model = UserShortcut::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'route' => fake()->randomElement(['dashboard', 'profile.edit', 'security.edit']),
            'visits' => fake()->numberBetween(1, 40),
            'last_visited_at' => now()->subDays(fake()->numberBetween(0, 30)),
            'pinned_at' => null,
        ];
    }

    public function pinned(): static
    {
        return $this->state(fn (): array => ['pinned_at' => now()]);
    }

    /**
     * A destination used heavily, but not for a while.
     */
    public function stale(int $days = 60): static
    {
        return $this->state(fn (): array => [
            'visits' => 100,
            'last_visited_at' => now()->subDays($days),
        ]);
    }

    public function forRoute(string $route): static
    {
        return $this->state(fn (): array => ['route' => $route]);
    }
}
