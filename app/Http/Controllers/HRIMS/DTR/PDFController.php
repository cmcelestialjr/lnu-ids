<?php

namespace App\Http\Controllers\HRIMS\DTR;
use App\Http\Controllers\Controller;
use App\Models\_Work;
use App\Models\EducOfferedSchedule;
use App\Models\EducOfferedScheduleDay;
use App\Models\Holidays;
use App\Models\Users;
use App\Models\UsersDTR;
use App\Models\UsersDTRTrack;
use App\Models\UsersRoleList;
use App\Models\UsersSchedDays;
use App\Services\NameServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PDFController extends Controller
{   
    public function PDF(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $id_no = $user->id_no;
        $id_no_req = $request->id_no;
        $year = $request->year;
        $month = $request->month;
        $range =$request->range;
        $result = 'error';
        $src = '';
        $check = UsersDTR::where('id_no',$id_no_req)
            ->whereYear('date',$year)
            ->whereMonth('date',$month)->first();
        if(($user_access_level==1 || $user_access_level==2) || ($id_no==$id_no_req) && $check!=NULL){
            $result = 'success';            
            $name_services = new NameServices;
            $src = $this->generateQR($id_no_req,$year,$month,$range);
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
    private function generateQR($id_no,$year,$month,$range){
        error_reporting(E_ERROR);
        $image = QrCode::format('png')
                    ->merge(public_path('assets\images\logo\lnu_logo.png'), .28, true)
                    ->style('round', 0.2)
                    //->eye('circle')
                    ->eyeColor(1, /*outer*/ 0, 0, 128, /*inner*/ 212,175,55, 0, 0)
                    ->eyeColor(2, /*outer*/ 212,175,55, /*inner*/ 0, 0, 128, 0, 0)
                    ->size(300)
                    ->errorCorrection('H')
                    ->generate(url('hrims/dtr/pdf/'.$year.'/'.$month.'/'.$id_no.'/'.$range));
        $imageName = $id_no.'_'.$year.'_'.$month.'_'.$range.'.png';
        $path = 'storage\hrims\employee/'.$id_no.'\dtr/'.$year.'/';
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
        $file = public_path($path . $imageName);
        file_put_contents($file, $image);
        $qrcode = $path.$imageName;
        $src = $this->generatePDF($id_no,$year,$month,$range,$qrcode);
        return $src;
    }
    private function generatePDF($id_no,$year,$month,$range,$qrcode){
        $pathUser = NULL;
        $user = Users::where('id_no',$id_no)->first();
        $user_id = $user->id;
        $name_services = new NameServices;
        $name = mb_strtoupper($name_services->firstname($user->lastname,$user->firstname,$user->middlename,$user->extname));
        
        $logo = public_path('assets\images\logo\lnu_logo.png');
        $logo_blur = public_path('assets\images\logo\lnu_logo_blur1.png');
        $scissor = public_path('assets\images\icons\png\scissor1.png');

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
                if($emp_type=='Employee'){
                    $day = UsersSchedDays::where('user_id',$user_id)->where('day',$weekDay)->first();
                    if($day!=NULL){
                        $time_from = date('H:i',strtotime($day->time->time_from));
                        $time_to = date('H:i',strtotime($day->time->time_to));
                    }
                }else{
                    $day = EducOfferedScheduleDay::where('no',$weekDay)
                        ->whereHas('schedule', function ($query) use ($user_id,$year,$month) {                        
                            $query->whereHas('course', function ($query) use ($user_id,$year,$month) {
                                $query->where('instructor_id',$user_id);
                                $query->whereHas('curriculum', function ($query) use ($year,$month) {
                                    $query->whereHas('offered_program', function ($query) use ($year,$month) {                                    
                                        $query->whereHas('school_year', function ($query) use ($year,$month) {
                                            $query->where('year_from','>=',$year);
                                            $query->whereHas('grade_period', function ($query) use ($month) {
                                                $query->whereHas('month', function ($query) use ($month) {
                                                    $query->where('month',$month);
                                                });
                                            });
                                        });
                                    });
                                });
                            });
                        })
                        ->pluck('offered_schedule_id')->toArray();
                    $time_from_query = EducOfferedSchedule::whereIn('id',$day)->orderBy('time_from','ASC')
                        ->whereHas('course', function ($query) {
                            $query->where('load_type',1);
                        })
                        ->first();
                    $time_to_query = EducOfferedSchedule::whereIn('id',$day)->orderBy('time_to','DESC')
                        ->whereHas('course', function ($query) {
                            $query->where('load_type',1);
                        })
                        ->first();
                    $time_minutes = EducOfferedSchedule::whereIn('id',$day)
                        ->whereHas('course', function ($query) {
                            $query->where('load_type',1);
                        })->get();                
                    if($time_minutes->count()>0){                    
                        foreach($time_minutes as $row){
                            $time_from_ = Carbon::parse($row->time_from);
                            $time_to_ = Carbon::parse($row->time_to);
                            $time_minutes_total += $time_to_->diffInMinutes($time_from_);
                        }
                    }
                    if($time_from_query!=NULL){
                        $time_from = date('H:i',strtotime($time_from_query->time_from));
                    }
                    if($time_to_query!=NULL){
                        $time_to =date('H:i',strtotime( $time_to_query->time_to));
                    }
                }
                $dtr[$l]['time_from'] = $time_from;
                $dtr[$l]['time_to'] = $time_to;
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
                    if($emp_type=='Employee'){
                        $day = UsersSchedDays::where('user_id',$user_id)->where('day',$weekDay)->first();
                        if($day!=NULL){
                            $time_from = date('H:i',strtotime($day->time->time_from));
                            $time_to = date('H:i',strtotime($day->time->time_to));
                        }
                    }else{
                        $day = EducOfferedScheduleDay::where('no',$weekDay)
                            ->whereHas('schedule', function ($query) use ($user_id,$year,$month) {                        
                                $query->whereHas('course', function ($query) use ($user_id,$year,$month) {
                                    $query->where('instructor_id',$user_id);
                                    $query->whereHas('curriculum', function ($query) use ($year,$month) {
                                        $query->whereHas('offered_program', function ($query) use ($year,$month) {                                    
                                            $query->whereHas('school_year', function ($query) use ($year,$month) {
                                                $query->where('year_from','>=',$year);
                                                $query->whereHas('grade_period', function ($query) use ($month) {
                                                    $query->whereHas('month', function ($query) use ($month) {
                                                        $query->where('month',$month);
                                                    });
                                                });
                                            });
                                        });
                                    });
                                });
                            })
                            ->pluck('offered_schedule_id')->toArray();
                        $time_from_query = EducOfferedSchedule::whereIn('id',$day)->orderBy('time_from','ASC')
                            ->whereHas('course', function ($query) {
                                $query->where('load_type',1);
                            })
                            ->first();
                        $time_to_query = EducOfferedSchedule::whereIn('id',$day)->orderBy('time_to','DESC')
                            ->whereHas('course', function ($query) {
                                $query->where('load_type',1);
                            })
                            ->first();
                        $time_minutes = EducOfferedSchedule::whereIn('id',$day)
                            ->whereHas('course', function ($query) {
                                $query->where('load_type',1);
                            })->get();                
                        if($time_minutes->count()>0){                    
                            foreach($time_minutes as $r){
                                $time_from_ = Carbon::parse($r->time_from);
                                $time_to_ = Carbon::parse($r->time_to);
                                $time_minutes_total += $time_to_->diffInMinutes($time_from_);
                            }
                        }
                        if($time_from_query!=NULL){
                            $time_from = date('H:i',strtotime($time_from_query->time_from));
                        }
                        if($time_to_query!=NULL){
                            $time_to =date('H:i',strtotime( $time_to_query->time_to));
                        }
                    }
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
                    $total_minutes = 0;
                    if($time_from!=''){
                        if($time_from<'12:00' && $time_to>'12:00'){
                            if(($time_in_am_for=='' || $time_out_am_for=='' || $time_out_pm_for=='' || $time_out_pm_for=='')
                                && $row->time_type==NULL){
                                $count_days += 1;
                            }
                        }elseif(($time_from<'12:00' && $time_to<'13:00') || $row->time_type==3){
                            if($time_in_am_for=='' || $time_out_am_for==''){
                                $count_days += 1;
                            }
                        }else{
                            if(($time_in_pm_for=='' || $time_out_pm_for=='') || $row->time_type==2){
                                $count_days += 1;
                            }
                        }
                        if($time_from<'12:00'){
                            if($time_in_am_for!='' && $time_in_am_for>$time_from){
                                $time_from_ = Carbon::parse($time_from);
                                $time_to_ = Carbon::parse($time_in_am_for);
                                $total_minutes = $time_to_->diffInMinutes($time_from_);
                            }
                            if($time_to>'13:00'){
                                if($time_out_am_for!='' && $time_out_am_for<'12:00'){
                                    $time_from_ = Carbon::parse($time_out_am_for);
                                    $time_to_ = Carbon::parse('12:00');
                                    $total_minutes = $total_minutes+$time_to_->diffInMinutes($time_from_);
                                }
                                if($time_in_pm_for!='' && $time_in_pm_for>'13:00'){
                                    $time_from_ = Carbon::parse('13:00');
                                    $time_to_ = Carbon::parse($time_in_pm_for);
                                    $total_minutes = $total_minutes+$time_to_->diffInMinutes($time_from_);
                                }
                                if($time_out_pm_for!='' && $time_out_pm_for<$time_to){
                                    $time_from_ = Carbon::parse($time_out_pm_for);
                                    $time_to_ = Carbon::parse($time_to);
                                    $total_minutes = $total_minutes+$time_to_->diffInMinutes($time_from_);
                                }
                                if($row->time_type==2){
                                    $time_from_ = Carbon::parse($time_from);
                                    $time_to_ = Carbon::parse('12:00');
                                    $total_minutes = $time_to_->diffInMinutes($time_from_);
                                }elseif($row->time_type==3){
                                    $time_from_ = Carbon::parse('13:00');
                                    $time_to_ = Carbon::parse($time_to);
                                    $total_minutes = $total_minutes+$time_to_->diffInMinutes($time_from_);
                                }elseif($row->time_type==1){
                                    $time_from_ = Carbon::parse($time_from);
                                    $time_to_ = Carbon::parse($time_to);
                                    $total_minutes = $time_to_->diffInMinutes($time_from_);
                                    if($total_minutes>=540){
                                        $total_minutes = 480;
                                    }
                                }                        
                            }else{
                                if($time_out_am_for!='' && $time_out_am_for<$time_to){
                                    $time_from_ = Carbon::parse($time_out_am_for);
                                    $time_to_ = Carbon::parse($time_to);
                                    $total_minutes = $time_to_->diffInMinutes($time_from_);
                                }
                                if($row->time_type==1){
                                    $total_minutes = $time_minutes_total;
                                }
                            }                    
                        }else{
                            if($time_in_pm_for!='' && $time_in_pm_for>$time_from){
                                $time_from_ = Carbon::parse($time_from);
                                $time_to_ = Carbon::parse($time_in_pm_for);
                                $total_minutes = $time_to_->diffInMinutes($time_from_);
                            }
                            if($time_out_pm_for!='' && $time_out_pm_for<$time_to){
                                $time_from_ = Carbon::parse($time_out_pm_for);
                                $time_to_ = Carbon::parse($time_to);
                                $total_minutes = $total_minutes+$time_to_->diffInMinutes($time_from_);
                            }
                            if($row->time_type==1){
                                $total_minutes = $time_minutes_total;
                            }
                        }
                    }
                    $hours = 0;
                    $minutes = $total_minutes;
                    if($total_minutes>=60){
                        $hours = floor($total_minutes / 60);
                        $minutes = $total_minutes % 60;
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
        $pdf = new Pdf('P', 'mm', $page_size, true, 'UTF-8', false);
        $height = 185;
        $width = 260;
        $pdf::reset();
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
            $pdf::SetXY(24+$x_add, $y+$y_add+5);
            $pdf::Image($logo_blur, '', '', 20, 20, '', '', 'T', false, 0, '', false, false, 0, false, false, false);

            $pdf::SetXY(62+$x_add, $y+$y_add+5);
            $pdf::Image($logo_blur, '', '', 20, 20, '', '', 'T', false, 0, '', false, false, 0, false, false, false);
            for($k=1;$k<=3;$k++){
                $y_add = $y_add+40;
                $pdf::SetXY(5+$x_add, $y+$y_add);
                $pdf::Image($logo_blur, '', '', 20, 20, '', '', 'T', false, 0, '', false, false, 0, false, false, false);

                $pdf::SetXY(42+$x_add, $y+$y_add);
                $pdf::Image($logo_blur, '', '', 20, 20, '', '', 'T', false, 0, '', false, false, 0, false, false, false);

                $pdf::SetXY(80+$x_add, $y+$y_add);
                $pdf::Image($logo_blur, '', '', 20, 20, '', '', 'T', false, 0, '', false, false, 0, false, false, false);

                $y_add = $y_add+40;

                $pdf::SetXY(24+$x_add, $y+$y_add);
                $pdf::Image($logo_blur, '', '', 20, 20, '', '', 'T', false, 0, '', false, false, 0, false, false, false);

                $pdf::SetXY(62+$x_add, $y+$y_add);
                $pdf::Image($logo_blur, '', '', 20, 20, '', '', 'T', false, 0, '', false, false, 0, false, false, false);
            }

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
                                            $pdf::Cell(15, '', $dtr[$j]['out_am'], 1, 1, 'C', 0, '', 1);
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
                                            $pdf::Cell(15, '', $dtr[$j]['in_pm'], 1, 1, 'C', 0, '', 1);
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
            $pdf::Cell(95, '', '', 'B', 1, 'C', 0, '', 1);

            $y = $y+4.5;

            $pdf::SetXY(5+$x_add, $y+40);
            $pdf::SetFont('typewriteb','',8);
            $pdf::Cell(95, '', 'Authorized signature', '', 1, 'C', 0, '', 1);


            $pdf::SetXY(5+$x_add, 291);
            $pdf::SetFont('typewritingsmall','',6);
            $pdf::Cell(95, '', 'Date Time printed: '.date('F d, Y h:i a'), '', 1, 'L', 0, '', 1);
        }
        $pdf::SetXY(103, 281);
        $pdf::Image($scissor, '', '', 4, 7, '', '', 'T', false, 0, '', false, false, 0, false, false, false);

        $pdf::SetXY(100, 5);
        $pdf::SetFont('typewriteb','',6);
        $pdf::MultiCell(10, 270, "|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n|\n", 0, 'C', 0, 0, '', '', true);
       
        $pathUser = 'storage\hrims\employee/'.$id_no.'\dtr/'.$year.'/'.$id_no.'_'.$year.'_'.$month.'.pdf';
        $pdf::Output(public_path($pathUser),'F');
        }
        return $pathUser;
    }
}