<?php
namespace App\Services;

use App\Models\_Work;

class PayrollServices
{
    public function year_end($year,$salary,$user_id){
        $from_service_guidelines = $year.'-01-01';
        $to_service_guidelines = $year.'-10-31';
        $salary_percent = 100;
        $cash_gift = 5000;

        $work = _Work::where('user_id',$user_id)
            ->whereIn('emp_stat_id',[1,2,3,6])
            ->orderBy('date_from','DESC')
            ->first();
        
        if($work==NULL){
            return array('status' => 'exclude');
        }

        $date_to = $work->date_to;

        if($date_to!='present' && $date_to<$to_service_guidelines){
            return array('status' => 'exclude');
        }

        $work_services = new WorkServices;
        $rendered = $work_services->rendered_months($user_id,'Y');

        if($rendered==9){
            $salary_percent = 95;
        }elseif($rendered==8){
            $salary_percent = 90;
        }elseif($rendered==7){
            $salary_percent = 80;
        }elseif($rendered==6){
            $salary_percent = 70;
        }elseif($rendered==5){
            $salary_percent = 60;
        }elseif($rendered==4){
            $salary_percent = 50;
        }elseif($rendered==3){
            $salary_percent = 0;
            $cash_gift = 2000;
        }elseif($rendered==2){
            $salary_percent = 0;
            $cash_gift = 1500;
        }elseif($rendered==1){
            $salary_percent = 0;
            $cash_gift = 1000;
        }elseif($rendered==0){
            $salary_percent = 0;
            $cash_gift = 500;
        }
        
        $year_end_bonus = round(($salary*($salary_percent/100)),2);
        return array('status' => 'include',
                    'year_end_bonus' => $year_end_bonus,
                    'cash_gift' => $cash_gift);
    }
}

?>