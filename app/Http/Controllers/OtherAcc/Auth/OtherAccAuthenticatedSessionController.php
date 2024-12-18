<?php

namespace App\Http\Controllers\OtherAcc\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\OtherAccLoginRequest;
use App\Models\FE;
use App\Models\OtherAcc;
use App\Models\Users;
use App\Models\AreaInCharge;
use App\Models\Areas;
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

class OtherAccAuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
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
     * Search user.
     */
    public function viewUser(Request $request)
    { 
        $data = $request->all();

        //find the user details by id
        $user_details = Users::find($data['id']);

        //find the fe list of user 
        $fe_list = FE::where('fe_user_id', $data['id'])->get();

        return view('otherAcc.otherAcc_view_user', ['user_details' => $user_details, 'fe_list' => $fe_list]);
    }

    /**
     * Proceed into main page and pass in data after authentication.
     */
    public function proceed(): View
    {
        //get the username of the authenticated user
        $username = Auth::guard('otherAcc')->user()->username;
        //get id of otherAcc
        $otherAcc_id = OtherAcc::where('username', $username)->first()->id;

        // Get all area_ids where user_id is otherAcc_id
        $area_ids = AreaInCharge::where('user_id', $otherAcc_id)->pluck('area_id');
        
        //Get all area_names matching their ids
        $area_names = Areas::whereIn('area_id', $area_ids)->pluck('area_name');
        // Get all users where the user's area is one of the area_names
        $user_list = Users::whereIn('area', $area_names)->get();
        //get the user's data
        return view('otherAcc.otherAcc_page', ['username' => $username, 'user_list' => $user_list]);
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

    public function getUsersData(Request $request)
    {   
        //get the username of the authenticated user
        $username = Auth::guard('otherAcc')->user()->username;
        
        //get id of otherAcc
        $otherAcc_id = OtherAcc::where('username', $username)->first()->id;

        // Get all area_ids where user_id is otherAcc_id
        $area_ids = AreaInCharge::where('user_id', $otherAcc_id)->pluck('area_id');
        
        //Get all area_names matching their ids
        $area_names = Areas::whereIn('area_id', $area_ids)->pluck('area_name');
        // Get all users where the user's area is one of the area_names
        $users = Users::whereIn('area', $area_names)->get();
        // Map data to a structure that DataTable expects
        $data = $users->map(function ($user) {

            return [
                'id' => $user->id,
                'username' => $user->username,
                'area' => $user->area,       
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

    // Fetch user data for editing
    public function fetchUserData(Request $request)
    {
        // dd($request->id);
        $user = Users::findOrFail($request->id);
        
        return response()->json($user);
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
}
