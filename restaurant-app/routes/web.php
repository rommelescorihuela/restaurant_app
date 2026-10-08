<?php

use App\Http\Controllers\MenuController;
use App\Http\Controllers\CocinaTvController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicHelpController;
use App\Http\Controllers\PublicReservationController;
use App\Http\Controllers\RegisterRestaurantController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/register', [RegisterRestaurantController::class, 'show'])->name('register');
Route::post('/register', [RegisterRestaurantController::class, 'store'])->name('register.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['web', InitializeTenancyByPath::class])->group(function () {
    Route::get('/{tenant}', function () {
        return view('welcome');
    })->name('restaurant.home');
    Route::get('/{tenant}/menu', [MenuController::class, 'index'])->name('menu');
    Route::post('/{tenant}/menu/llamar-mesonero', [PublicHelpController::class, 'store'])->name('menu.call-waiter');
    Route::get('/{tenant}/cocina', [CocinaTvController::class, 'index'])->name('cocina.tv');
    Route::post('/{tenant}/reservas', [PublicReservationController::class, 'store'])->name('reservations.store');
});
