<?php

namespace App\Http\Controllers\HRIMS\Employee\Information;
use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Models\UsersSchedDays;
use App\Models\UsersSchedTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

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
        if($request->from_sys=='hr'){
            $id = $request->id;
            $request->session()->put('from_sys','hr');
        }else{
            $user = Auth::user();
            $id = $user->id;
            $request->session()->put('from_sys','fis');
        }
        $active = $request->active;
        $query = Users::where('id',$id)->first();
        $time = UsersSchedTime::where('user_id',$id)->get();
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
            'user_access_level' => $user_access_level
        );
        return view('hrims/employee/information/schedule',$data);
    }    
    private function _schedNewModal($request){
        $id = $this->_getID($request);
        $data = array(
            'id' => $id
        );
        return view('hrims/employee/information/schedNewModal',$data);
    }    
    private function _schedNewDaysList($request){
        $id = $this->_getID($request);
        $time_other = UsersSchedTime::where('user_id',$id)->get();
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
        $time_from = date('H:i:s',strtotime($request->time_from));
        $time_to = date('H:i:s',strtotime($request->time_to));
        $remarks = $request->remarks;
        $days = $request->days;
        if($days!=''){
            $insert = new UsersSchedTime(); 
            $insert->user_id = $id;
            $insert->time_from = $time_from;
            $insert->time_to = $time_to;
            $insert->remarks = $remarks;
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
            $data = array(
                'query' => $time,
                'time_other' => $time_other
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
            $time_other = UsersSchedTime::where('user_id',$time->user_id)
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
            $time_from = date('H:i:s',strtotime($request->time_from));
            $time_to = date('H:i:s',strtotime($request->time_to));
            $remarks = $request->remarks;
            $days = $request->days;
            
            UsersSchedTime::where('id',$id)
                    ->update(['time_from' => $time_from,
                            'time_to' => $time_to,
                            'remarks' => $remarks,
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
        if($from_sys=='hr'){
            $id = $request->id;
        }else{
            $user = Auth::user();
            $id = $user->id;
        }
        return $id;
    }
    private function _getX($request){
        $user_access_level = $request->session()->get('user_access_level');
        $from_sys = $request->session()->get('from_sys'); 
        $user = Auth::user();
        $id = $request->id;
        $time = UsersSchedTime::where('id',$id)->first();
        $x = 0;
        if($from_sys=='hr'){
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