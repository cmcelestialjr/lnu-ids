<?php

namespace App\Http\Controllers\HRIMS\Employee;
use App\Http\Controllers\Controller;
use App\Models\_Work;
use App\Models\Status;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    public function status(Request $request){
        return $this->_status($request);
    }
    public function submit(Request $request){
        return $this->_submit($request);
    }
    private function _status($request){
        $user_access_level = $request->session()->get('user_access_level');
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $query = Users::where('id',$id)->first();
            $status = Status::where('id','<',3)->get();
            $cause = '';
            $separation_date = '';
            if(isset($query->employee_default)){
                $cause = $query->employee_default->cause;
                $separation_date = $query->employee_default->date_separation;
            }
            $data = array(
                'query' => $query,
                'status' => $status,
                'cause' => $cause,
                'separation_date' => $separation_date
            );
            return view('hrims/employee/statusModal',$data);
        }else{
            return view('layouts/error/404');
        }
    }
    private function _submit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        $class = '';
        $html = '';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $query = Users::where('id',$id)->first();
            if(isset($query->employee_default)){
                $status = $request->status;
                $cause = $request->cause;
                $separation_date = $request->separation_date;
                $work_id = $query->employee_default->id;
                $user = Auth::user();
                $updated_by = $user->id;
                Users::where('id',$id)
                ->update(['emp_status_id' => $status,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s')]);
                if($status=='1'){
                    $separation_date = NULL;
                    $cause = NULL;
                }else{
                    $separation_date = date('Y-m-d',strtotime($separation_date));
                }
                _Work::where('id',$work_id)
                ->update(['cause' => $cause,
                        'separation' => $separation_date,
                        'date_separation' => $separation_date,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s')]);
                $class = 'btn btn-success btn-success-scan';
                $html = 'Active';
                if($status==2){
                    $class = 'btn btn-danger btn-danger-scan';
                    $html = 'Inactive<br>'.date('M d, Y',strtotime($separation_date));
                }
                $result = 'success';
            }
        }
        $data = array('result' => $result,
                        'class' => $class,
                        'html' => $html);
        return response()->json($data);
    }
}