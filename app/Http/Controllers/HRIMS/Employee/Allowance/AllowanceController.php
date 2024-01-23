<?php

namespace App\Http\Controllers\HRIMS\Employee\Allowance;
use App\Http\Controllers\Controller;
use App\Models\FundCluster;
use App\Models\FundFinancing;
use App\Models\FundSource;
use App\Models\HRAllowance;
use App\Models\HRDeduction;
use App\Models\HRDeductionEmployee;
use App\Models\HRDeductionGroup;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AllowanceController extends Controller
{
    public function table(Request $request){
        return $this->_table($request);
    }
    private function _table($request){
        $data = array();
        $id = $request->id;
        $payroll_type = $request->payroll_type;
        $emp_stat = $request->emp_stat;
        // $get_employee_stat = Users::where('id',$id)->first();
        // if($get_employee_stat->employee_default){
        //     $emp_stat = $get_employee_stat->employee_default->emp_stat_id;
        // }
        if($emp_stat){
            $query = HRAllowance::
                whereHas('payroll_type', function ($query) use ($payroll_type) {
                    $query->where('payroll_type_id',$payroll_type);
                })
                ->whereHas('emp_stat', function ($query) use ($emp_stat) {
                    $query->where('emp_stat_id',$emp_stat);
                })
                ->get()
                ->map(function($query) use ($id){
                    
                    return [
                        'id' => $query->id,
                        'name' => $query->name,
                        'amount' => $query->amount
                    ];
                })->toArray();
            if(count($query)>0){
                $x = 1;
                foreach($query as $r){
                    $data_list['f1'] = $x;
                    $data_list['f2'] = $r['name'];
                    $data_list['f3'] = $r['amount'];
                    array_push($data,$data_list);
                    $x++;
                }
            }
        }
        return  response()->json($data);
    }
}