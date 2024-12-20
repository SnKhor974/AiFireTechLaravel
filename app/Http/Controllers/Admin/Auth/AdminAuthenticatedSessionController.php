<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;
use App\Models\Admin;
use App\Models\FE;
use App\Models\Staff;
use App\Models\Users;
use App\Models\Areas;
use App\Models\FeBrands;
use App\Models\OtherAcc;
use App\Models\AreaInCharge;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log; // Import the Log facade

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AdminAuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {

        $account_type = 'admin';
        return view('admin.admin_login', ['account_type' => $account_type]);
    }

    /**git 
     * Handle an incoming authentication request.
     */
    public function store(AdminLoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();     
        
        //redirect to the page and pass in username
        return redirect()->intended(route('admin-page'));
    }

    /**
     * Proceed into main page and pass in data after authentication.
     */
    public function proceed(): View
    {
        //get the username of the authenticated user
        $username = Auth::guard('admin')->user()->username;

        //get all users
        $user_list = Users::all();

        //get all area names and area id
        $area_list = Areas::orderBy('area_name', 'asc')->get();

        //get all staff
        $staff_list = Staff::all();

        //get name list
        $name_list = json_encode(Users::pluck('username')->toArray());

        //get area and staff list for autocomplete
        $area_list_autocomplete = json_encode(Areas::pluck('area_name')->toArray());
        $staff_list_autocomplete = json_encode(Staff::pluck('username')->toArray());

        $alphanumeric = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        
        return view('admin.admin_page', ['username' => $username, 'user_list' => $user_list, 'name_list' => $name_list, 'area_list_autocomplete' => $area_list_autocomplete, 'staff_list' => $staff_list, 'staff_list_autocomplete' => $staff_list_autocomplete, 'area_list' => $area_list, 'alphanumeric' => $alphanumeric]);
    }

    /**
     * Search user.
     */
    public function viewUser(Request $request)
    {
        $data = $request->all();
        
        //find the user details by id
        $user_details = Users::find($data['id']);

        // Find the staff id in charge of user
        $staff_id = $user_details->staff_id_in_charge;
    
        if ($staff_id == 0) {
            // If the staff_id is 0, set the staff_name to 'admin'
            $staff_name = 'Admin';
        } else {
            // Find the staff name in charge of user
            $staff_name = Staff::find($staff_id)->username;
        }
        //find the fe list of user 
        $fe_list = FE::where('fe_user_id', $data['id'])->get();

        //get area and staff list for autocomplete
        $area_list_autocomplete = json_encode(Areas::pluck('area_name')->toArray());
        $staff_list_autocomplete = json_encode(Staff::pluck('username')->toArray());

        return view('admin.admin_view_user', ['user_details' => $user_details, 'fe_list' => $fe_list, 'staff_name' => $staff_name, 'area_list_autocomplete' => $area_list_autocomplete, 'staff_list_autocomplete' => $staff_list_autocomplete]);
        
    }

    /**
     * Register new account.
     */
    public function storeReg(Request $request): RedirectResponse
    {
        $data = $request->all();
        // dd($data);

        if($data['role'] == 'admin'){
            $exists = Admin::where('username', $data['username'])->exists();

            if ($exists) {
                // If the username exists, redirect back with an error message
                return redirect()->back()->withErrors(['username' => 'Error: The username already exists.']);
            }
            
            $user = Admin::create([
                'username' => $data['username'],
                'password' => $data['password'],
            ]);

        }
        
        if($data['role'] == 'staff'){
            $exists = Staff::where('username', $data['username'])->exists();

            if ($exists) {
                // If the username exists, redirect back with an error message
                return redirect()->back()->withErrors(['username' => 'Error: The username already exists.']);
            }

            $user = Staff::create([
                'username' => $data['username'],
                'password' => $data['password'],
            ]);

        }
        if($data['role'] == 'user'){
            $exists = Users::where('username', $data['username'])->exists();

            if ($exists) {
                // If the username exists, redirect back with an error message
                return redirect()->back()->withErrors(['username' => 'Error: The username already exists.']);
            }

            $user = Users::create([
                'username' => $data['username'],
                'password' => $data['password'],
                'company_name' => $data['company_name'],
                'company_address' => $data['company_address'],
                'person_in_charge' => $data['person_in_charge'],
                'contact' => $data['contact'],
                'email' => $data['email'],
                'area' => $data['area'],
                'staff_id_in_charge' => 0
            ]);
    
        }
        if ($data['role'] == 'other'){
            $exists = OtherAcc::where('username', $data['username'])->exists();

            if ($exists) {
                // If the username exists, redirect back with an error message
                return redirect()->back()->withErrors(['username' => 'Error: The username already exists.']);
            }

            $selectedAreas = $request->input('areas');
            
            $user = OtherAcc::create([
                'username' => $data['username'],
                'password' => $data['password'],
            ]);

            $newUserId = $user->id;
            foreach ($selectedAreas as $area) {
                AreaInCharge::create([
                    'user_id' => $newUserId,
                    'area_id' => $area,
                ]);
            }
        }
        return redirect()->intended(route('admin-page'))->with('success', 'Registration Successful');
    }

    /**
     * Add new FE.
     */
    public function addFE(Request $request)
    {
        $data = $request->all();
        $fe_location = $data['location'];
        $fe_serial_number = strtoupper($data['serial_number']); // Convert to uppercase

        // Define the serial number pattern
        $pattern = '/^[A-Za-z]{2}[0-9]{6}[A-Za-z][0-9]{6}$/';

        // Check if the serial number matches the pattern
        if (!preg_match($pattern, $fe_serial_number)) {
            return redirect()->back()->withErrors(['serial_number' => 'Error: Invalid serial number format.']);
        }

        // Check if the serial number already exists in the database
        $exists = FE::where('fe_serial_number', $fe_serial_number)->exists();

        if ($exists) {
            // If the serial number exists, redirect back with an error message
            return redirect()->back()->withErrors(['serial_number' => 'Error: Serial number already exists.']);
        }

        $fe_exp_date = $fe_exp_date = str_replace('-', '/', $data['expiry_date']);;

        $fe_type = "UNKNOWN";

        if ($fe_serial_number[8] === 'Y'){
            $fe_type = "ABC";
        }else if ($fe_serial_number[8] === 'Z'){
            $fe_type = "CO2";
        }

        $brands = FeBrands::all();

        $fe_brand = "UNKNOWN";

        foreach ($brands as $brand){
            if (substr($fe_serial_number, 0, 2) === $brand->short){
                $fe_brand = $brand->name;
            }
        }

        $fe_man_date = substr(substr($fe_serial_number, 2, 6), 0, 2) . '/' . substr(substr($fe_serial_number, 2, 6), 2);
        $fe_user_id = $data['user_id'];
        
        //dd($fe_location, $fe_serial_number, $fe_type, $fe_brand, $fe_man_date, $fe_exp_date, $fe_user_id);

        
        $fe = FE::create([
            'fe_location' => $fe_location,
            'fe_serial_number' => $fe_serial_number,
            'fe_type' => $fe_type,
            'fe_brand' => $fe_brand,
            'fe_man_date' => $fe_man_date,
            'fe_exp_date' => $fe_exp_date,
            'fe_user_id' => $fe_user_id
        ]);
        $fe->save();

        return redirect()->back()->with('success', 'Added New Fire Extinguisher.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function generateReport(Request $request){
        
        $user_details = Users::find($request->id);

        $fe_list = FE::where('fe_user_id', $request->id)->get();

        $reportID = strval(rand(100000, 999999));
        $reportCurrentTime = date("Y-m-d");
        $reportName = strval($user_details->username);


        $sourceFile = public_path('report/FE List Report Template.xlsx');
        $fileName = "FE-Report-{$reportName}-{$reportCurrentTime}-{$reportID}.xlsx";
        $destinationFile = public_path("report/{$fileName}");

        copy($sourceFile, $destinationFile);


        $spreadsheet = IOFactory::load($destinationFile);
        $sheet = $spreadsheet->getActiveSheet();

        $drawing = new Drawing();
        $drawing->setName('Profile');
        $drawing->setPath('img/Screenshot 2024-07-15 203702.png');
        $drawing->setHeight(10 * 28.3465);
        $drawing->setWidth(20.6 * 28.3465);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);

        $writer = new Xlsx($spreadsheet);
        $writer->save($destinationFile);
        
        $sheet->setCellValue('C10', $user_details->company_name);
        $sheet->setCellValue('C11', $user_details->company_address);
        $sheet->setCellValue('C12', $user_details->person_in_charge);
        $sheet->setCellValue('C13', $user_details->contact);
        $sheet->setCellValue('C14', $user_details->email);
                
        $counter = 1;
        $excelrow = 17;

        foreach ($fe_list as $reportrow){
            
            $sheet->setCellValue('A' . $excelrow, $counter);
            $sheet->setCellValue('B' . $excelrow, $reportrow->fe_serial_number);
            $sheet->setCellValue('C' . $excelrow, $reportrow->fe_type);
            $sheet->setCellValue('D' . $excelrow, $reportrow->fe_brand);
            $sheet->setCellValue('E' . $excelrow, $reportrow->fe_man_date);
            $sheet->setCellValue('F' . $excelrow, $reportrow->fe_exp_date);

            $sheet->getStyle('A' . $excelrow . ':F' . $excelrow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ]);

            $excelrow++;
            $counter++;
        }
        
        $writer = new Xlsx($spreadsheet);
        $writer->save($destinationFile);

        return response()->download($destinationFile, $fileName)->deleteFileAfterSend(true);

    }
    public function getUsersData(Request $request)
    {   
        
        // Fetch data from your database (you can customize this)
        $users = Users::all();
        $staff = Staff::all();
        // dd($user->staff);
        // Map data to a structure that DataTable expects
        $data = $users->map(function ($user) {

            if ($user->staff_id_in_charge == 0){
                $staff_in_charge = 'Admin';
            }else{
 
                $staff = Staff::where('id', $user->staff_id_in_charge)->first();
                $staff_in_charge = $staff->username;   
            }

            return [
                'id' => $user->id,
                'username' => $user->username,
                'area' => $user->area,
                'staff_in_charge' => $staff_in_charge,
            ];
        });
        // Return the data as a JSON response
        return response()->json(['data' => $data]);
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
                'id' => $fe->id,
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

    public function deleteUser(Request $request)
    {
        $userId = $request->input('id');

        // Find the user and delete
        $user = Users::find($userId);
        if ($user) {
            $user->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function deleteFe(Request $request)
    {
        $feId = $request->input('id');

        // Find the fe and delete
        $fe = FE::where('id', $feId)->first();
        if ($fe) {
            $fe->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    // Fetch user data for editing
    public function fetchUserData(Request $request)
    {
        // dd($request->id);
        $user = Users::findOrFail($request->id);
        if ($user->staff_id_in_charge == 0){
            $staff_in_charge = 'Admin';
        }else{

            $staff = Staff::where('id', $user->staff_id_in_charge)->first();
            $staff_in_charge = $staff->username;   
        }
        $user['staff_in_charge'] = $staff_in_charge;
        
        return response()->json($user);
    }

    // Fetch fe data for editing
    public function fetchFeData(Request $request)
    {
        $fe = FE::where('id', $request->id)->firstOrFail();
        
        return response()->json($fe);
    }

    // Update user data
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|max:255',
            'area' => 'required|string|max:255',
            'staff_in_charge' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:255',
            'person_in_charge' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'email' => 'required|string|max:255'
        ]);

        // Find the staff id using the staff name
        $staff_id = Staff::where('username', $request->staff_in_charge)->first()->id;
    
        $user = Users::find($request->id);
    
        // Prepare the data for updating
        $data = $request->only(['username', 'area', 'company_name', 'company_address', 'person_in_charge', 'contact', 'email']);
    
        // Check if the password is provided and hash it
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        // Update the staff id in charge
        $data['staff_id_in_charge'] = $staff_id;
    
        // Update the user with the prepared data
        $user->update($data);
    
        return redirect()->back()->with('success', 'User updated successfully!');
    }

    public function updateFe(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:fire_extinguisher,id',
            'fe_serial_number' => 'required|string|max:255',
            'fe_exp_date' => 'required|string|max:255',
        ]);

        $data = $request->all();

        $fe_location = $data['fe_location'];
        $fe_serial_number = strtoupper($data['fe_serial_number']); // Convert to uppercase

        // Define the serial number pattern
        $pattern = '/^[A-Za-z]{2}[0-9]{6}[A-Za-z][0-9]{6}$/';

        // Check if the serial number matches the pattern
        if (!preg_match($pattern, $fe_serial_number)) {
            return redirect()->back()->withErrors(['fe_serial_number' => 'Error: Invalid serial number format.']);
        }

        // Check if the serial number already exists in the database (excluding the current record)
        $exists = FE::where('fe_serial_number', $fe_serial_number)
                    ->where('id', '!=', $data['id'])
                    ->exists();

        if ($exists) {
            // If the serial number exists, redirect back with an error message
            return redirect()->back()->withErrors(['fe_serial_number' => 'Error: Serial number already exists.']);
        }

        $fe_exp_date = str_replace('-', '/', $data['fe_exp_date']);

        $fe_type = "UNKNOWN";

        if ($fe_serial_number[8] === 'Y') {
            $fe_type = "ABC";
        } else if ($fe_serial_number[8] === 'Z') {
            $fe_type = "CO2";
        }

        $brands = FeBrands::all();

        $fe_brand = "UNKNOWN";

        foreach ($brands as $brand) {
            if (substr($fe_serial_number, 0, 2) === $brand->short) {
                $fe_brand = $brand->name;
            }
        }

        $fe_man_date = substr(substr($fe_serial_number, 2, 6), 0, 2) . '/' . substr(substr($fe_serial_number, 2, 6), 2);

        // Find the FE record by 'id'
        $fe = FE::where('id', $data['id'])->firstOrFail();

        // Log the data being updated
        Log::info('Updating FE record:', [
            'fe_location' => $fe_location,
            'fe_serial_number' => $fe_serial_number,
            'fe_exp_date' => $fe_exp_date,
            'fe_type' => $fe_type,
            'fe_brand' => $fe_brand,
            'fe_man_date' => $fe_man_date,
    
        ]);
       
        // Update the FE record
        $fe->update([
            'fe_location' => $fe_location,
            'fe_serial_number' => $fe_serial_number,
            'fe_exp_date' => $fe_exp_date,
            'fe_type' => $fe_type,
            'fe_brand' => $fe_brand,
            'fe_man_date' => $fe_man_date,
        ]);
       

        return redirect()->back()->with('success', 'Fire extinguisher updated successfully!');
    }
}
