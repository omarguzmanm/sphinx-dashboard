<?php

use App\Http\Controllers\OverviewController;
use App\Http\Controllers\ShortcutController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('overview', OverviewController::class)->name('overview');
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    Route::post('shortcuts', [ShortcutController::class, 'store'])->name('shortcuts.store');
    Route::delete('shortcuts', [ShortcutController::class, 'destroy'])->name('shortcuts.destroy');
});

require __DIR__.'/settings.php';
