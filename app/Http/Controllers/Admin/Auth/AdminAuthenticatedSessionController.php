<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;
use App\Models\FE;
use App\Models\Staff;
use App\Models\Users;
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
        //get name list
        $name_list = json_encode(Users::pluck('username')->toArray());

        return view('admin.admin_page', ['username' => $username, 'user_list' => $user_list, 'name_list' => $name_list]);
    }

    /**
     * Search user by ID.
     */
    public function viewUserByID(Request $request)
    {
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
    }

    /**
     * Search user by name.
     */
    public function viewUserByName(Request $request)
    {
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
}
