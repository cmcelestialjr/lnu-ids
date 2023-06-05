<?php

namespace App\Http\Controllers\RIMS\Schedule;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedSchedule;
use App\Models\Users;
use App\Services\NameServices;
use Illuminate\Http\Request;

class _SelectInstructorController extends Controller
{
    public function selectInstructor(Request $request){
        $name_services = new NameServices;
        $search = $request->input('search');
        $course_id = $request->course_id;
        $schedule_id = $request->schedule_id;
        $schedule = EducOfferedSchedule::where('id',$schedule_id)->first();
        $course = EducOfferedCourses::where('id',$course_id)->first();
        $school_year_id = $course->curriculum->offered_program->school_year_id;        
        if($schedule!=NULL){
            $user_ids = Users::
                whereHas('user_role', function($query){
                    $query->where('role_id', 3);
                })
                ->whereHas('courses', function ($query) use ($schedule,$school_year_id) {
                    $query->whereHas('curriculum', function ($query) use ($school_year_id) {
                        $query->whereHas('offered_program', function ($query) use ($school_year_id) {
                            $query->where('school_year_id',$school_year_id);
                        });
                    });
                    $query->whereHas('schedule', function ($query) use ($schedule) {
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
                                $dayss[] = $day->day;
                            }
                            $query->whereHas('days', function ($query) use ($dayss) {
                                $query->whereIn('day', $dayss);
                            });
                        }
                    });
                })->pluck('id')->toArray();
        }else{
            $user_ids = array();
        }
        $results = Users::whereNotIn('id',$user_ids)
            ->whereHas('user_role', function($query){
                $query->where('role_id', 3);
            })
            ->where(function($query) use ($search) {
                $query->where('lastname', 'LIKE', "%$search%")
                ->orWhere('firstname', 'LIKE', "%$search%");
            })
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->limit(20)
            ->get();
        $data = [];
        $data[] = ['id' => 'TBA', 'text' => 'TBA'];
        if($results->count()>0){            
            foreach ($results as $result) {
                $instructor = $name_services->lastname($result->lastname,$result->firstname,$result->middlename,$result->extname);
                $data[] = ['id' => $result->id, 'text' => $instructor];
            }
        }
        return response()->json($data);
    }
}