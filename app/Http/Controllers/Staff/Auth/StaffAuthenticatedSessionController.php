<?php

namespace App\Http\Controllers\Staff\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StaffLoginRequest;
use App\Models\FE;
use App\Models\Staff;
use App\Models\Users;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StaffAuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $account_type = 'staff';
        return view('staff.staff_login', ['account_type' => $account_type]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(StaffLoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('staff-page'));
    }

    /**
     * Proceed into main page and pass in data after authentication.
     */
    public function proceed(): View
    {
        //get the username of the authenticated user
        $username = Auth::guard('staff')->user()->username;
        //get id of staff
        $staff_id = Staff::where('username', $username)->first()->id;
        //get all users that the staff is in charge of
        $user_list = Users::where('staff_id_in_charge', $staff_id)->get();
        //get name list
        $name_list = json_encode(Users::where('staff_id_in_charge', $staff_id)->pluck('username')->toArray());

        return view('staff.staff_page', ['username' => $username, 'user_list' => $user_list, 'name_list' => $name_list]);
    }

    /**
     * Search user by ID.
     */
    public function viewUserByID(Request $request)
    {
        //get the staff id
        $staff_id = Staff::where('username', Auth::guard('staff')->user()->username)->first()->id;
        $id = $request->input('search_id');

        //find the user details by id
        $user_details = Users::find($id);

        //show error message if invalid id
        if (!$user_details || $user_details->staff_id_in_charge != $staff_id) {
            return redirect()->back()->with('user_id_invalid', 'User not found.');
        }
    
        //find the staff id in charge of user
        $staff_id = Staff::where('id', $user_details->staff_id_in_charge)->first()->id;
        //find the staff name in charge of user
        $staff_name = Staff::find($staff_id)->username; 
        //find the fe list of user 
        $fe_list = FE::where('fe_user_id', $id)->get();
        return view('staff.staff_view_user', ['user_details' => $user_details, 'fe_list' => $fe_list, 'staff_name' => $staff_name]);
    }

    /**
     * Search user by name.
     */
    public function viewUserByName(Request $request)
    {
        $staff_id = Staff::where('username', Auth::guard('staff')->user()->username)->first()->id;
        $name = $request->input('search_name');

        $user_details = Users::where('username', $name)->first();

        if (!$user_details || $user_details->staff_id_in_charge != $staff_id) {
            return redirect()->back()->with('user_name_invalid', 'User not found.');
        }

        $staff_id = Staff::where('id', $user_details->staff_id_in_charge)->first()->id;

        $staff_name = Staff::find($staff_id)->username;

        $fe_list = FE::where('fe_user_id', $user_details->id)->get();

        return view('staff.staff_view_user', ['user_details' => $user_details, 'fe_list' => $fe_list, 'staff_name' => $staff_name]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('staff')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
