<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;

class AdminController extends Controller
{
    public function admin_login()
    {
        return view('admin.admin_login');
    }

    public function admin_page()
    {
        return view('admin.admin_page');
    }
}
