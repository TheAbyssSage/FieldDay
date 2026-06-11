<?php

use Illuminate\Support\Facades\Route;
Route::view('admin/users', 'admin.users')->name('admin.users');
Route::view('/', 'welcome')->name('home');
Route::view('admin/users/create', 'admin.users.create')->name('admin.users.create');
// protecting the dashboard route with auth and verified middleware, only authenticated and verified users can access it.

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard');
    })->name('dashboard');
});
// de route /admin/trips/create is nu ook beschermd door de auth, verified, en role:admin middlewares, alleen admins kunnen deze route bereiken en een nieuwe trip aanmaken.
// admin routes — protected by auth, verified, and role:admin middleware
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::view('admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
    Route::view('admin/trips', 'admin.trips')->name('admin.trips');
    Route::view('admin/trips/create', 'admin.trips.create')->name('admin.trips.create');

    Route::get('admin/trips/{trip}/edit', function (\App\Models\FieldTrip $trip) {
        return view('admin.trips.edit', ['trip' => $trip]);
    })->name('admin.trips.edit');
});
// de require dir settings.php zorgt ervoor dat we de routes in routes/settings.php ook laden, deze routes zijn voor het beheren van de instellingen van de applicatie, en zijn ook beschermd door de auth, verified, en role:admin middlewares.
require __DIR__.'/settings.php';

// the route /admin/dashboard is now guarded by three middlewares — logged in (auth), verified email (verified), and role:admin. Anyone who isn't an admin gets a 403.