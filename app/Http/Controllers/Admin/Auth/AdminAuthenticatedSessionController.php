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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
    public function create(): View
    {
        $account_type = 'admin';
        return view('admin.admin_login', ['account_type' => $account_type]);
    }

    /**
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
        dd($user_list[5]);
        //get all staff
        $staff_list = Staff::all();

        //get name list
        $name_list = json_encode(Users::pluck('username')->toArray());

        //get area list
        $area_list = json_encode(Areas::pluck('area_name')->toArray());

        return view('admin.admin_page', ['username' => $username, 'user_list' => $user_list, 'name_list' => $name_list, 'area_list' => $area_list, 'staff_list' => $staff_list]);
    }

    /**
     * Search user.
     */
    public function viewUser(Request $request)
    {
        $search = $request->input('search');

        if ($search == 'id') {
            $id = $request->input('search_id');

            //find the user details by id
            $user_details = Users::find($id);

            //show error message if invalid id
            if (!$user_details) {
                return redirect()->back()->with('user_id_invalid', 'User not found.');
            }
        
            //find the staff id in charge of user
            $staff_id = Staff::where('id', $user_details->staff_id_in_charge)->first()->id;
            //find the staff name in charge of user
            $staff_name = Staff::find($staff_id)->username; 
            //find the fe list of user 
            $fe_list = FE::where('fe_user_id', $id)->get();
            return view('admin.admin_view_user', ['user_details' => $user_details, 'fe_list' => $fe_list, 'staff_name' => $staff_name]);
            
        } else if ($search == 'name') {
            $name = $request->input('search_name');

            $user_details = Users::where('username', $name)->first();

            if (!$user_details) {
                return redirect()->back()->with('user_name_invalid', 'User not found.');
            }

            $staff_id = Staff::where('id', $user_details->staff_id_in_charge)->first()->id;

            $staff_name = Staff::find($staff_id)->username;

            $fe_list = FE::where('fe_user_id', $user_details->id)->get();

            return view('admin.admin_view_user', ['user_details' => $user_details, 'fe_list' => $fe_list, 'staff_name' => $staff_name]);
    
        }
    }

    /**
     * Register new account.
     */
    public function storeReg(Request $request): RedirectResponse
    {
        $data = $request->all();
        // dd($data);

        if($data['role'] == 'admin'){
            $user = Admin::create([
                'username' => $data['username'],
                'password' => $data['password'],
            ]);

            $user->save();
        }
        
        if($data['role'] == 'staff'){
            $user = Staff::create([
                'username' => $data['username'],
                'password' => $data['password'],
            ]);
    
            $user->save();
        }
        if($data['role'] == 'user'){
            $user = Users::create([
                'username' => $data['username'],
                'password' => $data['password'],
                'company_name' => $data['company_name'],
                'company_address' => $data['company_address'],
                'person_in_charge' => $data['person_in_charge'],
                'contact' => $data['contact'],
                'email' => $data['email'],
                'area' => $data['search_area'],
                'staff_id_in_charge' => 0
            ]);
    
            $user->save();
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
        $fe_serial_number = $data['serial_number'];

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

        return view('admin.admin_redirect', ['fe_user_id' => $fe_user_id]);
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
    public function getUsersData()
    {
        // Fetch data from your database (you can customize this)
        $users = Users::all()->get();
        // Map data to a structure that DataTable expects
        $data = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'username' => $user->username,
                'area' => $user->area,
                'staff_in_charge' => $user->staff_id_in_charge ? $user->person_in_charge : null,
                'staff_username' => $user->staff_id_in_charge ? $user->person_in_charge : null,
            ];
        });

        // Return the data as a JSON response
        return response()->json(['data' => $data]);
    }

    public function deleteUser(Request $request)
    {
        $userId = $request->input('id');

        // Find the user and delete
        $user = User::find($userId);
        if ($user) {
            $user->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
}
