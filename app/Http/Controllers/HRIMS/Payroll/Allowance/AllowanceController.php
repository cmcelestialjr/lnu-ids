<?php

namespace App\Http\Controllers\HRIMS\Payroll\Allowance;
use App\Http\Controllers\Controller;
use App\Models\EmploymentStatus;
use App\Models\HRadgEmpStat;
use App\Models\HRadgPayrollType;
use App\Models\HRAllowance;
use App\Models\HRPayrollType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AllowanceController extends Controller
{
    public function table(Request $request){
        return $this->_table($request);
    }
    public function newModal(Request $request){
        return $this->_newModal($request);
    }
    public function newSubmit(Request $request){
        return $this->_newSubmit($request);
    }
    public function updateModal(Request $request){
        return $this->_updateModal($request);
    }
    public function updateSubmit(Request $request){
        return $this->_updateSubmit($request);
    }
    private function _table($request){
        $data = array();
        $query = HRAllowance::get()
            ->map(function($query) {
                $amount = '';
                if($query->amount!=NULL){
                    $amount = number_format($query->amount,2);
                }
                $emp_stat = '';
                $payroll_type = '';
                if(isset($query->emp_stat)){       
                    $emp_stat_array = array();             
                    foreach($query->emp_stat as $row){
                        $emp_stat_array[] = $row->emp_stat->name;
                    }
                    $emp_stat = implode(',',$emp_stat_array);
                }
                if(isset($query->payroll_type)){
                    $payroll_type_array = array();
                    foreach($query->payroll_type as $row){
                        $payroll_type_array[] = $row->payroll_type->name;
                    }
                    $payroll_type = implode(',',$payroll_type_array);
                }
                return [
                    'id' => $query->id,
                    'name' => $query->name,
                    'amount' => $amount,
                    'monthly' => $query->monthly,
                    'emp_stat' => $emp_stat,
                    'payroll_type' => $payroll_type
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['amount'];
                $data_list['f4'] = $r['monthly'];
                $data_list['f5'] = $r['emp_stat'];
                $data_list['f6'] = $r['payroll_type'];
                $data_list['f7'] = '<button class="btn btn-primary btn-primary-scan btn-sm update"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-edit"></span> 
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    private function _newModal($request){
        $emp_stat = EmploymentStatus::get();
        $payroll_type = HRPayrollType::get();
        $data = array(
            'emp_stat' => $emp_stat,
            'payroll_type' => $payroll_type
        );
        return view('hrims/payroll/allowance/newModal',$data);
    }
    private function _updateModal($request){
        $query = HRAllowance::where('id',$request->id)->first();
        $emp_stat = EmploymentStatus::get();
        $payroll_type = HRPayrollType::get();
        $data = array(
            'query' => $query,
            'emp_stat' => $emp_stat,
            'payroll_type' => $payroll_type
        );
        return view('hrims/payroll/allowance/updateModal',$data);
    }
    private function _newSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $name = mb_strtoupper($request->name);
            $amount = $request->amount;
            $monthly = $request->monthly;
            $check = HRAllowance::where(function ($query) use ($name) {
                    $query->where('name',$name);
                })->first();
            if($check==NULL){
                $user = Auth::user();
                $updated_by = $user->id;
                $insert = new HRAllowance(); 
                $insert->name = $name;
                $insert->amount = $amount;
                $insert->monthly = $monthly;
                $insert->updated_by = $updated_by;
                $insert->save();
                $allowance_id = $insert->id;
                foreach($request->emp_stat as $emp_stat){
                    $insert = new HRadgEmpStat(); 
                    $insert->allowance_id = $allowance_id;
                    $insert->emp_stat_id = $emp_stat;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                }
                foreach($request->payroll_type as $payroll_type){
                    $insert = new HRadgPayrollType(); 
                    $insert->allowance_id = $allowance_id;
                    $insert->payroll_type_id = $payroll_type;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                }
                $result = 'success';
            }
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }    
    private function _updateSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $allowance_id = $request->id;
            $name = mb_strtoupper($request->name);
            $amount = $request->amount;
            $monthly = $request->monthly;
            $check = HRAllowance::where('id','!=',$allowance_id)
                ->where(function ($query) use ($name) {
                    $query->where('name',$name);
                })->first();
            if($check==NULL){
                $user = Auth::user();
                $updated_by = $user->id;
                HRAllowance::where('id', $allowance_id)
                ->update([
                    'name' => $name,
                    'amount' => $amount,
                    'monthly' => $monthly,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $delete = HRadgEmpStat::whereNotIn('emp_stat_id', $request->emp_stat)
                                ->where('allowance_id', $allowance_id)->delete();
                $auto_increment = DB::update("ALTER TABLE hr_adg_emp_stat AUTO_INCREMENT = 0;");
                foreach($request->emp_stat as $emp_stat){
                    $check = HRadgEmpStat::where('emp_stat_id',$emp_stat)
                        ->where('allowance_id',$allowance_id)->first();
                    if($check==NULL){
                        $insert = new HRadgEmpStat(); 
                        $insert->allowance_id = $allowance_id;
                        $insert->emp_stat_id = $emp_stat;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                    }
                }
                $delete = HRadgPayrollType::whereNotIn('payroll_type_id', $request->payroll_type)
                                ->where('allowance_id', $allowance_id)->delete();
                $auto_increment = DB::update("ALTER TABLE hr_adg_payroll_type AUTO_INCREMENT = 0;");
                foreach($request->payroll_type as $payroll_type){
                    $check = HRadgPayrollType::where('payroll_type_id',$payroll_type)
                        ->where('allowance_id',$allowance_id)->first();
                    if($check==NULL){
                        $insert = new HRadgPayrollType();
                        $insert->allowance_id = $allowance_id;
                        $insert->payroll_type_id = $payroll_type;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                    }
                }
                $result = 'success';
            }
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
}