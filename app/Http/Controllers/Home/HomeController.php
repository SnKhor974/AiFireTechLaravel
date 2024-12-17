<?php
namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Clear all session data
        $request->session()->flush();

        // Return the home view
        return view('home');
    }
}