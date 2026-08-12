<?php

use App\Http\Middleware\HandleCustomizer;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('the customizer defaults are shared when no cookie is present', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('customizer', HandleCustomizer::DEFAULTS)
        );
});

test('the customizer cookie is shared with the front end', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withUnencryptedCookie('customizer', json_encode([
            'direction' => 'rtl',
            'layout' => 'horizontal',
            'container' => 'boxed',
            'cardStyle' => 'shadow',
            'primaryColor' => '#7c3aed',
            'secondaryColor' => '#ede9fe',
        ]))
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('customizer.direction', 'rtl')
            ->where('customizer.layout', 'horizontal')
            ->where('customizer.container', 'boxed')
            ->where('customizer.cardStyle', 'shadow')
            ->where('customizer.primaryColor', '#7c3aed')
        );
});

test('the root template renders the customizer attributes', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withUnencryptedCookie('customizer', json_encode([
            'direction' => 'rtl',
            'layout' => 'horizontal',
            'container' => 'boxed',
            'cardStyle' => 'shadow',
        ]))
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('dir="rtl"', false)
        ->assertSee('data-layout="horizontal"', false)
        ->assertSee('data-boxed-layout="boxed"', false)
        ->assertSee('data-card-style="shadow"', false);
});

test('a malformed customizer cookie falls back to the defaults', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withUnencryptedCookie('customizer', 'not-json')
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('customizer', HandleCustomizer::DEFAULTS)
        );
});

test('unknown customizer keys are discarded', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withUnencryptedCookie('customizer', json_encode([
            'layout' => 'horizontal',
            'evil' => '<script>alert(1)</script>',
        ]))
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('customizer.layout', 'horizontal')
            ->missing('customizer.evil')
        );
});
