<?php
namespace App\Services;

use App\Models\_Work;
use DateTime;

class WorkServices
{
    public function rendered_months($user_id,$gov){        
        $work = _Work::where('user_id',$user_id);
        if($gov=='Y' || $gov=='N'){
            $work = $work->where('gov_service',$gov);
        }
        $work = $work->orderBy('date_from','DESC')
            ->get();

        if($work->count()<=0){
            return 0;
        }

        $totalMonths = 0;
        $totalWork = $work->count();
        $dateToday = date('Y-m-d');

        foreach ($work as $key => $row) {
            if ($key <= $totalWork - 1) {
                $currentDateTo = ($row->date_to == 'present') ? new DateTime($dateToday) : new DateTime($row->date_to);
                $currentDateFrom = new DateTime($row->date_from);

                if($key==0){
                    $startDateTo = $currentDateTo;
                }
                
                $getTotalMonths = 0;
                if(isset($work[$key + 1])){
                    $nextDateTo = ($work[$key + 1]->date_to == 'present') ? new DateTime($dateToday) : new DateTime($work[$key + 1]->date_to);
                    $dateDifference = $currentDateFrom->diff($nextDateTo)->days;
                    if ($dateDifference <= 3) {
                        $getTotalMonths = 1;
                    }
                }
                
                if ($getTotalMonths==0) {
                    $day = ($startDateTo->diff($currentDateFrom)->d>=30) ? 1 : 0;
                    $totalMonths = $startDateTo->diff($currentDateFrom)->y * 12 + $startDateTo->diff($currentDateFrom)->m + $day;
                    break;
                }
            }
        }

        return $totalMonths;

    }

    public function rendered_months_aggregate($user_id,$gov,$payroll){        
        $work = _Work::where('user_id',$user_id);
        if($gov=='Y' || $gov=='N'){
            $work = $work->where('gov_service',$gov);
        }
        $work = $work->orderBy('date_from','DESC')
            ->get();

        if($work->count()<=0){
            return 0;
        }

        $totalMonths = 0;
        $totalDays = 0;
        $x = 0;
        $dateToday = date('Y-m-d');

        foreach ($work as $key => $row) {
            if ($key <= $work->count() - 1) {
                $date_from = strtotime($row->date_from);
                $date_to = ($row->date_to == 'present') ? strtotime($dateToday) : strtotime($row->date_to);
                $break = 0;
                if($payroll->month_from!=NULL && $payroll->day_from!=NULL){
                    $year_check = date('Y');
                    if($payroll->preceding_year==1){
                        $year_check = date('Y')-1;
                    }
                    $date_check = strtotime($year_check.'-'.$payroll->month_from.'-'.$payroll->day_from);
                    if($date_from<$date_check){
                        $date_from = $date_check;
                        $break = 1;
                    }
                }
                
                if($payroll->month_as_of!=NULL && $payroll->day_as_of!=NULL){
                    $date_as_of = strtotime(date('Y').'-'.$payroll->month_as_of.'-'.$payroll->day_as_of);
                    if($date_to>=$date_as_of){
                        $date_to = $date_as_of;
                    }
                }
                
                $date_diff = abs($date_to-$date_from);
                $day_no = $date_diff / (60 * 60 * 24);
				$months = (round($day_no)/30);
                $months_int = (int)$months;
                $totalDays += $day_no-$months_int*30;
                $totalMonths += $months_int;
                if($break==1){
                    break;
                }
                $x++;
            }
        }
        $day_add = (int)($totalDays/30);
        
        // foreach ($work as $key => $row) {
        //     if ($key <= $work->count() - 1) {
        //         $date_from = new DateTime($row->date_from);
        //         $date_to = ($row->date_to == 'present') ? new DateTime(date('Y-m-d')) : new DateTime($row->date_to);
        //         $break = 0;
        //         if($payroll->month_from!=NULL && $payroll->day_from!=NULL){
        //             $year_check = date('Y');
        //             if($payroll->preceding_year==1){
        //                 $year_check = date('Y')-1;
        //             }
        //             $date_check = new DateTime($year_check.'-'.$payroll->month_from.'-'.$payroll->day_from);
        //             if($date_from<$date_check){
        //                 $date_from = $date_check;
        //                 $break = 1;
        //             }
        //         }
                
        //         if($payroll->month_as_of!=NULL && $payroll->day_as_of!=NULL){
        //             $date_as_of = new DateTime(date('Y').'-'.$payroll->month_as_of.'-'.$payroll->day_as_of);
        //             if($date_to>=$date_as_of){
        //                 $date_to = $date_as_of;
        //             }
        //         }
        //         $year = ($date_from->format('Y')==$date_to->format('Y')) ? 0 : $date_to->diff($date_from)->y * 12;
          
        //         $totalMonths += $year + $date_to->diff($date_from)->m;
                
        //         if($break==1){
        //             break;
        //         }
        //         $x++;
        //     }
        // }

        return $totalMonths+$day_add;

    }
    
    
}

?>