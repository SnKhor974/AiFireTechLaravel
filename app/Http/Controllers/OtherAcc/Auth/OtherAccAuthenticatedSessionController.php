<?php

namespace App\Http\Controllers\OtherAcc\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\OtherAccLoginRequest;
use App\Models\FE;
use App\Models\OtherAcc;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OtherAccAuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $account_type = 'otherAcc';
        return view('otherAcc.otherAcc_login', ['account_type' => $account_type]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(OtherAccLoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        
        $request->session()->regenerate();

        return redirect()->intended(route('otherAcc-page'));
    }

    /**
     * Proceed into main page and pass in data after authentication.
     */
    public function proceed(): View
    {
        //get the username of the authenticated user
        $username = Auth::guard('otherAcc')->user()->username;
        //get id of user
        $user_id = OtherAcc::where('username', $username)->first()->id;
        //get the user's data
        
        return view('otherAcc.otherAcc_page', ['username' => $username]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('otherAcc')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
