<?php

use App\Http\Controllers\OtherAcc\Auth\OtherAccAuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('otherAcc')->middleware('guest:otherAcc')->group(function () {
    
    Route::get('login', [OtherAccAuthenticatedSessionController::class, 'create'])->name('otherAcc-login-page');

    Route::post('login', [OtherAccAuthenticatedSessionController::class, 'store'])->name('otherAcc-login');;

});

Route::prefix('otherAcc')->middleware('auth:otherAcc')->group(function () {

    Route::get('otherAcc-page', [OtherAccAuthenticatedSessionController::class, 'proceed'])->name('otherAcc-page');

    Route::post('logout', [OtherAccAuthenticatedSessionController::class, 'destroy'])->name('otherAcc-logout');

    Route::get('view-user', [OtherAccAuthenticatedSessionController::class, 'viewUser'])->name('otherAcc-view-user');

    Route::post('getUsersData', [OtherAccAuthenticatedSessionController::class, 'getUsersData'])->name('otherAcc-getUsersData');

    Route::post('getFeData', [OtherAccAuthenticatedSessionController::class, 'getFeData'])->name('otherAcc-getFeData');

    Route::get('fetchUserData', [OtherAccAuthenticatedSessionController::class, 'fetchUserData'])->name('otherAcc-fetchUserData');

    Route::get('generate-report', [OtherAccAuthenticatedSessionController::class, 'generateReport'])->name('otherAcc-generate-report');
});
