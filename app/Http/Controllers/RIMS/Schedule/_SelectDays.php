<?php

namespace App\Http\Controllers\RIMS\Schedule;
use App\Http\Controllers\Controller;
use App\Models\EducDay;
use Illuminate\Http\Request;

class _SelectDays extends Controller
{
    public function selectDays(Request $request){
        $search = $request->input('search');
        $select_days = $request->select_days;
        $select_time = $request->select_time;
        $get_day = $request->get_day;
        $get_time = $request->get_time;
        $data = [];
        if($get_day!=''){
            $get_days = array_unique($get_day);
            $x = 0;
            $with_day = [];
            $exclude_day = [];
            if($select_time!='TBA'){
                foreach($get_day as $day){
                    $time = $get_time[$x];
                    if(!in_array($day,$select_days)){
                        if($select_time==$time){
                            $with_day[] = $day;
                        }
                    }
                    $x++;
                }
                $with_day_unique = array_unique($with_day);
                foreach($get_days as $day){
                    if(!in_array($day,$with_day_unique)){
                        $exclude_day[] = $day;
                    }                
                }
            }
            if($select_days==''){
                $select_days = [];
            }
            $results = EducDay::whereIn('no',$get_days)
                ->whereNotIn('no',$select_days)
                ->whereNotIn('no',$exclude_day)       
                ->where('name','LIKE','%'.$search.'%')
                ->get();
            foreach ($results as $result) {
                $data[] = ['id' => $result->no, 'text' => $result->name];
            }
        }
        return response()->json($data);
    }
}