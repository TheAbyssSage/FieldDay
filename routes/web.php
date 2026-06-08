<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
// protecting the dashboard route with auth and verified middleware, only authenticated and verified users can access it.

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::view('admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
});

require __DIR__.'/settings.php';

// the route /admin/dashboard is now guarded by three middlewares — logged in (auth), verified email (verified), and role:admin. Anyone who isn't an admin gets a 403.