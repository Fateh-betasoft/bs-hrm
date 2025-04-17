<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserCreationController;
use App\Http\Controllers\UserDetailsController;
use App\Http\Controllers\UserRegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user-details/{token}', [UserRegistrationController::class, 'showRegistrationForm'])
    ->name('user.registration.form');

Route::post('/user-details/{token}', [UserRegistrationController::class, 'completeRegistration'])
    ->name('user.registration.complete');

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// User Details Multi-Step Form Routes (Protected by auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/user-details', [UserDetailsController::class, 'showPart1'])->name('user-details.part1.show');
    Route::post('/user-details/part1', [UserDetailsController::class, 'storePart1'])->name('user-details.part1.store');
    Route::post('/user-details/part2', [UserDetailsController::class, 'storePart2'])->name('user-details.part2.store');
    Route::post('/submit-user-form', [UserCreationController::class, 'submitForm']);
    Route::get('/user-details/{slug}', [UserCreationController::class, 'userDetails']);
});
