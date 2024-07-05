<?php

namespace App\Http\Controllers\HRIMS\Payroll;
use App\Http\Controllers\Controller;
use App\Models\AccAccountTitle;
use App\Models\AccAccountTitleAllowance;
use App\Models\AccAccountTitleDeduction;
use App\Models\AccAccountTitlePayroll;
use App\Models\HRAllowance;
use App\Models\HRDeduction;
use App\Models\HRDeductionGroup;
use App\Models\HRPayroll;
use App\Models\HRPayrollAllowance;
use App\Models\HRPayrollDeduction;
use App\Models\HRPayrollDuration;
use App\Models\HRPayrollList;
use App\Models\Signatory;
use App\Services\CodeServices;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;

class PayrollPrintController extends Controller
{
    public function src($pdf_option){
        $exp = explode(': ',$pdf_option);
        $result = 'error';
        $src = '';
        if(isset($exp[1]) && isset($exp[2])){
            $payroll_id = str_replace(' Code','',$exp[1]);
            $code = $exp[2];
            $code_services = new CodeServices;
            $decode = $code_services->decode($code,$payroll_id);

            if($decode=='error'){
                $payroll_id = '';
                return  response()->json('error');
            }

            $payroll = HRPayroll::with('fund_source.fund_source','emp_stat.emp_stat','payroll_type','months','account_title')->where('payroll_id',$payroll_id)->first();
            if($payroll){
                $src = $this->qr($payroll,$pdf_option);
                $result = 'success';
            }
        }
        $response = array('result' => $result,
                          'src' => $src);
        return $response;
    }
    private function qr($payroll,$pdf_option){
        error_reporting(E_ERROR);
        $payroll_id = $payroll->payroll_id;
        $year = $payroll->year;
        $month = $payroll->month;
        $image = QrCode::format('png')
                    ->merge(public_path('assets\images\logo\lnu_logo.png'), .28, true)
                    ->style('round', 0.2)
                    //->eye('circle')
                    ->eyeColor(1, /*outer*/ 0, 0, 128, /*inner*/ 212,175,55, 0, 0)
                    ->eyeColor(2, /*outer*/ 212,175,55, /*inner*/ 0, 0, 128, 0, 0)
                    ->size(300)
                    ->errorCorrection('H')
                    ->generate('pdf/view/'.$pdf_option);
        $imageName = $payroll_id.'_payroll.png';
        $path = 'storage\hrims\payroll/'.$year.'/'.$month.'/'.$payroll_id.'/';
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
        $file = public_path($path . $imageName);
        file_put_contents($file, $image);
        $qrcode = $path.$imageName;
        $src = $this->pdf($payroll,$qrcode);
        return $src;
    }
    private function pdf($payroll,$qrcode){
        $name_services = new NameServices;
        $src = '';
        $payroll_id = $payroll->payroll_id;
        $payroll_id_id = $payroll->id;
        $year = $payroll->year;
        $month = $payroll->month;

        // $show = HRDeduction::whereIn('id',[36])->get();
        // foreach($show as $row1){
        //     $insert = new AccAccountTitleDeduction();
        //     $insert->account_title_id = 385;
        //     $insert->deduction_id = $row1->id;
        //     $insert->updated_by = 1;
        //     $insert->save();
        // }

        $signatories = Signatory::with('signatory.employee_default.designation')
            ->where('system_shorten','HRIMS')
            ->where('type','payroll')
            ->get();
        $deduction_group = HRDeductionGroup::whereHas('deduction', function ($query) use ($payroll_id_id) {
                $query->whereHas('payroll_deduction', function ($query) use ($payroll_id_id) {
                    $query->where('payroll_id',$payroll_id_id);
                });
            })->orderBy('id','ASC')->get();
        $group_count = HRDeduction::whereHas('payroll_deduction', function ($query) use ($payroll_id_id) {
                $query->where('payroll_id',$payroll_id_id);
            })
            ->selectRaw('group_id, COUNT(*) as count')
            ->groupBy('group_id')
            ->get();
        $allowance_list = HRAllowance::whereHas('payroll_allowance', function ($query) use ($payroll_id_id) {
                $query->where('payroll_id',$payroll_id_id);
            })->orderBy('id','ASC')->get();
        $allowance_all = HRPayrollAllowance::with('list')
            ->where('payroll_id',$payroll_id_id)->get();
        $deduction_all = HRPayrollDeduction::with('list')
            ->where('payroll_id',$payroll_id_id)->get();
        $deduction_list = HRDeduction::whereHas('payroll_deduction', function ($query) use ($payroll_id_id) {
                $query->where('payroll_id',$payroll_id_id);
            })->whereNotNull('group_id')
            ->orderBy('group_id','ASC')
            ->orderBy('id','ASC')->get();
        $deduction_other = HRDeduction::whereHas('payroll_deduction', function ($query) use ($payroll_id_id) {
                $query->where('payroll_id',$payroll_id_id);
            })->whereNull('group_id')
            ->orderBy('id','ASC')->get();
        $allowance_ids = HRAllowance::whereHas('payroll_allowance', function ($query) use ($payroll_id_id) {
                $query->where('payroll_id',$payroll_id_id);
            })->orderBy('id','ASC')->pluck('id')->toArray();
        $fund_services_query = HRPayrollList::with('fund_services')
            ->select('fund_services_id')
            ->where('payroll_id',$payroll_id_id)
            ->groupBY('fund_services_id')
            ->orderBy('fund_services_id','ASC');
        $fund_services = $fund_services_query->get();
        $fund_services_first = $fund_services_query->first();
        $account_title_payroll = AccAccountTitlePayroll::with('account_title','payroll_type')->where('payroll_type_id',$payroll->payroll_type_id)
            ->whereHas('emp_stat', function ($query) use ($payroll) {
                $emp_stat_ids = [];
                if($payroll->emp_stat){
                    foreach($payroll->emp_stat as $emp_stat){
                        $emp_stat_ids[] = $emp_stat->emp_stat_id;
                    }
                }
                $query->where('emp_stat_id',$emp_stat_ids);
            })
            ->get();
        $account_title_allowance = AccAccountTitle::with('allowance')
            ->whereHas('allowance', function ($query) use ($allowance_ids) {
                $query->whereIn('allowance_id',$allowance_ids);
            })->get();
        $account_title_deduction = AccAccountTitle::with('deduction')
            ->whereHas('deduction.payroll_deductions', function ($query) use ($payroll_id_id) {
                $query->where('payroll_id',$payroll_id_id);
            })->orderBy('id','ASC')->get();
        $payroll_list = HRPayrollList::with('allowance','deductions','months','unclaimeds','month_unclaimed')
            ->where('payroll_id',$payroll_id_id)
            ->orderBy('fund_services_id','ASC')
            ->orderBy('lastname','ASC')
            ->orderBy('firstname','ASC')
            ->get()
            ->map(function($query) use ($name_services) {
                if($query->middlename_in_last=='Y'){
                    $name = $name_services->lastname_middlename_last($query->lastname,$query->firstname,$query->middlename,$query->extname);
                }else{
                    $name = $name_services->lastname($query->lastname,$query->firstname,$query->middlename,$query->extname);
                }
                $months = [];
                if(count($query->months)>0){
                    foreach($query->months as $row){
                        $months[$row->month] = $row->amount;
                    }
                }
                $unclaimeds = [];
                if(count($query->unclaimeds)>0){
                    foreach($query->unclaimeds as $row){
                        $unclaimeds[$row->month] = $row->amount;
                    }
                }
                $allowance = [];
                if(count($query->allowance)>0){
                    foreach($query->allowance as $row){
                        $allowance[$row->allowance_id] = $row->amount;
                    }
                }
                $deductions = [];
                if(count($query->deductions)>0){
                    foreach($query->deductions as $row){
                        $deductions[$row->deduction_id] = $row->amount;
                    }
                }
                return [
                    'id' => $query->id,
                    'fund_services_id' => $query->fund_services_id,
                    'name' => mb_strtoupper($name),
                    'position_shorten' => $query->position_shorten,
                    'salary' => number_format($query->salary,2),
                    'amount_base' => $query->amount_base==NULL ? '-' : number_format($query->amount_base,2),
                    'column_amount' => $query->column_amount==NULL ? '-' : number_format($query->column_amount,2),
                    'column_amount2' => $query->column_amount2==NULL ? '-' : number_format($query->column_amount2,2),
                    'amount_base_amount' => $query->amount_base,
                    'column_amount_amount' => $query->column_amount,
                    'column_amount2_amount' => $query->column_amount2,
                    'earned' => number_format($query->earned,2),
                    'salary_amount' => $query->salary,
                    'earned_amount' => $query->earned,
                    'lwop' => $query->lwop==NULL ? '-' : number_format($query->lwop,2),
                    'lwop_amount' => $query->lwop==NULL ? 0 : $query->lwop,
                    'deduction' => $query->deduction==NULL || $query->deduction<=0 ? '-' : number_format($query->deduction,2),
                    'deduction_amount' => $query->deduction==NULL || $query->deduction<=0 ? 0 : $query->deduction,
                    'netpay' => number_format($query->netpay,2),
                    'netpay_amount' => $query->netpay,
                    'allowance' => $allowance,
                    'deductions' => $deductions,
                    'months' => $months,
                    'unclaimeds' => $unclaimeds,
                    'month_unclaimed' => $query->month_unclaimed
                ];
            })->toArray();

        $logo = public_path('assets\images\logo\lnu_logo.png');
        $logo_blur = public_path('assets\images\logo\lnu_logo_blur1.png');
        //$pdf = new PDF('A4', 'mm', '', true, 'UTF-8', false);
        $page_size = array(215.9, 330.2);
        $pdf = new Pdf('P', 'mm', $page_size, true, 'UTF-8', false);
        $height = 185;
        $width = 260;
        $pdf::reset();
        $pdf::AddPage('L',$page_size);
        $pdf::SetAutoPageBreak(TRUE, 3);
        //landscape scale A4
        //$height = 185;
        //$width = 260;
        //Portrait scale A4
        //$width = 210;
        //height = 270;

        $pdf::setFooterCallback(function($pdf){
                $pdf->SetFont('arial','',6);
                $pdf->SetXY(5,-5);
                $pdf->Cell(320, '', $pdf->getAliasNumPage().' of '.$pdf->getAliasNbPages(), 0, 1, 'C', 0, '', 1);
                $pdf->SetXY(5,-5);
                $pdf->Cell(320, '', date('M d, Y h:i a'), 0, 1, 'R', 0, '', 1);
        });

        $fund_source = '';
        if(count($payroll->fund_source)>0){
            foreach($payroll->fund_source as $fund){
                $funds[] = $fund->fund_source->shorten;
            }
            $fund_source = implode(', ',$funds);
        }
        $first_day_payroll = $payroll->day_from;
        $last_day_payroll = $payroll->day_to;
        $first_day_month = 1;
        $last_day_month = date('t',strtotime($year.'-'.$month.'-01'));
        $month_name = date('F', strtotime($year.'-'.$month.'-01'));
        if($first_day_payroll==$first_day_month && $last_day_payroll==$last_day_month){
            $payroll_month = 'MONTH OF '.$month_name.' '.$year;
        }else{
            $payroll_month = 'PERIOD OF '.$month_name.' '.$first_day_payroll.'-'.$last_day_payroll.', '.$year;
        }
        $with_half_netpay = 'No';
        if($payroll->payroll_type_id==1){
            foreach($payroll->emp_stat as $emp_stat_row){
                if($emp_stat_row->emp_stat->gov=='Y'){
                    $with_half_netpay = 'Yes';
                }
            }
        }

        // if($fund_services->count()>0){
        //     $y_limit = 139;
        // }else{
        //     $y_limit = 200;
        // }
        $y_limit = 200;

        $x = 3;
        $y = 5;
        $y_add = 0;

        $pdf::SetXY(295, $y-2);
        $pdf::Image($qrcode, '', '', 30, 26, '', '', 'T', false, 0, '', false, false, 0, false, false, false);

        $pdf::SetXY($x, $y);
        $pdf::SetFont('arial','',7);
        $pdf::Cell(95, '', 'ENTITY NAME: LEYTE NORMAL UNIVERSITY', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+4;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(95, '', 'FUND CLUSTER: '.$fund_source, 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+7;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::SetFont('arial','b',7);
        $pdf::Cell(95, '', 'PAYROLL: '.mb_strtoupper($payroll->payroll_type->name), 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+4;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(95, '', 'FOR THE '.mb_strtoupper($payroll_month), 0, 1, 'L', 0, '', 1);

        //325
        $y_add = $y_add+10;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::SetFont('arial','b',6);
        $pdf::Cell(7, 6.6, '#', 1, 1, 'C', 0, '', 1);

        $add_column = 0;
        if($payroll->payroll_type->w_salary=='Yes' && $payroll->payroll_type->w_salary_name!=NULL){
            $add_column++;
        }

        if($payroll->payroll_type->column_name!=NULL){
            $add_column++;
        }

        if($payroll->payroll_type->column_name2!=NULL){
            $add_column++;
        }

        //318 total lenght allocated
        //60 for default allocated
        //258 allocated for column
        $column_count = 9+$add_column+(count($payroll->months)+count($payroll->unclaimeds)+$allowance_list->count()+$deduction_list->count()+$deduction_other->count());
        $allocated_per_column = 258/$column_count;

        $x_add = 7;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(25+$allocated_per_column, 6.6, 'Name', 1, 1, 'C', 0, '', 1);

        $x_add = $x_add+$allocated_per_column+25;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(5+$allocated_per_column, 6.6, 'Position', 1, 1, 'C', 0, '', 1);

        // $x_add = $x_add+35;
        // $pdf::SetXY($x+$x_add, $y+$y_add);
        // $pdf::Cell(10, 6.6, 'SG', 1, 1, 'C', 0, '', 1);

        $x_add = $x_add+$allocated_per_column+5;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(5+$allocated_per_column, 6.6, 'Salaries', 1, 1, 'C', 0, '', 1);

        if($payroll->payroll_type->w_salary=='Yes' && $payroll->payroll_type->w_salary_name!=NULL){
            $x_add = $x_add+$allocated_per_column+5;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(5+$allocated_per_column, 6.6, $payroll->payroll_type->w_salary_name, 1, 1, 'C', 0, '', 1);
        }

        if($payroll->payroll_type->column_name!=NULL){
            $x_add = $x_add+$allocated_per_column+5;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(5+$allocated_per_column, 6.6, $payroll->payroll_type->column_name, 1, 1, 'C', 0, '', 1);
        }

        if($payroll->payroll_type->column_name2!=NULL){
            $x_add = $x_add+$allocated_per_column+5;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(5+$allocated_per_column, 6.6, $payroll->payroll_type->column_name2, 1, 1, 'C', 0, '', 1);
        }

        if(count($payroll->months)>0){
            $x_add = $x_add+$allocated_per_column+5;
            $months_length = $allocated_per_column*count($payroll->months);
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell($months_length, 3.3, 'Months', 1, 1, 'C', 0, '', 1);
            $month_row_x = 0;
            foreach($payroll->months as $month_row){
                if($month_row_x>0){
                    $x_add = $x_add+$allocated_per_column;
                }
                $pdf::SetXY($x+$x_add, $y+$y_add+3.3);
                $pdf::Cell($allocated_per_column, 3.3, date('M',strtotime($month_row->year.'-'.$month_row->month.'-01')), 1, 1, 'C', 0, '', 1);
                $month_row_x++;
            }
            if(count($payroll->unclaimeds)>0){
                $unclaimed_length = $allocated_per_column*count($payroll->unclaimeds);
                $pdf::SetXY($x+$x_add+$allocated_per_column, $y+$y_add);
                $pdf::Cell($unclaimed_length, 3.3, 'Unclaimed ('.($year-1).')', 1, 1, 'C', 0, '', 1);

                foreach($payroll->unclaimeds as $unclaimeds_row){
                    $x_add = $x_add+$allocated_per_column;
                    $pdf::SetXY($x+$x_add, $y+$y_add+3.3);
                    $pdf::Cell($allocated_per_column, 3.3, date('M',strtotime($unclaimeds_row->year.'-'.$unclaimeds_row->month.'-01')), 1, 1, 'C', 0, '', 1);
                }
            }
            $x_add = $x_add+$allocated_per_column;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell($allocated_per_column, 6.6, 'Hours', 1, 1, 'C', 0, '', 1);
            $x_add = $x_add+$allocated_per_column;

        }else{
            $x_add = $x_add+$allocated_per_column+5;
        }
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(5+$allocated_per_column, 6.6, 'Earned', 1, 1, 'C', 0, '', 1);
        $x_add = $x_add+$allocated_per_column+5;

        if($allowance_list->count()>0){
            $allowance_x = 0;

            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell($allocated_per_column*$allowance_list->count(), 3.3, 'Allowances', 1, 1, 'C', 0, '', 1);

            foreach($allowance_list as $allowance){
                if($allowance_x>0){
                    $x_add = $x_add+$allocated_per_column;
                }
                $pdf::SetXY($x+$x_add, $y+$y_add+3.3);
                $pdf::Cell($allocated_per_column, 3.3, $allowance->name, 1, 1, 'C', 0, '', 1);
                $allowance_x++;
            }
            $x_add = $x_add+$allocated_per_column;
        }

        if($deduction_list->count()>0){
            $deduction_x = 0;
            if($group_count->count()>0){
                foreach($group_count as $deduction){
                    $deduction_group_count[$deduction->group_id] = $deduction->count;
                }
                $x_add_group = 0;
                foreach($deduction_group as $deduction){
                    $pdf::SetXY($x+$x_add+$x_add_group, $y+$y_add);
                    $pdf::Cell($allocated_per_column*$deduction_group_count[$deduction->id], 3.3, $deduction->name, 1, 1, 'C', 0, '', 1);
                    $x_add_group += $allocated_per_column*$deduction_group_count[$deduction->id];
                    $deduction_x++;
                }
            }
            $deduction_x = 0;
            foreach($deduction_list as $deduction){
                if($deduction_x>0){
                    $x_add = $x_add+$allocated_per_column;
                }
                $pdf::SetXY($x+$x_add, $y+$y_add+3.3);
                $pdf::Cell($allocated_per_column, 3.3, $deduction->name, 1, 1, 'C', 0, '', 1);
                $deduction_x++;
            }
            $x_add = $x_add+$allocated_per_column;
        }

        if($deduction_other->count()>0){
            $deduction_other_x = 0;
            foreach($deduction_other as $deduction){
                if($deduction_other_x>0){
                    $x_add = $x_add+$allocated_per_column;
                }
                $pdf::SetXY($x+$x_add, $y+$y_add);
                $pdf::Cell($allocated_per_column, 6.6, $deduction->name, 1, 1, 'C', 0, '', 1);
                $deduction_other_x++;
            }
            $x_add = $x_add+$allocated_per_column;
        }

        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell($allocated_per_column, 6.6, 'LWOP', 1, 1, 'C', 0, '', 1);

        $x_add = $x_add+$allocated_per_column;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(5+$allocated_per_column, 6.6, 'Deduction', 1, 1, 'C', 0, '', 1);

        $x_add = $x_add+$allocated_per_column+5;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(5+$allocated_per_column, 6.6, 'NetPay', 1, 1, 'C', 0, '', 1);

        if($with_half_netpay=='Yes'){
            $x_add = $x_add+$allocated_per_column+5;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(5+$allocated_per_column, 6.6, date('M 1-15',strtotime($year.'-'.$month.'-01')), 1, 1, 'C', 0, '', 1);

            $x_add = $x_add+$allocated_per_column+5;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(5+$allocated_per_column, 6.6, date('M 16-t',strtotime($year.'-'.$month.'-01')), 1, 1, 'C', 0, '', 1);
        }

        if(count($payroll_list)>0 && $fund_services->count()>0){
            $list_x_fund = 1;
            $list_x = 1;
            $y_add = $y_add+6.6;

            $hours_services = 0;
            $total_salary = 0;
            $total_amount_base = 0;
            $total_column_amount = 0;
            $total_column_amount2 = 0;
            $total_months = array_fill(0, count($payroll->months), 0);
            $total_unclaimeds = array_fill(0, count($payroll->unclaimeds), 0);
            $total_hours = 0;
            $total_earned = 0;
            $total_allowance = array_fill(0, $allowance_list->count(), 0);
            $total_deductions = array_fill(0, $deduction_list->count(), 0);
            $total_deductions_other = array_fill(0, $deduction_other->count(), 0);
            $total_lwop = 0;
            $total_deduction = 0;
            $total_netpay = 0;
            $total_netpay_half_1 = 0;
            $total_netpay_half_2 = 0;

            $group_services_count = 1;
            $group_services = $fund_services_first->fund_services->group;
            $services_group_name = [];
            $total_salary_services_group = 0;
            $total_amount_base_services_group = 0;
            $total_column_amount_services_group = 0;
            $total_column_amount2_services_group = 0;
            $total_months_services_group = array_fill(0, count($payroll->months), 0);
            $total_unclaimeds_services_group = array_fill(0, count($payroll->unclaimeds), 0);
            $total_hours_services_group = 0;
            $total_earned_services_group = 0;
            $total_allowance_services_group = array_fill(0, $allowance_list->count(), 0);
            $total_deductions_services_group = array_fill(0, $deduction_list->count(), 0);
            $total_deductions_other_services_group = array_fill(0, $deduction_other->count(), 0);
            $total_lwop_services_group = 0;
            $total_deduction_services_group = 0;
            $total_netpay_services_group = 0;
            $total_netpay_half_1_services_group = 0;
            $total_netpay_half_2_services_group = 0;
            $total_salary_services_group1 = [];
            $total_netpay_services_group1 = [];
            $total_amount_base_services_group1 = [];
            $total_column_amount_services_group1 = [];
            $total_column_amount2_services_group1 = [];
            for ($i = 0; $i < $fund_services->count(); $i++) {
            //foreach($fund_services as $fund_service){
                $fund_service = $fund_services[$i];
                $fund_service_next = $fund_services[$i + 1];

                $fund_services_id_main = $fund_service->id;
                $total_salary_services[$fund_services_id_main] = 0;
                $total_amount_base_services[$fund_services_id_main] = 0;
                $total_column_amount_services[$fund_services_id_main] = 0;
                $total_column_amount2_services[$fund_services_id_main] = 0;
                $total_months_services[$fund_services_id_main] = array_fill(0, count($payroll->months), 0);
                $total_unclaimeds_services[$fund_services_id_main] = array_fill(0, count($payroll->unclaimeds), 0);
                $total_hours_services[$fund_services_id_main] = 0;
                $total_earned_services[$fund_services_id_main] = 0;
                $total_allowance_services[$fund_services_id_main] = array_fill(0, $allowance_list->count(), 0);
                $total_deductions_services[$fund_services_id_main] = array_fill(0, $deduction_list->count(), 0);
                $total_deductions_other_services[$fund_services_id_main] = array_fill(0, $deduction_other->count(), 0);
                $total_lwop_services[$fund_services_id_main] = 0;
                $total_deduction_services[$fund_services_id_main] = 0;
                $total_netpay_services[$fund_services_id_main] = 0;
                $total_netpay_half_1_services[$fund_services_id_main] = 0;
                $total_netpay_half_2_services[$fund_services_id_main] = 0;

                $pdf::SetFont('arialb','',6);

                if($fund_services->count()>1){
                    if($list_x_fund>1){
                        $y_add = $y_add+5;
                    }
                    $pdf::SetXY($x, $y+$y_add);
                    $pdf::Cell($x+$x_add+$allocated_per_column+2, 5, '', 1, 1, 'L', 0, '', 1);
                    $pdf::SetXY($x+7, $y+$y_add);
                    $pdf::Cell(200, 5, $fund_service->fund_services->name.' ('.$fund_service->fund_services->shorten.')', 0, 1, 'L', 0, '', 1);
                    $list_x_fund++;

                    if($group_services!=$fund_service->fund_services->group){
                        $group_services_count = 1;
                    }

                }

                $pdf::SetFont('arial','',6);

                foreach($payroll_list as $list){
                    $fund_services_id = $list['fund_services_id'];
                    if($fund_service->fund_services_id==$fund_services_id){
                        $total_salary_services_group1[$fund_services_id] += $list['salary_amount'];
                        $total_netpay_services_group1[$fund_services_id] += $list['netpay_amount'];
                        $total_amount_base_services_group1[$fund_services_id] += $list['amount_base_amount'];
                        $total_column_amount_services_group1[$fund_services_id] += $list['column_amount_amount'];
                        $total_column_amount2_services_group1[$fund_services_id] += $list['column_amount2_amount'];
                        if($fund_services->count()>1){
                            $y_add = $y_add+5;
                            $total_salary_services[$fund_services_id_main] += $list['salary_amount'];
                            $total_earned_services[$fund_services_id_main] += $list['earned_amount'];
                            $total_amount_base_services[$fund_services_id_main] += $list['amount_base_amount'];
                            $total_column_amount_services[$fund_services_id_main] += $list['column_amount_amount'];
                            $total_column_amount2_services[$fund_services_id_main] += $list['column_amount2_amount'];

                            if($group_services==$fund_service->fund_services->group){
                                $services_group_name[] = $fund_service->fund_services->shorten;
                                $total_salary_services_group += $list['salary_amount'];
                                $total_earned_services_group += $list['earned_amount'];
                                $total_amount_base_services_group += $list['amount_base_amount'];
                                $total_column_amount_services_group += $list['column_amount_amount'];
                                $total_column_amount2_services_group += $list['column_amount2_amount'];
                            }else{
                                $services_group_name = [];
                                $services_group_name[] = $fund_service->fund_services->shorten;
                                $total_salary_services_group = $list['salary_amount'];
                                $total_earned_services_group = $list['earned_amount'];
                                $total_amount_base_services_group = $list['amount_base_amount'];
                                $total_column_amount_services_group = $list['column_amoun_amountt'];
                                $total_column_amount2_services_group = $list['column_amount2_amount'];
                            }
                        }else{
                            if($list_x>1){
                                $y_add = $y_add+5;
                            }
                        }

                        $total_salary += $list['salary_amount'];
                        $total_earned += $list['earned_amount'];
                        $total_amount_base += $list['amount_base_amount'];
                        $total_column_amount += $list['column_amount_amount'];
                        $total_column_amount2 += $list['column_amount2_amount'];

                        $pdf::SetXY($x, $y+$y_add);
                        $pdf::Cell(7, 5, $list_x, 1, 1, 'C', 0, '', 1);

                        $x_add = 7;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(25+$allocated_per_column, 5, $list['name'], 1, 1, 'L', 0, '', 1);

                        $x_add = $x_add+$allocated_per_column+25;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(5+$allocated_per_column, 5, $list['position_shorten'], 1, 1, 'C', 0, '', 1);

                        $x_add = $x_add+$allocated_per_column+5;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(5+$allocated_per_column, 5, $list['salary'], 1, 1, 'R', 0, '', 1);

                        if($payroll->payroll_type->w_salary=='Yes' && $payroll->payroll_type->w_salary_name!=NULL){
                            $x_add = $x_add+$allocated_per_column+5;
                            $pdf::SetXY($x+$x_add, $y+$y_add);
                            $pdf::Cell(5+$allocated_per_column, 5, $list['amount_base'], 1, 1, 'R', 0, '', 1);
                        }

                        if($payroll->payroll_type->column_name!=NULL){
                            $x_add = $x_add+$allocated_per_column+5;
                            $pdf::SetXY($x+$x_add, $y+$y_add);
                            $pdf::Cell(5+$allocated_per_column, 5, $list['column_amount'], 1, 1, 'R', 0, '', 1);
                        }

                        if($payroll->payroll_type->column_name2!=NULL){
                            $x_add = $x_add+$allocated_per_column+5;
                            $pdf::SetXY($x+$x_add, $y+$y_add);
                            $pdf::Cell(5+$allocated_per_column, 5, $list['column_amount2'], 1, 1, 'R', 0, '', 1);
                        }

                        if(count($payroll->months)>0){
                            $x_add = $x_add+$allocated_per_column+5;
                            $month_row_x = 0;

                            foreach($payroll->months as $month_row){

                                if($month_row_x>0){
                                    $x_add = $x_add+$allocated_per_column;
                                }

                                $month_amount = '-';
                                if(count($list['months'])>0){
                                    if(isset($list['months'][$month_row->month])){
                                        if($list['months'][$month_row->month]>0){
                                            $month_amount = number_format($list['months'][$month_row->month],2);
                                        }
                                    }
                                }

                                $total_months[$month_row->month] += $list['months'][$month_row->month];
                                if($fund_services->count()>1){
                                    $total_months_services[$fund_services_id_main][$month_row->month] += $list['months'][$month_row->month];

                                    if($group_services==$fund_service->fund_services->group){
                                        $total_months_services_group[$month_row->month] += $list['months'][$month_row->month];
                                    }else{
                                        $total_months_services_group[$month_row->month] = $list['months'][$month_row->month];
                                    }
                                }

                                $pdf::SetXY($x+$x_add, $y+$y_add);
                                $pdf::Cell($allocated_per_column, 5, $month_amount, 1, 1, 'R', 0, '', 1);
                                $month_row_x++;
                            }

                            if(count($payroll->unclaimeds)>0){
                                foreach($payroll->unclaimeds as $unclaimeds_row){
                                    $month_amount = '-';
                                    if(count($list['unclaimeds'])>0){
                                        if(isset($list['unclaimeds'][$unclaimeds_row->month])){
                                            if($list['unclaimeds'][$unclaimeds_row->month]>0){
                                                $month_amount = number_format($list['unclaimeds'][$unclaimeds_row->month],2);
                                            }
                                        }
                                    }
                                    $total_unclaimeds[$unclaimeds_row->month] += $list['months'][$unclaimeds_row->month];

                                    if($fund_services->count()>1){
                                        $total_unclaimeds_services[$fund_services_id_main][$unclaimeds_row->month] += $list['months'][$unclaimeds_row->month];

                                        if($group_services==$fund_service->fund_services->group){
                                            $total_unclaimeds_services_group[$unclaimeds_row->month] += $list['months'][$unclaimeds_row->month];
                                        }else{
                                            $total_unclaimeds_services_group[$unclaimeds_row->month] = $list['months'][$unclaimeds_row->month];
                                        }
                                    }

                                    $x_add = $x_add+$allocated_per_column;
                                    $pdf::SetXY($x+$x_add, $y+$y_add);
                                    $pdf::Cell($allocated_per_column, 5, $month_amount, 1, 1, 'R', 0, '', 1);
                                }
                            }

                            $hours_amount = '-';
                            $hours = 0;
                            if(count($list['month_unclaimed'])>0){
                                foreach($list['month_unclaimed'] as $month_row){
                                    $hours += $month_row->amount;
                                    $hours_services += $month_row->amount;
                                }
                                if($hours>0){
                                    $hours_amount = number_format($hours,2);
                                }
                            }

                            $total_hours += $hours_services;

                            if($fund_services->count()>1){
                                $total_hours_services[$fund_services_id_main] += $hours_services;

                                if($group_services==$fund_service->fund_services->group){
                                    $total_hours_services_group += $hours_services;
                                }else{
                                    $total_hours_services_group = $hours_services;
                                }
                            }

                            $x_add = $x_add+$allocated_per_column;
                            $pdf::SetXY($x+$x_add, $y+$y_add);
                            $pdf::Cell($allocated_per_column, 5, $hours_amount, 1, 1, 'R', 0, '', 1);
                            $x_add = $x_add+$allocated_per_column;

                        }else{
                            $x_add = $x_add+$allocated_per_column+5;
                        }

                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(5+$allocated_per_column, 5, $list['earned'], 1, 1, 'R', 0, '', 1);
                        $x_add = $x_add+$allocated_per_column+5;

                        if($allowance_list->count()>0){
                            $allowance_x = 0;
                            foreach($allowance_list as $allowance){
                                if($allowance_x>0){
                                    $x_add = $x_add+$allocated_per_column;
                                }
                                $allowance_amount = '-';
                                if(count($list['allowance'])>0){
                                    if(isset($list['allowance'][$allowance->id])){
                                        if($list['allowance'][$allowance->id]>0){
                                            $allowance_amount = number_format($list['allowance'][$allowance->id],2);
                                        }
                                    }
                                }

                                $total_allowance[$allowance->id] += $list['allowance'][$allowance->id];

                                if($fund_services->count()>1){
                                    $total_allowance_services[$fund_services_id_main][$allowance->id] += $list['allowance'][$allowance->id];

                                    if($group_services==$fund_service->fund_services->group){
                                        $total_allowance_services_group[$allowance->id] += $list['allowance'][$allowance->id];
                                    }else{
                                        $total_allowance_services_group[$allowance->id] = $list['allowance'][$allowance->id];
                                    }
                                }

                                $pdf::SetXY($x+$x_add, $y+$y_add);
                                $pdf::Cell($allocated_per_column, 5, $allowance_amount, 1, 1, 'R', 0, '', 1);
                                $allowance_x++;
                            }
                            $x_add = $x_add+$allocated_per_column;
                        }

                        if($deduction_list->count()>0){
                            $deduction_x = 0;
                            foreach($deduction_list as $deduction){
                                if($deduction_x>0){
                                    $x_add = $x_add+$allocated_per_column;
                                }
                                $deduction_amount = '-';
                                if(count($list['deductions'])>0){
                                    if(isset($list['deductions'][$deduction->id])){
                                        if($list['deductions'][$deduction->id]>0){
                                            $deduction_amount = number_format($list['deductions'][$deduction->id],2);
                                        }
                                    }
                                }

                                $total_deductions[$deduction->id] += $list['deductions'][$deduction->id];

                                if($fund_services->count()>1){
                                    $total_deductions_services[$fund_services_id_main][$deduction->id] += $list['deductions'][$deduction->id];

                                    if($group_services==$fund_service->fund_services->group){
                                        $total_deductions_services_group[$deduction->id] += $list['deductions'][$deduction->id];
                                    }else{
                                        $total_deductions_services_group[$deduction->id] = $list['deductions'][$deduction->id];
                                    }
                                }

                                $pdf::SetXY($x+$x_add, $y+$y_add);
                                $pdf::Cell($allocated_per_column, 5, $deduction_amount, 1, 1, 'R', 0, '', 1);
                                $deduction_x++;
                            }
                            $x_add = $x_add+$allocated_per_column;
                        }

                        if($deduction_other->count()>0){
                            $deduction_other_x = 0;
                            foreach($deduction_other as $deduction){
                                if($deduction_other_x>0){
                                    $x_add = $x_add+$allocated_per_column;
                                }
                                $deduction_amount = '-';
                                if(count($list['deductions'])>0){
                                    if(isset($list['deductions'][$deduction->id])){
                                        if($list['deductions'][$deduction->id]>0){
                                            $deduction_amount = number_format($list['deductions'][$deduction->id],2);
                                        }
                                    }
                                }

                                $total_deductions_other[$deduction->id] += $list['deductions'][$deduction->id];

                                if($fund_services->count()>1){
                                    $total_deductions_other_services[$fund_services_id_main][$deduction->id] += $list['deductions'][$deduction->id];

                                    if($group_services==$fund_service->fund_services->group){
                                        $total_deductions_other_services_group[$deduction->id] += $list['deductions'][$deduction->id];
                                    }else{
                                        $total_deductions_other_services_group[$deduction->id] = $list['deductions'][$deduction->id];
                                    }
                                }

                                $pdf::SetXY($x+$x_add, $y+$y_add);
                                $pdf::Cell($allocated_per_column, 5, $deduction_amount, 1, 1, 'R', 0, '', 1);
                                $deduction_other_x++;
                            }
                            $x_add = $x_add+$allocated_per_column;
                        }

                        $total_lwop += $list['lwop_amount'];
                        $total_deduction += $list['deduction_amount'];
                        $total_netpay += $list['netpay_amount'];

                        if($fund_services->count()>1){
                            $total_lwop_services[$fund_services_id_main] += $list['lwop_amount'];
                            $total_deduction_services[$fund_services_id_main] += $list['deduction_amount'];
                            $total_netpay_services[$fund_services_id_main] += $list['netpay_amount'];

                            if($group_services==$fund_service->fund_services->group){
                                $total_lwop_services_group += $list['lwop_amount'];
                                $total_deduction_services_group += $list['deduction_amount'];
                                $total_netpay_services_group += $list['netpay_amount'];
                            }else{
                                $total_lwop_services_group = $list['lwop_amount'];
                                $total_deduction_services_group = $list['deduction_amount'];
                                $total_netpay_services_group = $list['netpay_amount'];
                            }
                        }

                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell($allocated_per_column, 5, $list['lwop'], 1, 1, 'R', 0, '', 1);

                        $x_add = $x_add+$allocated_per_column;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(5+$allocated_per_column, 5, $list['deduction'], 1, 1, 'R', 0, '', 1);

                        $x_add = $x_add+$allocated_per_column+5;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(5+$allocated_per_column, 5, $list['netpay'], 1, 1, 'R', 0, '', 1);

                        if($with_half_netpay=='Yes'){
                            $netpay_half_1 = round(($list['netpay_amount']/2),2);
                            $netpay_half_2 = $list['netpay_amount']-$netpay_half_1;

                            $total_netpay_half_1 += $netpay_half_1;
                            $total_netpay_half_2 += $netpay_half_2;

                            if($fund_services->count()>1){
                                $total_netpay_half_1_services[$fund_services_id_main] += $netpay_half_1;
                                $total_netpay_half_2_services[$fund_services_id_main] += $netpay_half_2;

                                if($group_services==$fund_service->fund_services->group){
                                    $total_netpay_half_1_services_group += $netpay_half_1;
                                    $total_netpay_half_2_services_group += $netpay_half_2;
                                }else{
                                    $total_netpay_half_1_services_group = $netpay_half_1;
                                    $total_netpay_half_2_services_group = $netpay_half_2;
                                }
                            }

                            $x_add = $x_add+$allocated_per_column+5;
                            $pdf::SetXY($x+$x_add, $y+$y_add);
                            $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($netpay_half_1), 1, 1, 'R', 0, '', 1);

                            $x_add = $x_add+$allocated_per_column+5;
                            $pdf::SetXY($x+$x_add, $y+$y_add);
                            $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($netpay_half_2), 1, 1, 'R', 0, '', 1);
                        }
                        $list_x++;
                    }
                   if(count($payroll_list)==$list_x){
                        if($fund_services->count()>0){
                            $y_limit = 139;
                        }else{
                            $y_limit = 160;
                        }
                   }

                    if(($y+$y_add)>$y_limit){
                        $pdf::AddPage('L',$page_size);
                        $y = 10;
                        $y_add = 0;
                    }

                }
                if($fund_services->count()>1){
                    $y_add = $y_add+5;
                    $pdf::SetFont('arialb','',6);

                    $pdf::SetXY($x, $y+$y_add);
                    $pdf::Cell(7, 5, '', 1, 1, 'C', 0, '', 1);

                    $x_add = 7;
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::Cell(25+$allocated_per_column, 5, 'SUB-TOTAL', 1, 1, 'L', 0, '', 1);

                    $x_add = $x_add+$allocated_per_column+25;
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::Cell(5+$allocated_per_column, 5, '', 1, 1, 'C', 0, '', 1);

                    $x_add = $x_add+$allocated_per_column+5;
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_salary_services[$fund_services_id_main]), 1, 1, 'R', 0, '', 1);

                    if($payroll->payroll_type->w_salary=='Yes' && $payroll->payroll_type->w_salary_name!=NULL){
                        $x_add = $x_add+$allocated_per_column+5;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_amount_base_services[$fund_services_id_main]), 1, 1, 'R', 0, '', 1);
                    }

                    if($payroll->payroll_type->column_name!=NULL){
                        $x_add = $x_add+$allocated_per_column+5;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_column_amount_services[$fund_services_id_main]), 1, 1, 'R', 0, '', 1);
                    }

                    if($payroll->payroll_type->column_name2!=NULL){
                        $x_add = $x_add+$allocated_per_column+5;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_column_amount2_services[$fund_services_id_main]), 1, 1, 'R', 0, '', 1);
                    }

                    if(count($payroll->months)>0){
                        $x_add = $x_add+$allocated_per_column+5;
                        $month_row_x = 0;
                        foreach($payroll->months as $month_row){
                            if($month_row_x>0){
                                $x_add = $x_add+$allocated_per_column;
                            }
                            $pdf::SetXY($x+$x_add, $y+$y_add);
                            $pdf::Cell($allocated_per_column, 5, $this->check_zero($total_months_services[$fund_services_id_main][$month_row->month]), 1, 1, 'R', 0, '', 1);
                            $month_row_x++;
                        }
                        if(count($payroll->unclaimeds)>0){
                            foreach($payroll->unclaimeds as $unclaimeds_row){
                                $x_add = $x_add+$allocated_per_column;
                                $pdf::SetXY($x+$x_add, $y+$y_add);
                                $pdf::Cell($allocated_per_column, 5, $this->check_zero($total_unclaimeds_services[$fund_services_id_main][$unclaimeds_row->month]), 1, 1, 'R', 0, '', 1);
                            }
                        }

                        $x_add = $x_add+$allocated_per_column;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell($allocated_per_column, 5, $this->check_zero($total_hours_services[$fund_services_id_main]), 1, 1, 'R', 0, '', 1);
                        $x_add = $x_add+$allocated_per_column;

                    }else{
                        $x_add = $x_add+$allocated_per_column+5;
                    }

                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_earned_services[$fund_services_id_main]), 1, 1, 'R', 0, '', 1);
                    $x_add = $x_add+$allocated_per_column+5;

                    if($allowance_list->count()>0){
                        $allowance_x = 0;
                        foreach($allowance_list as $allowance){
                            if($allowance_x>0){
                                $x_add = $x_add+$allocated_per_column;
                            }
                            $pdf::SetXY($x+$x_add, $y+$y_add);
                            $pdf::Cell($allocated_per_column, 5, $this->check_zero($total_allowance_services[$fund_services_id_main][$allowance->id]), 1, 1, 'R', 0, '', 1);
                            $allowance_x++;
                        }
                        $x_add = $x_add+$allocated_per_column;
                    }

                    if($deduction_list->count()>0){
                        $deduction_x = 0;
                        foreach($deduction_list as $deduction){
                            if($deduction_x>0){
                                $x_add = $x_add+$allocated_per_column;
                            }
                            $pdf::SetXY($x+$x_add, $y+$y_add);
                            $pdf::Cell($allocated_per_column, 5, $this->check_zero($total_deductions_services[$fund_services_id_main][$deduction->id]), 1, 1, 'R', 0, '', 1);
                            $deduction_x++;
                        }
                        $x_add = $x_add+$allocated_per_column;
                    }

                    if($deduction_other->count()>0){
                        $deduction_other_x = 0;
                        foreach($deduction_other as $deduction){
                            if($deduction_other_x>0){
                                $x_add = $x_add+$allocated_per_column;
                            }
                            $pdf::SetXY($x+$x_add, $y+$y_add);
                            $pdf::Cell($allocated_per_column, 5, $this->check_zero($total_deductions_other_services[$fund_services_id_main][$deduction->id]), 1, 1, 'R', 0, '', 1);
                            $deduction_other_x++;
                        }
                        $x_add = $x_add+$allocated_per_column;
                    }

                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::Cell($allocated_per_column, 5, $this->check_zero($total_lwop_services[$fund_services_id_main]), 1, 1, 'R', 0, '', 1);

                    $x_add = $x_add+$allocated_per_column;
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_deduction_services[$fund_services_id_main]), 1, 1, 'R', 0, '', 1);

                    $x_add = $x_add+$allocated_per_column+5;
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_netpay_services[$fund_services_id_main]), 1, 1, 'R', 0, '', 1);

                    if($with_half_netpay=='Yes'){
                        $x_add = $x_add+$allocated_per_column+5;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_netpay_half_1_services[$fund_services_id_main]), 1, 1, 'R', 0, '', 1);

                        $x_add = $x_add+$allocated_per_column+5;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_netpay_half_2_services[$fund_services_id_main]), 1, 1, 'R', 0, '', 1);
                    }

                    $group_services_next = NULL;
                    if($fund_service_next){
                        $group_services_next = $fund_service_next->fund_services->group;
                    }
                    if($group_services_next!=$fund_service->fund_services->group && $group_services_count>1){
                        $y_add = $y_add+5;
                        $pdf::SetFont('arialb','',6);

                        $pdf::SetXY($x, $y+$y_add);
                        $pdf::Cell(7, 5, '', 1, 1, 'C', 0, '', 1);

                        $x_add = 7;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(25+$allocated_per_column, 5, 'TOTAL ('.implode(',',$services_group_name).')', 1, 1, 'L', 0, '', 1);

                        $x_add = $x_add+$allocated_per_column+25;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(5+$allocated_per_column, 5, '', 1, 1, 'C', 0, '', 1);

                        $x_add = $x_add+$allocated_per_column+5;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_salary_services_group1[$fund_services_id_main]), 1, 1, 'R', 0, '', 1);

                        if($payroll->payroll_type->w_salary=='Yes' && $payroll->payroll_type->w_salary_name!=NULL){
                            $x_add = $x_add+$allocated_per_column+5;
                            $pdf::SetXY($x+$x_add, $y+$y_add);
                            $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_amount_base_services_group1[$fund_services_id_main]), 1, 1, 'R', 0, '', 1);
                        }

                        if($payroll->payroll_type->column_name!=NULL){
                            $x_add = $x_add+$allocated_per_column+5;
                            $pdf::SetXY($x+$x_add, $y+$y_add);
                            $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_column_amount_services_group1[$fund_services_id_main]), 1, 1, 'R', 0, '', 1);
                        }

                        if($payroll->payroll_type->column_name2!=NULL){
                            $x_add = $x_add+$allocated_per_column+5;
                            $pdf::SetXY($x+$x_add, $y+$y_add);
                            $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_column_amount2_services_group1[$fund_services_id_main]), 1, 1, 'R', 0, '', 1);
                        }

                        if(count($payroll->months)>0){
                            $x_add = $x_add+$allocated_per_column+5;
                            $month_row_x = 0;
                            foreach($payroll->months as $month_row){
                                if($month_row_x>0){
                                    $x_add = $x_add+$allocated_per_column;
                                }
                                $pdf::SetXY($x+$x_add, $y+$y_add);
                                $pdf::Cell($allocated_per_column, 5, $this->check_zero($total_months_services_group[$month_row->month]), 1, 1, 'R', 0, '', 1);
                                $month_row_x++;
                            }
                            if(count($payroll->unclaimeds)>0){
                                foreach($payroll->unclaimeds as $unclaimeds_row){
                                    $x_add = $x_add+$allocated_per_column;
                                    $pdf::SetXY($x+$x_add, $y+$y_add);
                                    $pdf::Cell($allocated_per_column, 5, $this->check_zero($total_unclaimeds_services_group[$unclaimeds_row->month]), 1, 1, 'R', 0, '', 1);
                                }
                            }

                            $x_add = $x_add+$allocated_per_column;
                            $pdf::SetXY($x+$x_add, $y+$y_add);
                            $pdf::Cell($allocated_per_column, 5, $this->check_zero($total_hours_services_group), 1, 1, 'R', 0, '', 1);
                            $x_add = $x_add+$allocated_per_column;

                        }else{
                            $x_add = $x_add+$allocated_per_column+5;
                        }

                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_earned_services_group), 1, 1, 'R', 0, '', 1);
                        $x_add = $x_add+$allocated_per_column+5;

                        if($allowance_list->count()>0){
                            $allowance_x = 0;
                            foreach($allowance_list as $allowance){
                                if($allowance_x>0){
                                    $x_add = $x_add+$allocated_per_column;
                                }
                                $pdf::SetXY($x+$x_add, $y+$y_add);
                                $pdf::Cell($allocated_per_column, 5, $this->check_zero($total_allowance_services_group[$allowance->id]), 1, 1, 'R', 0, '', 1);
                                $allowance_x++;
                            }
                            $x_add = $x_add+$allocated_per_column;
                        }

                        if($deduction_list->count()>0){
                            $deduction_x = 0;
                            foreach($deduction_list as $deduction){
                                if($deduction_x>0){
                                    $x_add = $x_add+$allocated_per_column;
                                }
                                $pdf::SetXY($x+$x_add, $y+$y_add);
                                $pdf::Cell($allocated_per_column, 5, $this->check_zero($total_deductions_services_group[$deduction->id]), 1, 1, 'R', 0, '', 1);
                                $deduction_x++;
                            }
                            $x_add = $x_add+$allocated_per_column;
                        }

                        if($deduction_other->count()>0){
                            $deduction_other_x = 0;
                            foreach($deduction_other as $deduction){
                                if($deduction_other_x>0){
                                    $x_add = $x_add+$allocated_per_column;
                                }
                                $pdf::SetXY($x+$x_add, $y+$y_add);
                                $pdf::Cell($allocated_per_column, 5, $this->check_zero($total_deductions_other_services_group[$deduction->id]), 1, 1, 'R', 0, '', 1);
                                $deduction_other_x++;
                            }
                            $x_add = $x_add+$allocated_per_column;
                        }

                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell($allocated_per_column, 5, $this->check_zero($total_lwop_services_group), 1, 1, 'R', 0, '', 1);

                        $x_add = $x_add+$allocated_per_column;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_deduction_services_group), 1, 1, 'R', 0, '', 1);

                        $x_add = $x_add+$allocated_per_column+5;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_netpay_services_group), 1, 1, 'R', 0, '', 1);

                        if($with_half_netpay=='Yes'){
                            $x_add = $x_add+$allocated_per_column+5;
                            $pdf::SetXY($x+$x_add, $y+$y_add);
                            $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_netpay_half_1_services_group), 1, 1, 'R', 0, '', 1);

                            $x_add = $x_add+$allocated_per_column+5;
                            $pdf::SetXY($x+$x_add, $y+$y_add);
                            $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_netpay_half_2_services_group), 1, 1, 'R', 0, '', 1);
                        }
                    }
                    if(($y+$y_add)>$y_limit){
                        $pdf::AddPage('L',$page_size);
                        $y = 10;
                        $y_add = 0;
                    }
                }

                $group_services = $fund_service->fund_services->group;
                $group_services_count++;
            }
            $y_add = $y_add+5;
            $pdf::SetXY($x, $y+$y_add);
            $pdf::Cell(7, 5, '', 1, 1, 'C', 0, '', 1);

            $x_add = 7;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(25+$allocated_per_column, 5, 'TOTAL', 1, 1, 'C', 0, '', 1);

            $x_add = $x_add+$allocated_per_column+25;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(5+$allocated_per_column, 5, '', 1, 1, 'C', 0, '', 1);

            $x_add = $x_add+$allocated_per_column+5;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_salary), 1, 1, 'R', 0, '', 1);

            if($payroll->payroll_type->w_salary=='Yes' && $payroll->payroll_type->w_salary_name!=NULL){
                $x_add = $x_add+$allocated_per_column+5;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_amount_base), 1, 1, 'R', 0, '', 1);
            }

            if($payroll->payroll_type->column_name!=NULL){
                $x_add = $x_add+$allocated_per_column+5;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_column_amount), 1, 1, 'R', 0, '', 1);
            }

            if($payroll->payroll_type->column_name2!=NULL){
                $x_add = $x_add+$allocated_per_column+5;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_column_amount2), 1, 1, 'R', 0, '', 1);
            }

            if(count($payroll->months)>0){
                $x_add = $x_add+$allocated_per_column+5;
                $month_row_x = 0;
                foreach($payroll->months as $month_row){
                    if($month_row_x>0){
                        $x_add = $x_add+$allocated_per_column;
                    }
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::Cell($allocated_per_column, 5, $this->check_zero($total_months[$month_row->month]), 1, 1, 'R', 0, '', 1);
                    $month_row_x++;
                }
                if(count($payroll->unclaimeds)>0){
                    foreach($payroll->unclaimeds as $unclaimeds_row){
                        $x_add = $x_add+$allocated_per_column;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell($allocated_per_column, 5, $this->check_zero($total_unclaimeds[$unclaimeds_row->month]), 1, 1, 'R', 0, '', 1);
                    }
                }

                $x_add = $x_add+$allocated_per_column;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                $pdf::Cell($allocated_per_column, 5, $this->check_zero($total_hours), 1, 1, 'R', 0, '', 1);
                $x_add = $x_add+$allocated_per_column;

            }else{
                $x_add = $x_add+$allocated_per_column+5;
            }

            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_earned), 1, 1, 'R', 0, '', 1);
            $x_add = $x_add+$allocated_per_column+5;

            $total_allowances = 0;
            if($allowance_list->count()>0){
                $allowance_x = 0;
                foreach($allowance_list as $allowance){
                    if($allowance_x>0){
                        $x_add = $x_add+$allocated_per_column;
                    }
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::Cell($allocated_per_column, 5, $this->check_zero($total_allowance[$allowance->id]), 1, 1, 'R', 0, '', 1);
                    $total_allowances += $total_allowance[$allowance->id];
                    $allowance_x++;
                }
                $x_add = $x_add+$allocated_per_column;
            }

            if($deduction_list->count()>0){
                $deduction_x = 0;
                foreach($deduction_list as $deduction){
                    if($deduction_x>0){
                        $x_add = $x_add+$allocated_per_column;
                    }
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::Cell($allocated_per_column, 5, $this->check_zero($total_deductions[$deduction->id]), 1, 1, 'R', 0, '', 1);
                    $deduction_x++;
                }
                $x_add = $x_add+$allocated_per_column;
            }

            if($deduction_other->count()>0){
                $deduction_other_x = 0;
                foreach($deduction_other as $deduction){
                    if($deduction_other_x>0){
                        $x_add = $x_add+$allocated_per_column;
                    }
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::Cell($allocated_per_column, 5, $this->check_zero($total_deductions_other[$deduction->id]), 1, 1, 'R', 0, '', 1);
                    $deduction_other_x++;
                }
                $x_add = $x_add+$allocated_per_column;
            }

            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell($allocated_per_column, 5, $this->check_zero($total_lwop), 1, 1, 'R', 0, '', 1);

            $x_add = $x_add+$allocated_per_column;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_deduction), 1, 1, 'R', 0, '', 1);

            $x_add = $x_add+$allocated_per_column+5;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_netpay), 1, 1, 'R', 0, '', 1);

            if($with_half_netpay=='Yes'){
                $x_add = $x_add+$allocated_per_column+5;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_netpay_half_1), 1, 1, 'R', 0, '', 1);

                $x_add = $x_add+$allocated_per_column+5;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                $pdf::Cell(5+$allocated_per_column, 5, $this->check_zero($total_netpay_half_2), 1, 1, 'R', 0, '', 1);
            }

            if($fund_services->count()>0){
                $pdf::SetFont('arial','',7);
                $y_add = $y_add+10;
                $x_add = 20;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                $pdf::Cell(50, '', 'Recapitulation:', 0, 1, 'L', 0, '', 1);

                $x_add = $x_add+53;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                $pdf::Cell(30, '', 'Account Code', 0, 1, 'C', 0, '', 1);

                $group_services_count = 1;
                $group_services = $fund_services_first->fund_services->group;
                $services_group_name = [];

                $pdf::SetFont('arial','u',7);
                for ($i = 0; $i < $fund_services->count(); $i++) {
                    $fund_service = $fund_services[$i];
                    $fund_service_next = $fund_services[$i + 1];

                    if($group_services==$fund_service->fund_services->group){
                        $services_group_name[] = $fund_service->fund_services->shorten;
                    }else{
                        $services_group_name = [];
                        $services_group_name[] = $fund_service->fund_services->shorten;
                    }

                    $group_services_next = NULL;
                    if($fund_service_next){
                        $group_services_next = $fund_service_next->fund_services->group;
                    }

                    if($group_services_next!=$fund_service->fund_services->group){
                        $x_add = $x_add+40;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(40, '', implode('/',$services_group_name), 0, 1, 'C', 0, '', 1);
                    }

                    $group_services = $fund_service->fund_services->group;
                    $group_services_count++;
                }

                $x_add = $x_add+40;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                $pdf::Cell(40, '', 'TOTAL', 0, 1, 'C', 0, '', 1);

                $pdf::SetFont('arial','',7);


                if($account_title_payroll->count()>0){
                    foreach($account_title_payroll as $row){
                        if(($row->column==NULL && $row->payroll_type->w_salary=='Yes') ||
                            ($row->column=='w_salary_name' && $row->payroll_type->w_salary=='Yes') ||
                            ($row->column=='column_amount' && $row->payroll_type->column_name!=NULL) ||
                            ($row->column=='column_amount2' && $row->payroll_type->column_name2!=NULL)){
                            $x_add = 20;
                            $y_add = $y_add+3;
                            $pdf::SetXY($x+$x_add, $y+$y_add);

                            $pdf::Cell(50, '', $row->account_title->name, 0, 1, 'L', 0, '', 1);

                            $x_add = $x_add+48;
                            $pdf::SetXY($x+$x_add, $y+$y_add);
                            $pdf::Cell(28, '', $row->account_title->code, 0, 1, 'R', 0, '', 1);

                            $group_services_count = 1;
                            $group_services = $fund_services_first->fund_services->group;
                            $total_salary_services_group = 0;

                            for ($i = 0; $i < $fund_services->count(); $i++) {
                                $fund_service = $fund_services[$i];
                                $fund_service_next = $fund_services[$i + 1];
                                $salary_services_group = 0;
                                if($group_services==$fund_service->fund_services->group){
                                    $services_group_name[] = $fund_service->fund_services->shorten;
                                }else{
                                    $services_group_name = [];
                                    $services_group_name[] = $fund_service->fund_services->shorten;
                                }
                                $group_services_next = NULL;
                                if($fund_service_next){
                                    $group_services_next = $fund_service_next->fund_services->group;
                                }
                                if($group_services_next!=$fund_service->fund_services->group){
                                    $x_add = $x_add+40;
                                    $pdf::SetXY($x+$x_add, $y+$y_add);
                                    if($row->column=='w_salary_name'){
                                        $pdf::Cell(20, '', $this->check_zero($total_amount_base_services_group1[$fund_service->fund_services_id]), 0, 1, 'R', 0, '', 1);
                                    }elseif($row->column=='column_amount'){
                                        $pdf::Cell(20, '', $this->check_zero($total_column_amount_services_group1[$fund_service->fund_services_id]), 0, 1, 'R', 0, '', 1);
                                    }elseif($row->column=='column_amount2'){
                                        $pdf::Cell(20, '', $this->check_zero($total_column_amount2_services_group1[$fund_service->fund_services_id]), 0, 1, 'R', 0, '', 1);
                                    }else{
                                        $pdf::Cell(20, '', $this->check_zero($total_salary_services_group1[$fund_service->fund_services_id]), 0, 1, 'R', 0, '', 1);
                                    }
                                }
                                $group_services = $fund_service->fund_services->group;
                                $group_services_count++;
                            }
                            $x_add = $x_add+40;
                            $pdf::SetXY($x+$x_add, $y+$y_add);
                            if($row->column=='w_salary_name'){
                                $pdf::Cell(20, '', $this->check_zero($total_amount_base), 0, 1, 'R', 0, '', 1);
                            }elseif($row->column=='column_amount'){
                                $pdf::Cell(20, '', $this->check_zero($total_column_amount), 0, 1, 'R', 0, '', 1);
                            }elseif($row->column=='column_amount2'){
                                $pdf::Cell(20, '', $this->check_zero($total_column_amount2), 0, 1, 'R', 0, '', 1);
                            }else{
                                $pdf::Cell(20, '', $this->check_zero($total_earned), 0, 1, 'R', 0, '', 1);
                            }
                        }
                    }
                }

                $total_allowance_services_group1 = [];
                if($account_title_allowance->count()>0){
                    foreach($account_title_allowance as $row){
                        $y_add = $y_add+3;
                        $x_add = 20;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(50, '', $row->name, 0, 1, 'L', 0, '', 1);

                        $x_add = $x_add+48;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(28, '', $row->code, 0, 1, 'R', 0, '', 1);
                        $group_services = $fund_services_first->fund_services->group;
                        $total_allowance_services_group = 0;

                        for ($i = 0; $i < $fund_services->count(); $i++) {
                            $fund_service = $fund_services[$i];
                            $fund_service_next = $fund_services[$i + 1];
                            $allowance_services_group = 0;

                            if($group_services==$fund_service->fund_services->group){
                                $services_group_name[] = $fund_service->fund_services->shorten;
                            }else{
                                $services_group_name = [];
                                $services_group_name[] = $fund_service->fund_services->shorten;
                            }

                            $group_services_next = NULL;
                            if($fund_service_next){
                                $group_services_next = $fund_service_next->fund_services->group;
                            }

                            foreach($allowance_all as $list){
                                $fund_services_id = $list->list->fund_services_id;
                                if($fund_service->fund_services_id==$fund_services_id){
                                    foreach($row->allowance as $allow){
                                        if($list->allowance_id==$allow->allowance_id){
                                            $allowance_services_group += $list->amount;
                                        }
                                    }
                                }
                            }

                            if($group_services_next!=$fund_service->fund_services->group){
                                $x_add = $x_add+40;
                                $pdf::SetXY($x+$x_add, $y+$y_add);
                                $pdf::Cell(20, '', $this->check_zero($allowance_services_group), 0, 1, 'R', 0, '', 1);
                                $total_allowance_services_group1[$fund_service->fund_services_id] = $allowance_services_group;
                                $total_allowance_services_group += $allowance_services_group;
                            }
                            $group_services = $fund_service->fund_services->group;
                        }
                        $x_add = $x_add+40;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(20, '', $this->check_zero($total_allowance_services_group), 0, 1, 'R', 0, '', 1);
                    }
                }

                $total_deduction_services_group1 = [];
                if($account_title_deduction->count()>0){
                    foreach($account_title_deduction as $row){
                        $y_add = $y_add+3;
                        $x_add = 20;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(50, '', $row->name, 0, 1, 'L', 0, '', 1);

                        $x_add = $x_add+48;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(28, '', $row->code, 0, 1, 'R', 0, '', 1);

                        $group_services = $fund_services_first->fund_services->group;
                        $total_deduction_services_group = 0;
                        $x_add = $x_add+20;

                        for ($i = 0; $i < $fund_services->count(); $i++) {
                            $fund_service = $fund_services[$i];
                            $fund_service_next = $fund_services[$i + 1];
                            $deduction_services_group = 0;

                            if($group_services==$fund_service->fund_services->group){
                                $services_group_name[] = $fund_service->fund_services->shorten;
                            }else{
                                $services_group_name = [];
                                $services_group_name[] = $fund_service->fund_services->shorten;
                            }

                            $group_services_next = NULL;
                            if($fund_service_next){
                                $group_services_next = $fund_service_next->fund_services->group;
                            }

                            foreach($deduction_all as $list){
                                $fund_services_id = $list->list->fund_services_id;
                                if($fund_service->fund_services_id==$fund_services_id){
                                    foreach($row->deduction as $deduc){
                                        if($list->deduction_id==$deduc->deduction_id){
                                            $deduction_services_group += $list->amount;
                                        }
                                    }
                                }
                            }

                            if($group_services_next!=$fund_service->fund_services->group){
                                $x_add = $x_add+40;
                                $pdf::SetXY($x+$x_add, $y+$y_add);
                                $pdf::Cell(20, '', $this->check_zero($deduction_services_group), 0, 1, 'R', 0, '', 1);
                                $total_deduction_services_group1[$fund_service->fund_services_id] += $deduction_services_group;
                                $total_deduction_services_group += $deduction_services_group;
                            }
                            $group_services = $fund_service->fund_services->group;
                        }
                        $x_add = $x_add+40;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(20, '', $this->check_zero($total_deduction_services_group), 0, 1, 'R', 0, '', 1);
                    }
                }

                $y_add = $y_add+3;
                $x_add = 20;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                $pdf::Cell(50, '', $payroll->account_title->name, 0, 1, 'L', 0, '', 1);

                $x_add = $x_add+48;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                $pdf::Cell(28, '', $payroll->account_title->code, 0, 1, 'R', 0, '', 1);

                $x_add = $x_add+20;
                for ($i = 0; $i < $fund_services->count(); $i++) {
                    $fund_service = $fund_services[$i];
                    $fund_service_next = $fund_services[$i + 1];
                    $allowance_services_group = 0;

                    if($group_services==$fund_service->fund_services->group){
                        $services_group_name[] = $fund_service->fund_services->shorten;
                    }else{
                        $services_group_name = [];
                        $services_group_name[] = $fund_service->fund_services->shorten;
                    }

                    $group_services_next = NULL;
                    if($fund_service_next){
                        $group_services_next = $fund_service_next->fund_services->group;
                    }

                    if($group_services_next!=$fund_service->fund_services->group){
                        $x_add = $x_add+40;
                        $pdf::SetXY($x+$x_add, $y+$y_add);
                        $pdf::Cell(20, '', $this->check_zero($total_netpay_services_group1[$fund_service->fund_services_id]), 0, 1, 'R', 0, '', 1);

                        $pdf::SetXY($x+$x_add-17, $y+$y_add+3);
                        $pdf::Cell(37, '', '', 'TB', 1, 'L', 0, '', 1);

                        $pdf::SetXY($x+$x_add-17, $y+$y_add+3);
                        $pdf::Cell(17, '', $this->check_zero($total_netpay_services_group1[$fund_service->fund_services_id]
                                                        +$total_deduction_services_group1[$fund_service->fund_services_id]), 0, 1, 'R', 0, '', 1);

                        $pdf::SetXY($x+$x_add, $y+$y_add+3);
                        $pdf::Cell(20, '', $this->check_zero($total_netpay_services_group1[$fund_service->fund_services_id]
                            +$total_deduction_services_group1[$fund_service->fund_services_id]), 0, 1, 'R', 0, '', 1);

                        $pdf::SetXY($x+$x_add-17, $y+$y_add+3.5);
                        $pdf::Cell(37, '', '', 'B', 1, 'L', 0, '', 1);
                    }
                    $group_services = $fund_service->fund_services->group;
                }

                $x_add = $x_add+40;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                $pdf::Cell(20, '', $this->check_zero($total_netpay), 0, 1, 'R', 0, '', 1);

                $pdf::SetXY($x+$x_add-17, $y+$y_add+3);
                $pdf::Cell(37, '', '', 'TB', 1, 'L', 0, '', 1);
                $pdf::SetXY($x+$x_add-17, $y+$y_add+3);
                $pdf::Cell(17, '', $this->check_zero($total_earned+$total_allowances), 0, 1, 'R', 0, '', 1);
                $pdf::SetXY($x+$x_add, $y+$y_add+3);
                $pdf::Cell(20, '', $this->check_zero($total_netpay+$total_deduction), 0, 1, 'R', 0, '', 1);

                $pdf::SetXY($x+$x_add-17, $y+$y_add+3.5);
                $pdf::Cell(37, '', '', 'B', 1, 'L', 0, '', 1);
            }

            $pdf::SetFont('arial','',8);
            $y_add = $y_add+15;
            $x_add = 20;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(100, '', 'A.  CERTIFIED:  Services duly rendered as stated', 0, 1, 'L', 0, '', 1);

            $x_add = $x_add+140;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(100, '', 'C. APPROVED FOR PAYMENT :', 0, 1, 'L', 0, '', 1);

            $x_add = $x_add+60;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::SetFont('arial','b',8);
            $pdf::Cell(10, '', 'Php', 0, 1, 'L', 0, '', 1);

            $x_add = $x_add+10;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(30, '', $this->check_zero($total_netpay), 'B', 1, 'R', 0, '', 1);

            $signatory_a = '';
            $signatory_b = '';
            $signatory_c = '';
            $signatory_d = '';
            $signatory_position_a = '';
            $signatory_position_b = '';
            $signatory_position_c = '';
            $signatory_position_d = '';

            if($signatories->count()>0){
                foreach($signatories as $row){
                    if($row->signatory_id!=NULL){
                        $honorofic = $row->honorofic.' ';
                        $post_nominal = '';
                        if($row->post_nominal!='' || $row->post_nominal!=NULL){
                            $post_nominal = ', '.$row->post_nominal;
                        }
                        $signatory_name = $honorofic.$name_services->firstname($row->signatory->lastname,$row->signatory->firstname,$row->signatory->middlename,$row->signatory->extname)
                            .$post_nominal;
                        if($row->option=='a'){
                            $signatory_a = $signatory_name;
                            $signatory_position_a = $row->signatory->employee_default->designation->shorten;
                        }elseif($row->option=='b'){
                            $signatory_b = $signatory_name;
                            $signatory_position_b = $row->signatory->employee_default->position_title;
                        }elseif($row->option=='c'){
                            $signatory_c = $signatory_name;
                            $signatory_position_c = $row->signatory->employee_default->designation->shorten;
                        }elseif($row->option=='d'){
                            $signatory_d = $signatory_name;
                            $signatory_position_d = $row->signatory->employee_default->designation->shorten;
                        }
                    }
                }
            }

            $pdf::SetFont('arial','b',9);
            $y_add = $y_add+10;
            $x_add = 20;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            if($signatory_a==''){
                $pdf::Cell(80, '', 'NOT APPLICABLE', 'B', 1, 'C', 0, '', 1);
            }else{
                $pdf::Cell(80, '', $signatory_a, 0, 1, 'C', 0, '', 1);
            }

            $x_add = $x_add+140;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            if($signatory_c==''){
                $pdf::Cell(80, '', 'NOT APPLICABLE', 'B', 1, 'C', 0, '', 1);
            }else{
                $pdf::Cell(80, '', $signatory_c, 0, 1, 'C', 0, '', 1);
            }

            $pdf::SetFont('arial','',8);
            $y_add = $y_add+3.5;
            $x_add = 20;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(80, '', $signatory_position_a, 0, 1, 'C', 0, '', 1);

            $x_add = $x_add+140;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(80, '', $signatory_position_c, 0, 1, 'C', 0, '', 1);

            $pdf::SetFont('arial','',8);
            $y_add = $y_add+6;
            $x_add = 20;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(100, '', 'B.  CERTIFIED:  Supporting documents complete and proper and cash available in the', 0, 1, 'L', 0, '', 1);

            $x_add = $x_add+140;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(100, '', 'D.  CERTIFIED:  Each employee whose name appears on the payroll has been paid the', 0, 1, 'L', 0, '', 1);

            $y_add = $y_add+3.5;
            $x_add = 35;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(15, '', 'amount of ', 0, 1, 'L', 0, '', 1);

            $pdf::SetFont('arial','b',8);
            $x_add = $x_add+15;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(30, '', $this->check_zero($total_earned+$total_allowances), 'B', 1, 'C', 0, '', 1);

            $pdf::SetFont('arial','',8);
            $x_add = $x_add+125;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(100, '', 'amount as indicated opposite his/her name', 0, 1, 'L', 0, '', 1);

            $pdf::SetFont('arial','b',9);
            $y_add = $y_add+10;
            $x_add = 20;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            if($signatory_b==''){
                $pdf::Cell(80, '', 'NOT APPLICABLE', 'B', 1, 'C', 0, '', 1);
            }else{
                $pdf::Cell(80, '', $signatory_b, 0, 1, 'C', 0, '', 1);
            }

            $x_add = $x_add+150;
            $pdf::SetXY($x+$x_add, $y+$y_add);

            if($signatory_d==''){
                $pdf::Cell(80, '', 'NOT APPLICABLE', 'B', 1, 'C', 0, '', 1);
            }else{
                $pdf::Cell(80, '', $signatory_d, 0, 1, 'C', 0, '', 1);
            }

            $pdf::SetFont('arial','',8);
            $y_add = $y_add+3.5;
            $x_add = 20;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(80, '', $signatory_position_b, 0, 1, 'C', 0, '', 1);

            $x_add = $x_add+150;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(80, '', $signatory_position_d, 0, 1, 'C', 0, '', 1);

        }

        $src = 'storage\hrims\payroll/'.$year.'/'.$month.'/'.$payroll_id.'/'.$payroll_id.'.pdf';
        $pdf::Output(public_path($src),'F');

        return asset($src);
    }
    private function check_zero($amount){
        return ($amount<=0) ? '-' : number_format($amount,2);
    }
}
?>
