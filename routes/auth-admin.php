<?php

use App\Http\Controllers\Admin\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\Admin;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('guest:admin')->group(function () {
    
    Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])->name('admin-login-page');

    Route::post('login', [AdminAuthenticatedSessionController::class, 'store'])->name('admin-login');

});

Route::prefix('admin')->middleware('auth:admin')->group(function () {

    Route::get('/admin-page/{username}', function ($username) {
        return view('admin.admin_page', ['username' => $username]);
    })->name('admin-page');

    Route::post('logout', [AdminAuthenticatedSessionController::class, 'destroy'])->name('admin-logout');
});
