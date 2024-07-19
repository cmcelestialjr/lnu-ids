<?php

namespace App\Http\Controllers\HRIMS\Payroll;

use App\Http\Controllers\Controller;
use App\Models\_Work;
use App\Models\DTSDocs;
use App\Models\DTSDocsHistory;
use App\Models\HRAllowance;
use App\Models\HRDeduction;
use App\Models\HRDeductionGroup;
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
use App\Models\Users;
use App\Services\CodeServices;
use App\Services\NameServices;
use App\Services\PayrollUpdateServices;
use App\Services\WorkServices;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class PayrollViewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $code_services = new CodeServices;
        $decode = $code_services->decode($request->encoded,$request->payroll_id);
        $payroll_id = '';

        if($decode!='success'){
            return view('layouts/error/404');
        }

        $payroll_id = $request->payroll_id;

        $query = HRPayroll::with('payroll_type','months','unclaimeds')->where('payroll_id',$payroll_id)->first();

        if($query==NULL){
            return view('layouts/error/404');
        }

        $payroll_id = $query->id;
        $deduction_group = HRDeductionGroup::whereHas('deduction', function ($query) use ($payroll_id) {
                $query->whereHas('payroll_deduction', function ($query) use ($payroll_id) {
                    $query->where('payroll_id',$payroll_id);
                });
            })->orderBy('id','ASC')->get();
        $group_count = HRDeduction::whereHas('payroll_deduction', function ($query) use ($payroll_id) {
                $query->where('payroll_id',$payroll_id);
            })
            ->selectRaw('group_id, COUNT(*) as count')
            ->groupBy('group_id')
            ->get();
        $deduction_list = HRDeduction::whereHas('payroll_deduction', function ($query) use ($payroll_id) {
                $query->where('payroll_id',$payroll_id);
            })->whereNotNull('group_id')
            ->orderBy('group_id','ASC')
            ->orderBy('id','ASC')->get();
        $allowance_list = HRAllowance::whereHas('payroll_allowance', function ($query) use ($payroll_id) {
                $query->where('payroll_id',$payroll_id);
            })->orderBy('id','ASC')->get();
        $deduction_other = HRDeduction::whereHas('payroll_deduction', function ($query) use ($payroll_id) {
                $query->where('payroll_id',$payroll_id);
            })->whereNull('group_id')
            ->orderBy('id','ASC')->get();
        $bg_color = '';
        if($query->generate_option=='partial'){
            $bg_color = 'bg-yellow-light';
        }
        $pdf_option = 'Payroll: '.$request->payroll_id.' Code: '.$request->encoded;

        $data = array(
            'query' => $query,
            'deduction_group' => $deduction_group,
            'group_count' => $group_count,
            'allowance_list' => $allowance_list,
            'deduction_list' => $deduction_list,
            'deduction_other' => $deduction_other,
            'bg_color' => $bg_color,
            'payroll_id' => $request->payroll_id,
            'pdf_option' => $pdf_option
        );
        return view('hrims/payroll/view/payrollView',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $response_data = array('result' => 'error');

        // Validate the incoming request data
        $validator = $this->storeValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($response_data);
        }

        $user_access_level = $request->session()->get('user_access_level');
        $user_access_levels = array(1,2,3);

        // Check user access level
        if (!in_array($user_access_level, $user_access_levels)) {
            return  response()->json($response_data);
        }

        $code_services = new CodeServices;
        $work_services = new WorkServices;
        $payroll_update_services = new PayrollUpdateServices;

        $decode = $code_services->decode($request->code,$request->id);
        if($decode=='error'){
            return  response()->json($response_data);
        }

        $payroll_id = $request->id;
        $employee = $request->val;

        $user = Auth::user();
        $updated_by = $user->id;

        $payroll = HRPayroll::where('payroll_id',$payroll_id)->first();
        if($payroll==NULL){
            return  response()->json($response_data);
        }

        $payroll_id = $payroll->id;
        $status = $payroll->status_of_employee;
        $year = $payroll->year;
        $month = $payroll->month;
        $payroll_type = $payroll->payroll_type_id;
        $option = $payroll->option_id;
        $duration = $payroll->duration_id;
        $day_from = $payroll->day_from;
        $day_to = $payroll->day_to;
        $include_pera = $payroll->include_pera;
        $emp_stats = $payroll->emp_stat()->pluck('emp_stat_id')->toArray();
        $fund_sources = $payroll->fund_source()->pluck('fund_source_id')->toArray();
        $payroll_type_query = HRPayrollType::with('guideline')->where('id',$payroll_type)->first();
        if($payroll_type_query==NULL){
            return  response()->json($response_data);
        }

        $gov_service = $payroll_type_query->gov_service;
        $grant_separated = $payroll_type_query->grant_separated;

        $info = Users::where('id',$employee)->first();
        if($info==NULL){
            return  response()->json($response_data);
        }

        $getWork = _Work::where('user_id',$employee)
            ->whereIn('emp_stat_id',$emp_stats)
            ->whereIn('fund_source_id',$fund_sources)
            ->orderBy('date_from','DESC')->first();
        if($getWork==NULL){
            return  response()->json($response_data);
        }

        $emp_stat = $getWork->emp_stat_id;
        $gov = $getWork->emp_stat->gov;
        $salary = $getWork->salary;
        $include = 'Y';
        $w_salary_amount = 0;
        $column_amount = 0;
        $column_amount2 = 0;
        $earned = 0;
        if($payroll_type==1){
            if($emp_stat!=5 && $emp_stat!=7){
                $payroll_update_services->updatePhilHealth($salary,$employee,$gov,$year,$month,$payroll_type,$emp_stat,$duration,$option,$day_from,$day_to);
                $payroll_update_services->updatePagibig($employee,$gov,$payroll_type,$emp_stat);
                if($gov=='Y'){
                    $payroll_update_services->updateGSIS($salary,$employee,$gov,$year,$month,$payroll_type,$emp_stat,$duration,$option,$day_from,$day_to);
                }
            }
            $earned = $payroll_update_services->getEarned1($year,$month,$salary,$gov,$duration,$option,$day_from,$day_to);
            $amount_base = $salary;
        }elseif($payroll_type==2){
            $amount = HRAllowance::find(1)->amount;
            $amount_base = $amount;
            $earned = $payroll_update_services->getEarned1($year,$month,$amount,$gov,$duration,$option,$day_from,$day_to);
        }else{
            if($payroll->aggregate==1){
                $rendered_months = $work_services->rendered_months_aggregate($employee,$gov_service,$payroll_type_query);
            }else{
                $rendered_months = $work_services->rendered_months($employee,$gov_service);
            }
            if($rendered_months>=$payroll_type_query->month_no){
                if($payroll_type_query->w_salary=='Yes'){
                    $w_salary_amount = $salary;
                }
                if($payroll_type_query->column_name!=NULL){
                    $column_amount = $payroll_type_query->amount;
                }
                if($payroll_type_query->column_name2!=NULL){
                    $column_amount2 = $payroll_type_query->amount2;
                }
            }else{
                $include = 'N';
                if(count($payroll_type_query->guideline)>0){
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
                            $column_amount = round(($payroll_type_query->amount*$payroll_guideline->percent/100),2);
                        }
                        if($payroll_guideline->amount2!=NULL){
                            $column_amount2 = $payroll_guideline->amount2;
                        }elseif($payroll_guideline->percent2!=NULL){
                            $column_amount2 = round(($payroll_type_query->amount2*$payroll_guideline->percent2/100),2);
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
            $getDays = $payroll_update_services->getDays($day_from,$day_to,$year,$month,$option,$duration,$gov);
            $weekdays = $getDays['weekdays'];

            $allowance = $payroll_update_services->getAllowance1($emp_stat,$payroll_type,$include_pera);
            $deduction = $payroll_update_services->getDeduction1($emp_stat,$payroll_type,$employee);
            $netpay = $earned-$deduction;

            $insert = new HRPayrollList();
            $insert->payroll_id = $payroll_id;
            $insert->user_id = $employee;
            $insert->emp_stat_id = $emp_stat;
            $insert->fund_source_id = $getWork->fund_source_id;
            $insert->fund_services_id = $getWork->fund_services_id;
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
            $insert->gross = $earned+$allowance;
            $insert->deduction = $deduction;
            $insert->netpay = $netpay;
            $insert->day_from = $day_from;
            $insert->day_to = $day_to;
            $insert->day_accu = $weekdays;
            $insert->updated_by = $updated_by;
            $insert->save();

            $payroll_list_id = $insert->id;

            $payroll_update_services->insertEmployeeAllowance($emp_stat,$payroll_type,$include_pera,$gov,$payroll_list_id,$payroll_id,$employee,$updated_by);
            $payroll_update_services->insertEmployeeDeduction($emp_stat,$payroll_type,$payroll_list_id,$payroll_id,$employee,$updated_by);

            if(($emp_stat==5 || $emp_stat==7) && $payroll_type==1){
                if(isset($payroll->months)){
                    foreach($payroll->months as $month){
                        $check = HRPayrollMonths::where('user_id',$employee)
                            ->where('year',$year)
                            ->where('month',$month)
                            ->first();
                        $status = 'unclaimed';
                        if($check!=NULL){
                            $status = 'claimed';
                        }
                        $insert = new HRPayrollMonths();
                        $insert->payroll_list_id = $payroll_list_id;
                        $insert->payroll_id = $payroll_id;
                        $insert->user_id = $employee;
                        $insert->year = $year;
                        $insert->month = $month;
                        $insert->amount = 0;
                        $insert->option = 'default';
                        $insert->status = $status;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                    }
                }
                if(count($payroll->unclaimeds)>0){
                    foreach($payroll->unclaimeds as $month1){
                        $check = HRPayrollMonths::where('user_id',$employee)
                            ->where('year',($year-1))
                            ->where('month',$month)
                            ->first();
                        $status = 'unclaimed';
                        if($check!=NULL){
                            $status = 'claimed';
                        }
                        $insert = new HRPayrollMonths();
                        $insert->payroll_list_id = $payroll_list_id;
                        $insert->payroll_id = $payroll_id;
                        $insert->user_id = $employee;
                        $insert->year = ($year-1);
                        $insert->month = $month1;
                        $insert->amount = 0;
                        $insert->option = 'unclaimed';
                        $insert->status = $status;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                    }
                }
            }

            $payroll_update_services->payrollUpdateInfo($payroll_id,$updated_by);
        }
        $response = array('result' => 'success');
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $data = array();
        $name_services = new NameServices;
        $code_services = new CodeServices;
        $decode = $code_services->decode($request->code,$request->payroll_id);
        $payroll_id = '';

        if($decode=='error'){
            return  response()->json($data);
        }

        $payroll_id = $request->payroll_id;
        $payroll = HRPayroll::where('payroll_id',$payroll_id)->first();

        if($payroll==NULL){
            return response()->json($data);
        }

        $payroll_id = $payroll->id;
        $query = HRPayrollList::with('allowance','deductions','months','unclaimeds','month_unclaimed')
                ->where('payroll_id',$payroll_id);
        $query = $query->orderBy('fund_services_id','ASC')
            ->orderBy('lastname','ASC')
            ->orderBy('firstname','ASC')
            ->get()
            ->map(function($query) use ($name_services) {
                if($query->middlename_in_last=='Y'){
                    $name = $name_services->lastname_middlename_last($query->lastname,$query->firstname,$query->middlename,$query->extname);
                }else{
                    $name = $name_services->lastname($query->lastname,$query->firstname,$query->middlename,$query->extname);
                }
                return [
                    'id' => $query->id,
                    'name' => $name,
                    'position_shorten' => $query->position_shorten,
                    'salary' => number_format($query->salary,2),
                    'amount_base' => $query->amount_base==NULL ? '-' : number_format($query->amount_base,2),
                    'column_amount' => $query->column_amount==NULL ? '-' : number_format($query->column_amount,2),
                    'column_amount2' => $query->column_amount2==NULL ? '-' : number_format($query->column_amount2,2),
                    'earned' => number_format($query->earned,2),
                    'lwop' => $query->lwop==NULL ? '-' : number_format($query->lwop,2),
                    'deduction' => $query->deduction==NULL || $query->deduction<=0 ? '-' : number_format($query->deduction,2),
                    'netpay' => number_format($query->netpay,2),
                    'allowance' => $query->allowance,
                    'deductions' => $query->deductions,
                    'months' => $query->months,
                    'unclaimeds' => $query->unclaimeds,
                    'month_unclaimed' => $query->month_unclaimed
                ];
            })->toArray();

        if(count($query)>0){
            $x = 1;
            $deduction_list = HRPayrollDeduction::where('payroll_id',$payroll_id)->select('deduction_id')->groupBy('deduction_id')->pluck('deduction_id')->toArray();
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = '<button class="no-design text-require removeEmployeeModal"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-times"></span>
                                    </button>';
                $data_list['name'] = $r['name'];
                $data_list['position'] = $r['position_shorten'];
                $data_list['salary'] = '<span id="salary_'.$r['id'].'">'.$r['salary'].'</span>';
                $data_list['w_salary_amount'] = '<span class="text-primary" id="amount_base_'.$r['id'].'">'.$r['amount_base'].'</span>';
                $data_list['column_amount'] = '<span class="text-primary" id="column_amount_'.$r['id'].'">'.$r['column_amount'].'</span>';
                $data_list['column_amount2'] = '<span class="text-primary" id="column_amount2_'.$r['id'].'">'.$r['column_amount2'].'</span>';
                $data_list['earned'] = '<span class="text-primary" id="earned_'.$r['id'].'">'.$r['earned'].'</span>';
                $data_list['lwop'] = '<span id="lwop_'.$r['id'].'">'.$r['lwop'].'</span>';
                $data_list['deduction'] = '<button class="btn btn-danger btn-danger-scan btn-sm deductionModal"
                                            data-id="'.$r['id'].'">
                                            <span id="deduction_'.$r['id'].'">'.$r['deduction'].'</span></button>';
                $data_list['netpay'] = '<span class="text-success" id="netpay_'.$r['id'].'">'.$r['netpay'].'</span>';
                foreach($deduction_list as $list){
                    $data_list[$list] = '-';
                }
                if(count($r['allowance'])>0){
                    foreach($r['allowance'] as $allowance){
                        $data_list['allowance_'.$allowance->allowance_id] = number_format($allowance->amount,2);
                    }
                }
                if(count($r['deductions'])>0){
                    foreach($r['deductions'] as $deduction){
                        $data_list[$deduction->deduction_id] = number_format($deduction->amount,2);
                    }
                }
                if(count($r['months'])>0){
                    foreach($r['months'] as $month){
                        if($month->status=='unclaimed'){
                            $data_list[$month->month.'_month'] = '<input type="number" class="form-control month_input"
                                                                    data-id="'.$month->id.'"
                                                                    data-t="default"
                                                                    value="'.$month->amount.'" style="width:80px;">';
                        }else{
                            $data_list[$month->month.'_month'] = '';
                        }
                    }
                }
                if(count($r['unclaimeds'])>0){
                    foreach($r['unclaimeds'] as $month){
                        if($month->status=='unclaimed'){
                            $data_list[$month->month.'_unclaimed'] = '<input type="number" class="form-control month_input"
                                                                        data-id="'.$month->id.'"
                                                                        data-t="unclaimed"
                                                                        value="'.$month->amount.'" style="width:80px;">';
                        }else{
                            $data_list[$month->month.'_unclaimed'] = '';
                        }
                    }
                }
                if(count($r['month_unclaimed'])>0){
                    $hours = 0;
                    foreach($r['month_unclaimed'] as $month){
                        $hours += $month->amount;
                    }
                    $data_list['hours'] =  '<span id="hours_'.$r['id'].'">'.number_format($hours,2).'</span>';
                }
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $response_data = array('result' => 'error');

        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($response_data);
        }

        $user_access_level = $request->session()->get('user_access_level');
        $user_access_levels = array(1,2,3);

        // Check user access level
        if (!in_array($user_access_level, $user_access_levels)) {
            return  response()->json($response_data);
        }

        $code_services = new CodeServices;
        $decode = $code_services->decode($request->code,$request->id);

        if($decode=='error'){
            return  response()->json($response_data);
        }

        $id = $request->id;
        $user = Auth::user();
        $updated_by = $user->id;

        try{
            HRPayroll::where('payroll_id', $id)
                ->where('generate_option', 'partial')
                ->update([
                    'generate_option' => 'generate',
                    'generated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
            ]);
            return response()->json(['result' => 'success']);
        } catch (QueryException $e) {
            // Handle database query exceptions
            return $this->handleDatabaseError($e);
        } catch (PDOException $e) {
            // Handle PDO exceptions
            return $this->handleDatabaseError($e);
        } catch (Exception $e) {
            // Handle other exceptions
            return $this->handleOtherError($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyView(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $id = $request->id;
        $query = HRPayrollList::find($id);
        if ($query==NULL) {
            return view('layouts/error/404');
        }

        $data = array(
            'query' => $query
        );
        return view('hrims/payroll/view/removeEmployeeModal',$data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $data = array('result' => 'error');

        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($data);
        }

        $user_access_level = $request->session()->get('user_access_level');
        $user_access_levels = array(1,2,3);
        // Check user access level
        if (!in_array($user_access_level, $user_access_levels)) {
            return  response()->json($data);
        }

        $payroll_update_services = new PayrollUpdateServices;
        $result = 'error';
        $check = 1;
        $id = $request->id;

        $list = HRPayrollList::with('payroll')->where('id',$id)->first();
        if ($list==NULL) {
            return  response()->json($data);
        }

        $payroll_id = $list->payroll_id;
        $tracking_id = NULL;
        if($list->payroll){
            $tracking_id = $list->payroll->tracking_id;
        }

        $delete = HRPayrollAllowance::where('payroll_list_id', $id)->delete();
        $auto_increment = DB::update("ALTER TABLE `hr_payroll_allowance` AUTO_INCREMENT = 1;");
        $delete = HRPayrollDeduction::where('payroll_list_id', $id)->delete();
        $auto_increment = DB::update("ALTER TABLE `hr_payroll_deduction` AUTO_INCREMENT = 1;");
        $delete = HRPayrollMonths::where('payroll_list_id', $id)->delete();
        $auto_increment = DB::update("ALTER TABLE `hr_payroll_months` AUTO_INCREMENT = 1;");
        $delete = HRPayrollList::where('id', $id)->delete();
        $auto_increment = DB::update("ALTER TABLE `hr_payroll_list` AUTO_INCREMENT = 1;");

        $check = HRPayrollList::where('payroll_id',$payroll_id)->count();
        if($check==0){
            $delete = HRPayrollEmpStat::where('payroll_id', $payroll_id)->delete();
            $auto_increment = DB::update("ALTER TABLE `hr_payroll_emp_stat` AUTO_INCREMENT = 1;");
            $delete = HRPayrollFundSource::where('payroll_id', $payroll_id)->delete();
            $auto_increment = DB::update("ALTER TABLE `hr_payroll_fund_source` AUTO_INCREMENT = 1;");
            $delete = HRPayrollFundService::where('payroll_id', $payroll_id)->delete();
            $auto_increment = DB::update("ALTER TABLE `hr_payroll_fund_service` AUTO_INCREMENT = 1;");
            $delete = HRPayroll::where('id', $payroll_id)->delete();
            $auto_increment = DB::update("ALTER TABLE `hr_payroll` AUTO_INCREMENT = 1;");
            if($tracking_id){
                $delete = DTSDocsHistory::where('doc_id', $tracking_id)->delete();
                $auto_increment = DB::update("ALTER TABLE `dts_docs_history` AUTO_INCREMENT = 1;");
                $delete = DTSDocs::where('id', $tracking_id)->delete();
                $auto_increment = DB::update("ALTER TABLE `dts_docs` AUTO_INCREMENT = 1;");
            }

        }else{
            $user = Auth::user();
            $updated_by = $user->id;
            $payroll_update_services->payrollUpdateInfo($payroll_id,$updated_by);
        }
        $result = 'success';
        $response = array('result' => $result,
                         'check' => $check);
        return response()->json($response);
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function showValidateRequest($request)
    {
        $rules = [
            'payroll_id' => 'required|string'
        ];

        $customMessages = [
            'payroll_id.required' => 'Payroll ID is required',
            'payroll_id.string' => 'Payroll ID must be a string',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function storeValidateRequest($request)
    {
        $rules = [
            'id' => 'required|numeric',
            'val' => 'required|numeric'
        ];

        $customMessages = [
            'id.required' => 'ID is required',
            'id.numeric' => 'ID must be a number',
            'val.required' => 'Val is required',
            'val.numeric' => 'Val must be a number',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function idValidateRequest($request)
    {
        $rules = [
            'id' => 'required|numeric'
        ];

        $customMessages = [
            'id.required' => 'ID is required',
            'id.numeric' => 'ID must be a number'
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

     /**
     * Handle database errors during the transaction.
     *
     * @param Exception $e The exception object.
     * @return \Illuminate\Http\JsonResponse The JSON response with error details.
     */
    private function handleDatabaseError($e)
    {
        return response()->json(['result' => $e->getMessage()], 400);
    }

    /**
     * Handle other errors during the transaction.
     *
     * @param Exception $e The exception object.
     * @return \Illuminate\Http\JsonResponse The JSON response with error details.
     */
    private function handleOtherError($e)
    {
        return response()->json(['result' => $e->getMessage()], 500);
    }
}
