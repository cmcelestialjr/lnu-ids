<?php

namespace App\Http\Controllers\HRIMS\DTR;
use App\Http\Controllers\Controller;
use App\Models\_Work;
use App\Models\EducOfferedSchedule;
use App\Models\EducOfferedScheduleDay;
use App\Models\Holidays;
use App\Models\Users;
use App\Models\UsersDTR;
use App\Models\UsersDTRInfo;
use App\Models\UsersDTRTrack;
use App\Models\UsersRoleList;
use App\Models\UsersSchedDays;
use App\Models\UsersSchedTime;
use App\Services\DTRInfoServices;
use App\Services\NameServices;
use App\Services\PasswordServices;
use App\Services\TokenServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PDFController extends Controller
{
    /**
     * Show the form for viewing a resource.
     */
    public function index(Request $request){
        $id_no = $request->id_no;
        $year = $request->year;
        $month = $request->month;
        $range = $request->range;
        $option = $request->option;
        $pdf_code = $request->pdf_code;
        $date = date('Y-m-d',strtotime($year.'-'.$month.'-01'));

        $first_remove = substr($pdf_code, 4);
        $second_remove = substr($first_remove, 0, -4);
        $check_pdf_code = $second_remove;

        $employee = Users::where('id_no',$id_no)->first();
        if($employee==NULL || $check_pdf_code!=mb_substr($date, -1)){
            return view('layouts/error/404');
        }
        $src = 'storage/hrims/employee/'.$id_no.'\dtr/'.$year.'/'.$id_no.'_'.$year.'_'.$month.'_'.$range.'_'.$option.'.pdf';

        $data = [
                'src' => $src
        ];
        return view('pdf/main_view',$data);
    }
    public function PDF(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $id_no = $user->id_no;
        $id_no_req = $request->id_no;
        $year = $request->year;
        $month = $request->month;
        $range = $request->range;
        $option = $request->option;
        $result = 'error';
        $src = '';
        $check = UsersDTR::where('id_no',$id_no_req)
            ->whereYear('date',$year)
            ->whereMonth('date',$month)->first();
        if(($user_access_level==1 || $user_access_level==2) || ($id_no==$id_no_req) && $check!=NULL){
            $result = 'success';
            $name_services = new NameServices;
            $src = $this->generateQR($id_no_req,$year,$month,$range,$option);
            $user = Users::where('id_no',$id_no_req)->first();
            $name = mb_strtoupper($name_services->firstname($user->lastname,$user->firstname,$user->middlename,$user->extname));
            if($range==2){
                $dateMonth = date('F 1-15, Y',strtotime($year.'-'.$month.'-01'));
            }else{
                $dateMonth = date('F 1-t, Y',strtotime($year.'-'.$month.'-01'));
            }
            if($src==NULL){
                return view('layouts/error/404');
            }else{
                if($user_access_level==1 || $user_access_level==2){
                    $datas['status'] = 1;
                    UsersDTR::where('id_no',$id_no_req)
                        ->whereYear('date',$year)
                        ->whereMonth('date',$month)
                        ->where('date','<=',date('Y-m-d'))
                        ->where('status',NULL)
                        ->update($datas);
                    $check1 = UsersDTRTrack::where('id_no',$id_no_req)
                        ->whereYear('date',$year)
                        ->whereMonth('date',$month)
                        ->where('range',$range)
                        ->first();
                    if($check1==NULL){
                        $insert = new UsersDTRTrack();
                        $insert->id_no = $id_no_req;
                        $insert->date = date('Y-m-d',strtotime($year.'-'.$month.'-'.$range));
                        $insert->range = $range;
                        $insert->date_submitted = date('Y-m-d H:i:s');
                        $insert->updated_by = $user->id;
                        $insert->save();
                    }
                }
                $data = array(
                    'src' => $src,
                    'id_no' => $id_no_req,
                    'name' => $name,
                    'month' => $dateMonth
                );
                return view('hrims/dtr/pdf',$data);
            }
        }else{
            return view('layouts/error/404');
        }
    }
    public function show(Request $request)
    {
        // Validate the request
        $validator = $this->showValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            // If validation fails, return a JSON response with validation errors and a 400 status code
            return response()->json(['result' => $validator->errors()], 400);
        }

        $id_no = $request->id_no;
        $employee = Users::where('id_no',$id_no)
            ->first();

        // Check if the employee exists
        if($employee==NULL){
            return response()->json(['result' => 'error']);
        }

        $option = $request->option;
        $year = $request->year;
        $month = $request->month;
        $range = $request->range;
        $option_id = $request->option_id;

        $url = $this->generateQR($id_no,$year,$month,$range,$option);

        return response()->json(['result' => 'success',
                                'url' => $url
                                ]);
    }
    private function generateQR($id_no,$year,$month,$range,$option){
        error_reporting(E_ERROR);
        $token = new TokenServices;
        $date = date('Y-m-d',strtotime($year.'-'.$month.'-01'));
        $token1 = $token->token_w_upper(4);
        $token2 = $token->token_w_upper(4);
        $pdf_code = $token1.mb_substr($date, -1).$token2;
        $password = $token1.mb_substr($date, -1).$token2;
        $image = QrCode::format('png')
                    ->merge(public_path('assets\images\logo\lnu_logo.png'), .28, true)
                    ->style('round', 0.2)
                    //->eye('circle')
                    ->eyeColor(1, /*outer*/ 0, 0, 128, /*inner*/ 212,175,55, 0, 0)
                    ->eyeColor(2, /*outer*/ 212,175,55, /*inner*/ 0, 0, 128, 0, 0)
                    ->size(300)
                    ->errorCorrection('H')
                    ->generate('hrims/dtr/pdf/'.$year.'/'.$month.'/'.$id_no.'/'.$range.'/'.$option.'/'.$pdf_code);
        $imageName = $id_no.'_'.$year.'_'.$month.'_'.$range.'_'.$option.'_'.$pdf_code.'.png';
        $path = 'storage\hrims\employee/'.$id_no.'\dtr/'.$year.'/';
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
        $file = public_path($path . $imageName);
        file_put_contents($file, $image);
        $qrcode = $path.$imageName;
        $src = $this->generatePDF($id_no,$year,$month,$range,$qrcode,$option,$password);
        return $src;
    }

    private function generatePDF($id_no,$year,$month,$range,$qrcode,$option,$password){
        $password_services = new PasswordServices;

        $master_password = $password_services->master();

        //$pdf = new PDF('A4', 'mm', '', true, 'UTF-8', false);
        $page_size = array(215.9, 330.2);
        $pdf = new Pdf('P', 'mm', $page_size, true, 'UTF-8', false);
        $height = 185;
        $width = 260;

        $permissions = [
            'print' => true,
            'modify' => false,
            'copy' => false,
            'annotate' => false
        ];

        $pdf::reset();
        $pdf = $this->generatePDFDetails($pdf,$id_no,$year,$month,$range,$qrcode,$option,$password);
        $pdf::setProtection($permissions);
        $pathUserUnprotected = 'storage\hrims\employee/'.$id_no.'\dtr/'.$year.'/'.$id_no.'_'.$year.'_'.$month.'_'.$range.'_'.$option.'.pdf';
        $pdf::Output(public_path($pathUserUnprotected),'F');

        // $pdf = new Pdf('P', 'mm', $page_size, true, 'UTF-8', false);
        // $pdf::reset();
        // $pdf = $this->generatePDFDetails($id_no,$year,$month,$range,$qrcode,$option,$password);
        // $pdf::setProtection($permissions, $password, $master_password, 0, null);
        // $pathUserUnprotected = 'storage\hrims\employee/'.$id_no.'\dtr/'.$year.'/'.$id_no.'_'.$year.'_'.$month.'_protected.pdf';
        // $pdf::Output(public_path($pathUserProtected),'F');

        return 'hrims/dtr/pdf/'.$year.'/'.$month.'/'.$id_no.'/'.$range.'/'.$option.'/'.$password;
    }
    private function generatePDFDetails($pdf,$id_no,$year,$month,$range,$qrcode,$option,$password){
        $name_services = new NameServices;
        $pathUser = NULL;
        $user = Users::with('employee_default.position.office_designate.current_user','instructor_info.position.office_designate.current_user')->where('id_no',$id_no)->first();
        $user_id = $user->id;
        $emp_stat_gov = $user->employee_default->emp_stat->gov;
        $name = mb_strtoupper($name_services->firstname($user->lastname,$user->firstname,$user->middlename,$user->extname));

        $logo = public_path('assets\images\logo\lnu_logo.png');
        $logo_blur = public_path('assets\images\logo\lnu_logo_blur1.png');
        $scissor = public_path('assets\images\icons\png\scissor1.png');

        $signatory = '';

        if($option==2){
            if($user->instructor_info->position->office_designate->current_user){
                $current_user = $user->instructor_info->position->office_designate->current_user;
                $signatory = mb_strtoupper($name_services->firstname($current_user->lastname,$current_user->firstname,$current_user->middlename,$current_user->extname));
            }
        }else{
            if($user->employee_default->position->office_designate->current_user){
                $current_user = $user->instructor_info->position->office_designate->current_user;
                $signatory = mb_strtoupper($name_services->firstname($current_user->lastname,$current_user->firstname,$current_user->middlename,$current_user->extname));
            }
        }

        $dtr_info_service = new DTRInfoServices;
        $option_id = $option;
        $holidays = 0;
        $dtr = [];
        $start_date = date('Y-m-01', strtotime("$year-$month-01"));
        $last_date = date('Y-m-t',strtotime($start_date));
        $next_day = date('Y-m-d', strtotime($last_date . ' +1 day'));
        $lastDay = date('t',strtotime($last_date));

        $getDtr = $dtr_info_service->getDtr($user_id, $year, $month);
        $getDtrNext = $dtr_info_service->getDtrNext($user_id, $next_day);
        $getDtrSched = $dtr_info_service->getDtrSched($user_id, $start_date, $last_date, $option_id);
        $getHolidays = $dtr_info_service->getHolidays($year, $month);

        $getDtrInitial = $dtr_info_service->initial([
                'lastDay' => $lastDay,
                'year' => $year,
                'month' => $month,
                'defaultValues' => $dtr_info_service->defaultValues(),
                'range' => $range,
                'getDtrSched' => $getDtrSched,
                'dtr' => $dtr
        ]);
        $dtr = $getDtrInitial['dtr'];
        $included_days = $getDtrInitial['included_days'];

        $getDtrHolidays = $dtr_info_service->holidays([
            'getHolidays' => $getHolidays,
            'included_days' => $included_days,
            'holidays' => $holidays,
            'dtr' => $dtr
        ]);
        $dtr = $getDtrHolidays['dtr'];
        $included_days = $getDtrHolidays['included_days'];
        $holidays = $getDtrHolidays['holidays'];

        $getDtrUser = $dtr_info_service->dtr([
            'getDtr' => $getDtr,
            'dtr' => $dtr,
            'range' => $range,
            'included_days' => $included_days
        ]);
        $dtr = $getDtrUser['dtr'];
        $included_days = $getDtrUser['included_days'];

        $getDtrInfo = $dtr_info_service->dtrInfo([
            'id' => $user_id,
            'year' => $year,
            'month' => $month,
            'option_id' => $option_id,
            'dtr' => $dtr,
            'range' => $range
        ]);
        $dtr = $getDtrInfo['dtr'];

        $getDtrInfoTotal = $dtr_info_service->getDtrInfoTotal($user_id, $year, $month, $option_id);



        //$pdf = new PDF('A4', 'mm', '', true, 'UTF-8', false);
        $page_size = array(215.9, 330.2);
        // $pdf = new Pdf('P', 'mm', $page_size, true, 'UTF-8', false);
        // $height = 185;
        // $width = 260;
        // $pdf::reset();
        $pdf::AddPage('P',$page_size);
        $pdf::SetAutoPageBreak(TRUE, 3);
       //landscape scale A4
        //$height = 185;
        //$width = 260;
        //Portrait scale A4
        //$width = 210;
        //height = 270;
        for ($j = 1; $j <= $lastDay; $j++)
        {
            if($j==0){
                $x_add = 0;
            }else{
                $x_add = 105;
            }

            $y = 6;
            $y_add = 14;

        }

        $pdf::SetXY(103, 281);
        $pdf::Image($scissor, '', '', 4, 7, '', '', 'T', false, 0, '', false, false, 0, false, false, false);

        $pdf::SetXY(100, 5);
        $pdf::SetFont('typewriteb','',6);
        $pdf::MultiCell(10, 270, "|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n", 0, 'C', 0, 0, '', '', true);

        // $pathUser = 'storage\hrims\employee/'.$id_no.'\dtr/'.$year.'/'.$id_no.'_'.$year.'_'.$month.'.pdf';
        // $pdf::Output(public_path($pathUser),'F');

        return $pdf;
    }
    private function generatePDFDetails1($pdf,$id_no,$year,$month,$range,$qrcode,$option,$password){
        $name_services = new NameServices;
        $pathUser = NULL;
        $user = Users::with('employee_default.position.office_designate.current_user','instructor_info.position.office_designate.current_user')->where('id_no',$id_no)->first();
        $user_id = $user->id;
        $emp_stat_gov = $user->employee_default->emp_stat->gov;
        $name = mb_strtoupper($name_services->firstname($user->lastname,$user->firstname,$user->middlename,$user->extname));

        $logo = public_path('assets\images\logo\lnu_logo.png');
        $logo_blur = public_path('assets\images\logo\lnu_logo_blur1.png');
        $scissor = public_path('assets\images\icons\png\scissor1.png');

        $signatory = '';

        if($option==2){
            if($user->instructor_info->position->office_designate->current_user){
                $current_user = $user->instructor_info->position->office_designate->current_user;
                $signatory = mb_strtoupper($name_services->firstname($current_user->lastname,$current_user->firstname,$current_user->middlename,$current_user->extname));
            }
        }else{
            if($user->employee_default->position->office_designate->current_user){
                $current_user = $user->instructor_info->position->office_designate->current_user;
                $signatory = mb_strtoupper($name_services->firstname($current_user->lastname,$current_user->firstname,$current_user->middlename,$current_user->extname));
            }
        }

        $work = _Work::where('user_id',$user_id)->orderBy('date_from','DESC')->orderBy('emp_stat_id','ASC')->first();
        if($work->role_id==3){
            if($work->credit_type_id==2 || $work->credit_type_id==NULL){
                $emp_type = 'Employee';
            }else{
                $emp_type = 'Faculty';
            }
        }else{
            $emp_type = 'Employee';
        }

        $user_dtr = UsersDTR::where('id_no',$id_no)
            ->whereYear('date',$year)
            ->whereMonth('date',$month)
            ->orderBy('date','ASC')
            ->orderBy('time_type','DESC')
            ->get();

        $holidays = Holidays::
            where(function ($query) use ($month) {
                $query->whereMonth('date', $month)
                    ->where('option','Yes');
            })
            ->orWhere(function ($query) use ($year,$month) {
                $query->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->where('option','No');
            })
            ->orderBy('date','ASC')
            ->get();
            $count_days = 0;
            for($m=1;$m<=date('t',strtotime($year.'-'.$month.'-01'));$m++){
                $dtr[$m]['check'] = '';
                $dtr[$m]['val'] = '';
                $dtr[$m]['in_am'] = '';
                $dtr[$m]['out_am'] = '';
                $dtr[$m]['in_pm'] = '';
                $dtr[$m]['out_pm'] = '';
                $dtr[$m]['time_from'] = '';
                $dtr[$m]['time_to'] = '';
                $dtr[$m]['time_type'] = '';
                $dtr[$m]['time_type_name'] = '';
                $dtr[$m]['time_in_am_type'] = '';
                $dtr[$m]['time_out_am_type'] = '';
                $dtr[$m]['time_in_pm_type'] = '';
                $dtr[$m]['time_out_pm_type'] = '';
                $dtr[$m]['hours'] = 0;
                $dtr[$m]['minutes'] = 0;
                $time_from = '';
                $time_to = '';
                if($range==2 && $m>=15){
                    $l = 15;
                }else{
                    $l = $m;
                    if(date('Y-m')==date('Y-m',strtotime($year.'-'.$month.'-01')) && $m>date('d')){
                        $l = date('d');
                    }
                }
                $weekDay = date('w', strtotime($year.'-'.$month.'-'.$l));
                if($weekDay==0){
                    $weekDay = 7;
                }
                $time_minutes_total = 0;

                $schedTimeFrom = UsersSchedTime::where('user_id',$user_id)
                    ->where('date_to','>=',date('Y-m-d',strtotime($year.'-'.$month.'-'.$l)))
                    ->where('date_from','<=',date('Y-m-d',strtotime($year.'-'.$month.'-'.$l)))
                    ->whereHas('days', function ($query) use ($weekDay) {
                        $query->where('day',$weekDay);
                    });
                    if($option=='o'){
                        $schedTimeFrom = $schedTimeFrom->where('option_id',2);
                    }else{
                        $schedTimeFrom = $schedTimeFrom->where('option_id',1);
                    }
                $schedTimeFrom = $schedTimeFrom->orderBy('time_from','ASC')
                    ->first();

                $schedTimeTo = UsersSchedTime::where('user_id',$user_id)
                    ->where('option_id',1)
                    ->where('date_to','>=',date('Y-m-d',strtotime($year.'-'.$month.'-'.$l)))
                    ->where('date_from','<=',date('Y-m-d',strtotime($year.'-'.$month.'-'.$l)))
                    ->whereHas('days', function ($query) use ($weekDay) {
                        $query->where('day',$weekDay);
                    });
                    if($option=='o'){
                        $schedTimeTo = $schedTimeTo->where('option_id',2);
                    }else{
                        $schedTimeTo = $schedTimeTo->where('option_id',1);
                    }
                $schedTimeTo = $schedTimeTo->orderBy('time_to','DESC')
                    ->first();

                if($schedTimeFrom!=NULL){
                    $time_from = date('H:i',strtotime($schedTimeFrom->time_from));
                }
                if($schedTimeTo!=NULL){
                    $time_to = date('H:i',strtotime($schedTimeTo->time_to));
                }

                $dtr[$l]['time_from'] = $time_from;
                $dtr[$l]['time_to'] = $time_to;

                $next_user_dtr = UsersDtr::where('id_no',$id_no)
                    ->where('date',date('Y-m-d', strtotime("+1 day", strtotime($year.'-'.$month.'-'.$l))))
                    ->first();
                $next_time_in_am_for = '';
                $next_time_out_am_for = '';
                $next_time_in_pm_for = '';
                $next_time_out_pm_for = '';
                if($next_user_dtr){
                    if($next_user_dtr->time_in_am){
                        $next_time_in_am_for = date('H:i',strtotime($next_user_dtr->time_in_am));
                    }
                    if($next_user_dtr->time_out_am){
                        $next_time_out_am_for = date('H:i',strtotime($next_user_dtr->time_out_am));
                    }
                    if($next_user_dtr->time_in_pm){
                        $next_time_in_pm_for = date('H:i',strtotime($next_user_dtr->time_in_pm));
                    }
                    if($next_user_dtr->time_out_pm){
                        $next_time_out_pm_for = date('H:i',strtotime($next_user_dtr->time_out_pm));
                    }
                }

                $user_dtr = UsersDtr::where('id_no',$id_no)
                    ->where('date',date('Y-m-d',strtotime($year.'-'.$month.'-'.$l)))
                    ->whereMonth('date',$month)
                    ->first();
                if($user_dtr!=NULL){
                    $row = $user_dtr;

                    $date_day = date('j',strtotime($row->date));
                    $weekDay = date('w', strtotime($row->date));
                    if($weekDay==0){
                        $weekDay = 7;
                    }
                    $time_from = '';
                    $time_to = '';
                    $time_minutes_total = 0;

                    $dtr[$date_day]['check'] = 'dtr';

                    if($row->time_in_am==NULL){
                        $time_in_am = '';
                        $time_in_am_for = '';
                    }else{
                        $time_in_am = date('h:ia',strtotime($row->time_in_am));
                        $time_in_am_for = date('H:i',strtotime($row->time_in_am));
                    }
                    if($row->time_out_am==NULL){
                        $time_out_am = '';
                        $time_out_am_for = '';
                    }else{
                        $time_out_am = date('h:ia',strtotime($row->time_out_am));
                        $time_out_am_for = date('H:i',strtotime($row->time_out_am));
                    }
                    if($row->time_in_pm==NULL){
                        $time_in_pm = '';
                        $time_in_pm_for = '';
                    }else{
                        $time_in_pm = date('h:ia',strtotime($row->time_in_pm));
                        $time_in_pm_for = date('H:i',strtotime($row->time_in_pm));
                    }
                    if($row->time_out_pm==NULL){
                        $time_out_pm = '';
                        $time_out_pm_for = '';
                    }else{
                        $time_out_pm = date('h:ia',strtotime($row->time_out_pm));
                        $time_out_pm_for = date('H:i',strtotime($row->time_out_pm));
                    }
                    // if($time_from<'12:00' && $time_to>'12:00'){
                    //     if(($time_in_am_for=='' || $time_out_am_for=='' || $time_out_pm_for=='' || $time_out_pm_for=='')
                    //         && $row->time_type==NULL){
                    //         $count_days += 1;
                    //     }
                    // }elseif(($time_from<'12:00' && $time_to<'13:00') && $row->time_type==3){
                    //     if($time_in_am_for=='' || $time_out_am_for==''){
                    //         $count_days += 1;
                    //     }
                    // }else{
                    //     if(($time_in_pm_for=='' || $time_out_pm_for=='') && $row->time_type==2){
                    //         $count_days += 1;
                    //     }
                    // }

                    $total_minutes = 0;
                    $tardy_minutes = 0;
                    $tardy_no = 0;
                    $ud_minutes = 0;
                    $ud_no = 0;
                    $hd_minutes = 0;
                    $hd_no = 0;
                    $abs_minutes = 0;
                    $abs_no = 0;
                    $is_rotation_duty = 'No';

                    $schedTimeGet = UsersSchedTime::where('user_id',$user_id)
                        ->where('date_to','>=',date('Y-m-d',strtotime($year.'-'.$month.'-'.$l)))
                        ->where('date_from','<=',date('Y-m-d',strtotime($year.'-'.$month.'-'.$l)))
                        ->whereHas('days', function ($query) use ($weekDay) {
                            $query->where('day',$weekDay);
                        });
                    if($option=='o'){
                        $schedTimeGet = $schedTimeGet->where('option_id',2);
                    }else{
                        $schedTimeGet = $schedTimeGet->where('option_id',1);
                    }
                    $schedTimeGet = $schedTimeGet->get();
                    if($schedTimeGet->count()>0){
                        foreach($schedTimeGet as $key => $rowSchedTime){
                            $time_from = date('H:i',strtotime($rowSchedTime->time_from));
                            $time_to = date('H:i',strtotime($rowSchedTime->time_to));
                            $is_rotation_duty = $rowSchedTime->is_rotation_duty;

                            if($is_rotation_duty=='Yes'){
                                if(($time_from<'12:00' && $time_in_am_for=='') ||
                                    ($time_from>='12:00' && $time_in_pm_for=='')){
                                    $count_days += 1;
                                }
                                if($time_to<'12:00' && $time_from>='12:00' && $next_time_out_am_for==''){
                                    $count_days += 1;
                                }elseif($time_to>='12:00' && $time_from<'12:00' && $time_out_pm_for==''){
                                    $count_days += 1;
                                }
                            }else{
                                if($time_from<'12:00' && $time_to>'12:00'){
                                    if(($time_in_am_for=='' || $time_out_am_for=='' || $time_out_pm_for=='' || $time_out_pm_for=='')
                                        && $row->time_type==NULL){
                                        $count_days += 1;
                                    }
                                }elseif(($time_from<'12:00' && $time_to<'13:00') && $row->time_type==3){
                                    if($time_in_am_for=='' || $time_out_am_for==''){
                                        $count_days += 1;
                                    }
                                }else{
                                    if(($time_in_pm_for=='' || $time_out_pm_for=='') && $row->time_type==2){
                                        $count_days += 1;
                                    }
                                }
                            }
                            if($time_from<'12:00'){
                                if($time_in_am_for!='' && $time_in_am_for>$time_from){
                                    $time_from_ = Carbon::parse($time_from)->seconds(0);
                                    $time_to_ = Carbon::parse($time_in_am_for)->seconds(0);
                                    $total_minutes += $time_to_->diffInMinutes($time_from_);
                                    $tardy_minutes += $time_to_->diffInMinutes($time_from_);
                                    $tardy_no++;
                                }
                            }else{
                                if($time_in_pm_for!='' && $time_in_pm_for>$time_from){
                                    $time_from_ = Carbon::parse($time_from)->seconds(0);
                                    $time_to_ = Carbon::parse($time_in_pm_for)->seconds(0);
                                    $total_minutes += $time_to_->diffInMinutes($time_from_);
                                    $tardy_minutes += $time_to_->diffInMinutes($time_from_);
                                    $tardy_no++;
                                }
                            }
                            if($is_rotation_duty=='Yes'){
                                if($time_to<'12:00' && $time_from>='12:00'){
                                    if($next_time_out_am_for!='' && $next_time_out_am_for<$time_to){
                                        $time_from_ = Carbon::parse($next_time_out_am_for)->seconds(0);
                                        $time_to_ = Carbon::parse($time_to)->seconds(0);
                                        $total_minutes += $time_to_->diffInMinutes($time_from_);
                                        $ud_minutes += $time_to_->diffInMinutes($time_from_);
                                        $ud_no++;
                                    }
                                }else{
                                    if($time_out_pm_for!='' && $time_out_pm_for<$time_to){
                                        $time_from_ = Carbon::parse($time_out_pm_for)->seconds(0);
                                        $time_to_ = Carbon::parse($time_to)->seconds(0);
                                        $total_minutes += $time_to_->diffInMinutes($time_from_);
                                        $ud_minutes += $time_to_->diffInMinutes($time_from_);
                                        $ud_no++;
                                    }
                                }
                            }else{
                                if($time_to<'13:00'){
                                    if($time_out_am_for!='' && $time_out_am_for<$time_to){
                                        $time_from_ = Carbon::parse($time_out_am_for)->seconds(0);
                                        $time_to_ = Carbon::parse($time_to)->seconds(0);
                                        $total_minutes += $time_to_->diffInMinutes($time_from_);
                                        $ud_minutes += $time_to_->diffInMinutes($time_from_);
                                        $ud_no++;
                                    }
                                }else{
                                    if($time_out_pm_for!='' && $time_out_pm_for<$time_to){
                                        $time_from_ = Carbon::parse($time_out_pm_for)->seconds(0);
                                        $time_to_ = Carbon::parse($time_to)->seconds(0);
                                        $total_minutes += $time_to_->diffInMinutes($time_from_);
                                        $ud_minutes += $time_to_->diffInMinutes($time_from_);
                                        $ud_no++;
                                    }
                                }
                            }

                            if($row->time_type==1 || $row->time_type==2 || $row->time_type==3){
                                $time_from_ = Carbon::parse($time_from)->seconds(0);
                                $time_to_ = Carbon::parse($time_to)->seconds(0);
                                $get_time_diff = $time_to_->diffInMinutes($time_from_);
                                if($emp_stat_gov=='N'){
                                    $total_minutes += $get_time_diff;
                                }
                                if($row->time_type==1){
                                    $abs_minutes += $get_time_diff;
                                    $abs_no = 1;
                                }elseif($row->time_type==2){
                                    $hd_minutes = $get_time_diff;
                                    $hd_no = 1;
                                }elseif($row->time_type==3){
                                    $hd_minutes = $get_time_diff;
                                    $hd_no = 1;
                                }

                            }
                        //}
                        }
                    }

                    $hours = 0;
                    $minutes = $total_minutes;
                    if($total_minutes>=60){
                        $hours = floor($total_minutes / 60);
                        $minutes = $total_minutes % 60;
                    }
                    $tardy_hr = 0;
                    $tardy_min = $tardy_minutes;
                    if($tardy_minutes>=60){
                        $tardy_hr = floor($tardy_minutes / 60);
                        $tardy_min = $tardy_minutes % 60;
                    }
                    $ud_hr = 0;
                    $ud_min = $ud_minutes;
                    if($ud_minutes>=60){
                        $ud_hr = floor($ud_minutes / 60);
                        $ud_min = $ud_minutes % 60;
                    }
                    $hd_hr = 0;
                    $hd_min = $hd_minutes;
                    if($hd_minutes>=60){
                        $hd_hr = floor($hd_minutes / 60);
                        $hd_min = $hd_minutes % 60;
                    }
                    $abs_hr = 0;
                    $abs_min = $abs_minutes;
                    if($abs_minutes>=60){
                        $abs_hr = floor($abs_minutes / 60);
                        $abs_min = $abs_minutes % 60;
                    }
                    if($row->time_type_==NULL){
                        $time_type_name = '';
                    }else{
                        $time_type_name = $row->time_type_->name;
                    }

                    $dtr[$date_day]['in_am'] = $time_in_am;
                    $dtr[$date_day]['out_am'] = $time_out_am;
                    $dtr[$date_day]['in_pm'] = $time_in_pm;
                    $dtr[$date_day]['out_pm'] = $time_out_pm;
                    $dtr[$date_day]['time_from'] = $time_from;
                    $dtr[$date_day]['time_to'] = $time_to;
                    $dtr[$date_day]['time_type'] = $row->time_type;
                    $dtr[$date_day]['time_type_name'] = $row->time_type_->name;
                    $dtr[$date_day]['time_in_am_type'] = $row->time_in_am_type;
                    $dtr[$date_day]['time_out_am_type'] = $row->time_out_am_type;
                    $dtr[$date_day]['time_in_pm_type'] = $row->time_in_pm_type;
                    $dtr[$date_day]['time_out_pm_type'] = $row->time_out_pm_type;
                    $dtr[$date_day]['hours'] = $hours;
                    $dtr[$date_day]['minutes'] = $minutes;

                }else{
                    if($dtr[$m]['time_from']!=''){
                        $count_days += 1;
                    }else{
                        if($weekDay!=7 && $weekDay!=6){
                            $count_days += 1;
                        }
                    }
                }
            }
            foreach($holidays as $row){
                $date_day = date('j',strtotime($row->date));
                if($dtr[$date_day]['check']==''){
                    $dtr[$date_day]['check'] = 'holiday';
                    $dtr[$date_day]['val'] = $row->name;
                    $count_days = $count_days-1;
                }
            }
        if($count_days<=0){

        //$pdf = new PDF('A4', 'mm', '', true, 'UTF-8', false);
        $page_size = array(215.9, 330.2);
        // $pdf = new Pdf('P', 'mm', $page_size, true, 'UTF-8', false);
        // $height = 185;
        // $width = 260;
        // $pdf::reset();
        $pdf::AddPage('P',$page_size);
        $pdf::SetAutoPageBreak(TRUE, 3);
       //landscape scale A4
        //$height = 185;
        //$width = 260;
        //Portrait scale A4
        //$width = 210;
        //height = 270;

        for($i=0;$i<=2;$i++){
            if($i==0){
                $x_add = 0;
            }else{
                $x_add = 105;
            }

            $y = 6;
            $y_add = 14;
            // $pdf::SetXY(24+$x_add, $y+$y_add+5);
            // $pdf::Image($logo_blur, '', '', 20, 20, '', '', 'T', false, 0, '', false, false, 0, false, false, false);

            // $pdf::SetXY(62+$x_add, $y+$y_add+5);
            // $pdf::Image($logo_blur, '', '', 20, 20, '', '', 'T', false, 0, '', false, false, 0, false, false, false);
            // for($k=1;$k<=3;$k++){
            //     $y_add = $y_add+40;
            //     $pdf::SetXY(5+$x_add, $y+$y_add);
            //     $pdf::Image($logo_blur, '', '', 20, 20, '', '', 'T', false, 0, '', false, false, 0, false, false, false);

            //     $pdf::SetXY(42+$x_add, $y+$y_add);
            //     $pdf::Image($logo_blur, '', '', 20, 20, '', '', 'T', false, 0, '', false, false, 0, false, false, false);

            //     $pdf::SetXY(80+$x_add, $y+$y_add);
            //     $pdf::Image($logo_blur, '', '', 20, 20, '', '', 'T', false, 0, '', false, false, 0, false, false, false);

            //     $y_add = $y_add+40;

            //     $pdf::SetXY(24+$x_add, $y+$y_add);
            //     $pdf::Image($logo_blur, '', '', 20, 20, '', '', 'T', false, 0, '', false, false, 0, false, false, false);

            //     $pdf::SetXY(62+$x_add, $y+$y_add);
            //     $pdf::Image($logo_blur, '', '', 20, 20, '', '', 'T', false, 0, '', false, false, 0, false, false, false);
            // }

            $pdf::SetXY(72+$x_add, $y-4);
            $pdf::Image($qrcode, '', '', 30, 26, '', '', 'T', false, 0, '', false, false, 0, false, false, false);

            $pdf::SetFont('typewritingsmall','',9);
            $pdf::SetXY(7+$x_add, $y-2);
            $pdf::Image($logo, '', '', 23, 23, '', '', 'T', false, 0, '', false, false, 0, false, false, false);

            $pdf::SetXY(5+$x_add, $y+2);
            $pdf::Cell(95, '', 'Civil Service Form 48', 0, 1, 'C', 0, '', 1);

            $pdf::SetXY(5+$x_add, $y+9);
            $pdf::SetFont('typewriteb','',9);
            $pdf::Cell(95, '', 'DAILY TIME RECORD', 0, 1, 'C', 0, '', 1);

            $y = $y+4;

            $pdf::SetXY(5+$x_add, $y+18);
            $pdf::SetFont('typewritingsmall','',11);
            $pdf::Cell(95, '', $name, 'B', 1, 'C', 0, '', 1);

            $pdf::SetXY(5+$x_add, $y+24);
            $pdf::SetFont('typewriteb','',10);
            $pdf::Cell(95, '', '(NAME)', 0, 1, 'C', 0, '', 1);

            $pdf::SetXY(5+$x_add, $y+30);
            $pdf::SetFont('typewritingsmall','',9);
            $pdf::Cell(95, '', 'Official Hours for arrival and departure', 0, 1, 'C', 0, '', 1);

            $pdf::SetXY(5+$x_add, $y+34);
            $pdf::SetFont('typewritingsmall','',9);
            $pdf::Cell(95, '', 'For the month of ', 0, 1, 'L', 0, '', 1);

            if($range==2){
                $pdf::SetXY(35+$x_add, $y+34.8);
                $pdf::SetFont('typewriteb','',9);
                $pdf::Cell(95, '', date('F 1-15, Y', strtotime($year.'-'.$month.'-01')), 0, 1, 'L', 0, '', 1);
            }else{
                $pdf::SetXY(35+$x_add, $y+34.8);
                $pdf::SetFont('typewriteb','',9);
                $pdf::Cell(95, '', date('F Y', strtotime($year.'-'.$month.'-01')), 0, 1, 'L', 0, '', 1);
            }

            $pdf::setCellPaddings(0, 1, 0, 1);
            $pdf::SetXY(5+$x_add, $y+40);
            $pdf::SetFont('typewritingsmall','',9);
            $pdf::Cell(10, '', '', 1, 1, 'L', 0, '', 1);

            $x_tr_add = 10;

            $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
            $pdf::Cell(30, '', 'AM', 1, 1, 'C', 0, '', 1);

            $x_tr_add = $x_tr_add+30;

            $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
            $pdf::Cell(30, '', 'PM', 1, 1, 'C', 0, '', 1);

            $x_tr_add = $x_tr_add+30;

            $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
            $pdf::Cell(25, '', 'Undertime', 1, 1, 'C', 0, '', 1);

            $y = $y+6;

            $pdf::SetXY(5+$x_add, $y+40);
            $pdf::Cell(10, '', 'Day', 1, 1, 'C', 0, '', 1);

            $x_tr_add = 10;

            $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
            $pdf::Cell(15, '', ' Arrival ', 1, 1, 'C', 0, '', 1);

            $x_tr_add = $x_tr_add+15;

            $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
            $pdf::Cell(15, '', ' Departure ', 1, 1, 'C', 0, '', 1);

            $x_tr_add = $x_tr_add+15;

            $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
            $pdf::Cell(15, '', ' Arrival ', 1, 1, 'C', 0, '', 1);

            $x_tr_add = $x_tr_add+15;

            $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
            $pdf::Cell(15, '', ' Departure ', 1, 1, 'C', 0, '', 1);

            $x_tr_add = $x_tr_add+15;

            $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
            $pdf::Cell(12.5, '', ' Hours ', 1, 1, 'C', 0, '', 1);

            $x_tr_add = $x_tr_add+12.5;

            $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
            $pdf::Cell(12.5, '', ' Minutes ', 1, 1, 'C', 0, '', 1);
            $total_minutes = 0;
            for($j=1;$j<=date('t',strtotime($year.'-'.$month.'-01'));$j++){
                $y = $y+6;
                $weekDay = date('w', strtotime($year.'-'.$month.'-'.$j));
                $pdf::SetXY(5+$x_add, $y+40);
                $pdf::Cell(10, '', $j, 1, 1, 'C', 0, '', 1);

                $x_tr_add = 10;

                if(($weekDay == 0 || $weekDay == 6) && $dtr[$j]['check']==''){
                    if($weekDay==0){
                        $pdf::SetTextColor(220,20,60);
                    }else{
                        $pdf::SetTextColor(0,0,0);
                    }
                    $weekDayName = date('l', strtotime($year.'-'.$month.'-'.$j));
                    $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
                    $pdf::Cell(60, '', $weekDayName, 1, 1, 'C', 0, '', 1);

                    $x_tr_add = $x_tr_add+60;

                }elseif($dtr[$j]['check']=='holiday'){
                    //$pdf::SetDrawColor(255, 127, 127);
                    // $pdf::SetFillColor(255, 0, 0);
                    $pdf::SetTextColor(65,105,225);
                    $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
                    $pdf::Cell(60, '', $dtr[$j]['val'], 1, 1, 'C', 0, '', 1);

                    $x_tr_add = $x_tr_add+60;

                }else{
                    if($range==2 && $j>15){
                        $pdf::SetTextColor(0,0,0);
                        $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
                        $pdf::Cell(60, '', '-------------------', 1, 1, 'C', 0, '', 1);

                        $x_tr_add = $x_tr_add+60;
                    }else{
                        if(date('Y-m')==date('Y-m',strtotime($year.'-'.$month.'-01')) && $j>date('d')){
                            $pdf::SetTextColor(0,0,0);
                            $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
                            $pdf::Cell(60, '', '-------------------', 1, 1, 'C', 0, '', 1);

                            $x_tr_add = $x_tr_add+60;
                        }else{
                            if($dtr[$j]['time_type']=='1' || $dtr[$j]['time_type']=='4' || $dtr[$j]['time_type']=='5'
                                || ($dtr[$j]['time_in_am_type']!=NULL && $dtr[$j]['time_in_am_type']!='1' &&
                                $dtr[$j]['time_out_am_type']!=NULL && $dtr[$j]['time_out_am_type']!='1' &&
                                $dtr[$j]['time_in_pm_type']!=NULL && $dtr[$j]['time_in_pm_type']!='1' &&
                                $dtr[$j]['time_out_pm_type']!=NULL && $dtr[$j]['time_out_pm_type']!='1')){

                                $pdf::SetTextColor(0,0,0);
                                $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
                                if($dtr[$j]['time_type']==7){
                                    $pdf::Cell(60, '', '-------------------', 1, 1, 'C', 0, '', 1);
                                }else{
                                    $pdf::Cell(60, '', $dtr[$j]['time_type_name'], 1, 1, 'C', 0, '', 1);
                                }

                                $x_tr_add = $x_tr_add+60;
                            }else{
                                if($dtr[$j]['time_type']=='2'){
                                    $pdf::SetTextColor(0,0,0);
                                    $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
                                    $pdf::Cell(30, '', 'Half Day', 1, 1, 'C', 0, '', 1);

                                    $x_tr_add = $x_tr_add+30;
                                }elseif($dtr[$j]['time_in_am_type']!=NULL && $dtr[$j]['time_in_am_type']!='1' &&
                                        $dtr[$j]['time_out_am_type']!=NULL && $dtr[$j]['time_out_am_type']!='1'){
                                    $pdf::SetTextColor(0,0,0);
                                    $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);

                                    if($dtr[$j]['time_type']==7){
                                        $pdf::Cell(30, '', '-------', 1, 1, 'C', 0, '', 1);
                                    }else{
                                        $pdf::Cell(30, '', $dtr[$j]['time_type_name'], 1, 1, 'C', 0, '', 1);
                                    }

                                    $x_tr_add = $x_tr_add+30;
                                }else{
                                    if($dtr[$j]['time_in_am_type']!=NULL && $dtr[$j]['time_in_am_type']!='1'){
                                        $pdf::SetDrawColor(0,0,0);
                                        $pdf::SetTextColor(0,0,0);
                                        $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);

                                        if($dtr[$j]['time_type']==7){
                                            $pdf::Cell(15, '', '-------', 1, 1, 'C', 0, '', 1);
                                        }else{
                                            $pdf::Cell(15, '', $dtr[$j]['time_type_name'], 1, 1, 'C', 0, '', 1);
                                        }

                                    }else{
                                        if($dtr[$j]['time_in_am_type']=='1'){
                                            $pdf::SetDrawColor(220,20,60);
                                            $pdf::SetTextColor(220,20,60);
                                            $pdf::SetXY(6+$x_tr_add+$x_add, $y+39);
                                            $pdf::Cell(13, '', '', 'B', 1, 'C', 0, '', 1);
                                        }else{
                                            $pdf::SetDrawColor(0,0,0);
                                            $pdf::SetTextColor(0,0,0);
                                        }
                                        if($dtr[$j]['time_from']!='' && $dtr[$j]['time_from']>'12:00' && $dtr[$j]['in_am']==''){
                                            $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
                                            $pdf::Cell(15, '', '------', 1, 1, 'C', 0, '', 1);
                                        }else{
                                            $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
                                            $pdf::Cell(15, '', $dtr[$j]['in_am'], 1, 1, 'C', 0, '', 1);
                                        }
                                    }

                                    $x_tr_add = $x_tr_add+15;

                                    if($dtr[$j]['time_out_am_type']!=NULL && $dtr[$j]['time_out_am_type']!='1'){
                                        $pdf::SetDrawColor(0,0,0);
                                        $pdf::SetTextColor(0,0,0);
                                        $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);

                                        if($dtr[$j]['time_type']==7){
                                            $pdf::Cell(15, '', '-------', 1, 1, 'C', 0, '', 1);
                                        }else{
                                            $pdf::Cell(15, '', $dtr[$j]['time_type_name'], 1, 1, 'C', 0, '', 1);
                                        }

                                    }else{
                                        if($dtr[$j]['time_out_am_type']=='1'){
                                            $pdf::SetDrawColor(220,20,60);
                                            $pdf::SetTextColor(220,20,60);
                                            $pdf::SetXY(6+$x_tr_add+$x_add, $y+39);
                                            $pdf::Cell(13, '', '', 'B', 1, 'C', 0, '', 1);
                                        }else{
                                            $pdf::SetDrawColor(0,0,0);
                                            $pdf::SetTextColor(0,0,0);
                                        }
                                        if($dtr[$j]['time_from']!='' && $dtr[$j]['time_from']>'12:00' && $dtr[$j]['out_am']==''){
                                            $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
                                            $pdf::Cell(15, '', '------', 1, 1, 'C', 0, '', 1);
                                        }else{
                                            $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
                                            $dtr_out_am = $dtr[$j]['out_am'];
                                            if($dtr[$j]['out_am']==NULL && $dtr[$j]['in_am']!=NULL){
                                                $dtr_out_am = '12:00pm*';
                                            }
                                            $pdf::Cell(15, '', $dtr_out_am, 1, 1, 'C', 0, '', 1);
                                        }
                                    }
                                    $x_tr_add = $x_tr_add+15;
                                }
                                if($dtr[$j]['time_type']=='3'){
                                    $pdf::SetTextColor(0,0,0);
                                    $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
                                    $pdf::Cell(30, '', 'Half Day', 1, 1, 'C', 0, '', 1);

                                    $x_tr_add = $x_tr_add+30;
                                }elseif($dtr[$j]['time_in_pm_type']!=NULL && $dtr[$j]['time_in_pm_type']!='1' &&
                                        $dtr[$j]['time_out_pm_type']!=NULL && $dtr[$j]['time_out_pm_type']!='1'){
                                    $pdf::SetTextColor(0,0,0);
                                    $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
                                    if($dtr[$j]['time_type']==7){
                                        $pdf::Cell(30, '', '-------', 1, 1, 'C', 0, '', 1);
                                    }else{
                                        $pdf::Cell(30, '', $dtr[$j]['time_type_name'], 1, 1, 'C', 0, '', 1);
                                    }

                                    $x_tr_add = $x_tr_add+30;
                                }else{
                                    if($dtr[$j]['time_in_pm_type']!=NULL && $dtr[$j]['time_in_pm_type']!='1'){
                                        $pdf::SetDrawColor(0,0,0);
                                        $pdf::SetTextColor(0,0,0);
                                        $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);

                                        if($dtr[$j]['time_type']==7){
                                            $pdf::Cell(15, '', '-------', 1, 1, 'C', 0, '', 1);
                                        }else{
                                            $pdf::Cell(15, '', $dtr[$j]['time_type_name'], 1, 1, 'C', 0, '', 1);
                                        }

                                    }else{
                                        if($dtr[$j]['time_in_pm_type']=='1'){
                                            $pdf::SetDrawColor(220,20,60);
                                            $pdf::SetTextColor(220,20,60);
                                            $pdf::SetXY(6+$x_tr_add+$x_add, $y+39);
                                            $pdf::Cell(13, '', '', 'B', 1, 'C', 0, '', 1);
                                        }else{
                                            $pdf::SetDrawColor(0,0,0);
                                            $pdf::SetTextColor(0,0,0);
                                        }
                                        if($dtr[$j]['time_to']!='' && $dtr[$j]['time_to']<'13:00' && $dtr[$j]['in_pm']==''){
                                            $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
                                            $pdf::Cell(15, '', '-----', 1, 1, 'C', 0, '', 1);
                                        }else{
                                            $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
                                            $dtr_in_pm = $dtr[$j]['in_pm'];
                                            if($dtr[$j]['in_pm']==NULL && $dtr[$j]['in_am']!=NULL){
                                                $dtr_in_pm = '01:00pm*';
                                            }
                                            $pdf::Cell(15, '', $dtr_in_pm, 1, 1, 'C', 0, '', 1);
                                        }
                                    }
                                    $x_tr_add = $x_tr_add+15;

                                    if($dtr[$j]['time_out_pm_type']!=NULL && $dtr[$j]['time_out_pm_type']!='1'){
                                        $pdf::SetDrawColor(0,0,0);
                                        $pdf::SetTextColor(0,0,0);
                                        $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);

                                        if($dtr[$j]['time_type']==7){
                                            $pdf::Cell(15, '', '-------', 1, 1, 'C', 0, '', 1);
                                        }else{
                                            $pdf::Cell(15, '', $dtr[$j]['time_type_name'], 1, 1, 'C', 0, '', 1);
                                        }

                                    }else{
                                        if($dtr[$j]['time_out_pm_type']=='1'){
                                            $pdf::SetDrawColor(220,20,60);
                                            $pdf::SetTextColor(220,20,60);
                                            $pdf::SetXY(6+$x_tr_add+$x_add, $y+39);
                                            $pdf::Cell(13, '', '', 'B', 1, 'C', 0, '', 1);
                                        }else{
                                            $pdf::SetDrawColor(0,0,0);
                                            $pdf::SetTextColor(0,0,0);
                                        }
                                        if($dtr[$j]['time_to']!='' && $dtr[$j]['time_to']<'13:00' && $dtr[$j]['out_pm']==''){
                                            $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
                                            $pdf::Cell(15, '', '-----', 1, 1, 'C', 0, '', 1);
                                        }else{
                                            $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
                                            $pdf::Cell(15, '', $dtr[$j]['out_pm'], 1, 1, 'C', 0, '', 1);
                                        }
                                    }

                                    $x_tr_add = $x_tr_add+15;
                                }
                            }
                        }
                    }
                }
                $pdf::SetDrawColor(0, 0, 0);
                $pdf::SetFillColor(0, 0, 0);
                $pdf::SetTextColor(0, 0, 0);

                if($dtr[$j]['hours']>0){
                    $hours = $dtr[$j]['hours'];
                }else{
                    $hours = '';
                }
                $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
                $pdf::Cell(12.5, '', $hours, 1, 1, 'C', 0, '', 1);

                $x_tr_add = $x_tr_add+12.5;

                if($dtr[$j]['minutes']>0){
                    $minutes = $dtr[$j]['minutes'];
                }else{
                    $minutes = '';
                }
                $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
                $pdf::Cell(12.5, '', $minutes, 1, 1, 'C', 0, '', 1);

                $total_minutes += $dtr[$j]['minutes']+$dtr[$j]['hours']*60;
            }
            $y = $y+6;
            $x_tr_add = 0;
            $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
            $pdf::SetFont('typewriteb','',9);
            $pdf::Cell(70, '', 'TOTAL ', 1, 1, 'R', 0, '', 1);

            $x_tr_add = $x_tr_add+70;

            $hours = 0;
            $minutes = $total_minutes;
            if($total_minutes>=60){
                $hours = floor($total_minutes / 60);
                $minutes = $total_minutes % 60;
            }
            if($hours>0){
                $hours = $hours;
            }else{
                $hours = '';
            }
            if($minutes>0){
                $minutes = $minutes;
            }else{
                $minutes = '';
            }
            $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
            $pdf::Cell(12.5, '', $hours, 1, 1, 'C', 0, '', 1);

            $x_tr_add = $x_tr_add+12.5;

            $pdf::SetXY(5+$x_tr_add+$x_add, $y+40);
            $pdf::Cell(12.5, '', $minutes, 1, 1, 'C', 0, '', 1);

            $pdf::setCellPaddings(0, 0, 0, 0);

            $y = $y+6.25;

            $pdf::SetXY(10+$x_add, $y+40);
            $pdf::SetFont('typewritingsmall','',9);
            $pdf::Cell(85, '', 'I CERTIFY on my honor that the above is a true and correct', 0, 0, 'C', 0, '', 1);

            $y = $y+4;

            $pdf::SetXY(10+$x_add, $y+40);
            $pdf::Cell(85, '', 'report of the hours of work performed records of which was', 0, 0, 'C', 0, '', 1);

            $y = $y+4;

            $pdf::SetXY(10+$x_add, $y+40);
            $pdf::Cell(85, '', 'made daily at the time of arrival at and departure from office.', 0, 0, 'C', 0, '', 1);

            $y = $y+6;

            $pdf::SetXY(5+$x_add, $y+40);
            $pdf::SetFont('typewriteb','',8);
            $pdf::Cell(95, '', '', 'B', 1, 'C', 0, '', 1);

            $y = $y+4.5;

            $pdf::SetXY(5+$x_add, $y+40);
            $pdf::Cell(95, '', 'Verified as to the prescribed office hours', '', 1, 'C', 0, '', 1);

            $y = $y+9;

            $pdf::SetXY(5+$x_add, $y+40);
            $pdf::SetFont('typewriteb','',8);
            $pdf::Cell(95, '', $signatory, '', 1, 'C', 0, '', 1);

            $y = $y-0.4;

            $pdf::SetXY(5+$x_add, $y+40);
            $pdf::SetFont('typewriteb','',8);
            $pdf::Cell(95, '', '', 'B', 1, 'C', 0, '', 1);

            $y = $y+4.5;

            $pdf::SetXY(5+$x_add, $y+40);
            $pdf::SetFont('typewriteb','',8);
            $pdf::Cell(95, '', 'Authorized signature', '', 1, 'C', 0, '', 1);

            $schedTimeGet = UsersSchedTime::with('days')
                ->where('user_id',$user_id)
                ->where('date_to','>=',date('Y-m-01',strtotime($year.'-'.$month.'-01')))
                ->where('date_from','<=',date('Y-m-t',strtotime($year.'-'.$month.'-01')));
                if($option=='o'){
                    $schedTimeGet = $schedTimeGet->where('option_id',2);
                }else{
                    $schedTimeGet = $schedTimeGet->where('option_id',1);
                }
            $schedTimeGet = $schedTimeGet->get();
            if($schedTimeGet->count()>0){
                $y = $y+8;
                $pdf::SetXY(5+$x_add, $y+40);
                $pdf::SetFont('typewrite','',8);
                $pdf::Cell(95, '', 'Official Time:', '', 1, 'L', 0, '', 1);

                $listDays = array('S', 'M', 'T', 'W','TH','F', 'S');

                foreach($schedTimeGet as $sched){
                    $getDays = array();
                    foreach($sched->days as $days){
                        $getDays[] = $listDays[$days->day];
                    }
                    $implodeDays = implode('',$getDays);
                    $y = $y+3.5;
                    $pdf::SetXY(5+$x_add, $y+40);
                    $pdf::SetFont('typewrite','',8);
                    $pdf::Cell(95, '', $implodeDays.' - '.date('h:ia',strtotime($sched->time_from)).' - '.date('h:ia',strtotime($sched->time_to)), '', 1, 'L', 0, '', 1);
                }
            }

            $pdf::SetXY(5+$x_add, 320);
            $pdf::SetFont('typewritingsmall','',6);
            $pdf::Cell(95, '', 'Date Time printed: '.date('F d, Y h:i a'), '', 1, 'L', 0, '', 1);
        }
        $pdf::SetXY(103, 281);
        $pdf::Image($scissor, '', '', 4, 7, '', '', 'T', false, 0, '', false, false, 0, false, false, false);

        $pdf::SetXY(100, 5);
        $pdf::SetFont('typewriteb','',6);
        $pdf::MultiCell(10, 270, "|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n", 0, 'C', 0, 0, '', '', true);

        // $pathUser = 'storage\hrims\employee/'.$id_no.'\dtr/'.$year.'/'.$id_no.'_'.$year.'_'.$month.'.pdf';
        // $pdf::Output(public_path($pathUser),'F');
        }
        return $pdf;
    }
    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function showValidateRequest(Request $request)
    {
        $rules = [
            'id_no' => 'required|string',
            'option' => 'required|string',
            'year' => 'required|string',
            'month' => 'required|string',
            'range' => 'required|string'
        ];

        $customMessages = [
            'id_no.required' => 'ID is required.',
            'id_no.string' => 'ID must be a string.',
            'option.required' => 'Option is required.',
            'option.string' => 'Option must be a string.'
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }
}
