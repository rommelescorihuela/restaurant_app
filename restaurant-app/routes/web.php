<?php

use App\Http\Controllers\MenuController;
use App\Http\Controllers\CocinaTvController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicHelpController;
use App\Http\Controllers\PublicReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::post('/menu/llamar-mesonero', [PublicHelpController::class, 'store'])->name('menu.call-waiter');
Route::get('/cocina/tv', [CocinaTvController::class, 'index'])->name('cocina.tv');
Route::post('/reservas', [PublicReservationController::class, 'store'])->name('reservations.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
