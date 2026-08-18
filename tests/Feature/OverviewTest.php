<?php

use App\Models\User;
use App\Models\UserShortcut;
use Inertia\Testing\AssertableInertia as Assert;

test('guests are redirected to the login page', function () {
    $this->get(route('overview'))->assertRedirect(route('login'));
});

test('authenticated users land on the overview', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('overview'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('Overview'));
});

test('a visit is recorded after the response is sent', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('dashboard'))->assertOk();

    $shortcut = UserShortcut::query()
        ->where('user_id', $user->id)
        ->where('route', 'dashboard')
        ->sole();

    expect($shortcut->visits)->toBe(1)
        ->and($shortcut->last_visited_at)->not->toBeNull();
});

test('repeat visits increment the same row instead of adding new ones', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('dashboard'));
    $this->actingAs($user)->get(route('dashboard'));
    $this->actingAs($user)->get(route('dashboard'));

    expect(UserShortcut::query()->where('user_id', $user->id)->count())->toBe(1)
        ->and(UserShortcut::query()->where('user_id', $user->id)->sole()->visits)->toBe(3);
});

test('the overview itself is never recorded as activity', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('overview'))->assertOk();

    expect(UserShortcut::query()->where('user_id', $user->id)->count())->toBe(0);
});

test('activity is not recorded for guests', function () {
    $this->get(route('home'))->assertOk();

    expect(UserShortcut::query()->count())->toBe(0);
});

test('a recently used destination outranks a heavily used stale one', function () {
    $user = User::factory()->create();

    UserShortcut::factory()->for($user)->forRoute('profile.edit')->stale(120)->create();
    UserShortcut::factory()->for($user)->forRoute('dashboard')->create([
        'visits' => 5,
        'last_visited_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('overview'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('suggested.0.route', 'dashboard')
            ->where('suggested.1.route', 'profile.edit')
        );
});

test('fallback destinations are offered until there is activity to rank', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('overview'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('suggested', [])
            ->has('fallback', 3)
            ->where('fallback.0.route', 'dashboard')
        );
});

test('the fallback disappears once a destination has been used', function () {
    $user = User::factory()->create();

    UserShortcut::factory()->for($user)->forRoute('dashboard')->create();

    $this->actingAs($user)
        ->get(route('overview'))
        ->assertInertia(fn (Assert $page) => $page->where('fallback', []));
});

test('a destination can be pinned and stops being suggested', function () {
    $user = User::factory()->create();

    UserShortcut::factory()->for($user)->forRoute('dashboard')->create();

    $this->actingAs($user)
        ->from(route('overview'))
        ->post(route('shortcuts.store'), ['route' => 'dashboard'])
        ->assertRedirect(route('overview'));

    $this->actingAs($user)
        ->get(route('overview'))
        ->assertInertia(fn (Assert $page) => $page
            ->has('pinned', 1)
            ->where('pinned.0.route', 'dashboard')
            ->where('pinned.0.pinned', true)
            ->where('suggested', [])
        );
});

test('pinning a destination never visited still works', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('overview'))
        ->post(route('shortcuts.store'), ['route' => 'security.edit'])
        ->assertRedirect(route('overview'));

    expect(UserShortcut::query()->where('user_id', $user->id)->sole())
        ->visits->toBe(0)
        ->pinned_at->not->toBeNull();
});

test('unpinning keeps the accumulated activity', function () {
    $user = User::factory()->create();

    UserShortcut::factory()->for($user)->forRoute('dashboard')->pinned()->create([
        'visits' => 9,
    ]);

    $this->actingAs($user)
        ->from(route('overview'))
        ->delete(route('shortcuts.destroy'), ['route' => 'dashboard'])
        ->assertRedirect(route('overview'));

    $shortcut = UserShortcut::query()->where('user_id', $user->id)->sole();

    expect($shortcut->pinned_at)->toBeNull()
        ->and($shortcut->visits)->toBe(9);
});

test('an unknown route cannot be pinned', function () {
    $this->actingAs(User::factory()->create())
        ->from(route('overview'))
        ->post(route('shortcuts.store'), ['route' => 'totally.made.up'])
        ->assertSessionHasErrors('route');

    expect(UserShortcut::query()->count())->toBe(0);
});

test('auth routes cannot be pinned', function () {
    $this->actingAs(User::factory()->create())
        ->from(route('overview'))
        ->post(route('shortcuts.store'), ['route' => 'login'])
        ->assertSessionHasErrors('route');
});

test('one user never sees another user shortcuts', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    UserShortcut::factory()->for($other)->forRoute('dashboard')->pinned()->create();

    $this->actingAs($user)
        ->get(route('overview'))
        ->assertInertia(fn (Assert $page) => $page->where('pinned', []));
});
