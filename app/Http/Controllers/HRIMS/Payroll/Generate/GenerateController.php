<?php

namespace App\Http\Controllers\HRIMS\Payroll\Generate;
use App\Http\Controllers\Controller;
use App\Models\_Work;
use App\Models\AccAccountTitle;
use App\Models\DTSDocs;
use App\Models\DTSDocsHistory;
use App\Models\EmploymentStatus;
use App\Models\HRAllowance;
use App\Models\HRDeduction;
use App\Models\HRDeductionEmployee;
use App\Models\HRPayroll;
use App\Models\HRPayrollAllowance;
use App\Models\HRPayrollDeduction;
use App\Models\HRPayrollEmpStat;
use App\Models\HRPayrollFundService;
use App\Models\HRPayrollFundSource;
use App\Models\HRPayrollList;
use App\Models\HRPayrollMonths;
use App\Models\HRPayrollType;
use App\Models\HRPayrollTypeGuideline;
use App\Models\HRPTMonths;
use App\Models\Users;
use App\Services\DTSServices;
use App\Services\NameServices;
use App\Services\WorkServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GenerateController extends Controller
{
    public function list(Request $request){
        return $this->_list($request);
    }
    public function generate(Request $request){
        return $this->_generate($request);

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
    public function table(Request $request){
        $payroll = HRPayrollType::find($request->payroll_type);

        if($payroll==NULL){
            return 'error';
        }

        $data = array(
            'payroll' => $payroll
        );
        return view('hrims/payroll/generate/table',$data);
    }
    private function _list($request){
        $name_services = new NameServices;
        $work_services = new WorkServices;
        $data = array();

        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|string',
            'months' => 'required|array',
            'payroll_type' => 'required|integer',
            'emp_stats' => 'required|array',
            'fund_sources' => 'required|array',
            'fund_services' => 'nullable|array',
            'duration' => 'required|integer',
            'pt_option' => 'required|integer',
            'option' => 'required|integer',
            'day_from' => 'required|integer',
            'day_to' => 'required|integer',
            'status' => 'required|integer',
            'include_pera' => 'required|string',
        ]);

        $year = $request->year;
        $month = $request->month;
        $months = $request->months;
        $payroll_type = $request->payroll_type;
        $emp_stats = $request->emp_stats;
        $fund_sources = $request->fund_sources;
        $fund_services = $request->fund_services;
        $duration = $request->duration;
        $pt_option = $request->pt_option;
        $option = $request->option;
        $day_from = $request->day_from;
        $day_to = $request->day_to;
        $status = $request->status;
        $include_pera = $request->include_pera;

        $payroll = HRPayrollType::with('guideline')->where('id',$payroll_type)->first();
        $gov_service = $payroll->gov_service;
        $grant_separated = $payroll->grant_separated;

        $checkGov = EmploymentStatus::whereIn('id',$emp_stats)->pluck('gov')->toArray();
        $countGov = count(array_unique($checkGov));
        if($countGov==1){
            $date_check = date('Y-m-d',strtotime($year.'-'.$month.'-01'));
            if(in_array(5,$emp_stats) || in_array(7,$emp_stats)){
                $date_check = date('Y-m-d',strtotime($year.'-'.$months[0].'-01'));
            }
            $query = Users::
                whereHas('work', function ($query) use ($emp_stats,$fund_sources,$fund_services,$status,$date_check,$gov_service,$grant_separated,$pt_option) {
                    $query->whereIn('emp_stat_id',$emp_stats);
                    $query->whereIn('fund_source_id',$fund_sources);
                    if($gov_service=='Y' || $gov_service=='N'){
                        $query->where('gov_service',$gov_service);
                    }
                    if($fund_services!=''){
                        $query->whereIn('fund_services_id',$fund_services);
                    }
                    $query->where('status',$status);
                    if($grant_separated==0){
                        $query->where(function ($query) use ($date_check) {
                            $query->where('date_to', 'present');
                            $query->orWhere('date_to','>=',$date_check);
                        });
                    }
                    if(in_array(5,$emp_stats) || in_array(7,$emp_stats)){
                        $query->where('pt_option_id',$pt_option);
                    }
                });

            if($status==1){
                $query = $query->where('emp_status_id',1);
            }else{
                $query = $query->where('emp_status_id','>',1);
            }

            if($payroll->month_as_of!=NULL && $payroll->day_as_of!=NULL){
                if($grant_separated==0){
                    $date_as_of = date('Y-m-d',strtotime($year.'-'.$payroll->month_as_of.'-'.$payroll->day_as_of));
                    if($gov_service=='Y'){
                        $query = $query->whereHas('employee_gov_y',function ($query) use ($date_as_of) {
                            $query->where('date_to','>=',$date_as_of)
                                ->orWhere('date_to','present');
                        });
                    }elseif($gov_service=='N'){
                        $query = $query->whereHas('employee_gov_n',function ($query) use ($date_as_of) {
                            $query->where('date_to','>=',$date_as_of)
                                ->orWhere('date_to','present');
                        });
                    }else{
                        $query = $query->whereHas('employee_default',function ($query) use ($date_as_of) {
                            $query->where('date_to','>=',$date_as_of)
                                ->orWhere('date_to','present');
                        });
                    }
                }
            }

            if($payroll->month_from!=NULL && $payroll->day_from!=NULL){
                if($grant_separated==1){
                    $date_as_of = date('Y-m-d',strtotime($year.'-'.$payroll->month_from.'-'.$payroll->day_from));
                    if($gov_service=='Y'){
                        $query = $query->whereHas('employee_gov_y',function ($query) use ($date_as_of) {
                            $query->where('date_to','>=',$date_as_of);
                        });
                    }elseif($gov_service=='N'){
                        $query = $query->whereHas('employee_gov_n',function ($query) use ($date_as_of) {
                            $query->where('date_to','>=',$date_as_of);
                        });
                    }else{
                        $query = $query->whereHas('employee_default',function ($query) use ($date_as_of) {
                            $query->where('date_to','>=',$date_as_of);
                        });
                    }
                }
            }

            if(in_array(5,$emp_stats) || in_array(7,$emp_stats)){
                $user_ids = $query->pluck('id')->toArray();
                $getMonths = HRPayrollMonths::whereIn('user_id',$user_ids)
                    ->where('year',$year)
                    ->where('pt_option_id',$pt_option)
                    ->pluck('user_id')
                    ->toArray();
                if(count($getMonths)>0){
                    $listUser = [];
                    foreach($getMonths as $row){
                        $getMonths1 = HRPayrollMonths::where('year',$year)
                            ->where('user_id',$row)
                            ->where('pt_option_id',$pt_option)
                            ->pluck('month')
                            ->toArray();
                        $nonExistingValues = array_diff($months, $getMonths1);
                        if (!empty($nonExistingValues)) {
                            $listUser[] = $row;
                        }
                    }
                    $query = $query->whereIn('id',$listUser);
                }
            }else{
                $query =  $query->whereDoesntHave('payrolls', function ($query) use ($year,$month,$months,$payroll_type,$emp_stats,$fund_sources,$fund_services,$option,$day_from,$day_to) {
                        $query->whereHas('payroll', function ($query) use ($year,$month,$payroll_type,$emp_stats,$fund_sources,$fund_services,$option,$day_from,$day_to) {
                            $query->where('year',$year);
                            $query->where('month',$month);
                            $query->where('payroll_type_id',$payroll_type);
                            $query->whereHas('fund_source', function ($query) use ($fund_sources) {
                                $query->whereIn('fund_source_id',$fund_sources);
                            });
                            if($fund_services!=''){
                                $query->whereHas('fund_service', function ($query) use ($fund_services) {
                                    $query->whereIn('fund_service_id',$fund_services);
                                });
                            }
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
            $query = $query->get()
                ->map(function($query) use ($name_services,$work_services,
                                            $payroll,$year,$gov_service,
                                            $month,$emp_stats,
                                            $fund_sources,$fund_services,
                                            $payroll_type,$duration,
                                            $option,$day_from,
                                            $day_to,$include_pera,
                                            $pt_option) {

                    if($query->middlename_in_last=='Y'){
                        $name = $name_services->lastname_middlename_last($query->lastname,$query->firstname,$query->middlename,$query->extname);
                    }else{
                        $name = $name_services->lastname($query->lastname,$query->firstname,$query->middlename,$query->extname);
                    }
                    $getWork = $query->work()
                        ->whereIn('emp_stat_id',$emp_stats)
                        ->whereIn('fund_source_id',$fund_sources);
                    if($fund_services!=''){
                        $getWork->whereIn('fund_services_id',$fund_services);
                    }
                    if(in_array(5,$emp_stats) || in_array(7,$emp_stats)){
                        $getWork->where('pt_option_id',$pt_option);
                    }
                    $getWork = $getWork->orderBy('date_from','DESC')->first();

                    if($getWork){
                        $salary = $getWork->salary;
                        $emp_stat = $getWork->emp_stat_id;
                        $user_id = $query->id;
                        $gov = $getWork->emp_stat->gov;
                        $w_salary_amount = 0;
                        $column_amount = 0;
                        $column_amount2 = 0;
                        $include = 'Y';

                        if($payroll_type==1){
                            if($emp_stat!=5 && $emp_stat!=7){
                                $this->updatePhilHealth($salary,$user_id,$gov,$year,$month,$payroll_type,$emp_stat,$duration,$option,$day_from,$day_to);
                                $this->updatePagibig($user_id,$gov,$payroll_type,$emp_stat);
                                if($gov=='Y'){
                                    $this->updateGSIS($salary,$user_id,$gov,$year,$month,$payroll_type,$emp_stat,$duration,$option,$day_from,$day_to);
                                }
                            }
                            $earned = $this->getEarned($year,$month,$salary,$gov,$duration,$option,$day_from,$day_to);
                        }elseif($payroll_type==2){
                            $amount = HRAllowance::find(1)->amount;
                            $earned = $this->getEarned($year,$month,$amount,$gov,1,$option,$day_from,$day_to);
                        }else{
                            if($payroll->aggregate==1){
                                $rendered_months = $work_services->rendered_months_aggregate($user_id,$gov_service,$payroll);
                            }else{
                                $rendered_months = $work_services->rendered_months($user_id,$gov_service);
                            }
                            if($rendered_months>=$payroll->month_no){
                                if($payroll->w_salary=='Yes'){
                                    $w_salary_amount = $salary;
                                }
                                if($payroll->column_name!=NULL){
                                    $column_amount = $payroll->amount;
                                }
                                if($payroll->column_name2!=NULL){
                                    $column_amount2 = $payroll->amount2;
                                }
                            }else{
                                $include = 'N';
                                if(count($payroll->guideline)>0){
                                    $payroll_guideline = HRPayrollTypeGuideline::where('payroll_type_id',$payroll_type)
                                        ->where('from','<=',$rendered_months)
                                        ->where('to','>',$rendered_months)
                                        ->first();
                                    if($payroll_guideline){
                                        $include = 'Y';
                                        if($payroll_guideline->w_salary_percent!=NULL){
                                            $w_salary_amount = round(($salary*$payroll_guideline->w_salary_percent/100),2);
                                        }
                                        if($payroll_guideline->amount!=NULL){
                                            $column_amount = $payroll_guideline->amount;
                                        }elseif($payroll_guideline->percent!=NULL){
                                            $column_amount = round(($payroll->amount*$payroll_guideline->percent/100),2);
                                        }
                                        if($payroll_guideline->amount2!=NULL){
                                            $column_amount2 = $payroll_guideline->amount2;
                                        }elseif($payroll_guideline->percent2!=NULL){
                                            $column_amount2 = round(($payroll->amount2*$payroll_guideline->percent2/100),2);
                                        }
                                    }
                                }
                            }
                            if($include=='Y'){
                                $earned = $w_salary_amount+$column_amount+$column_amount2;
                            }
                        }
                        if($include=='Y'){
                            $allowance = $this->getAllowance($emp_stat,$payroll_type,$include_pera);
                            $deduction = $this->getDeduction($emp_stat,$payroll_type,$user_id);

                            $gross = $earned+$allowance;
                            $netpay = $gross-$deduction;
                            return [
                                'id' => $user_id,
                                'name' => $name,
                                'position' => $getWork->position_shorten,
                                'salary' => number_format($salary,2),
                                'w_salary_amount' => number_format($w_salary_amount,2),
                                'column_amount' => number_format($column_amount,2),
                                'column_amount2' => number_format($column_amount2,2),
                                'gross' => number_format($gross,2),
                                'deduction' => number_format($deduction,2),
                                'netpay' => number_format($netpay,2)
                            ];
                        }
                    }
                })->toArray();
            if(count($query)>0){
                $x = 1;
                foreach($query as $r){
                    if(isset($r['id'])){
                        $data_list['f1'] = $x;
                        $data_list['f2'] = '<input type="checkbox" class="form-control employee" value="'.$r['id'].'" checked>';
                        $data_list['f3'] = $r['name'];
                        $data_list['f4'] = $r['position'];
                        $data_list['f5'] = $r['salary'];
                        $data_list['f6'] = $r['w_salary_amount'];
                        $data_list['f7'] = $r['column_amount'];
                        $data_list['f8'] = $r['column_amount2'];
                        $data_list['f9'] = $r['gross'];
                        $data_list['f10'] = $r['deduction'];
                        $data_list['f11'] = $r['netpay'];
                        array_push($data,$data_list);
                        $x++;
                    }
                }
            }
        }
        return  response()->json($data);
    }
    private function _generate($request){
        $validator = Validator::make($request->all(), [
            'year' => 'required|integer',
            'month' => 'required|string',
            'months' => 'required|array',
            'unclaimeds' => 'nullable|array',
            'payroll_type' => 'required|integer',
            'emp_stats' => 'required|array',
            'fund_sources' => 'required|array',
            'fund_services' => 'nullable|array',
            'duration' => 'required|integer',
            'pt_option' => 'required|integer',
            'option' => 'required|integer',
            'day_from' => 'required|integer',
            'day_to' => 'required|integer',
            'status' => 'required|integer',
            'employees' => 'required|array',
            'generate_option' => 'required|string',
            'include_pera' => 'required|string',
            'account_title' => 'required|integer'
        ]);

        if ($validator->fails()) {
            $response = ['result' => 'error'];
            return response()->json($response);
        }

        $name_services = new NameServices;
        $work_services = new WorkServices;
        $dts_services = new DTSServices;

        DB::beginTransaction();
        try {
            $year = $request->year;
            $month = $request->month;
            $months = $request->months;
            $unclaimeds = $request->unclaimeds;
            $payroll_type = $request->payroll_type;
            $emp_stats = $request->emp_stats;
            $fund_sources = $request->fund_sources;
            $fund_services = $request->fund_services;
            $duration = $request->duration;
            $pt_option = $request->pt_option;
            $option = $request->option;
            $day_from = $request->day_from;
            $day_to = $request->day_to;
            $status = $request->status;
            $employees = $request->employees;
            $generate_option = $request->generate_option;
            $include_pera = $request->include_pera;
            $account_title = $request->account_title;

            $check = AccAccountTitle::where('id',$account_title)
                ->where('payment','yes')->count();
            if($check==0){
                return response()->json(['result' => 'error']);
            }

            $last_day = date('t',strtotime($year.'-'.$month.'-01'));
            $checkGov = EmploymentStatus::whereIn('id',$emp_stats)->pluck('gov')->toArray();
            $countGov = count(array_unique($checkGov));

            if($countGov==1){
                $gov = $checkGov[0];
                $getDays = $this->getDays($day_from,$day_to,$year,$month,$option,$duration,$gov);
                $weekdays = $getDays['weekdays'];

                $user = Auth::user();
                $updated_by = $user->id;
                $payroll = HRPayrollType::with('guideline')->where('id',$payroll_type)->first();
                $gov_service = $payroll->gov_service;
                $grant_separated = $payroll->grant_separated;

                if(in_array('5',$emp_stats) || in_array('7',$emp_stats)){
                    $month = end($months);
                }
                if($payroll_type==1 && !in_array('5',$emp_stats) && !in_array('7',$emp_stats)){
                    if($option==1){
                        $day_from = 1;
                        $day_to = $last_day;
                        if($gov=='N'){
                            if($duration==2){
                                $day_from = 1;
                                $day_to = 15;
                            }elseif($duration==3){
                                $day_from = 16;
                            }
                        }
                    }
                }elseif($payroll_type==2){
                    if($option==1){
                        $day_from = 1;
                        $day_to = $last_day;
                    }
                    $include_pera = 'No';
                }else{
                    $day_from = 1;
                    $day_to = date('t',strtotime($year.'-'.$month.'-01'));
                    $include_pera = 'No';
                }
                if($day_to>=$last_day){
                    $day_to = $last_day;
                }
                if($gov=='N'){
                    $include_pera = 'No';
                }

                $payroll_id = $this->getPayrollId($year,$month);

                $insert = new HRPayroll();
                $insert->payroll_id = $payroll_id;
                $insert->year = $year;
                $insert->month = $month;
                $insert->payroll_type_id = $payroll_type;
                $insert->duration_id = $duration;
                $insert->option_id = $option;
                $insert->day_from = $day_from;
                $insert->day_to = $day_to;
                $insert->include_pera = $include_pera;
                $insert->generate_option = $generate_option;
                $insert->status_of_employee = $status;
                $insert->account_title_id = $account_title;
                $insert->generated_by = $updated_by;
                $insert->generated_at = date('Y-m-d H:i:s');
                $insert->updated_by = $updated_by;
                $insert->save();
                $payroll_id = $insert->id;

                foreach($emp_stats as $emp_stat){
                    $emp_status = EmploymentStatus::find($emp_stat);
                    $insert = new HRPayrollEmpStat();
                    $insert->payroll_id = $payroll_id;
                    $insert->emp_stat_id = $emp_stat;
                    $insert->gov = $emp_status->gov;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                }

                foreach($fund_sources as $fund_source){
                    $insert = new HRPayrollFundSource();
                    $insert->payroll_id = $payroll_id;
                    $insert->fund_source_id = $fund_source;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                }
                if($fund_services!=''){
                    foreach($fund_services as $fund_service){
                        $insert = new HRPayrollFundService();
                        $insert->payroll_id = $payroll_id;
                        $insert->fund_service_id = $fund_service;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                    }
                }

                foreach($employees as $employee){
                    $info = Users::where('id',$employee)->first();
                    $getWork = _Work::where('user_id',$employee)
                        ->whereIn('emp_stat_id',$emp_stats)
                        ->whereIn('fund_source_id',$fund_sources)
                        ->orderBy('date_from','DESC')->first();

                    $salary = $getWork->salary;
                    $emp_stat = $getWork->emp_stat_id;
                    $include = 'Y';
                    $w_salary_amount = 0;
                    $column_amount = 0;
                    $column_amount2 = 0;

                    if($payroll_type==1){
                        $earned = $this->getEarned($year,$month,$salary,$gov,$duration,$option,$day_from,$day_to);
                        $amount_base = $salary;
                    }elseif($payroll_type==2){
                        $amount = HRAllowance::find(1)->amount;
                        $amount_base = $amount;
                        $earned = $this->getEarned($year,$month,$amount,$gov,$duration,$option,$day_from,$day_to);
                    }else{
                        if($payroll->aggregate==1){
                            $rendered_months = $work_services->rendered_months_aggregate($employee,$gov_service,$payroll);
                        }else{
                            $rendered_months = $work_services->rendered_months($employee,$gov_service);
                        }

                        if($rendered_months>=$payroll->month_no){
                            if($payroll->w_salary=='Yes'){
                                $w_salary_amount = $salary;
                            }
                            if($payroll->column_name!=NULL){
                                $column_amount = $payroll->amount;
                            }
                            if($payroll->column_name2!=NULL){
                                $column_amount2 = $payroll->amount2;
                            }
                        }else{
                            $include = 'N';
                            if(count($payroll->guideline)>0){
                                $payroll_guideline = HRPayrollTypeGuideline::where('payroll_type_id',$payroll_type)
                                    ->where('from','<=',$rendered_months)
                                    ->where('to','>',$rendered_months)
                                    ->first();
                                if($payroll_guideline){
                                    $include = 'Y';
                                    if($payroll_guideline->w_salary_percent!=NULL){
                                        $w_salary_amount = round(($salary*$payroll_guideline->w_salary_percent/100),2);
                                    }
                                    if($payroll_guideline->amount!=NULL){
                                        $column_amount = $payroll_guideline->amount;
                                    }elseif($payroll_guideline->percent!=NULL){
                                        $column_amount = round(($payroll->amount*$payroll_guideline->percent/100),2);
                                    }
                                    if($payroll_guideline->amount2!=NULL){
                                        $column_amount2 = $payroll_guideline->amount2;
                                    }elseif($payroll_guideline->percent2!=NULL){
                                        $column_amount2 = round(($payroll->amount2*$payroll_guideline->percent2/100),2);
                                    }
                                }
                            }
                        }
                        if($include=='Y'){
                            $amount_base = $w_salary_amount;
                            $earned = $w_salary_amount+$column_amount+$column_amount2;
                        }
                    }

                    if($include=='Y'){
                        $allowance = $this->getAllowance($emp_stat,$payroll_type,$include_pera);
                        $deduction = $this->getDeduction($emp_stat,$payroll_type,$employee);

                        $gross = $earned+$allowance;
                        $netpay = $gross-$deduction;

                        $insert = new HRPayrollList();
                        $insert->payroll_id = $payroll_id;
                        $insert->user_id = $employee;
                        $insert->emp_stat_id = $getWork->emp_stat_id;
                        $insert->fund_source_id = $getWork->fund_source_id;
                        $insert->fund_services_id = $getWork->fund_services_id;
                        $insert->pt_option_id = $pt_option;
                        $insert->lastname = $info->lastname;
                        $insert->firstname = $info->firstname;
                        $insert->middlename = $info->middlename;
                        $insert->extname = $info->extname;
                        $insert->middlename_in_last = $info->personal_info->middlename_in_last;
                        $insert->position_title = $getWork->position_title;
                        $insert->position_shorten = $getWork->position_shorten;
                        $insert->salary = $getWork->salary;
                        $insert->sg = $getWork->sg;
                        $insert->step = $getWork->step;
                        $insert->amount_base = $amount_base;
                        $insert->column_amount = $column_amount;
                        $insert->column_amount2 = $column_amount2;
                        $insert->earned = $earned;
                        $insert->allowances = $allowance;
                        $insert->gross = $gross;
                        $insert->deduction = $deduction;
                        $insert->netpay = $netpay;
                        $insert->day_from = $day_from;
                        $insert->day_to = $day_to;
                        $insert->day_accu = $weekdays;
                        $insert->updated_by = $updated_by;
                        $insert->save();

                        $payroll_list_id = $insert->id;
                        $emp_stat = $getWork->emp_stat_id;
                        $emp_fund_services[] = $getWork->fund_services_id;

                        $this->insertEmployeeAllowance($emp_stat,$payroll_type,$include_pera,$gov,$payroll_list_id,$payroll_id,$employee,$updated_by);
                        $this->insertEmployeeDeduction($emp_stat,$payroll_type,$payroll_list_id,$payroll_id,$employee,$updated_by);

                        if((in_array(5,$emp_stats) || in_array(7,$emp_stats)) && $payroll_type==1){
                            foreach($months as $month){
                                $check = HRPayrollMonths::where('user_id',$employee)
                                    ->where('year',$year)
                                    ->where('month',$month)
                                    ->where('pt_option_id',$pt_option)
                                    ->first();
                                $hr_month = HRPTMonths::where('user_id',$employee)
                                    ->where('year',$year)
                                    ->where('month',$month)
                                    ->where('pt_option_id',$pt_option)
                                    ->where('emp_stat_id',$emp_stat)
                                    ->first();
                                $status = 'unclaimed';
                                if($check!=NULL){
                                    $status = 'claimed';
                                }
                                $hours = $hr_month ? $hr_month->hour : 0;
                                $earned = $getWork->salary*$hours;
                                $insert = new HRPayrollMonths();
                                $insert->payroll_list_id = $payroll_list_id;
                                $insert->payroll_id = $payroll_id;
                                $insert->user_id = $employee;
                                $insert->pt_option_id = $pt_option;
                                $insert->year = $year;
                                $insert->month = $month;
                                $insert->amount = $hours;
                                $insert->earned = $earned;
                                $insert->option = 'default';
                                $insert->status = $status;
                                $insert->updated_by = $updated_by;
                                $insert->save();
                            }
                            if($unclaimeds!=NULL){
                                if(count($unclaimeds)>0){
                                    foreach($unclaimeds as $month1){
                                        $check = HRPayrollMonths::where('user_id',$employee)
                                            ->where('year',($year-1))
                                            ->where('month',$month)
                                            ->where('pt_option_id',$pt_option)
                                            ->first();
                                        $hr_month = HRPTMonths::where('user_id',$employee)
                                            ->where('year',$year)
                                            ->where('month',$month)
                                            ->where('pt_option_id',$pt_option)
                                            ->where('emp_stat_id',$emp_stat)
                                            ->first();
                                        $status = 'unclaimed';
                                        if($check!=NULL){
                                            $status = 'claimed';
                                        }
                                        $hours = $hr_month ? $hr_month->hour : 0;
                                        $earned = $getWork->salary*$hours;
                                        $insert = new HRPayrollMonths();
                                        $insert->payroll_list_id = $payroll_list_id;
                                        $insert->payroll_id = $payroll_id;
                                        $insert->user_id = $employee;
                                        $insert->pt_option_id = $pt_option;
                                        $insert->year = ($year-1);
                                        $insert->month = $month1;
                                        $insert->amount = $hours;
                                        $insert->earned = $earned;
                                        $insert->option = 'unclaimed';
                                        $insert->status = $status;
                                        $insert->updated_by = $updated_by;
                                        $insert->save();
                                    }
                                }
                            }
                        }
                    }
                }

                $list = HRPayrollList::with('employee.personal_info')
                    ->where('payroll_id', $payroll_id);
                $gross = $list->sum('gross');
                $netpay = $list->sum('netpay');
                $count = $list->get()->count();
                $get_et_al = $list->orderBy('fund_services_id','ASC')
                    ->orderBy('lastname','ASC')
                    ->orderBy('firstname','ASC')
                    ->first();
                $ob = $gross == null ? '' : number_format($gross,2);
                $dv = $netpay == null ? '' : number_format($netpay,2);
                $etal = '';
                if($get_et_al){
                    $et_al = $count > 1 ? ' etal' : '';
                    if($get_et_al->employee->personal_info->middlename_in_last=='Y'){
                        $etal = $name_services->lastname_middlename_last($get_et_al->lastname,$get_et_al->firstname,$get_et_al->middlename,$get_et_al->extname);
                    }else{
                        $etal = $name_services->lastname($get_et_al->lastname,$get_et_al->firstname,$get_et_al->middlename,$get_et_al->extname);
                    }
                    $etal = $etal.$et_al;
                }
                if((in_array(5,$emp_stats) || in_array(7,$emp_stats)) && $payroll_type==1 && count($months)>0){
                    $payroll_name = $payroll->name.' (Part-Time)';
                }else{
                    $payroll_name = $payroll->name;
                }
                if($payroll->time_period_id==4){
                    $period = date('M Y',strtotime($year.'-'.$month.'-01'));
                }else{
                    $period = date('M',strtotime($year.'-'.$month.'-01')).' '.$day_from.'-'.$day_to.', '.$year;
                }

                $particulars = $payroll_name.' '.$period;
                $dts_id = $dts_services->dts_id();

                $user = Users::with('employee_default')->where('id',$updated_by)->first();
                $user_office_id = $user->employee_default->office_id;

                $insert = new DTSDocs();
                $insert->dts_id = $dts_id;
                $insert->type_id = 1;
                $insert->office_id = $user_office_id;
                $insert->particulars = $etal.' OB: '.$ob.' DV: '.$dv;
                $insert->description = $particulars;
                $insert->amount = $gross;
                $insert->status_id = 1;
                $insert->created_by = $updated_by;
                $insert->updated_by = $updated_by;
                $insert->save();
                $doc_id = $insert->id;

                $update = HRPayroll::find($payroll_id);
                $update->name = $payroll_name;
                $update->period = $period;
                $update->etal = $etal;
                $update->ob = $gross;
                $update->dv = $netpay;
                $update->tracking_id = $doc_id;
                $update->save();

                if($fund_services==''){
                    $emp_fund_service = array_unique($emp_fund_services);
                    foreach ($emp_fund_service as $fund_service) {
                        $insert = new HRPayrollFundService();
                        $insert->payroll_id = $payroll_id;
                        $insert->fund_service_id = $fund_service;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                    }
                }
                DB::commit();
                $result = 'success';
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $result = 'error';
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
    private function getPayrollId($year,$month){
        $query = HRPayroll::where('year',$year)
            ->where('month',$month)
            ->orderBy('payroll_id','DESC')
            ->first();
        $no = '00001';
        if($query){
            $no = str_pad((substr($query->payroll_id, -5)+1),5,0,STR_PAD_LEFT);
        }
        return date('ym',strtotime($year.'-'.$month.'-01')).$no;
    }
    private function updatePagibig($user_id,$gov,$payroll_type,$emp_stat){

        $check = HRDeduction::where('id',29)
            ->whereHas('emp_stat', function ($query) use ($emp_stat) {
                $query->where('emp_stat_id',$emp_stat);
            })->first();
        if($check){
            $user = Auth::user();
            $updated_by = $user->id;
            if($gov=='N'){
                $amount = 400;
                $amount_employer = 0;
            }else{
                $amount = 200;
                $amount_employer = $check->amount;
            }
            $query = HRDeductionEmployee::where('user_id',$user_id)
                ->where('payroll_type_id',$payroll_type)
                ->where('emp_stat_id',$emp_stat)
                ->where('deduction_id',29)
                ->first();
            if($query){
                if($query->amount>$amount){
                    $amount = $query->amount;
                }
            }
            HRDeductionEmployee::updateOrCreate(
                [
                    'user_id' => $user_id,
                    'payroll_type_id' => $payroll_type,
                    'emp_stat_id' => $emp_stat,
                    'deduction_id' => 29,
                ],
                [
                    'amount' => $amount,
                    'amount_employer' => $amount_employer,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            );
        }
    }
    private function updatePhilHealth($salary,$user_id,$gov,$year,$month,$payroll_type,$emp_stat,$duration,$option,$day_from,$day_to){
        $checkPhilHealth = HRDeduction::where('id',36)
            ->whereHas('emp_stat', function ($query) use ($emp_stat) {
                $query->where('emp_stat_id',$emp_stat);
            })->first();
        if($checkPhilHealth){
            $user = Auth::user();
            $updated_by = $user->id;
            $getPhilHealth = HRDeduction::where('id',36)->first();
            $last_day = date('t',strtotime($year.'-'.$month.'-01'));

            if($option==1){
                $philhealth = round(($salary*($getPhilHealth->percent/100)),2);
            }else{
                $getDays = $this->getDays($day_from,$day_to,$year,$month,$option,$duration,$gov);
                $weekdays = $getDays['weekdays'];

                $philhealth = round(($salary/22*$weekdays*($getPhilHealth->percent/100)),2);
            }

            if($philhealth>=$getPhilHealth->ceiling){
                $philhealth = $getPhilHealth->ceiling;
            }
            if($gov=='Y'){
                $philhealth_employer = round(($philhealth/2),2);
                $philhealth = round(($philhealth-$philhealth_employer),2);
            }else{
                $philhealth_employer = NULL;
            }
            HRDeductionEmployee::updateOrCreate(
                [
                    'user_id' => $user_id,
                    'payroll_type_id' => $payroll_type,
                    'emp_stat_id' => $emp_stat,
                    'deduction_id' => 36,
                ],
                [
                    'amount' => $philhealth,
                    'amount_employer' => $philhealth_employer,
                    'percent' => $getPhilHealth->percent,
                    'percent_employer' => $getPhilHealth->percent_employer,
                    'ceiling' => $getPhilHealth->ceiling,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            );
        }
    }
    private function updateGSIS($salary,$user_id,$gov,$year,$month,$payroll_type,$emp_stat,$duration,$option,$day_from,$day_to){
        $checkGSIS = HRDeduction::where('id',3)
            ->whereHas('employee', function ($query) use ($emp_stat) {
                $query->where('emp_stat_id',$emp_stat);
            })->first();
        if($checkGSIS){
            $user = Auth::user();
            $updated_by = $user->id;
            $last_day = date('t',strtotime($year.'-'.$month.'-01'));

            $getDays = $this->getDays($day_from,$day_to,$year,$month,$option,$duration,$gov);
            $weekdays = $getDays['weekdays'];
            $weekends = $getDays['weekends'];

            $getGSIS = HRDeduction::where('id',3)->first();
            if($weekdays>=22){
                $ps = round(($salary*($getGSIS->percent/100)),2);
                $gs = round(($salary*($getGSIS->percent_employer/100)),2);
            }else{
                $ps = round(($salary*0.09/$last_day*($weekdays+$weekends)),2);
                $gs = round(($ps/0.09*0.12),2);
            }
            HRDeductionEmployee::updateOrCreate(
                [
                    'user_id' => $user_id,
                    'payroll_type_id' => $payroll_type,
                    'emp_stat_id' => $emp_stat,
                    'deduction_id' => 3,
                ],
                [
                    'amount' => $ps,
                    'amount_employer' => $gs,
                    'percent' => $getGSIS->percent,
                    'percent_employer' => $getGSIS->percent_employer,
                    'ceiling' => $getGSIS->ceiling,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            );
        }
    }
    private function getEarned($year,$month,$salary,$gov,$duration,$option,$day_from,$day_to){
        $earned = 0;
        if($option==1){
            $earned = $salary;
            if($gov!='Y'){
                if($duration>1){
                    $earned = round(($salary/2),2);
                }
            }
        }else{
            $per_salary = $this->getPerSalary($salary);
            $getDays = $this->getDays($day_from,$day_to,$year,$month,$option,$duration,$gov);
            $weekdays = $getDays['weekdays'];
            if($weekdays==11){
                $earned = round(($salary/2),2);
            }else{
                $earned = round(($per_salary['day']*$weekdays),2);
            }
        }
        return $earned;
    }
    private function getAllowance($emp_stat,$payroll_type,$include_pera){
        $allowance = HRAllowance::whereHas('emp_stat', function ($query) use ($emp_stat) {
            $query->where('emp_stat_id',$emp_stat);
        })->whereHas('payroll_type', function ($query) use ($payroll_type) {
            $query->where('payroll_type_id',$payroll_type);
        });
        if($include_pera=='No'){
            $allowance = $allowance->where('id','>','1');
        }
        $allowance = $allowance->sum('amount');
        return $allowance;
    }
    private function getDeduction($emp_stat,$payroll_type,$user_id){
        $deduction = HRDeductionEmployee::where('payroll_type_id',$payroll_type)
                            ->where('emp_stat_id',$emp_stat)
                            ->where('user_id',$user_id)->sum('amount');
        return $deduction;
    }
    private function getPerSalary($salary){
        $day = round($salary/22,2);
        $hour = round($day/8,2);
        $minute = round($hour/60,2);
        $per_salary['day'] = $day;
        $per_salary['hour'] = $hour;
        $per_salary['minute'] = $minute;
        return $per_salary;
    }
    private function getDays($day_from,$day_to,$year,$month,$option,$duration,$gov){
        $weekends = 0;
        $weekdays = 0;
        $last_day = date('t',strtotime($year.'-'.$month.'-01'));
        if($option==1){
            $weekdays = 22;
        }else{
            if($duration>1 && $gov=='N'){
                $weekdays = 11;
            }else{
                if(($day_from==1 && $day_to==15) || ($day_from==16 && $day_to>=$last_day)){
                    $weekdays = 11;
                }else{
                    for($i=$day_from; $i <= $day_to; $i++){
                        $wkday = date('l',strtotime($year.'-'.$month.'-'.$i));

                        if($wkday == 'Sunday' || $wkday == 'Saturday'){
                            $weekends++;
                        }else{
                            $weekdays++;
                        }
                    }
                    if($weekdays>=22){
                        $weekdays = 22;
                    }
                }
            }
        }
        $getDays['weekdays'] = $weekdays;
        $getDays['weekends'] = $weekends;
        return $getDays;
    }
    private function insertEmployeeAllowance($emp_stat,$payroll_type,$include_pera,$gov,$payroll_list_id,$payroll_id,$employee,$updated_by){
        $query = HRAllowance::whereHas('emp_stat', function ($query) use ($emp_stat) {
            $query->where('emp_stat_id',$emp_stat);
        })->whereHas('payroll_type', function ($query) use ($payroll_type) {
            $query->where('payroll_type_id',$payroll_type);
        });
        if($include_pera=='No' || $gov=='N' || $payroll_type>1){
            $query = $query->where('id','>','1');
        }
        $query = $query->get();

        if($query->count()>0){
            foreach($query as $row){
                $insert = new HRPayrollAllowance();
                $insert->payroll_list_id = $payroll_list_id;
                $insert->payroll_id = $payroll_id;
                $insert->user_id = $employee;
                $insert->allowance_id = $row->id;
                $insert->amount = $row->amount;
                $insert->updated_by = $updated_by;
                $insert->save();
            }
        }
    }
    private function insertEmployeeDeduction($emp_stat,$payroll_type,$payroll_list_id,$payroll_id,$employee,$updated_by){
        $query = HRDeductionEmployee::where('emp_stat_id',$emp_stat)
            ->where('payroll_type_id',$payroll_type)
            ->where('user_id',$employee)
            ->get();
        if($query->count()>0){
            foreach($query as $row){
                $insert = new HRPayrollDeduction();
                $insert->payroll_list_id = $payroll_list_id;
                $insert->payroll_id = $payroll_id;
                $insert->user_id = $employee;
                $insert->deduction_id = $row->deduction_id;
                $insert->amount = $row->amount;
                $insert->percent = $row->percent;
                $insert->percent_employer = $row->percent_employer;
                $insert->ceiling = $row->ceiling;
                $insert->updated_by = $updated_by;
                $insert->save();
            }
        }
    }
}
