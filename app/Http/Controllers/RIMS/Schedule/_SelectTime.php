<?php

namespace App\Http\Controllers\RIMS\Schedule;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedSchedule;
use Illuminate\Http\Request;

class _SelectTime extends Controller
{
    public function selectTime(Request $request){
        $search = $request->input('search');
        $schedule_id = $request->schedule_id;
        $select_days = $request->select_days;
        $get_day = $request->get_day;
        $get_time = $request->get_time;
        $query = EducOfferedSchedule::where('id',$schedule_id)->first();
        $sched_time = '';
        if($query!=NULL){
            $sched_time = date('h:ia',strtotime($query->time_from)).'-'.date('h:ia',strtotime($query->time_to));
        }
        $data = [];
        $available_time = [];
        if($get_day!=''){
            $x = 0;
            foreach($get_day as $day){
                $time = $get_time[$x];
                if($select_days!=''){
                    if(in_array($day,$select_days)){
                        $available_time[] = $time;
                    }
                }else{
                    $available_time[] = $time;
                }                
                $x++;
            }
            if($select_days!=''){
                if(count($select_days)==1){
                    $results = array_unique($available_time);
                }else{
                    $count = array_count_values($available_time);
                    $unique = array_filter($available_time, function($elem) use ($count) {
                        return $count[$elem] == 1;
                    });
                    if (!empty($unique)) {
                        foreach ($unique as $value) {
                            if (($key = array_search($value, $available_time)) !== false) {
                                unset($available_time[$key]);
                            }
                        }
                    }
                    $results = array_unique($available_time);
                }
            }else{
                $results = array_unique($available_time);
            }
            $data[] = ['id' => 'TBA', 'text' => 'TBA'];
            if($select_days!=''){
                foreach ($results as $result) {
                    if($sched_time!=$result){
                        if($search!=''){
                            if (strpos($result, $search) !== false) {
                                $data[] = ['id' => $result, 'text' => $result];
                            }
                        }else{
                            $data[] = ['id' => $result, 'text' => $result];
                        }
                    }
                }
            }
        }
        return response()->json($data);
    }
}