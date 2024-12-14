<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

require __DIR__.'/auth-users.php';
require __DIR__.'/auth-admin.php';
require __DIR__.'/auth-staff.php';
require __DIR__.'/auth-otherAcc.php';