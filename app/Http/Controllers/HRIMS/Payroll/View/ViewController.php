<?php

namespace App\Http\Controllers\HRIMS\Payroll\View;
use App\Http\Controllers\Controller;
use App\Models\_Work;
use App\Models\HRAllowance;
use App\Models\HRDeduction;
use App\Models\HRDeductionEmployee;
use App\Models\HRDeductionGroup;
use App\Models\HRPayroll;
use App\Models\HRPayrollAllowance;
use App\Models\HRPayrollDeduction;
use App\Models\HRPayrollEmpStat;
use App\Models\HRPayrollFundSource;
use App\Models\HRPayrollList;
use App\Models\HRPayrollMonths;
use App\Models\HRPayrollType;
use App\Models\HRPayrollTypeGuideline;
use App\Models\Tracking;
use App\Models\Users;
use App\Services\CodeServices;
use App\Services\NameServices;
use App\Services\TrackingServices;
use App\Services\WorkServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ViewController extends Controller
{
    public function table(Request $request){
        return $this->_table($request);
    }
    public function view(Request $request){
        return $this->_view($request);
    }
    public function payroll_table(Request $request){
        return $this->_payroll_table($request);
    }
    public function deductionModal(Request $request){
        return $this->_deductionModal($request);
    }
    public function deductionModalTable(Request $request){
        return $this->_deductionModalTable($request);
    }
    public function deductionModalInput(Request $request){
        return $this->_deductionModalInput($request);
    }
    public function allowanceModalTable(Request $request){
        return $this->_allowanceModalTable($request);
    }
    public function allowanceModalCheck(Request $request){
        return $this->_allowanceModalCheck($request);
    }
    public function lwopModalInput(Request $request){
        return $this->_lwopModalInput($request);
    }
    public function monthInput(Request $request){
        return $this->_monthInput($request);
    }
    public function salaryChange(Request $request){
        return $this->_salaryChange($request);
    }
    public function addEmployeeSubmit(Request $request){
        return $this->_addEmployeeSubmit($request);
    }
    public function removeEmployeeModal(Request $request){
        return $this->_removeEmployeeModal($request);
    }
    public function removeEmployeeModalSubmit(Request $request){
        return $this->_removeEmployeeModalSubmit($request);
    }
    public function generatePayroll(Request $request){
        return $this->_generatePayroll($request);
    }
    private function _table($request){
        $user_access_level = $request->session()->get('user_access_level');
        $name_services = new NameServices;
        $data = array();

        $validator = Validator::make($request->all(), [
            'payroll_type' => 'required',
            'by' => 'required|string',
            'year' => 'required|integer',
            'month' => 'required|string',
            'type' => 'required|string'
        ]);

        if($validator->fails()){
            return  response()->json($data);
        }

        $payroll_type = $request->payroll_type;
        $by = $request->by;
        $year = $request->year;
        $month = $request->month;        
        $type = $request->type;
        
        $query = HRPayroll::with('emp_stat')
            ->where('generate_option',$type)
            ->where('year',$year);            
        if($payroll_type!='All'){
            $query = $query->where('payroll_type_id',$payroll_type);
        }
        if($by=='month'){
            $query = $query->where('month',$month);
        }
        $query = $query->get()
            ->map(function($query) use ($name_services) {
                $payroll_id = $query->id;
                foreach($query->emp_stat as $row){
                    $emp_stats[] = $row->gov;
                }
                return [
                    'id' => $query->id,
                    'payroll_id' => $query->payroll_id,
                    'payroll_type_id' => $query->payroll_type_id,
                    'option' => $query->option_id,
                    'etal' => $query->etal,
                    'payroll_type' => $query->name.'<br>'.$query->period,
                    'amount' => 'OB:'.$query->ob.'<br>DV:'.$query->dv,
                    'emp_stats' => $emp_stats,
                    'year' => $query->year,
                    'month' => $query->month,
                    'day_from' => $query->day_from,
                    'day_to' => $query->day_to,
                    'generated_at' => $query->generated_at,
                ];
            })->toArray();

        if(count($query)>0){
            $x = 1;
            $code_services = new CodeServices;
            foreach($query as $r){       
                $payroll_id = $r['payroll_id'];         
                $encoded = $code_services->encode($payroll_id).'1';                
                $data_list['f1'] = $x;
                $data_list['f2'] = '<form action="'.url('hrims/payroll/view/'.$payroll_id.'/'.$encoded).'" method="GET" target="_blank">
                                        <button type="submit" class="btn btn-primary btn-primary-scan">
                                        '.$r['payroll_id'].'</button>
                                    </form>';
                $data_list['f3'] = $r['etal'];
                $data_list['f4'] = $r['payroll_type'];
                $data_list['f5'] = $r['amount'];

                if($r['payroll_type_id']==1 && $r['option']==1 && in_array('Y', $r['emp_stats'])){
                    $bank = 'Period: 1-5 - Date: '.$r['generated_at'].'<br>
                             Period: 16-'.$r['day_to'].' - ';
                }else{
                    $bank = 'Period: '.$r['day_from'].'-'.$r['day_to'].' - ';
                }

                if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
                    $data_list['f6'] = '<button class="btn btn-primary btn-primary-scan btn-xs bank"
                                            data-id="'.$r['id'].'">
                                            '.$bank.'
                                        </button>';
                }else{
                    $data_list['f6'] = $bank.' '.$r['generated_at'];
                }

                $data_list['f6'] = $bank.' Date: '.date('M d, Y h:ia',strtotime($r['generated_at']));

                $data_list['f7'] = '<button class="btn btn-danger btn-danger-scan btn-xs delete"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-trash"></span>
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    private function _payroll_table($request){
        $name_services = new NameServices;
        $data = array();

        $validator = Validator::make($request->all(), [
            'payroll_id' => 'required|string'
        ]);

        if($validator->fails()){
            return  response()->json('error');
        }

        $code_services = new CodeServices;
        $decode = $code_services->decode($request->code,$request->payroll_id);
        $payroll_id = '';
        if($decode=='error'){
            return  response()->json('error');
        }
        $payroll_id = $request->payroll_id;
        $payroll = HRPayroll::where('payroll_id',$payroll_id)->first();
        
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
    private function _view($request){
        $code_services = new CodeServices;
        $decode = $code_services->decode($request->encoded,$request->payroll_id);
        $payroll_id = '';
        if($decode=='success'){
            $payroll_id = $request->payroll_id;
        }
        $query = HRPayroll::with('payroll_type','months','unclaimeds')->where('payroll_id',$payroll_id)->first();
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
    private function _deductionModal($request){
        $id = $request->id;
        $query = HRPayrollList::find($id);
        $salaries = _Work::where('user_id',$query->user_id)
            ->where('emp_stat_id',$query->emp_stat_id)
            ->groupBy('salary')
            ->pluck('salary')
            ->toArray();
        $allowance = $query->allowance; 
        $deductions = $query->deductions; 
        $per_salary = $this->getPerSalary($query->salary);
        $data = array(
            'query' => $query,
            'salaries' => $salaries,
            'allowance' => $allowance,
            'deductions' => $deductions,
            'per_salary' => $per_salary
        );
        return view('hrims/payroll/view/deductionModal',$data);
    }
    private function _removeEmployeeModal($request){
        $id = $request->id;
        $query = HRPayrollList::find($id);
        $data = array(
            'query' => $query
        );
        return view('hrims/payroll/view/removeEmployeeModal',$data);
    }
    private function _deductionModalTable($request){
        $data = array();
        $id = $request->id; // Get ID from request
        $employee = HRPayrollList::find($id);
        $emp_stat = $employee->emp_stat_id;
        $query = HRDeduction::orderBy('group_id')
                ->whereHas('emp_stat', function ($query) use ($emp_stat) {
                    $query->where('emp_stat_id',$emp_stat);
                })
                ->orderBY('group_id')
                ->orderBy('id')
                ->get()
                ->map(function($query) use ($id,$employee){
                    $employee_deduction = HRPayrollDeduction::where('payroll_list_id',$id)
                        ->where('deduction_id',$query->id)
                        ->first();
                    $employee_deduction_main = HRDeductionEmployee::where('user_id',$employee->user_id)
                        ->where('payroll_type_id',$employee->payroll->payroll_type_id)
                        ->where('emp_stat_id',$employee->emp_stat_id)
                        ->where('deduction_id',$query->id)
                        ->first();
                    $group = $query->group_id ? $query->group->name : '';
                    $amount = NULL;
                    $date_from = NULL;
                    $date_to = NULL;
                    $docs = NULL;
                    $remarks = NULL;                    
                    if($employee_deduction){
                        $amount = $employee_deduction->amount;                        
                    }
                    if($employee_deduction_main){
                        $date_from = $employee_deduction_main->date_from;
                        $date_to = $employee_deduction_main->date_to;
                        $remarks = $employee_deduction_main->remarks;
                        $docs = $employee_deduction_main->docs;
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
                $docs = '';
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
                    data-did="'.$r['id'].'"
                    value="'.$r['amount'].'">';
                $data_list['f5'] = $r['date_from'];
                $data_list['f6'] = $r['date_to'];
                $data_list['f7'] = $docs;
                $data_list['f8'] = $r['remarks'];
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    private function _deductionModalInput($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        $values = NULL;
        
        // Check user access level
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $did = $request->did;
            $amount = $request->amount;

            $query = HRPayrollList::find($id);

            if($query){
                $user = Auth::user();
                $updated_by = $user->id;
                
                if($amount>0){
                    $percent = NULL;
                    $percent_employer = NULL;
                    $ceiling = NULL;

                    $deduction = HRDeductionEmployee::where('user_id',$query->user_id)
                        ->where('deduction_id',$did)
                        ->where('payroll_type_id',$query->payroll->payroll_type_id)
                        ->where('emp_stat_id',$query->emp_stat_id)
                        ->first();               

                    if($deduction){
                        $percent = $deduction->percent;
                        $percent_employer = $deduction->percent_employer;
                        $ceiling = $deduction->ceiling;
                    }
                    // Update or create the deduction employee record
                    HRPayrollDeduction::updateOrCreate(
                        [
                            'payroll_list_id' => $id,
                            'deduction_id' => $did,
                        ],
                        [
                            'payroll_id' => $query->payroll_id,
                            'user_id' => $query->user_id,
                            'amount' => $amount,
                            'percent' => $percent,
                            'percent_employer' => $percent_employer,
                            'ceiling' => $ceiling,
                            'updated_by' => $updated_by,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]
                    );
                }else{
                    $delete = HRPayrollDeduction::where('payroll_list_id', $id)
                        ->where('deduction_id',$did)->delete();                    
                    $auto_increment = DB::update("ALTER TABLE `hr_payroll_deduction` AUTO_INCREMENT = 0;");
                }

                $values = $this->updatePayrollList($id,$updated_by);

                $result = 'success';
            }
        }
        
        $response = array('result' => $result, 'values' => $values);
        return response()->json($response);

    }
    private function _allowanceModalTable($request){
        $data = array();
        $id = $request->id;
        $payroll = HRPayrollList::find($id);
        $payroll_type = $payroll->payroll->payroll_type_id;
        $emp_stat = $payroll->emp_stat_id;
        $query = HRAllowance::
            whereHas('payroll_type', function ($query) use ($payroll_type) {
                    $query->where('payroll_type_id',$payroll_type);
            })
            ->whereHas('emp_stat', function ($query) use ($emp_stat) {
                $query->where('emp_stat_id',$emp_stat);
            });
        if($payroll->payroll->include_pera=='No'){
            $query = $query->where('id','>',1);
        }
        $query = $query->get()
            ->map(function($query) use ($id){
                $check = HRPayrollAllowance::where('payroll_list_id',$id)
                    ->where('allowance_id',$query->id)
                    ->first();
                $checked = '';
                if($check){
                    $checked = 'checked';
                }
                return [
                    'id' => $query->id,
                    'name' => $query->name,
                    'amount' => $query->amount,
                    'checked' => $checked
                ];
            })->toArray();
            if(count($query)>0){
                $x = 1;
                foreach($query as $r){
                    $data_list['f1'] = $x;
                    $data_list['f2'] = $r['name'];
                    $data_list['f3'] = $r['amount'];
                    $data_list['f4'] = '<input type="checkbox" class="form-control allowance" data-id="'.$r['id'].'" '.$r['checked'].'>';
                    array_push($data,$data_list);
                    $x++;
                }
            }
        return  response()->json($data);
    }
    private function _allowanceModalCheck($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        $values = NULL;
        
        // Check user access level
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $aid = $request->aid;
            $check = $request->check;

            $query = HRPayrollList::find($id);

            $user = Auth::user();
            $updated_by = $user->id;
            
            if($check=='yes' && $query!=NULL){
                $allowance = HRAllowance::find($aid);

                // Update or create the deduction employee record
                HRPayrollAllowance::updateOrCreate(
                    [
                        'payroll_list_id' => $id,
                        'allowance_id' => $aid,
                    ],
                    [
                        'payroll_id' => $query->payroll_id,
                        'user_id' => $query->user_id,
                        'amount' => $allowance->amount,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]
                );
            }else{
                $delete = HRPayrollAllowance::where('payroll_list_id', $id)
                    ->where('allowance_id',$aid)->delete();                    
                $auto_increment = DB::update("ALTER TABLE `hr_payroll_allowance` AUTO_INCREMENT = 0;");
            }
            $values = $this->updatePayrollList($id,$updated_by);
            $result = 'success';
        }
        $response = array('result' => $result, 'values' => $values);
        return response()->json($response);
    }
    private function _lwopModalInput($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        $values = NULL;
        $lwop = NULL;
        // Check user access level
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $n = $request->n;
            $val = $request->val;
            if($val==NULL || $val<=0){
                $val = 0;
            }
            if($n=='day_accu'){
                if($val>=22){
                    $val = 22;
                }
            }

            $user = Auth::user();
            $updated_by = $user->id;
            
            HRPayrollList::where('id', $id)
                ->update([
                    $n => $val,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $lwop = $this->updateLwop($id,$updated_by);
            $values = $this->updatePayrollList($id,$updated_by);
            $result = 'success';
            
        }
        $response = array('result' => $result,
                            'lwop' => $lwop,
                            'values' => $values);
        return response()->json($response);
    }
    private function _monthInput($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        $values = NULL;
        $payroll_list_id = '';
        // Check user access level
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $val = $request->val;
            if($val==NULL || $val<=0){
                $val = 0;
            }

            $user = Auth::user();
            $updated_by = $user->id;
            
            HRPayrollMonths::where('id', $id)
                ->where('status','unclaimed')
                ->update([
                    'amount' => $val,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $query = HRPayrollMonths::find($id);
            $payroll_list_id = $query->payroll_list_id;
            $values = $this->updatePayrollList($payroll_list_id,$updated_by);
            $result = 'success';
            
        }
        $response = array('result' => $result,
                            'values' => $values);
        return response()->json($response);
    }
    private function _salaryChange($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        $values = NULL;
        // Check user access level
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $val = $request->val;

            $user = Auth::user();
            $updated_by = $user->id;
            
            HRPayrollList::where('id', $id)
                ->update([
                    'salary' => $val,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $query = HRPayrollList::find($id);
            $payroll_type = $query->payroll->payroll_type_id;
            if($payroll_type==1){
                HRPayrollList::where('id', $id)
                    ->update([
                        'amount_base' => $val,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            $values = $this->updatePayrollList($id,$updated_by);
            $result = 'success';
            
        }
        $response = array('result' => $result,
                            'values' => $values);
        return response()->json($response);
    }
    private function _addEmployeeSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        // Check user access level
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){            
            $code_services = new CodeServices;
            $work_services = new WorkServices;

            $decode = $code_services->decode($request->code,$request->id);
            if($decode=='error'){
                return  response()->json(['result' => $result]);
            }
            $payroll_id = $request->id;
            $employee = $request->val;

            $user = Auth::user();
            $updated_by = $user->id;
            
            $payroll = HRPayroll::where('payroll_id',$payroll_id)->first();
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
            $gov_service = $payroll_type_query->gov_service;
            $grant_separated = $payroll_type_query->grant_separated;

            $info = Users::where('id',$employee)->first();
            $getWork = _Work::where('user_id',$employee)
                ->whereIn('emp_stat_id',$emp_stats)
                ->whereIn('fund_source_id',$fund_sources)
                ->orderBy('date_from','DESC')->first();
            $emp_stat = $getWork->emp_stat_id;
            $gov = $getWork->emp_stat->gov;
            $salary = $getWork->salary;
            $include = 'Y';
            $w_salary_amount = 0;
            $column_amount = 0;
            $column_amount2 = 0;
            if($payroll_type==1){    
                if($emp_stat!=5){
                    $this->updatePhilHealth($salary,$employee,$gov,$year,$month,$payroll_type,$emp_stat,$duration,$option,$day_from,$day_to);
                    $this->updatePagibig($employee,$gov,$payroll_type,$emp_stat);
                    if($gov=='Y'){
                        $this->updateGSIS($salary,$employee,$gov,$year,$month,$payroll_type,$emp_stat,$duration,$option,$day_from,$day_to);
                    }
                }                        
                $earned = $this->getEarned1($year,$month,$salary,$gov,$duration,$option,$day_from,$day_to);
                $amount_base = $salary;
            }elseif($payroll_type==2){
                $amount = HRAllowance::find(1)->amount;
                $amount_base = $amount;
                $earned = $this->getEarned1($year,$month,$amount,$gov,$duration,$option,$day_from,$day_to);
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
                $getDays = $this->getDays($day_from,$day_to,$year,$month,$option,$duration,$gov);
                $weekdays = $getDays['weekdays'];
                        
                $allowance = $this->getAllowance1($emp_stat,$payroll_type,$include_pera);
                $deduction = $this->getDeduction1($emp_stat,$payroll_type,$employee);
                $earned = $earned;
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
                
                $this->insertEmployeeAllowance($emp_stat,$payroll_type,$include_pera,$gov,$payroll_list_id,$payroll_id,$employee,$updated_by);
                $this->insertEmployeeDeduction($emp_stat,$payroll_type,$payroll_list_id,$payroll_id,$employee,$updated_by);

                if($emp_stat==5 && $payroll_type==1){
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
                
                $this->payrollUpdateInfo($payroll_id,$updated_by);
            }
            $result = 'success';
            
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    private function _removeEmployeeModalSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        $check = 1;
        // Check user access level
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $list = HRPayrollList::find($id);
            $payroll_id = $list->payroll_id;

            $delete = HRPayrollAllowance::where('payroll_list_id', $id)->delete();
            $auto_increment = DB::update("ALTER TABLE `hr_payroll_allowance` AUTO_INCREMENT = 0;");
            $delete = HRPayrollDeduction::where('payroll_list_id', $id)->delete();
            $auto_increment = DB::update("ALTER TABLE `hr_payroll_deduction` AUTO_INCREMENT = 0;");
            $delete = HRPayrollMonths::where('payroll_list_id', $id)->delete();
            $auto_increment = DB::update("ALTER TABLE `hr_payroll_months` AUTO_INCREMENT = 0;");
            $delete = HRPayrollList::where('id', $id)->delete();
            $auto_increment = DB::update("ALTER TABLE `hr_payroll_list` AUTO_INCREMENT = 0;");

            $check = HRPayrollList::where('payroll_id',$payroll_id)->count();
            if($check==0){
                $delete = HRPayrollEmpStat::where('payroll_id', $payroll_id)->delete();
                $auto_increment = DB::update("ALTER TABLE `hr_payroll_emp_stat` AUTO_INCREMENT = 0;");
                $delete = HRPayrollFundSource::where('payroll_id', $payroll_id)->delete();
                $auto_increment = DB::update("ALTER TABLE `hr_payroll_fund_source` AUTO_INCREMENT = 0;");
                $delete = HRPayroll::where('id', $payroll_id)->delete();
                $auto_increment = DB::update("ALTER TABLE `hr_payroll` AUTO_INCREMENT = 0;");
            }
            $user = Auth::user();
            $updated_by = $user->id;
            $this->payrollUpdateInfo($payroll_id,$updated_by);
            $result = 'success';
        }
        $response = array('result' => $result,
                         'check' => $check);
        return response()->json($response);
    }
    private function _generatePayroll($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        $check = 1;
        // Check user access level
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $code_services = new CodeServices;
            $decode = $code_services->decode($request->code,$request->id);

            if($decode=='error'){
                return  response()->json(['result' => $result]);
            }

            $id = $request->id;
            $user = Auth::user();
            $updated_by = $user->id;

            HRPayroll::where('payroll_id', $id)
                ->where('generate_option', 'partial')
                ->update([
                    'generate_option' => 'generate',
                    'generated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $result = 'success';
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    private function updateLwop($id,$updated_by){
        $query = HRPayrollList::find($id);
        $gov = $query->emp_stat->gov;
        $lwop = 0;            
        if($gov=='N'){
            $getPerSalary = $this->getPerSalary($query->salary);
            $lwop_day = round(($getPerSalary['day']*$query->lwop_day),2);
            $lwop_hour = round(($getPerSalary['hour']*$query->lwop_hour),2);
            $lwop_minute = round(($getPerSalary['minute']*$query->lwop_minute),2);
            $lwop = $lwop_day+$lwop_hour+$lwop_minute;
            
            HRPayrollList::where('id', $id)
                ->update([
                    'lwop' => $lwop,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        $values['gov'] = $gov;
        $values['lwop'] = $lwop;
        return $values;
    }
    private function updatePayrollList($id,$updated_by){
        $work_services = new WorkServices;
        $query = HRPayrollList::find($id);
        $payroll_type = $query->payroll->payroll_type_id;
        $payroll_id = $query->payroll_id;
        $payroll = HRPayrollType::with('guideline')->where('id',$payroll_type)->first();
        $gov_service = $payroll->gov_service;
        $grant_separated = $payroll->grant_separated;
        $user_id = $query->user_id;
        $salary = $query->salary;
        $day_accu = $query->day_accu;
        $include_pera = $query->payroll->include_pera;
        $lwop = $query->lwop;
        $hours = 0;
        $w_salary_amount = 0;
        $column_amount = 0;
        $column_amount2 = 0;
        $include = 'Y';
        if($payroll_type==1){
            if($query->emp_stat_id==5){
                $hours = HRPayrollMonths::where('payroll_list_id',$id)->sum('amount');
                $earned = round(($query->salary*$hours),2);
            }else{
                $earned = $this->getEarned($salary,$day_accu);
            }
            
        }elseif($payroll_type==2){
            $amount = $query->amount_base;
            $earned = $this->getEarned($amount,$day_accu);
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
            HRPayrollList::where('id', $id)
                    ->update([
                        'amount_base' => $w_salary_amount
                    ]);
        }
        $allowance = $this->getAllowance($id,$include_pera);
        $gross = round(($earned+$allowance-$lwop),2);
        $getDeduction = $this->getDeduction($id);
        $deduction = round(($getDeduction+$lwop),2);
        $netpay = round(($earned+$allowance-$deduction),2);

        HRPayrollList::where('id', $id)
                    ->update([
                        'column_amount' => $column_amount,
                        'column_amount2' => $column_amount2,
                        'earned' => $earned,
                        'allowances' => $allowance,
                        'gross' => $gross,
                        'deduction' => $deduction,
                        'netpay' => $netpay,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
        $this->payrollUpdateInfo($payroll_id,$updated_by);
        $values['salary'] = $salary;
        $values['amount_base'] = $w_salary_amount;
        $values['column_amount'] = $column_amount;
        $values['column_amount2'] = $column_amount2;
        $values['earned'] = $earned;
        $values['allowance'] = $allowance;
        $values['deduction'] = $deduction;
        $values['netpay'] = $netpay;
        $values['lwop'] = $lwop;
        $values['hours'] = $hours;
        $values['list_id'] = $id;
        return $values;
    }
    private function getEarned($salary,$day_accu){
        $per_salary = $this->getPerSalary($salary);
        if($day_accu==11){
            $earned = round(($salary/2),2);
        }elseif($day_accu>=22){
            $earned = round(($salary),2);
        }else{
            $earned = round(($per_salary['day']*$day_accu),2);
        }
        return $earned;
    }
    private function getAllowance($id,$include_pera){
        $allowance = HRPayrollAllowance::where('payroll_list_id',$id);
        if($include_pera=='No'){
            $allowance = $allowance->where('allowance_id','>','1');
        }
        $allowance = $allowance->sum('amount');
        return $allowance;
    }
    private function getDeduction($id){
        $deduction = HRPayrollDeduction::where('payroll_list_id',$id)->sum('amount');
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
    private function updatePagibig($user_id,$gov,$payroll_type,$emp_stat){
        $check = HRDeduction::where('id',27)
            ->whereHas('emp_stat', function ($query) use ($emp_stat) {
                $query->where('emp_stat_id',$emp_stat);
            })->first();
        if($check){
            $user = Auth::user();
            $updated_by = $user->id;
            if($gov=='N'){
                $amount = 200;
                $amount_employer = 0;
            }else{
                $amount = 100;
                $amount_employer = $check->amount;
            }
            $query = HRDeductionEmployee::firstOrCreate(
                [
                    'user_id' => $user_id,
                    'payroll_type_id' => $payroll_type,
                    'emp_stat_id' => $emp_stat,
                    'deduction_id' => 27,
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
        $checkPhilHealth = HRDeduction::where('id',34)
            ->whereHas('emp_stat', function ($query) use ($emp_stat) {
                $query->where('emp_stat_id',$emp_stat);
            })->first();
        if($checkPhilHealth){
            $user = Auth::user();
            $updated_by = $user->id;
            $getPhilHealth = HRDeduction::where('id',34)->first();
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
                    'deduction_id' => 34,
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
        $checkGSIS = HRDeduction::where('id',1)
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

            $getGSIS = HRDeduction::where('id',1)->first();
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
                    'deduction_id' => 1,
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
    private function getEarned1($year,$month,$salary,$gov,$duration,$option,$day_from,$day_to){
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
    private function getAllowance1($emp_stat,$payroll_type,$include_pera){
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
    private function getDeduction1($emp_stat,$payroll_type,$user_id){
        $deduction = HRDeductionEmployee::where('payroll_type_id',$payroll_type)
                            ->where('emp_stat_id',$emp_stat)
                            ->where('user_id',$user_id)->sum('amount');
        return $deduction;
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
    private function payrollUpdateInfo($payroll_id,$updated_by){
        $name_services = new NameServices;
        $payroll = HRPayroll::with('months','emp_stat')->where('id',$payroll_id)->first();
        $list = HRPayrollList::with('employee.personal_info')
            ->where('payroll_id', $payroll_id);
        $gross = $list->sum('gross');
        $netpay = $list->sum('netpay');
        $count = $list->get()->count();
        $get_et_al = $list->orderBy('fund_services_id','ASC')
            ->orderBy('lastname','ASC')
            ->orderBy('firstname','ASC')
            ->first();                
        $ob = ($gross) == null ? '' : number_format($gross,2);
        $dv = ($netpay) == null ? '' : number_format($netpay,2);
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
        foreach($payroll->emp_stat as $row){
            $emp_stats[] = $row->gov;
        }
        if(in_array(5,$emp_stats) && $payroll->payroll_type_id==1 && count($payroll->months)>0){
            $payroll_name = $payroll->name.' (Part-Time)';
        }else{
            $payroll_name = $payroll->name;
        }
        $period = date('M',strtotime($payroll->year.'-'.$payroll->month.'-01')).' '.$payroll->day_from.'-'.$payroll->day_to.', '.$payroll->year;
        $particulars = $payroll_name.' '.$period;  

        $update = Tracking::find($payroll->tracking_id);
        $update->subject = $etal.' OB: '.$ob.' DV: '.$dv;
        $update->particulars = $particulars;
        $update->updated_by = $updated_by;
        $update->save();

        $update = HRPayroll::find($payroll_id); 
        $update->name = $payroll_name;
        $update->period = $period;
        $update->etal = $etal;
        $update->ob = $gross;
        $update->dv = $netpay;
        $update->save();
    }
}