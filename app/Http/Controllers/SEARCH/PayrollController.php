<?php

namespace App\Http\Controllers\SEARCH;
use App\Http\Controllers\Controller;
use App\Models\HRPayroll;
use App\Models\HRPayrollMonths;
use App\Models\Users;
use App\Services\CodeServices;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class PayrollController extends Controller
{
    public function employee(Request $request){
        $name_services = new NameServices;
        $code_services = new CodeServices;
        $decode = $code_services->decode($request->code,$request->id);
        if($decode=='error'){
            return  response()->json([]);
        }
        $id = $request->id;
        $payroll = HRPayroll::where('payroll_id',$id)->first();
        $status = $payroll->status_of_employee;
        $year = $payroll->year;
        $month = $payroll->month;
        $payroll_type = $payroll->payroll_type_id;
        $option = $payroll->option_id;
        $day_from = $payroll->day_from;
        $day_to = $payroll->day_to;
        $emp_stats = $payroll->emp_stat()->pluck('emp_stat_id')->toArray();
        $fund_sources = $payroll->fund_source()->pluck('fund_source_id')->toArray();
        $search = $request->input('search');
        $date_check = date('Y-m-d',strtotime($year.'-'.$month.'-01'));
        $months = [];
        if(isset($payroll->months)){
            $months = $payroll->months()->pluck('month')->toArray();
        }
        $query = Users::
                whereHas('work', function ($query) use ($emp_stats,$fund_sources,$status,$date_check) {
                            $query->whereIn('emp_stat_id',$emp_stats);
                            $query->whereIn('fund_source_id',$fund_sources);
                            $query->where('status',$status);
                            $query->where(function ($query) use ($date_check) {
                                $query->where('date_to', 'present')
                                    ->orWhere('date_to','>=',$date_check);
                            });
                });
            if(in_array(5,$emp_stats)){
                $user_ids = $query->pluck('id')->toArray();
                $getMonths = HRPayrollMonths::whereIn('user_id',$user_ids)
                    ->where('year',$year)->pluck('user_id')->toArray();
                if(count($getMonths)>0){
                    $listUser = [];
                    foreach($getMonths as $row){
                        $getMonths1 = HRPayrollMonths::where('year',$year)
                            ->where('user_id',$row)->pluck('month')->toArray();
                        $nonExistingValues = array_diff($months, $getMonths1);
                        if (!empty($nonExistingValues)) {
                            $listUser[] = $row;
                        } 
                    }
                    $query = $query->whereIn('id',$listUser);
                }
                
            }else{
                
                $query =  $query->whereDoesntHave('payrolls', function ($query) use ($year,$month,$months,$payroll_type,$emp_stats,$fund_sources,$option,$day_from,$day_to) {
                        $query->whereHas('payroll', function ($query) use ($year,$month,$payroll_type,$emp_stats,$fund_sources,$option,$day_from,$day_to) {
                            $query->where('year',$year);
                            $query->where('month',$month);
                            $query->where('payroll_type_id',$payroll_type);
                            $query->whereHas('fund_source', function ($query) use ($fund_sources) {
                                $query->whereIn('fund_source_id',$fund_sources);
                            });
                            $query->whereHas('emp_stat', function ($query) use ($emp_stats) {
                                $query->whereIn('emp_stat_id',$emp_stats);
                            });
                            if($payroll_type==1){
                                if($option>1){
                                    $query->where('day_from','<',$day_from);
                                    $query->where('day_to','<',$day_from);
                                    $query->where('day_from','<',$day_to);
                                    $query->where('day_to','<',$day_to);
                                }
                            }
                        });
                });
            }
        $query = $query
            ->where(function ($query) use ($search) {
                $query->where('lastname', 'LIKE', "%$search%")
                    ->orWhere('firstname', 'LIKE', "%$search%");
            })
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->limit(15)
            ->get();
        $data = [];
        if($query->count()>0){
            foreach ($query as $result) {
                $name = $name_services->lastname($result->lastname,$result->firstname,$result->middlename,$result->extname);
                $data[] = ['id' => $result->id, 'text' => $name];
            }
        }
        return response()->json($data);
    }
}

