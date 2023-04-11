<?php

namespace App\Http\Controllers\RIMS\Enrollment;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class UpdateController extends Controller
{    
    public function courseAnotherSubmit(Request $request){
        $name_services = new NameServices;
        $course_id = $request->course_id;
        $course_id_another = $request->course_id_another;
        $query = EducOfferedCourses::where('id',$course_id_another)->first();
        $result = 'error';
        $code = '';
        $name = '';
        $units = '';
        $pre_name = '';           
        $schedule = '';
        $room = '';
        $instructor = '';
        $status = '';
        if($query!=NULL){
            $result = 'success';
            $code = $query->course->code.' ('.$query->curriculum->offered_program->name.'-'.$query->curriculum->offered_program->program->shorten.')';
            $name = $query->course->name;
            $units = $query->course->units;
            $pre_name = $query->course->pre_name;
            $status = $query->status->name;      
            $schedule = 'TBA';
            $room = 'TBA';
            $instructor = 'TBA';
            if($query->instructor_id!=NULL){
                $instructor = $name_services->firstname($query->instructor->lastname,$query->instructor->firstname,$query->instructor->middlename,$query->instructor->extname);
            }
            if(count($query->schedule)>0){
                foreach($query->schedule as $row){
                    $days = array();
                    if($row->room_id==NULL){
                        $rm = 'TBA';
                    }else{
                        $rm = $row->room->name;
                    }
                    foreach($row->days as $day){
                        $days[] = $day->day;
                    }
                    $days1 = implode('',$days);
                    $schedules[] = date('h:ia',strtotime($row->time_from)).'-'.
                                        date('h:ia',strtotime($row->time_to)).' '.$days1;
                    $rooms[] = $rm;
                }
                $schedule = implode('<br>',$schedules);
                $room = implode('<br>',$rooms);
            }
        }
        $response = array('result' => $result,
                          'code' => $code,
                          'name' => $name,
                          'units' => $units,
                          'pre_name' => $pre_name,
                          'schedule' => $schedule,
                          'room' => $room,
                          'instructor' => $instructor,
                          'status' => $status
                        );
        return response()->json($response);
    }
}