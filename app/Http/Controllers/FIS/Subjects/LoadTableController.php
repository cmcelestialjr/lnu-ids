<?php

namespace App\Http\Controllers\FIS\Subjects;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedSchoolYear;
use App\Models\StudentsCourses;
use App\Models\StudentsInfo;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoadTableController extends Controller
{
    public function subjectsTable(Request $request){
        $data = array();
        $user = Auth::user();
        $instructor_id = $user->id;
        $school_year = $request->school_year;
        $level = $request->level;
        $query = EducOfferedCourses::
            where('instructor_id',$instructor_id)
            ->whereHas('curriculum', function ($query) use ($school_year) {
                $query->whereHas('offered_program', function ($query) use ($school_year) {
                    $query->where('school_year_id',$school_year);
                });
            })
            ->whereHas('course', function ($query) use ($level) {
                $query->whereHas('grade_level', function ($query) use ($level) {
                    if($level==NULL){
                        $query->where('program_level_id','>',0);
                    }else{
                        $query->whereIn('program_level_id',$level);
                    }
                });
            })->get()
            ->map(function($query) {
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
                $no_student = '-';
                if(count($query->students)>0){
                    $no_student = count($query->students);
                }
                if($query->status_id==1){
                    $status = '<button class="btn btn-success btn-success-scan btn-xs">'.$query->status->name.'</button>';
                }else{
                    $status = '<button class="btn btn-danger btn-danger-scan btn-xs">'.$query->status->name.'</button>';
                }
                return [
                    'id' => $query->id,
                    'program' => $query->curriculum->offered_program->name.'-'.$query->curriculum->offered_program->program->shorten,
                    'curriculum' => $query->curriculum->code.'-'.$query->curriculum->curriculum->year_from.'-'.$query->curriculum->curriculum->year_to,
                    'section' => $query->section_code,
                    'course_code' => $query->code,
                    'units' => $query->course->units,
                    'schedule' => $schedule_implode,
                    'room' => $room_implode,
                    'no_student' => $no_student,
                    'status' => $status,
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['program'];
                $data_list['f3'] = $r['curriculum'];
                $data_list['f4'] = $r['section'];
                $data_list['f5'] = $r['course_code'];
                $data_list['f6'] = $r['units'];
                $data_list['f7'] = $r['schedule'];
                $data_list['f8'] = $r['room'];
                $data_list['f9'] = $r['no_student'];
                $data_list['f10'] = $r['status'];
                $data_list['f11'] = '<button class="btn btn-primary btn-primary-scan btn-sm studentsListModal"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-eye"></span> View
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    public function studentsListTable(Request $request){
        $name_services = new NameServices;
        $data = array();
        $user = Auth::user();
        $instructor_id = $user->id;
        $id = $request->id;
        $query = StudentsInfo::whereHas('courses', function ($query) use ($id) {
                $query->where('offered_course_id',$id);
            })->get()
            ->map(function($query) use ($name_services,$id) {
                $name = $name_services->lastname($query->info->lastname,
                                            $query->info->firstname,
                                            $query->info->middlename,
                                            $query->info->extname);
                $grade = StudentsCourses::where('offered_course_id',$id)
                    ->where('user_id',$query->user_id)
                    ->first();
                if($grade->final_grade==NULL){
                    $grade = 'NG';
                }else{
                    $grade = $grade->final_grade;
                }
                return [
                    'id' => $id,
                    'user_id' => $query->user_id,
                    'name' => $name,
                    'id_no' => $query->id_no,
                    'program' => $query->program->shorten,
                    'grade_level' => $query->grade_level->name,
                    'grade' => $grade
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['id_no'];
                $data_list['f4'] = $r['program'];
                $data_list['f5'] = $r['grade_level'];
                $data_list['f6'] = '<button class="btn btn-primary btn-primary-scan btn-sm studentGradeModal"
                                        data-id="'.$r['id'].'"
                                        data-sid="'.$r['user_id'].'">
                                        <span id="studentGrade'.$r['user_id'].'">'.$r['grade'].'</span>
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
}

?>