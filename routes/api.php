<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::controller(RegisterController::class)->group(function () {
    Route::post('/register', 'register')->name('register');
    Route::get('/confirm/{token}', 'confirm');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
