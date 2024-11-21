<?php

use App\Http\Controllers\Users\Auth\UsersAuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->middleware('guest:users')->group(function () {
    
    Route::get('login', [UsersAuthenticatedSessionController::class, 'create'])->name('users-login-page');

    Route::post('login', [UsersAuthenticatedSessionController::class, 'store'])->name('users-login');;

});

Route::prefix('users')->middleware('auth:users')->group(function () {

    Route::get('/users-page/{username}', function ($username) {
        return view('users.users_page',['username' => $username]);
    })->name('users-page');
    Route::post('logout', [UsersAuthenticatedSessionController::class, 'destroy'])->name('users-logout');
});
