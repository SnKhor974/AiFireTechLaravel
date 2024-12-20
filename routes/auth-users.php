<?php

use App\Http\Controllers\Users\Auth\UsersAuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->middleware('guest:users')->group(function () {
    
    Route::get('login', [UsersAuthenticatedSessionController::class, 'create'])->name('users-login-page');

    Route::post('login', [UsersAuthenticatedSessionController::class, 'store'])->name('users-login');;

});

Route::prefix('users')->middleware('auth:users')->group(function () {

    Route::get('users-page', [UsersAuthenticatedSessionController::class, 'proceed'])->name('users-page');

    Route::post('logout', [UsersAuthenticatedSessionController::class, 'destroy'])->name('users-logout');

    Route::post('getFeData', [UsersAuthenticatedSessionController::class, 'getFeData'])->name('users-getFeData');
});
