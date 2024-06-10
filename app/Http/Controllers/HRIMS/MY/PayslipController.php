<?php

namespace App\Http\Controllers\HRIMS\MY;

use App\Http\Controllers\Controller;
use App\Models\HRDeduction;
use App\Models\HRDeductionGroup;
use App\Models\HRPayroll;
use App\Models\HRPayrollList;
use App\Models\HRPayrollType;
use App\Models\UsersDTR;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use PDF;


class PayslipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $user_id = $user->id;
        $id_no = $user->id_no;
        $id_no_req = $request->id_no;
        $payroll_type = $request->payroll_type;
        $year = $request->year;
        $month = date('m',strtotime($year.'-'.$request->month.'-01'));
        $check = UsersDTR::where('id_no',$id_no_req)
            ->whereYear('date',$year)
            ->whereMonth('date',$month)->first();
        if(($user_access_level==1 || $user_access_level==2) || ($id_no==$id_no_req) && $check!=NULL){
            //Access granted to all or only authorized user accessing their own payslips
            $payroll_type_query = HRPayrollType::find($payroll_type);
            $payroll_period = $payroll_type_query->time_period_id;
            $payroll = HRPayroll::whereHas('list', function ($subQuery) use ($user_id) {
                    $subQuery->where('user_id',$user_id);
                })->whereHas('bank', function ($subQuery) {

                })
                ->where('payroll_type_id',$payroll_type)
                ->where('year',$year);
            // if($payroll_period!=4){
            //     $payroll = $payroll->where('month',$month);
            // }
            $payroll = $payroll->get();

            if($payroll->count()<=0){
                return response()->json(['result' => 'No available payslip.',
                        'src' => asset('assets\pdf\no-data-found.pdf')]);
            }

            if($payroll->count()>1){
                $src = 'storage\hrims\employee/'.$id_no.'\payslip/'.$year.'_'.$month.'_'.$payroll_type.'_merge.pdf';
                $oMerger = PDFMerger::init();
                foreach($payroll as $row){
                    $payroll_id = $row->payroll_id;
                    $pdf = $this->generateQR($id_no,$payroll_id);
                    $oMerger->addPDF(public_path($pdf), 'all','L');
                }
                $oMerger->merge();
                $oMerger->save($src);
            }else{
                foreach($payroll as $row){
                    $payroll_id = $row->payroll_id;
                    $src = $this->generateQR($id_no,$payroll_id);
                }
            }

            return response()->json(['result' => 'success',
                        'src' => asset($src)]);
        }else{
            return response()->json(['result' => 'error',
                        'src' => asset('assets\pdf\pdf_error.pdf')]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function generateQR($id_no,$payroll_id){
        error_reporting(E_ERROR);
        $image = QrCode::format('png')
                    ->merge(public_path('assets\images\logo\lnu_logo.png'), .28, true)
                    ->style('round', 0.2)
                    //->eye('circle')
                    ->eyeColor(1, /*outer*/ 0, 0, 128, /*inner*/ 212,175,55, 0, 0)
                    ->eyeColor(2, /*outer*/ 212,175,55, /*inner*/ 0, 0, 128, 0, 0)
                    ->size(300)
                    ->errorCorrection('H')
                    ->generate('hrims/payslip/'.$id_no.'/'.$payroll_id);
        $imageName = $payroll_id.'.png';
        $path = 'storage\hrims\employee/'.$id_no.'\payslip/';
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
        $file = public_path($path . $imageName);
        file_put_contents($file, $image);
        $qrcode = $path.$imageName;
        $src = $this->generatePDF($id_no,$payroll_id,$qrcode);
        return $src;
    }
    private function generatePDF($id_no,$payroll_id,$qrcode){
        $name_services = new NameServices;
        $user = Auth::user();
        $user_id = $user->id;

        $path = NULL;
        $logo = public_path('assets\images\logo\lnu_logo.png');

        $payroll = HRPayrollList::with('payroll.payroll_type','employee.personal_info','deductions','emp_stat')
            ->whereHas('payroll', function ($subQuery) use ($payroll_id) {
                $subQuery->where('payroll_id',$payroll_id);
            })->where('user_id',$user_id)->first();
        $payroll_id1 = $payroll->payroll_id;
        $deduction_user = [];
        if(count($payroll->deductions)>0){
            foreach($payroll->deductions as $deduct){
                $deduction_user[$deduct->deduction_id] = $deduct->amount;
            }
        }

        $deduction_list = HRDeduction::whereHas('payroll_deduction', function ($subQuery) use ($payroll_id1,$user_id){
                $subQuery->where('payroll_id',$payroll_id1);
                $subQuery->where('user_id',$user_id);
            })->where('group_id',NULL)->get();

        $deduction_list_group = HRDeductionGroup::with([
                'deduction' => function ($deductionQuery) use ($payroll_id1,$user_id) {
                    $deductionQuery->whereHas('payroll_deduction', function ($subQuery) use ($payroll_id1,$user_id){
                        $subQuery->where('payroll_id',$payroll_id1);
                        $subQuery->where('user_id',$user_id);
                    });
                }
            ])
            ->whereHas('deduction.payroll_deduction', function ($subQuery) use ($payroll_id1,$user_id){
                $subQuery->where('payroll_id',$payroll_id1);
                $subQuery->where('user_id',$user_id);
            })->get();

        if($payroll->employee->personal_info->middlename_in_last=='Y'){
            $name = mb_strtoupper($name_services->lastname_middlename_last($payroll->employee->lastname,$payroll->employee->firstname,$payroll->employee->middlename,$payroll->employee->extname));
        }else{
            $name = mb_strtoupper($name_services->lastname($payroll->employee->lastname,$payroll->employee->firstname,$payroll->employee->middlename,$payroll->employee->extname));
        }

        $last_day = date('t',strtotime($payroll->payroll->year.'-'.$payroll->payroll->month.'-01'));

        //$pdf = new PDF('A4', 'mm', '', true, 'UTF-8', false);
        //$page_size = array(215.9, 330.2);
        $page_size = array(210, 297);
        $pdf = new Pdf('P', 'mm', $page_size, true, 'UTF-8', false);
        $height = 185;
        $width = 260;
        $pdf::reset();
        $pdf::AddPage('L',$page_size);
        $pdf::SetAutoPageBreak(TRUE, 3);
       //landscape scale A4
        //$height = 185;
        //$width = 260;
        //Portrait scale A4
        //$width = 210;
        //height = 270;

        $x = 20;
        $y = 10;
        $x_add = 0;
        $y_add = 0;

        $pdf::SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(0, 0, 0)));
        $pdf::SetXY($x-1, $y-1);
        $pdf::Cell(259, 160, '', 1, 1, 'C', 0, '', 1);

        $pdf::SetXY($x-1, $y+29);
        $pdf::Cell(259, 150, '', 'T', 1, 'C', 0, '', 1);

        $pdf::SetXY($x-1, $y+43);
        $pdf::Cell(259, 150, '', 'T', 1, 'C', 0, '', 1);

        $pdf::SetXY($x-1, $y+43);
        $pdf::Cell(73, 115, '', 'R', 1, 'C', 0, '', 1);

        $pdf::SetXY($x-1, $y+43);
        $pdf::Cell(195, 115, '', 'R', 1, 'C', 0, '', 1);

        $pdf::SetXY($x+2, $y+2);
        $pdf::Image($logo, '', '', 25, 25, '', '', 'T', false, 0, '', false, false, 0, false, false, false);

        $pdf::SetXY($x+229, $y);
        $pdf::Image($qrcode, '', '', 28, 28, '', '', 'T', false, 0, '', false, false, 0, false, false, false);

        $pdf::SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));


        $pdf::SetXY($x+72, $y+128);
        $pdf::Cell(122, '', '', 'B', 1, 'C', 0, '', 1);

        $pdf::SetXY($x+72, $y+137);
        $pdf::Cell(122, '', '', 'B', 1, 'C', 0, '', 1);

        $pdf::SetXY($x+28, $y+7);
        $pdf::SetFont('typewriteb','',13);
        $pdf::Cell(63, '', 'Leyte Normal University', '', 1, 'C', 0, '', 1);

        $pdf::SetXY($x+28, $y+12);
        $pdf::SetFont('typewriteb','',13);
        $pdf::Cell(63, '', 'Tacloban City', '', 1, 'C', 0, '', 1);

        $pdf::SetXY($x+168, $y+9);
        $pdf::SetFont('typewriteb','',11);
        $pdf::Cell(60, '', $name, '', 1, 'C', 0, '', 1);

        $pdf::SetXY($x+168, $y+13);
        $pdf::SetFont('typewriteb','',11);
        $pdf::Cell(60, '', $payroll->employee->id_no, '', 1, 'C', 0, '', 1);

        $pdf::SetXY($x, $y+22);
        $pdf::SetFont('typewriteb','',16);
        $pdf::Cell(259, '', 'P A Y S L I P', 0, 1, 'C', 0, '', 1);

        $pdf::SetXY($x+2, $y+32);
        $pdf::SetFont('typewrite','',11);
        $pdf::Cell(35, '', 'Payroll Type:', '', 1, 'L', 0, '', 1);

        $pdf::SetXY($x+37, $y+32);
        $pdf::SetFont('typewriteb','',11);
        $pdf::Cell(100, '', $payroll->payroll->payroll_type->name, '', 1, 'L', 0, '', 1);

        $pdf::SetXY($x+150, $y+32);
        $pdf::SetFont('typewrite','',11);
        $pdf::Cell(35, '', 'For the Period:', '', 1, 'L', 0, '', 1);

        $pdf::SetXY($x+183, $y+32);
        $pdf::SetFont('typewriteb','',11);
        $pdf::Cell(82, '', $payroll->payroll->period, '', 1, 'L', 0, '', 1);

        $pdf::SetXY($x+2, $y+38);
        $pdf::SetFont('typewrite','',11);
        $pdf::Cell(35, '', 'Employee Name:', '', 1, 'L', 0, '', 1);

        $pdf::SetXY($x+37, $y+38);
        $pdf::SetFont('typewriteb','',11);
        $pdf::Cell(100, '', $name, '', 1, 'L', 0, '', 1);

        $pdf::SetXY($x+150, $y+38);
        $pdf::SetFont('typewrite','',11);
        $pdf::Cell(35, '', 'Monthly Basic:', '', 1, 'L', 0, '', 1);

        $pdf::SetXY($x+183, $y+38);
        $pdf::SetFont('typewriteb','',11);
        $pdf::Cell(82, '', number_format($payroll->salary,2), '', 1, 'L', 0, '', 1);

        $y_add = 51;

        if($payroll->payroll->payroll_type_id==1){
            $pdf::SetXY($x+2, $y+$y_add);
            $pdf::SetFont('typewrite','',11);
            $pdf::Cell(30, '', 'Basic Pay:', '', 1, 'L', 0, '', 1);

            $pdf::SetXY($x+32, $y+$y_add);
            $pdf::SetFont('typewriteb','',11);
            $pdf::Cell(39, '', number_format($payroll->earned,2), '', 1, 'R', 0, '', 1);

            $y_add = $y_add+15;

        }elseif($payroll->payroll->payroll_type_id==2){
            $pdf::SetXY($x+2, $y+$y_add);
            $pdf::SetFont('typewrite','',11);
            $pdf::Cell(30, '', 'PERA:', '', 1, 'L', 0, '', 1);

            $pdf::SetXY($x+32, $y+$y_add);
            $pdf::SetFont('typewriteb','',11);
            $pdf::Cell(39, '', number_format($payroll->earned,2), '', 1, 'R', 0, '', 1);

            $y_add = $y_add+15;

        }else{
            if($payroll->payroll->payroll_type->w_salary=='Yes'){
                $pdf::SetXY($x+2, $y+$y_add);
                $pdf::SetFont('typewrite','',11);
                $pdf::Cell(30, '', $payroll->payroll->payroll_type->w_salary_name.':', '', 1, 'L', 0, '', 1);

                $pdf::SetXY($x+32, $y+$y_add);
                $pdf::SetFont('typewriteb','',11);
                $pdf::Cell(39, '', number_format($payroll->amount_base,2), '', 1, 'R', 0, '', 1);

                $y_add = $y_add+15;
            }
            if($payroll->payroll->payroll_type->column_name!=NULL){
                $pdf::SetXY($x+2, $y+$y_add);
                $pdf::SetFont('typewrite','',11);
                $pdf::Cell(30, '', $payroll->payroll->payroll_type->column_name.':', '', 1, 'L', 0, '', 1);

                $pdf::SetXY($x+32, $y+$y_add);
                $pdf::SetFont('typewriteb','',11);
                $pdf::Cell(39, '', number_format($payroll->column_amount,2), '', 1, 'R', 0, '', 1);

                $y_add = $y_add+13;
            }
            if($payroll->payroll->payroll_type->column_name2!=NULL){
                $pdf::SetXY($x+2, $y+$y_add);
                $pdf::SetFont('typewrite','',11);
                $pdf::Cell(30, '', $payroll->payroll->payroll_type->column_name2.':', '', 1, 'L', 0, '', 1);

                $pdf::SetXY($x+32, $y+$y_add);
                $pdf::SetFont('typewriteb','',11);
                $pdf::Cell(39, '', number_format($payroll->column_amount2,2), '', 1, 'R', 0, '', 1);

                $y_add = $y_add+15;
            }
        }

        // $pdf::SetXY($x+2, $y+$y_add);
        // $pdf::SetFont('typewrite','',11);
        // $pdf::Cell(30, '', 'Earned:', '', 1, 'L', 0, '', 1);
        // $y_add = $y_add+15;

        if($payroll->allowances>0){
            $pdf::SetTextColor(0, 43, 225);
            $pdf::SetXY($x+2, $y+$y_add);
            $pdf::SetFont('typewrite','',11);
            $pdf::Cell(30, '', 'Allowances:', '', 1, 'L', 0, '', 1);

            $pdf::SetXY($x+32, $y+$y_add);
            $pdf::SetFont('typewriteb','',11);
            $pdf::Cell(39, '', number_format($payroll->allowances,2), '', 1, 'R', 0, '', 1);

            $y_add = $y_add+15;
        }

        $pdf::SetTextColor(0, 43, 225);
        $pdf::SetXY($x+2, $y+$y_add);
        $pdf::SetFont('typewriteb','',11);
        $pdf::Cell(30, '', 'Gross:', '', 1, 'L', 0, '', 1);

        $pdf::SetXY($x+32, $y+$y_add);
        $pdf::SetFont('typewriteb','',11);
        $pdf::Cell(39, '', number_format($payroll->gross,2), '', 1, 'R', 0, '', 1);

        $y_add = $y_add+15;

        $pdf::SetTextColor(216,0,0);
        $pdf::SetXY($x+2, $y+$y_add);
        $pdf::SetFont('typewriteb','',11);
        $pdf::Cell(30, '', 'Deductions:', '', 1, 'L', 0, '', 1);

        $pdf::SetXY($x+32, $y+$y_add);
        $pdf::SetFont('typewriteb','',11);
        $pdf::Cell(39, '', number_format($payroll->deduction,2), '', 1, 'R', 0, '', 1);

        $y_add = $y_add+15;

        $pdf::SetTextColor(0,118,59);
        $pdf::SetXY($x+2, $y+$y_add);
        $pdf::SetFont('typewriteb','',11);
        $pdf::Cell(30, '', 'Netpay:', '', 1, 'L', 0, '', 1);

        $pdf::SetXY($x+32, $y+$y_add);
        $pdf::SetFont('typewriteb','',11);
        $pdf::Cell(39, '', number_format($payroll->netpay,2), '', 1, 'R', 0, '', 1);

        $pdf::SetTextColor(0, 0, 0);

        $y_add = 55;
        $x_add = 196;

        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::SetFont('typewrite','',10);
        $pdf::Cell(60, '', 'I hereby acknowledge receipt of the', '', 1, 'L', 0, '', 1);

        $y_add = $y_add+6;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::SetFont('typewrite','',10);
        $pdf::Cell(60, '', 'amount stated below', '', 1, 'L', 0, '', 1);

        $y_add = $y_add+20;
        $pdf::SetXY($x+194, $y+$y_add);
        $pdf::Cell(64, '', '', 'B', 1, 'C', 0, '', 1);

        $y_add = $y_add+6;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::SetFont('typewrite','',10);
        $pdf::Cell(60, '', 'Signature', '', 1, 'C', 0, '', 1);

        $y_add = $y_add+28;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::SetFont('typewriteb','',11);
        $pdf::Cell(21, '', 'Netpay:', '', 1, 'L', 0, '', 1);

        $netpay_half_1 = round(($payroll->netpay/2),2);
        $netpay_half_2 = $payroll->netpay-$netpay_half_1;

        if($payroll->payroll->payroll_type_id==1){
            if($payroll->emp_stat->gov=='Y'){
                if($payroll->payroll->option_id==1){
                    $x_add = $x_add+13;
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::SetFont('typewriteb','',11);
                    $pdf::Cell(20, '', ' 1-15', '', 1, 'L', 0, '', 1);

                    $x_add = $x_add+20;
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::SetFont('typewriteb','',11);
                    $pdf::Cell(25, '', number_format($netpay_half_1,2), '', 1, 'R', 0, '', 1);

                    $y_add = $y_add+6;

                    $x_add = $x_add-19.5;
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::SetFont('typewriteb','',11);
                    $pdf::Cell(20, '', ':', '', 1, 'L', 0, '', 1);

                    $x_add = $x_add+2;
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::SetFont('typewriteb','',11);
                    $pdf::Cell(20, '', '16-'.$last_day, '', 1, 'L', 0, '', 1);

                    $x_add = $x_add+17.5;
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::SetFont('typewriteb','',11);
                    $pdf::Cell(25, '', number_format($netpay_half_2,2), '', 1, 'R', 0, '', 1);

                }else{
                    $x_add = $x_add+36;
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::SetFont('typewriteb','',11);
                    $pdf::Cell(24, '', number_format($payroll->netpay,2), '', 1, 'R', 0, '', 1);
                }
            }else{
                $x_add = $x_add+36;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                $pdf::SetFont('typewriteb','',11);
                $pdf::Cell(24, '', number_format($payroll->netpay,2), '', 1, 'R', 0, '', 1);
            }
        }else{
            $x_add = $x_add+36;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::SetFont('typewriteb','',11);
            $pdf::Cell(24, '', number_format($payroll->netpay,2), '', 1, 'R', 0, '', 1);
        }

        $x_add = 90;
        $y_add = 136.5;

        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::SetFont('typewriteb','',11);
        $pdf::Cell(50, '', 'TOTAL DEDUCTIONS : ', '', 1, 'L', 0, '', 1);

        $x_add = $x_add+47;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::SetFont('typewriteb','',11);
        $pdf::Cell(55, '', number_format($payroll->deduction,2), '', 1, 'R', 0, '', 1);

        $y_add = $y_add+10;
        $x_add = 80;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::SetFont('typewriteb','',10);
        $pdf::Cell(40, '', 'NETPAY    :', '', 1, 'L', 0, '', 1);

        if($payroll->payroll->payroll_type_id==1){
            if($payroll->emp_stat->gov=='Y'){
                if($payroll->payroll->option_id==1){

                    $x_add = $x_add+30;
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::SetFont('typewriteb','',11);
                    $pdf::Cell(20, '', '1-15', '', 1, 'L', 0, '', 1);

                    $x_add = $x_add+45;
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::SetFont('typewriteb','',10);
                    $pdf::Cell(25, '', number_format($netpay_half_1,2), '', 1, 'R', 0, '', 1);

                    $y_add = $y_add+6;

                    $x_add = $x_add-51.5;
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::SetFont('typewriteb','',11);
                    $pdf::Cell(20, '', ':', '', 1, 'L', 0, '', 1);

                    $x_add = $x_add+6.5;
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::SetFont('typewriteb','',11);
                    $pdf::Cell(20, '', '16-'.$last_day, '', 1, 'L', 0, '', 1);

                    $x_add = $x_add+45;
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::SetFont('typewriteb','',10);
                    $pdf::Cell(25, '', number_format($netpay_half_2,2), '', 1, 'R', 0, '', 1);
                }else{
                    $x_add = $x_add+75;
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::SetFont('typewriteb','',10);
                    $pdf::Cell(25, '', number_format($payroll->netpay,2), '', 1, 'R', 0, '', 1);
                }
            }else{
                $x_add = $x_add+75;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                $pdf::SetFont('typewriteb','',10);
                $pdf::Cell(25, '', number_format($payroll->netpay,2), '', 1, 'R', 0, '', 1);
            }
        }else{
            $x_add = $x_add+75;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::SetFont('typewriteb','',10);
            $pdf::Cell(25, '', number_format($payroll->netpay,2), '', 1, 'R', 0, '', 1);
        }

        $y_add = 48;
        $x_add = 74;
        $i_check = 16;
        $i_check1 = 16;
        $y_add1 = 49;
        $y_add1_check = 0;

        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::SetFont('typewriteb','',11);
        $pdf::Cell(117, '', 'Less:', '', 1, 'L', 0, '', 1);

        $y_add = 49;
        if($deduction_list_group->count()>0){
            $pdf::SetFont('typewrite','',10);
            foreach ($deduction_list_group as $row) {
                $y_add = $y_add+5;

                if($i_check==0){
                    if($y_add1_check==0){
                        $y_add = 54;
                    }
                    $y_add1_check++;
                    $pdf::SetXY($x+134, $y+$y_add);
                    $i_check1--;
                    $y_add1 = $y_add;
                }else{
                    $pdf::SetXY($x+74, $y+$y_add);
                    $i_check--;
                }

                $pdf::SetFont('typewriteb','',10);
                $pdf::Cell(30, '', $row->name, '', 1, 'L', 0, '', 1);
                $pdf::SetFont('typewrite','',10);

                foreach($row->deduction as $subRow){
                    $y_add = $y_add+5;

                    if($i_check==0){
                        if($y_add1_check==0){
                            $y_add = 54;
                        }
                        $y_add1_check++;
                        $x_add = 140;
                        $i_check1--;
                        $y_add1 = $y_add;
                    }else{
                        $x_add = 80;
                        $i_check--;
                    }

                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::Cell(25, '', $subRow->name, '', 1, 'L', 0, '', 1);

                    $pdf::SetXY($x+$x_add+25, $y+$y_add);
                    $pdf::Cell(25, '', $deduction_user[$subRow->id], '', 1, 'R', 0, '', 1);
                }

            }
        }

        if($deduction_list->count()>0){
            $pdf::SetFont('typewrite','',10);
            foreach ($deduction_list as $row) {
                $y_add = $y_add+5;

                if($i_check==0){
                    if($y_add1_check==0){
                        $y_add = 54;
                    }
                    $y_add1_check++;
                    $x_add = 134;
                    $i_check1--;
                    $y_add1 = $y_add;
                }else{
                    $x_add = 74;
                    $i_check--;
                }

                $pdf::SetXY($x+$x_add, $y+$y_add);
                $pdf::Cell(31, '', $row->name, '', 1, 'L', 0, '', 1);

                $pdf::SetXY($x+$x_add+31, $y+$y_add);
                $pdf::Cell(25, '', $deduction_user[$row->id], '', 1, 'R', 0, '', 1);
            }
        }

        for($i=0;$i<$i_check;$i++){
            $y_add = $y_add+5;
            $pdf::SetXY($x+74, $y+$y_add);
            $pdf::Cell(31, '', '-', '', 1, 'C', 0, '', 1);

            $pdf::SetXY($x+105, $y+$y_add);
            $pdf::Cell(25, '', '-', '', 1, 'R', 0, '', 1);
        }


        for($i=0;$i<$i_check1;$i++){
            $y_add1 = $y_add1+5;
            $pdf::SetXY($x+134, $y+$y_add1);
            $pdf::Cell(31, '', '-', '', 1, 'C', 0, '', 1);

            $pdf::SetXY($x+165, $y+$y_add1);
            $pdf::Cell(25, '', '-', '', 1, 'R', 0, '', 1);
        }

        $path = 'storage\hrims\employee/'.$id_no.'\payslip/'.$payroll_id.'.pdf';
        $pdf::Output(public_path($path),'F');

        return $path;
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function indexValidateRequest(Request $request)
    {
        $rules = [
            'payroll_type' => 'required|numeric',
            'year' => 'required|numeric',
            'month' => 'required|string',
        ];

        $customMessages = [
            'payroll_type.required' => 'Payroll Type is required',
            'payroll_type.numeric' => 'Payroll Type must be a number',
            'year.required' => 'Year is required',
            'year.numeric' => 'Year must be a number',
            'month.required' => 'Month is required',
            'month.string' => 'Month must be a number',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }
}
