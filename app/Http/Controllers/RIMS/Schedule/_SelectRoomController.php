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
        $course_id = $request->course_id;
        $schedule_id = $request->schedule_id;
        $schedule = EducOfferedSchedule::where('id',$schedule_id)->first();
        $room_ids = EducRoom::
            whereHas('rooms', function ($query) use ($course_id,$schedule) {
                $query->where('offered_course_id',$course_id);
                if($schedule!=NULL){
                    $query->where(function ($query) use ($schedule) {
                        $query->where(function ($query) use ($schedule) {
                            $query->where('time_from','>=',$schedule->time_from)
                            ->where('time_to','<=',$schedule->time_from);
                        });
                        $query->orWhere(function ($query) use ($schedule) {
                            $query->where('time_from','<=',$schedule->time_from)
                            ->where('time_to','>',$schedule->time_from);
                        });
                        $query->orWhere(function ($query) use ($schedule) {
                            $query->where('time_from','<',$schedule->time_to)
                            ->where('time_to','>=',$schedule->time_to);
                        });
                        $query->orWhere(function ($query) use ($schedule) {
                            $query->where('time_from','>=',$schedule->time_from)
                            ->where('time_to','<=',$schedule->time_from);
                        });
                    });                
                    if(count($schedule->days)>0){
                        foreach($schedule->days as $day){
                            $days[] = $day->day;
                        }
                        $query->whereHas('days', function ($query) use ($days) {
                            $query->whereIn('day', $days);
                        });
                    }
                }
            })->pluck('id')->toarray();
        $results = EducRoom::whereNotIn('id',$room_ids)
            ->where('name','like','%'.$search.'%')
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