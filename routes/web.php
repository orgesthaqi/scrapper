<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FollowersController;
use App\Http\Controllers\DashboardController;

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/followers', [FollowersController::class, 'index'])->name('followers');
    Route::get('/followers/{account}', [FollowersController::class, 'show'])->name('followers.show');
    Route::get('/followers/export/{account}', [FollowersController::class, 'export'])->name('followers.export');
    Route::post('/followers', [FollowersController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
