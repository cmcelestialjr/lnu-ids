<?php

namespace App\Http\Controllers\FIS\Subjects;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use App\Models\StudentsCourses;
use App\Models\StudentsInfo;
use App\Models\StudentsProgram;
use App\Services\NameServices;
use Illuminate\Http\Request;

class ModalController extends Controller
{
    public function subjectsViewModal(Request $request){
        $id = $request->id;
        $query = StudentsInfo::where('user_id',$id)->first();
        $program_level = StudentsProgram::where('user_id',$id)
                ->select('program_level_id','curriculum_id')
                ->orderBy('program_level_id','DESC')->first();
        $data = array(
            'id' => $id,
            'query' => $query,
            'program_level' => $program_level->program_level_id,
            'curriculum' => $program_level->curriculum_id
        );
        return view('fis/subjects/subjectsViewModal',$data);
    }
    public function studentsListModal(Request $request){
        $id = $request->id;
        $query = EducOfferedCourses::where('id',$id)->first();
        $schedule_implode = 'TBA';
        $room_implode = 'TBA';
        if(count($query->schedule)>0){
            foreach($query->schedule as $row){
                $days = array();
                if($row->room_id==NULL){
                    $room = 'TBA';
                }else{
                    $room = $row->room->name;
                }
                foreach($row->days as $day){
                    $days[] = $day->day;
                }
                $days1 = implode('',$days);
                $rooms[] = $room;
                $schedules[] = date('h:ia',strtotime($row->time_from)).'-'.
                                    date('h:ia',strtotime($row->time_to)).' '.$days1;
            }
            $schedule_implode = implode('<br>',$schedules);
            $room_implode = implode('<br>',$rooms);                                                        
        }
        if($query->status_id==1){
            $status = '<button class="btn btn-success btn-success-scan btn-xs">'.$query->status->name.'</button>';
        }else{
            $status = '<button class="btn btn-danger btn-danger-scan btn-xs">'.$query->status->name.'</button>';
        }
        $data = array(
            'id' => $id,
            'query' => $query,
            'schedule' => $schedule_implode,
            'room' => $room_implode,
            'status' => $status
        );
        return view('fis/subjects/studentsListModal',$data);
    }
    public function studentGradeModal(Request $request){
        $name_services = new NameServices;
        $id = $request->id;
        $sid = $request->sid;
        $query = StudentsCourses::where('offered_course_id',$id)
            ->where('user_id',$sid)->first();
        $name = $name_services->lastname($query->student_info->info->lastname,
                                            $query->student_info->info->firstname,
                                            $query->student_info->info->middlename,
                                            $query->student_info->info->extname);
        $data = array(
            'id' => $id,
            'sid' => $sid,
            'query' => $query,
            'name' => $name
        );
        return view('fis/subjects/studentGradeModal',$data);
    }
}