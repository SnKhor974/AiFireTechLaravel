<?php

use App\Http\Controllers\Admin\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\Admin;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('guest:admin')->group(function () {
    
    Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])->name('admin-login-page');

    Route::post('login', [AdminAuthenticatedSessionController::class, 'store'])->name('admin-login');

});

Route::prefix('admin')->middleware('auth:admin')->group(function () {

    Route::get('admin-page', [AdminAuthenticatedSessionController::class, 'proceed'])->name('admin-page');

    Route::post('view-user', [AdminAuthenticatedSessionController::class, 'viewUser'])->name('admin-view-user');

    Route::get('generate-report', [AdminAuthenticatedSessionController::class, 'generateReport'])->name('admin-generate-report');

    Route::post('register', [AdminAuthenticatedSessionController::class, 'storeReg'])->name('admin-store-reg');

    Route::post('logout', [AdminAuthenticatedSessionController::class, 'destroy'])->name('admin-logout');

    Route::post('add-fe', [AdminAuthenticatedSessionController::class, 'addFE'])->name('admin-add-fe');

    Route::post('/admin-page/getUsersData', [AdminAuthenticatedSessionController::class, 'getUsersData'])->name('getUsersData');

    Route::post('/admin-page/delete-user', [AdminAuthenticatedSessionController::class, 'deleteUser']);
    
});
