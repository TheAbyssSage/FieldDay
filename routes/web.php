<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
// protecting the dashboard route with auth and verified middleware, only authenticated and verified users can access it.

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});
// de route /admin/trips/create is nu ook beschermd door de auth, verified, en role:admin middlewares, alleen admins kunnen deze route bereiken en een nieuwe trip aanmaken.
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::view('admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
    Route::view('admin/trips', 'admin.trips')->name('admin.trips');
    Route::view('admin/trips/create', 'admin.trips.create')->name('admin.trips.create');
});

require __DIR__.'/settings.php';

// the route /admin/dashboard is now guarded by three middlewares — logged in (auth), verified email (verified), and role:admin. Anyone who isn't an admin gets a 403.