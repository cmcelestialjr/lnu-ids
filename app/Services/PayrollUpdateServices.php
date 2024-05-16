<?php
namespace App\Services;

use App\Models\DTSDocs;
use App\Models\HRAllowance;
use App\Models\HRDeduction;
use App\Models\HRDeductionEmployee;
use App\Models\HRPayroll;
use App\Models\HRPayrollAllowance;
use App\Models\HRPayrollDeduction;
use App\Models\HRPayrollList;
use App\Models\HRPayrollMonths;
use App\Models\HRPayrollType;
use App\Models\HRPayrollTypeGuideline;
use Illuminate\Support\Facades\Auth;

class PayrollUpdateServices
{
    public function updateLwop($id,$updated_by){
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
    public function updatePayrollList($id,$updated_by){
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
    public function getEarned($salary,$day_accu){
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
    public function getAllowance($id,$include_pera){
        $allowance = HRPayrollAllowance::where('payroll_list_id',$id);
        if($include_pera=='No'){
            $allowance = $allowance->where('allowance_id','>','1');
        }
        $allowance = $allowance->sum('amount');
        return $allowance;
    }
    public function getDeduction($id){
        $deduction = HRPayrollDeduction::where('payroll_list_id',$id)->sum('amount');
        return $deduction;
    }
    public function getPerSalary($salary){
        $day = round($salary/22,2);
        $hour = round($day/8,2);
        $minute = round($hour/60,2);
        $per_salary['day'] = $day;
        $per_salary['hour'] = $hour;
        $per_salary['minute'] = $minute;
        return $per_salary;
    }
    public function updatePagibig($user_id,$gov,$payroll_type,$emp_stat){
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
    public function updatePhilHealth($salary,$user_id,$gov,$year,$month,$payroll_type,$emp_stat,$duration,$option,$day_from,$day_to){
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
    public function updateGSIS($salary,$user_id,$gov,$year,$month,$payroll_type,$emp_stat,$duration,$option,$day_from,$day_to){
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
    public function getEarned1($year,$month,$salary,$gov,$duration,$option,$day_from,$day_to){
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
    public function getAllowance1($emp_stat,$payroll_type,$include_pera){
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
    public function getDeduction1($emp_stat,$payroll_type,$user_id){
        $deduction = HRDeductionEmployee::where('payroll_type_id',$payroll_type)
                            ->where('emp_stat_id',$emp_stat)
                            ->where('user_id',$user_id)->sum('amount');
        return $deduction;
    }
    public function getDays($day_from,$day_to,$year,$month,$option,$duration,$gov){
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
    public function insertEmployeeAllowance($emp_stat,$payroll_type,$include_pera,$gov,$payroll_list_id,$payroll_id,$employee,$updated_by){
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
    public function insertEmployeeDeduction($emp_stat,$payroll_type,$payroll_list_id,$payroll_id,$employee,$updated_by){
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
    public function payrollUpdateInfo($payroll_id,$updated_by){
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

        $update = DTSDocs::find($payroll->tracking_id);
        $update->particulars = $etal.' OB: '.$ob.' DV: '.$dv;
        $update->description = $particulars;
        $update->amount = $gross;
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
