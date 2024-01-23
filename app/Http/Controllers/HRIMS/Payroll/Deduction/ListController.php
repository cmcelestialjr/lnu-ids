<?php

namespace App\Http\Controllers\HRIMS\Payroll\Deduction;
use App\Http\Controllers\Controller;
use App\Models\EmploymentStatus;
use App\Models\FundCluster;
use App\Models\FundFinancing;
use App\Models\FundSource;
use App\Models\HRadgEmpStat;
use App\Models\HRadgPayrollType;
use App\Models\HRDeduction;
use App\Models\HRDeductionEmployee;
use App\Models\HRDeductionGroup;
use App\Models\HRPayrollType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ListController extends Controller
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
        $query = HRDeduction::orderBy('group_id')->get()
            ->map(function($query) {
                $group = '';
                if($query->group_id!=NULL){
                    $group = $query->group->name;
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
                    'group' => $group,
                    'emp_stat' => $emp_stat,
                    'payroll_type' => $payroll_type
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['group'];
                $data_list['f5'] = $r['emp_stat'];
                $data_list['f6'] = $r['payroll_type'];
                $data_list['f4'] = '<button class="btn btn-primary btn-primary-scan btn-sm update"
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
        $group = HRDeductionGroup::get();
        $emp_stat = EmploymentStatus::get();
        $payroll_type = HRPayrollType::get();
        $data = array(
            'group' => $group,
            'emp_stat' => $emp_stat,
            'payroll_type' => $payroll_type
        );
        return view('hrims/payroll/deduction/list/newModal',$data);
    }
    private function _newSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $name = mb_strtoupper($request->name);
            $group = $request->group;
            $payroll_type = $request->payroll_type;
            $emp_stat = $request->emp_stat;
            if($group=='None'){
                $group = NULL;
            }
            $check = HRDeduction::where('name',$name)
                ->where('group_id',$group)->first();
            if($check==NULL){                
                $user = Auth::user();
                $updated_by = $user->id;
                $insert = new HRDeduction(); 
                $insert->name = $name;
                $insert->group_id = $group;
                $insert->updated_by = $updated_by;
                $insert->save();
                $deduction_id = $insert->id;
                if($group!=NULL){
                    $emp_stat = HRadgEmpStat::where('group_id',$group)->pluck('emp_stat_id')->toArray();
                    $payroll_type = HRadgEmpStat::where('group_id',$group)->pluck('payroll_type_id')->toArray();
                }
                foreach($emp_stat as $emp_stat){
                    $insert = new HRadgEmpStat(); 
                    $insert->deduction_id = $deduction_id;
                    $insert->emp_stat_id = $emp_stat;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                }
                foreach($payroll_type as $payroll_type){
                    $insert = new HRadgPayrollType(); 
                    $insert->deduction_id = $deduction_id;
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
    private function _updateModal($request){
        $id = $request->id;
        $query = HRDeduction::where('id',$id)->first();
        $group = HRDeductionGroup::get();
        $emp_stat = EmploymentStatus::get();
        $payroll_type = HRPayrollType::get();
        $data = array(
            'query' => $query,
            'group' => $group,
            'emp_stat' => $emp_stat,
            'payroll_type' => $payroll_type
        );
        return view('hrims/payroll/deduction/list/updateModal',$data);
    }
    private function _updateSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $name = $request->name;
            $group = $request->group;
            $percent = $request->percent;
            $percent_employer = $request->percent_employer;
            $ceiling = $request->ceiling;
            $emp_stat = $request->emp_stat;
            $payroll_type = $request->payroll_type;
            if($group=='None'){
                $group = NULL;
            }
            $check = HRDeduction::where('id','!=',$id)
                ->where(function ($query) use ($name,$group) {
                    $query->where('name',$name)
                    ->where('group_id',$group);
                })->first();
            if($check==NULL){
                $user = Auth::user();
                $updated_by = $user->id;
                HRDeduction::where('id', $id)
                ->update([
                    'name' => $name,
                    'group_id' => $group,
                    'percent' => $percent,
                    'percent_employer' => $percent_employer,
                    'ceiling' => $ceiling,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                HRDeductionEmployee::where('deduction_id', $id)
                ->update([
                    'percent' => $percent,
                    'percent_employer' => $percent_employer,
                    'ceiling' => $ceiling,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                if($group!=NULL){
                    $emp_stat = HRadgEmpStat::where('group_id',$group)->pluck('emp_stat_id')->toArray();
                    $payroll_type = HRadgPayrollType::where('group_id',$group)->pluck('payroll_type_id')->toArray();
                }
                $delete = HRadgEmpStat::whereNotIn('emp_stat_id', $emp_stat)
                                ->where('deduction_id', $id)->delete();
                $auto_increment = DB::update("ALTER TABLE `hr_adg_emp_stat` AUTO_INCREMENT = 0;");
                foreach($emp_stat as $emp_stat){
                    $check = HRadgEmpStat::where('emp_stat_id',$emp_stat)
                        ->where('deduction_id',$id)->first();
                    if($check==NULL){
                        $insert = new HRadgEmpStat(); 
                        $insert->deduction_id = $id;
                        $insert->group_id = NULL;
                        $insert->emp_stat_id = $emp_stat;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                    }
                }
                $delete = HRadgPayrollType::whereNotIn('payroll_type_id', $payroll_type)
                                ->where('deduction_id', $id)->delete();
                $auto_increment = DB::update("ALTER TABLE `hr_adg_payroll_type` AUTO_INCREMENT = 0;");
                foreach($payroll_type as $payroll_type){
                    $check = HRadgPayrollType::where('payroll_type_id',$payroll_type)
                        ->where('deduction_id',$id)->first();
                    if($check==NULL){
                        $insert = new HRadgPayrollType();
                        $insert->deduction_id = $id;
                        $insert->group_id = NULL;
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