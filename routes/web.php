<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Home\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

require __DIR__.'/auth-users.php';
require __DIR__.'/auth-admin.php';
require __DIR__.'/auth-staff.php';
require __DIR__.'/auth-otherAcc.php';