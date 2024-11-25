<?php

use App\Http\Controllers\Staff\Auth\StaffAuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('staff')->middleware('guest:staff')->group(function () {
    
    Route::get('login', [StaffAuthenticatedSessionController::class, 'create'])->name('staff-login-page');

    Route::post('login', [StaffAuthenticatedSessionController::class, 'store'])->name('staff-login');;

});

Route::prefix('staff')->middleware('auth:staff')->group(function () {

    Route::get('staff-page', [StaffAuthenticatedSessionController::class, 'proceed'])->name('staff-page');

    Route::post('view-user-by-id', [StaffAuthenticatedSessionController::class, 'viewUserByID'])->name('staff-view-user-by-id');

    Route::post('view-user-by-name', [StaffAuthenticatedSessionController::class, 'viewUserByName'])->name('staff-view-user-by-name');

    Route::get('generate-report', [StaffAuthenticatedSessionController::class, 'generateReport'])->name('staff-generate-report');

    Route::post('logout', [StaffAuthenticatedSessionController::class, 'destroy'])->name('staff-logout');
});
