<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function user_login()
    {
        return view('user.user_login');
    }
}
