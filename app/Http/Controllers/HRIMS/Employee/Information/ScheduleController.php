<?php

namespace App\Http\Controllers\HRIMS\Employee\Information;
use App\Http\Controllers\Controller;
use App\Models\_Work;
use App\Models\Users;
use App\Models\UsersSchedDays;
use App\Models\UsersSchedTime;
use App\Models\UsersSchedTimeOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function schedule(Request $request){
        return $this->_schedule($request);
    }
    public function schedNewModal(Request $request){
        return $this->_schedNewModal($request);
    }
    public function schedNewDaysList(Request $request){
        return $this->_schedNewDaysList($request);
    }
    public function schedNewSubmit(Request $request){
        return $this->_schedNewSubmit($request);
    }
    public function schedEditModal(Request $request){
        return $this->_schedEditModal($request);
    }
    public function schedEditDaysList(Request $request){
        return $this->_schedEditDaysList($request);
    }
    public function schedEditSubmit(Request $request){
        return $this->_schedEditSubmit($request);
    }
    public function schedDeleteModal(Request $request){
        return $this->_schedDeleteModal($request);
    }
    public function schedDeleteSubmit(Request $request){
        return $this->_schedDeleteSubmit($request);
    }

    private function _schedule($request){
        $user_access_level = $request->session()->get('user_access_level');
        $year = date('Y');
        $month = date('m');
        if($request->from_sys=='hr'){
            $id = $request->id;
            $request->session()->put('from_sys','hr');
        }elseif($request->from_sys=='dtr'){
            $id = $request->id;
            $request->session()->put('from_sys','dtr');
        }else{
            $user = Auth::user();
            $id = $user->id;
            $request->session()->put('from_sys','fis');
        }
        if($request->year!=''){
            $year = $request->year;
        }
        if($request->month!=''){
            $month = $request->month;
        }
        $active = $request->active;
        $date = date('Y-m-01',strtotime($year.'-'.$month.'-01'));
        $query = Users::where('id',$id)->first();
        $time = UsersSchedTime::with('option')
            ->where(function ($query) use ($year,$month,$id) {
                $query->whereMonth('date_to','>=',$month)
                ->whereYear('date_to','>=',$year)
                ->whereMonth('date_from','<=',$month)
                ->whereYear('date_from','<=',$year)
                ->where('user_id',$id);
            })
            // ->orWhere(function ($query) use ($id) {
            //     $query->where('date_to',NULL)
            //     ->where('date_from',NULL)
            //     ->where('user_id',$id) ;
            // })
            ->orderBy('time_from','ASC')->get();
        $active_view = 'show active';
        $active_table = '';
        if($active=='table_active'){
            $active_view = '';
            $active_table = 'show active';
        }
        $data = array(
            'query' => $query,
            'time' => $time,
            'active_view' => $active_view,
            'active_table' => $active_table,
            'year' => $year,
            'month' => $month,
            'from_sys' => $request->from_sys,
            'user_access_level' => $user_access_level,
            'date' => $date
        );
        return view('hrims/employee/information/schedule',$data);
    }
    private function _schedNewModal($request){
        $id = $this->_getID($request);
        $sched_option = $this->sched_option($id);
        $data = array(
            'id' => $id,
            'sched_option' => $sched_option
        );
        return view('hrims/employee/information/schedNewModal',$data);
    }

    private function _schedNewDaysList($request){
        $id = $this->_getID($request);
        if($request->duration=='none'){
            $date_from = date('Y-m-01');
            $date_to = date('Y-m-t');
        }else{
            $exp = explode('-',$request->duration);
            $date_from = date('Y-m-01',strtotime($exp[0]));
            $date_to = date('Y-m-t',strtotime($exp[1]));
        }
        $time_other = UsersSchedTime::where('user_id',$id)
            ->where('date_to','>=',$date_to)
            ->where('date_from','<=',$date_from)
            ->get();
        if($request->time_from=='none' && $request->time_to=='none'){
            $time_from = '';
            $time_to = '';
        }else{
            $time_from = date('H:i:s',strtotime($request->time_from));
            $time_to = date('H:i:s',strtotime($request->time_to));
        }
        $data = array(
            'time_from' => $time_from,
            'time_to' => $time_to,
            'time_other' => $time_other
        );
        return view('hrims/employee/information/schedNewDaysList',$data);
    }
    private function _schedNewSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $result = 'error';
        $id = $this->_getID($request);
        $option = $request->option;
        $is_rotation_duty = $request->is_rotation_duty;
        $time_from = date('H:i:s',strtotime($request->time_from));
        $time_to = date('H:i:s',strtotime($request->time_to));
        $exp = explode('-',$request->duration);
        $date_from = date('Y-m-01',strtotime($exp[0]));
        $date_to = date('Y-m-t',strtotime($exp[1]));
        $remarks = $request->remarks;
        $days = $request->days;
        if($days!=''){
            $insert = new UsersSchedTime();
            $insert->user_id = $id;
            $insert->option_id = $option;
            $insert->date_from = $date_from;
            $insert->date_to = $date_to;
            $insert->time_from = $time_from;
            $insert->time_to = $time_to;
            $insert->remarks = $remarks;
            $insert->is_rotation_duty = $is_rotation_duty;
            $insert->updated_by = $updated_by;
            $insert->save();
            $user_time_id = $insert->id;
            foreach($days as $day){
                $insert = new UsersSchedDays();
                $insert->user_time_id = $user_time_id;
                $insert->user_id = $id;
                $insert->day = $day;
                $insert->updated_by = $updated_by;
                $insert->save();
            }
            $result = 'success';
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
    private function _schedEditModal($request){
        $user = Auth::user();
        $id = $request->id;
        $time = UsersSchedTime::where('id',$id)->first();
        $x = $this->_getX($request);
        if($x==0){
            $time_other = UsersSchedTime::where('user_id',$time->user_id)
                ->where('id','<>',$id)->get();
            $sched_option = $this->sched_option($id);
            $data = array(
                'query' => $time,
                'time_other' => $time_other,
                'sched_option' => $sched_option
            );
            return view('hrims/employee/information/schedEditModal',$data);
        }else{
            return view('layouts/error/404');
        }
    }
    private function _schedEditDaysList($request){
        $user = Auth::user();
        $id = $request->id;
        $time = UsersSchedTime::where('id',$id)->first();
        $x = $this->_getX($request);
        if($x==0){
            if($request->duration=='none'){
                $date_from = date('Y-m-01');
                $date_to = date('Y-m-t');
            }else{
                $exp = explode('-',$request->duration);
                $date_from = date('Y-m-01',strtotime($exp[0]));
                $date_to = date('Y-m-t',strtotime($exp[1]));
            }
            $time_other = UsersSchedTime::where('user_id',$time->user_id)
                ->where('date_to','>=',$date_to)
                ->where('date_from','<=',$date_from)
                ->where('id','<>',$id)->get();
            if($request->time_from=='none' && $request->time_to=='none'){
                $time_from = $time->time_from;
                $time_to = $time->time_to;
            }else{
                $time_from = date('H:i:s',strtotime($request->time_from));
                $time_to = date('H:i:s',strtotime($request->time_to));
            }
            $data = array(
                'query' => $time,
                'time_from' => $time_from,
                'time_to' => $time_to,
                'time_other' => $time_other
            );
            return view('hrims/employee/information/schedEditDaysList',$data);
        }else{
            return view('layouts/error/404');
        }
    }
    private function _schedEditSubmit($request){
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $time = UsersSchedTime::where('id',$id)->first();
        $x = $this->_getX($request);
        $result = 'error';
        if($x==0){
            $option = $request->option;
            $is_rotation_duty = $request->is_rotation_duty;
            $exp = explode('-',$request->duration);
            $date_from = date('Y-m-01',strtotime($exp[0]));
            $date_to = date('Y-m-t',strtotime($exp[1]));
            $time_from = date('H:i:s',strtotime($request->time_from));
            $time_to = date('H:i:s',strtotime($request->time_to));
            $remarks = $request->remarks;
            $days = $request->days;

            UsersSchedTime::where('id',$id)
                    ->update(['time_from' => $time_from,
                            'time_to' => $time_to,
                            'date_from' => $date_from,
                            'date_to' => $date_to,
                            'remarks' => $remarks,
                            'option_id' => $option,
                            'is_rotation_duty' => $is_rotation_duty,
                            'updated_by' => $updated_by,
                            'updated_at' => date('Y-m-d H:i:s')]);
            $delete = UsersSchedDays::whereHas('time', function ($query) use ($id) {
                    $query->where('id', $id);
                })->delete();
            $auto_increment = DB::update("ALTER TABLE users_sched_days AUTO_INCREMENT = 0;");
            foreach($days as $day){
                $insert = new UsersSchedDays();
                $insert->user_time_id = $id;
                $insert->user_id = $time->user_id;
                $insert->day = $day;
                $insert->updated_by = $updated_by;
                $insert->save();
            }
            $result = 'success';
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
    private function _schedDeleteModal($request){
        $id = $request->id;
        $query = UsersSchedTime::where('id',$id)->first();
        $x = $this->_getX($request);
        if($x==0){
            $data = array(
                'query' => $query
            );
            return view('hrims/employee/information/schedDeleteModal',$data);
        }else{
            return view('layouts/error/404');
        }
    }
    private function _schedDeleteSubmit($request){
        $result = 'error';
        $x = $this->_getX($request);
        if($x==0){
            $id = $request->id;
            $delete = UsersSchedDays::whereHas('time', function ($query) use ($id) {
                    $query->where('id', $id);
                })->delete();
            $auto_increment = DB::update("ALTER TABLE users_sched_days AUTO_INCREMENT = 0;");
            $delete = UsersSchedTime::where('id', $id)->delete();
            $auto_increment = DB::update("ALTER TABLE users_sched_time AUTO_INCREMENT = 0;");
            $result = 'success';
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
    private function _getID($request){
        $from_sys = $request->session()->get('from_sys');
        if($from_sys=='hr' || $from_sys=='dtr'){
            $id = $request->id;
        }else{
            $user = Auth::user();
            $id = $user->id;
        }
        return $id;
    }
    private function sched_option($id){
        $work = _Work::where('user_id',$id)
            ->where('date_to','present')
            ->pluck('emp_stat_id')
            ->toArray();
        $sched_option = UsersSchedTimeOption::orderBy('id');
        if(count($work)==1){
            if (in_array(5, $work)){
                $sched_option = $sched_option->where('id',1);
            }
        }
        return $sched_option->get();
    }
    private function _getX($request){
        $user_access_level = $request->session()->get('user_access_level');
        $from_sys = $request->session()->get('from_sys');
        $user = Auth::user();
        $id = $request->id;
        $time = UsersSchedTime::where('id',$id)->first();
        $x = 0;
        if($from_sys=='hr' || $from_sys=='dtr'){
            if($user_access_level!=1 && $user_access_level!=2 && $user_access_level!=3){
                $x++;
            }
        }else{
            if($time->user_id!=$user->id){
                $x++;
            }
        }
        return $x;
    }
}
