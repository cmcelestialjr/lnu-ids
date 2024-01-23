<?php

namespace App\Http\Controllers\RIMS\Schedule;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedSchedule;
use App\Models\EducRoom;
use Illuminate\Http\Request;

class _SelectRoomController extends Controller
{
    public function selectRoom(Request $request){
        $search = $request->input('search');
        $course_id = $request->id;
        
        $select_days = $request->select_days;
        $select_time = $request->select_time;
        $time_from = NULL;
        $time_to = NULL;
        if($select_time!='TBA'){
            $exp_time = explode('-',$select_time);
            $time_from = date('H:i',strtotime($exp_time[0]));
            $time_to = date('H:i',strtotime($exp_time[1]));
        }
        $results = EducRoom::where('name','like','%'.$search.'%')
            ->where(function ($query) use ($course_id,$select_time,$select_days,$time_from,$time_to) {
                if($select_time!='TBA' && $select_days!='TBA'){
                    $query->WhereDoesntHave('rooms', function ($query) use ($course_id,$select_days,$time_from,$time_to) {
                        $query->where('offered_course_id','<>',$course_id);
                        $query->where(function ($query) use ($time_from,$time_to) {
                            $query->where(function ($query) use ($time_from) {
                                $query->where('time_from','>=',$time_from)
                                ->where('time_to','<=',$time_from);
                            });
                            $query->orWhere(function ($query) use ($time_from) {
                                $query->where('time_from','<=',$time_from)
                                ->where('time_to','>',$time_from);
                            });
                            $query->orWhere(function ($query) use ($time_to) {
                                $query->where('time_from','<',$time_to)
                                ->where('time_to','>=',$time_to);
                            });
                            $query->orWhere(function ($query) use ($time_from) {
                                $query->where('time_from','>=',$time_from)
                                ->where('time_to','<=',$time_from);
                            });
                        });
                        $query->whereHas('days', function ($query) use ($select_days) {
                            $query->whereIn('no', $select_days);
                        });
                    });
                }else{
                    $query->whereHas('rooms');
                }
                $query->orWhereDoesntHave('rooms');
            })
            ->limit(20)
            ->get();
        $data = [];
        $data[] = ['id' => 'TBA', 'text' => 'TBA'];
        if($results->count()>0){            
            foreach ($results as $result) {
                $data[] = ['id' => $result->id, 'text' => $result->name];
            }
        }
        return response()->json($data);
    }
}