<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::view('/worksheets', 'worksheets')
    ->middleware(['auth', 'verified'])
    ->name('worksheets');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
