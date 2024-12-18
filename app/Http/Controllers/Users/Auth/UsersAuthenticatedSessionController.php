<?php

namespace App\Http\Controllers\Users\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UsersLoginRequest;
use App\Models\FE;
use App\Models\Users;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UsersAuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        
        $account_type = 'users';
        return view('users.users_login', ['account_type' => $account_type]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(UsersLoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        
        $request->session()->regenerate();

        return redirect()->intended(route('users-page'));
    }

    /**
     * Proceed into main page and pass in data after authentication.
     */
    public function proceed(): View
    {
        //get the username of the authenticated user
        $username = Auth::guard('users')->user()->username;
        //get id of user
        $user_id = Users::where('username', $username)->first()->id;
        //get all user details and fe list
        $user_details = Users::find($user_id);
        $fe_list = FE::where('fe_user_id', $user_id)->get();
        return view('users.users_page', ['username' => $username, 'fe_list' => $fe_list, 'user_details' => $user_details]);
    }

    public function getFeData(Request $request)
    {   
        $userId = $request->input('user_id');
        //find the fe list of user 
        $fe_list = FE::where('fe_user_id', $userId)->get();
        // dd($user->staff);
        // Map data to a structure that DataTable expects
        $fe_data = $fe_list->map(function ($fe) {

            return [
                'fe_id' => $fe->fe_id,
                'fe_location' => $fe->fe_location,
                'fe_serial_number' => $fe->fe_serial_number,
                'fe_type' => $fe->fe_type,
                'fe_brand' => $fe->fe_brand,
                'fe_man_date' => $fe->fe_man_date,
                'fe_exp_date' => $fe->fe_exp_date,
            ];
        });
        // Return the data as a JSON response
        return response()->json(['fe_data' => $fe_data]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('users')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
