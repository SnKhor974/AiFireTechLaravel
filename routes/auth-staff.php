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

    Route::get('view-user', [StaffAuthenticatedSessionController::class, 'viewUser'])->name('staff-view-user');

    Route::get('generate-report', [StaffAuthenticatedSessionController::class, 'generateReport'])->name('staff-generate-report');

    Route::post('register', [StaffAuthenticatedSessionController::class, 'storeReg'])->name('staff-store-reg');

    Route::post('logout', [StaffAuthenticatedSessionController::class, 'destroy'])->name('staff-logout');

    Route::post('add-fe', [StaffAuthenticatedSessionController::class, 'addFE'])->name('staff-add-fe');

    Route::post('getUsersData', [StaffAuthenticatedSessionController::class, 'getUsersData'])->name('staff-getUsersData');

    Route::post('getFeData', [StaffAuthenticatedSessionController::class, 'getFeData'])->name('staff-getFeData');

    Route::get('fetchFeData', [StaffAuthenticatedSessionController::class, 'fetchFeData'])->name('staff-fetchFeData');

    Route::get('fetchUserData', [StaffAuthenticatedSessionController::class, 'fetchUserData'])->name('staff-fetchUserData');

    Route::post('updateFe', [StaffAuthenticatedSessionController::class, 'updateFe'])->name('staff-updateFeData');

    Route::post('update', [StaffAuthenticatedSessionController::class, 'update'])->name('staff-updateUserData');

    Route::post('delete-user', [StaffAuthenticatedSessionController::class, 'deleteUser'])->name('staff-deleteUserData');

    Route::post('delete-fe', [StaffAuthenticatedSessionController::class, 'deleteFe'])->name('staff-deleteFeData');
});
