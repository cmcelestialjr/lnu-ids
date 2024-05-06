<?php

namespace App\Http\Controllers\HRIMS\Employee\Deduction;

use App\Http\Controllers\Controller;
use App\Models\EmploymentStatus;
use App\Models\HRDeduction;
use App\Models\HRDeductionEmployee;
use App\Models\HRPayrollType;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeductionController extends Controller
{
    public function deductionModal(Request $request){
        return $this->_deductionModal($request); // Call private _deductionModal function
    }
    public function deductionTable(Request $request){
        return $this->_deductionTable($request); // Call private _deductionTable function
    }
    public function deductionSubmit(Request $request){
        return $this->_deductionSubmit($request); // Call private _deductionSubmit function
    }
    public function update(Request $request){
        return $this->_update($request); // Call private _update function
    }
    private function _deductionModal($request){
        $id = $request->id;
        $deduction = HRDeduction::get(); // Retrieve HR deductions
        $query = Users::find($id); // Retrieve specific user query based on ID
        $payroll_type = HRPayrollType::get(); // Retrieve HR payroll types
        $emp_stat = EmploymentStatus::
            whereHas('work', function ($query) use ($id) {
                $query->where('user_id',$id);
            })->get(); // Retrieve HR Employent Status base on employee
        $data = array(
            'query' => $query,
            'deduction' => $deduction,
            'payroll_type' => $payroll_type,
            'emp_stat' => $emp_stat
        );
        return view('hrims/employee/deduction/deductionModal',$data); // Return view with data
    }
    private function _deductionTable($request){
        $data = array(); // Initialize empty array
        $id = $request->id; // Get ID from request
        $payroll_type = $request->payroll_type; // Get payroll type from request
        $get_employee_stat = Users::where('id',$id)->first(); // Get employee statistics based on ID
        if($request->emp_stat=='None'){
            if($get_employee_stat->employee_default){ // Check if employee_default exists
                $emp_stat = $get_employee_stat->employee_default->emp_stat_id; // Assign emp_stat_id to emp_stat
            }
        }else{
            $emp_stat = $request->emp_stat;
        }

        $query = HRDeduction::orderBy('group_id')
                // ->whereHas('payroll_type', function ($query) use ($payroll_type) {
                //     $query->where('payroll_type_id',$payroll_type);
                // })
                ->whereHas('emp_stat', function ($query) use ($emp_stat) {
                    $query->where('emp_stat_id',$emp_stat);
                })
                ->get()
                ->map(function($query) use ($id,$payroll_type,$emp_stat){
                    $employee_deduction = HRDeductionEmployee::where('user_id',$id)
                        ->where('deduction_id',$query->id)
                        ->where('payroll_type_id',$payroll_type)
                        ->where('emp_stat_id',$emp_stat)
                        ->first();
                    $group = $query->group_id ? $query->group->name : '';
                    $amount = NULL;
                    $date_from = NULL;
                    $date_to = NULL;
                    $docs = NULL;
                    $remarks = NULL;
                    if($employee_deduction){
                        $amount = $employee_deduction->amount;
                        $date_from = $employee_deduction->date_from;
                        $date_to = $employee_deduction->date_to;
                        $remarks = $employee_deduction->remarks;
                        $docs = $employee_deduction->docs;
                    }
                    if($date_from){
                        $date_from = date('m/d/Y',strtotime($date_from));
                    }
                    if($date_to){
                        $date_to = date('m/d/Y',strtotime($date_to));
                    }

                    return [
                        'id' => $query->id,
                        'name' => $query->name,
                        'group' => $group,
                        'amount' => $amount,
                        'date_from' => $date_from,
                        'date_to' => $date_to,
                        'docs' => $docs,
                        'remarks' => $remarks,
                    ];
                })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $docs = '<button class="btn btn-danger btn-danger-scan btn-xs docs"
                    data-id="'.$r['id'].'"><span class="fa fa-file"></span></button>';
                if($r['docs']){
                    if(count($r['docs'])>0){
                        $docs = '<button class="btn btn-success btn-success-scan btn-xs docs"
                        data-id="'.$r['id'].'"><span class="fa fa-file"></span></button>';
                    }
                }
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['group'];
                $data_list['f4'] = '<input type="number" class="form-control input"
                    id="amount'.$r['id'].'"
                    data-id="'.$r['id'].'"
                    data-val="amount"
                    value="'.$r['amount'].'">';
                $data_list['f5'] = '<input type="text" class="form-control datePicker input"
                    id="date_from'.$r['id'].'"
                    data-id="'.$r['id'].'"
                    data-val="date_from"
                    value="'.$r['date_from'].'">';
                $data_list['f6'] = '<input type="text" class="form-control datePicker input"
                    id="date_to'.$r['id'].'"
                    data-id="'.$r['id'].'"
                    data-val="date_to"
                    value="'.$r['date_to'].'">';
                $data_list['f7'] = $docs;
                $data_list['f8'] = '<input type="text" class="form-control input"
                    id="remarks'.$r['id'].'"
                    data-id="'.$r['id'].'"
                    data-val="remarks"
                    value="'.$r['remarks'].'">';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data); // Return JSON response
    }
    private function _deductionSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';

        // Check user access level
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $name = $request->name;
            $group = $request->group;
            $percent = $request->percent;
            $percent_employer = $request->percent_employer;
            $ceiling = $request->ceiling;

            // Handle group value
            if($group=='None'){
                $group = NULL;
            }

            // Check if the deduction already exists
            $check = HRDeduction::where('id','!=',$id)
                ->where(function ($query) use ($name,$group) {
                    $query->where('name',$name)
                        ->where('group_id',$group);
                })->first();

            // If deduction doesn't exist, update it
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

                $result = 'success';
            }
        }

        $response = array('result' => $result);
        return response()->json($response);
    }

    private function _update($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        $check_amount = NULL;

        // Check user access level
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $did = $request->did;
            $val = $request->val;
            $value = $request->value;
            $payroll_type = $request->payroll_type;
            $emp_stat = $request->emp_stat;
            // Handle date values
            if($val=='date_from' || $val=='date_to'){
                $value = empty($value) ? NULL : date('Y-m-d', strtotime($value));
            }

            $user = Auth::user();
            $updated_by = $user->id;

            // Update or create the deduction employee record
            HRDeductionEmployee::updateOrCreate(
                [
                    'user_id' => $id,
                    'payroll_type_id' => $payroll_type,
                    'emp_stat_id' => $emp_stat,
                    'deduction_id' => $did,
                ],
                [
                    $val => $value,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            );

            // Check the deduction amount
            $check_deduction = HRDeductionEmployee::where('user_id', $id)
                ->where('payroll_type_id', $payroll_type)
                ->where('emp_stat_id', $emp_stat)
                ->where('deduction_id', $did)->first();

            if($check_deduction){
                if($check_deduction->amount > 0){
                    $check_amount = $check_deduction->amount;
                }
            }

            // If the deduction amount is null, delete the record
            if($check_amount == NULL){
                $delete = HRDeductionEmployee::where('user_id', $id)
                    ->where('payroll_type_id', $payroll_type)
                    ->where('emp_stat_id', $emp_stat)
                    ->where('deduction_id', $did)->delete();

                $auto_increment = DB::update("ALTER TABLE `hr_deduction_employee` AUTO_INCREMENT = 1;");
            }

            $result = 'success';
        }

        $response = array('result' => $result, 'check_amount' => $check_amount);
        return response()->json($response);
    }
}
