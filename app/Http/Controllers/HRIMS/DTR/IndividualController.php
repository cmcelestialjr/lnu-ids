<?php

namespace App\Http\Controllers\HRIMS\DTR;
use App\Http\Controllers\Controller;
use App\Models\_Work;
use App\Models\DTRtimeType;
use App\Models\EducOfferedSchedule;
use App\Models\EducOfferedScheduleDay;
use App\Models\Holidays;
use App\Models\Users;
use App\Models\UsersDTR;
use App\Models\UsersRoleList;
use App\Models\UsersSchedDays;
use App\Services\NameServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class IndividualController extends Controller
{   
    public function individual(Request $request,Users $users,
        _Work $_work, UsersDTR $usersDtr, Holidays $_holidays,
        UsersSchedDays $usersSchedDays, EducOfferedScheduleDay $educOfferedScheduleDay,
        EducOfferedSchedule $educOfferedSchedule){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $id_no = $user->id_no;
        $id_no_req = $request->id_no;
        $year = $request->year;
        $month = $request->month;
        $range = $request->range;
        $dtr_type = $request->dtr_type;
        $result = 'error';
        if($dtr_type==0 && ($user_access_level==1 || $user_access_level==2)){
            $id_no = $id_no_req;
        }
        $check = UsersDTR::where('id_no',$id_no)
            ->whereYear('date',$year)
            ->whereMonth('date',$month)->first();
        if(($user_access_level==1 || $user_access_level==2) || ($id_no==$id_no_req) && $check!=NULL){            
            $result = 'success';
            $name_services = new NameServices;
            $user = Users::where('id_no',$id_no)->first();
            $name = mb_strtoupper($name_services->firstname($user->lastname,$user->firstname,$user->middlename,$user->extname));
            $data = array(
                'id_no' => $id_no,
                'name' => $name,
                'year' => $year,
                'month' => $month,
                'range' => $range,
                'users' => $users,
                '_work' => $_work,
                'usersDtr' => $usersDtr,
                '_holidays' => $_holidays,
                'usersSchedDays' => $usersSchedDays,
                'educOfferedScheduleDay' => $educOfferedScheduleDay,
                'educOfferedSchedule' => $educOfferedSchedule
            );
            return view('hrims/dtr/individual_view',$data);
        }else{
            return $result;
        } 
    }
    public function dtrInputModal(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $id_no = $user->id_no;
        $id_no_req = $request->id_no;
        $year = $request->year;
        $month = $request->month;
        $day = $request->day;        
        if(($user_access_level==1 || $user_access_level==2) || ($id_no==$id_no_req)){
            $weekDay = date('w', strtotime($year.'-'.$month.'-'.$day));
            $date = date('Y-m-d',strtotime($year.'-'.$month.'-'.$day));
            $date_name = date('F d, Y',strtotime($year.'-'.$month.'-'.$day));
            if($weekDay==0){
                $weekDay = 7;
            }
            $query = UsersDTR::where('id_no',$id_no_req)
                ->where('date',$date)
                ->first();
            $users = Users::where('id_no',$id_no_req)
                ->first();
            
            $user_id = $users->id;

            $time_from_to = $this->time_from_to($user_id,$year,$month,$weekDay);
            $emp_type = $this->emp_type($user_id);
            $emp_stat_id = $emp_type['emp_stat_id'];
            $emp_type = $emp_type['emp_type'];
            $time_from = $time_from_to['time_from'];
            $time_to = $time_from_to['time_to'];
            $time_type_ids = array(1,2,3,4,5,6,7);
            $time_type_ids_not = array('');
            if($query!=NULL){
                if(($query->status==1) || 
                    ($query->time_in_am!=NULL && $query->time_in_am_type==NULL &&
                     $query->time_out_am!=NULL && $query->time_out_am_type==NULL &&
                     $query->time_in_pm!=NULL && $query->time_in_pm_type==NULL &&
                     $query->time_out_pm!=NULL && $query->time_out_pm_type==NULL)){
                    $time_type_ids = array('');
                }else{
                    $push = 0;                    
                    if($query->time_in_am!=NULL && $query->time_in_am_type==NULL){
                        array_push($time_type_ids_not,2);
                        $push++;
                    }
                    if($query->time_out_am!=NULL && $query->time_out_am_type==NULL){
                        array_push($time_type_ids_not,2);
                        $push++;
                    }
                    if($query->time_in_pm!=NULL && $query->time_in_pm_type==NULL){
                        array_push($time_type_ids_not,3);
                        $push++;
                    }
                    if($query->time_out_pm!=NULL && $query->time_out_pm_type==NULL){
                        array_push($time_type_ids_not,3);
                        $push++;
                    }
                    if($push>0){
                        array_push($time_type_ids_not,1,4,5);
                    }
                }
            }
            if($emp_stat_id==4 || $emp_stat_id==5){
                array_push($time_type_ids_not,4,7);
            }else{
                array_push($time_type_ids_not,1);
            }
            if($time_from!='' && $time_to!=''){
                if($time_from<'12:00' && $time_to<'13:00'){
                    array_push($time_type_ids_not,3);
                }elseif($time_from>'12:00' && $time_to>'12:00'){
                    array_push($time_type_ids_not,2);
                }
            }
            $time_type_ = DTRtimeType::whereIn('id',$time_type_ids)
                ->whereNotIn('id',$time_type_ids_not)->get();

            $data = array(
                'query' => $query,
                'users' => $users,
                'day' => $day,
                'date_name' => $date_name,
                'time_type_' => $time_type_,
                'time_from' => $time_from,
                'time_to' => $time_to,
                'emp_type' => $emp_type,
                'emp_stat_id' => $emp_stat_id
            );
            return view('hrims/dtr/dtrInputModal',$data);
        }else{

        }
    }
    public function dtrInputTable(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $id_no = $user->id_no;
        $id_no_req = $request->id_no;
        $year = $request->year;
        $month = $request->month;
        $day = $request->day;        
        if(($user_access_level==1 || $user_access_level==2) || ($id_no==$id_no_req)){
            $time_type = $request->time_type;
            $weekDay = date('w', strtotime($year.'-'.$month.'-'.$day));
            if($weekDay==0){
                $weekDay = 7;
            }
            $query = UsersDTR::where('id_no',$id_no_req)
                ->where('date',date('Y-m-d',strtotime($year.'-'.$month.'-'.$day)))
                ->first();
            $users = Users::where('id_no',$id_no_req)
                ->first();
            $user_id = $users->id;

            $data = array(
                'query' => $query,
                'time_type' => $time_type,
                'day' => $day
            );
            return view('hrims/dtr/dtrInputTable',$data);
        }
    }
    public function dtrInputDurationModal(Request $request){
        $id_no = $request->id_no;
        $year = $request->year;
        $month = $request->month;
        $query = Users::where('id_no',$id_no)
            ->first();
        $date_name = date('M Y',strtotime($year.'-'.$month.'-01'));
        $time_type_ids = array(1,4,5,6,7);
        $time_type_ = DTRtimeType::whereIn('id',$time_type_ids)->get();
        $data = array(
            'query' => $query,
            'year' => $year,
            'month' => $month,
            'date_name' => $date_name,
            'time_type_' => $time_type_
         );
        return view('hrims/dtr/dtrInputDurationModal',$data);
    }    
    public function dtrInputSubmit(Request $request){
        $result = 'error';
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $id_no = $user->id_no;
        $id_no_req = $request->id_no;
        $year = $request->year;
        $month = $request->month;
        $day = $request->day;
        $time_type = $request->time_type;
        if(($user_access_level==1 || $user_access_level==2) || ($id_no==$id_no_req)){
            $time_in_am = date('Y-m-d H:i:s',strtotime($year.'-'.$month.'-'.$day.' '.$request->time_in_am));
            $time_out_am = date('Y-m-d H:i:s',strtotime($year.'-'.$month.'-'.$day.' '.$request->time_out_am));
            $time_in_pm = date('Y-m-d H:i:s',strtotime($year.'-'.$month.'-'.$day.' '.$request->time_in_pm));
            $time_out_pm = date('Y-m-d H:i:s',strtotime($year.'-'.$month.'-'.$day.' '.$request->time_out_pm));
            $date = date('Y-m-d',strtotime($year.'-'.$month.'-'.$day));
            $query = UsersDTR::where('id_no',$id_no_req)
                ->where('date',$date)
                ->first();
            $check = 0;
            // if($query!=NULL){
            //     if($query->status!=NULL){
            //         $check++;
            //     }
            // }
            if($check==0){
                if($time_type==''){
                    if($query!=NULL){
                        $datas['updated_by'] = $updated_by;
                        if($query->time_in_am!=NULL && $query->time_in_am_type==NULL){
                        }else{
                            $datas['time_in_am'] = $time_in_am;
                            $datas['time_in_am_type'] = 1;
                        }
                        if($query->time_out_am!=NULL && $query->time_out_am_type==NULL){
                        }else{
                            $datas['time_out_am'] = $time_out_am;
                            $datas['time_out_am_type'] = 1;
                        }
                        if($query->time_in_pm!=NULL && $query->time_in_pm_type==NULL){
                        }else{
                            $datas['time_in_pm'] = $time_in_pm;
                            $datas['time_in_pm_type'] = 1;
                        }
                        if($query->time_out_pm!=NULL && $query->time_out_pm_type==NULL){
                        }else{
                            $datas['time_out_pm'] = $time_out_pm;
                            $datas['time_out_pm_type'] = 1;
                        }
                        UsersDTR::where('id', $query->id)
                            ->update($datas);
                        $result = 'success';
                    }else{
                        $insert = new UsersDTR();
                        $insert->id_no = $id_no_req;
                        $insert->date = $date;
                        $insert->time_in_am = $time_in_am;
                        $insert->time_in_am_type = 1;
                        $insert->time_out_am = $time_out_am;
                        $insert->time_out_am_type = 1;
                        $insert->time_in_pm = $time_in_pm;
                        $insert->time_in_pm_type = 1;
                        $insert->time_out_pm = $time_out_pm;
                        $insert->time_out_pm_type = 1;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                        $result = 'success';
                    }
                }elseif($time_type=='2'){
                    if($query!=NULL){
                        if($query->time_in_pm!=NULL && $query->time_in_pm_type==NULL){
                        }else{
                            $datas['time_in_pm'] = $time_in_pm;
                            $datas['time_in_pm_type'] = 1;
                        }
                        if($query->time_out_pm!=NULL && $query->time_out_pm_type==NULL){
                        }else{
                            $datas['time_out_pm'] = $time_out_pm;
                            $datas['time_out_pm_type'] = 1;
                        }
                        $datas['updated_by'] = $updated_by;
                        $datas['time_type'] = $time_type;
                        UsersDTR::where('id', $query->id)
                            ->update($datas);
                        $result = 'success';
                    }else{
                        $insert = new UsersDTR();
                        $insert->id_no = $id_no_req;
                        $insert->date = $date;
                        $insert->time_in_pm = $time_in_pm;
                        $insert->time_in_pm_type = 1;
                        $insert->time_out_pm = $time_out_pm;
                        $insert->time_out_pm_type = 1;
                        $insert->time_type = $time_type;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                        $result = 'success';
                    }
                }elseif($time_type=='3'){
                    if($query!=NULL){
                        if($query->time_in_am!=NULL && $query->time_in_am_type==NULL){
                        }else{
                            $datas['time_in_am'] = $time_in_am;
                            $datas['time_in_am_type'] = 1;
                        }
                        if($query->time_out_am!=NULL && $query->time_out_am_type==NULL){
                        }else{
                            $datas['time_out_am'] = $time_out_am;
                            $datas['time_out_am_type'] = 1;
                        }
                        $datas['updated_by'] = $updated_by;
                        $datas['time_type'] = $time_type;
                        UsersDTR::where('id', $query->id)
                            ->update($datas);
                        $result = 'success';
                    }else{
                        $insert = new UsersDTR();
                        $insert->id_no = $id_no_req;
                        $insert->date = $date;
                        $insert->time_in_am = $time_in_am;
                        $insert->time_in_am_type = 1;
                        $insert->time_out_am = $time_out_am;
                        $insert->time_out_am_type = 1;
                        $insert->time_type = $time_type;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                        $result = 'success';
                    }
                }elseif($time_type=='6' || $time_type=='7'){
                    if($query!=NULL){
                        if($query->time_in_am!=NULL && $query->time_in_am_type==NULL){
                        }else{
                            if($query->time_in_am!=NULL && $request->time_in_am!='Travel' && $request->time_in_am!='Vacant'){
                                $datas['time_in_am'] = $time_in_am;
                                $datas['time_in_am_type'] = 1;
                            }else{
                                $datas['time_in_am_type'] = $time_type;
                            }
                        }
                        if($query->time_out_am!=NULL && $query->time_out_am_type==NULL){
                        }else{
                            if($query->time_out_am!=NULL && $request->time_out_am!='Travel' && $request->time_out_am!='Vacant'){
                                $datas['time_out_am'] = $time_out_am;
                                $datas['time_out_am_type'] = 1;
                            }else{
                                $datas['time_out_am_type'] = $time_type;
                            }
                        }
                        if($query->time_in_pm!=NULL && $query->time_in_pm_type==NULL){
                        }else{
                            if($query->time_in_pm!=NULL && $request->time_in_pm!='Travel' && $request->time_in_pm!='Vacant'){
                                $datas['time_in_pm'] = $time_in_pm;
                                $datas['time_in_pm_type'] = 1;
                            }else{
                                $datas['time_in_pm_type'] = $time_type;
                            }
                        }
                        if($query->time_out_pm!=NULL && $query->time_out_pm_type==NULL){
                        }else{
                            if($query->time_out_pm!=NULL && $request->time_out_pm!='Travel' && $request->time_out_pm!='Vacant'){
                                $datas['time_out_pm'] = $time_out_pm;
                                $datas['time_out_pm_type'] = 1;
                            }else{
                                $datas['time_out_pm_type'] = $time_type;
                            }
                        }
                        $datas['updated_by'] = $updated_by;
                        $datas['time_type'] = $time_type;
                        UsersDTR::where('id', $query->id)
                            ->update($datas);
                        $result = 'success';
                    }else{
                        $insert = new UsersDTR();
                        $insert->id_no = $id_no_req;
                        $insert->date = $date;
                        if($request->time_in_am=='Travel' || $request->time_in_am=='Vacant'){
                            $insert->time_in_am = NULL;
                            $insert->time_in_am_type = $time_type;
                        }else{
                            $insert->time_in_am = date('Y-m-d H:i:s',strtotime($year.'-'.$month.'-'.$day.' '.$request->time_in_am));
                            $insert->time_in_am_type = 1;
                        }
                        if($request->time_out_am=='Travel' || $request->time_out_am=='Vacant'){
                            $insert->time_out_am = NULL;
                            $insert->time_out_am_type = $time_type;
                        }else{
                            $insert->time_out_am = date('Y-m-d H:i:s',strtotime($year.'-'.$month.'-'.$day.' '.$request->time_out_am));
                            $insert->time_out_am_type = 1;
                        }
                        if($request->time_in_pm=='Travel' || $request->time_in_pm=='Vacant'){
                            $insert->time_in_pm = NULL;
                            $insert->time_in_pm_type = $time_type;
                        }else{
                            $insert->time_in_pm = date('Y-m-d H:i:s',strtotime($year.'-'.$month.'-'.$day.' '.$request->time_in_pm));
                            $insert->time_in_pm_type = 1;
                        }
                        if($request->time_out_pm=='Travel' || $request->time_out_pm=='Vacant'){
                            $insert->time_out_pm = NULL;
                            $insert->time_out_pm_type = $time_type;
                        }else{
                            $insert->time_out_pm = date('Y-m-d H:i:s',strtotime($year.'-'.$month.'-'.$day.' '.$request->time_out_pm));
                            $insert->time_out_pm_type = 1;
                        }
                        $insert->time_type = $time_type;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                        $result = 'success';
                    }
                }elseif($time_type=='1' || $time_type=='4' || $time_type=='5'){
                    if($query==NULL){
                        $insert = new UsersDTR();
                        $insert->id_no = $id_no_req;
                        $insert->date = $date;
                        $insert->time_type = $time_type;
                        $insert->time_in_am_type = $time_type;
                        $insert->time_out_am_type = $time_type;
                        $insert->time_in_pm_type = $time_type;
                        $insert->time_out_pm_type = $time_type;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                        $result = 'success';
                    }
                }
            }
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
    public function dtrInputDurationSubmit(Request $request){
        $result = 'error';
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $id_no = $user->id_no;
        $id_no_req = $request->id_no;
        $year = $request->year;
        $month = $request->month;
        $day_from = $request->day_from;
        $day_to = $request->day_to;
        $time_type = $request->time_type;
        if(($user_access_level==1 || $user_access_level==2) || ($id_no==$id_no_req)){
            $time_type_ids = array(1,4,5,6,7);
            if(in_array($time_type,$time_type_ids)){
                for($i=$day_from;$i<=$day_to;$i++){
                    $date = date('Y-m-d',strtotime($year.'-'.$month.'-'.$i));
                    $day = date('d',strtotime($date));
                    $x = 0;
                    $query = UsersDTR::where('id_no',$id_no_req)
                        ->where('date',$date)
                        ->first();
                    $holiday = Holidays::where(function($query) use ($month,$day){
                            $query->whereMonth('date',$month)
                            ->whereDay('date',$day)
                            ->where('option','Yes');
                        })->orWhere('date',$date)
                        ->first();
                    if($query!=NULL){
                        $x++;
                    }
                    if($holiday!=NULL){
                        $x++;
                    }
                    $weekDay = date('w', strtotime($year.'-'.$month.'-'.$i));
                    if(($weekDay == 0 || $weekDay == 6)){
                        $x++;
                    }
                    if($x==0){
                        $insert = new UsersDTR();
                        $insert->id_no = $id_no_req;
                        $insert->date = $date;
                        $insert->time_in_am_type = $time_type;
                        $insert->time_out_am_type = $time_type;
                        $insert->time_in_pm_type = $time_type;
                        $insert->time_out_pm_type = $time_type;
                        $insert->time_type = $time_type;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                    }
                    $result = 'success';
                }
            }
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
    private function emp_type($user_id){
        $work = _Work::where('user_id',$user_id)
                ->where('date_to','present')
                ->orderBy('emp_stat_id','ASC')
                ->orderBy('date_from','DESC')                
                ->first();
        $emp_type = 'Employee';
        if($work!=NULL){
            $emp_stat_id = $work->emp_stat_id;
            if($work->role_id==3){
                if($work->credit_type_id==2 || $work->credit_type_id==NULL){
                    $emp_type = 'Employee';
                }else{
                    $emp_type = 'Faculty';
                }
            }
        }
        
        return array(
            'emp_type' => $emp_type,
            'emp_stat_id' => $emp_stat_id
        );
    }
    private function time_from_to($user_id,$year,$month,$weekDay){
            $emp_type1 = $this->emp_type($user_id);
            $emp_type = $emp_type1['emp_type'];
            //$emp_type = 'Faculty';
            $time_from = '';
            $time_to = '';
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
                if($time_from_query!=NULL){
                    $time_from = date('H:i',strtotime($time_from_query->time_from));
                }
                if($time_to_query!=NULL){
                    $time_to =date('H:i',strtotime( $time_to_query->time_to));
                }
            }
        return array(
                'time_from' => $time_from,
                'time_to' => $time_to
            );
    }
}