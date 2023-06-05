<?php

namespace App\Http\Controllers\RIMS\Enrollment;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\StudentsCourses;
use App\Models\StudentsCoursesAdvise;
use App\Models\StudentsInfo;
use App\Models\StudentsProgram;
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
    public function courseAddSubmit(Request $request){
        $name_services = new NameServices;
        $courses = $request->courses;
        $result = 'error';
        $query = EducOfferedCourses::whereIn('id',$courses)
                    ->get()
                    ->map(function($query) use ($name_services) {
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
                        return [
                            'id' => $query->id,
                            'program' => $query->curriculum->offered_program->name.'-'.$query->curriculum->offered_program->program->shorten,
                            'code' => $query->code,
                            'name' => $query->course->name,
                            'units' => $query->course->units,
                            'section' => $query->section_code,
                            'schedule' => $schedule,
                            'room' => $room,
                            'instructor' => $instructor
                        ];
                    })->toArray();
        if(count($query)>0){
            $result = 'success';
        }
        $response = array('result' => $result,
                          'query' => $query
                        );
        return response()->json($response);
    }
    public function enrollAdvisedSubmit(Request $request){
        $courses = $request->courses;
        $cid = $request->cid;
        $query = StudentsCoursesAdvise::where('id',$courses[count($courses)-1])->first();
        $offered_course_id = StudentsCoursesAdvise::whereIn('id',$courses)->pluck('offered_course_id')->toArray();
        $student_id = $query->user_id;
        $program_id = $query->program_id;
        $curriculum_id = $query->offered_curriculum_id;
        $response = $this->enrollSubmitStudent($request,$student_id,$program_id,$curriculum_id,$offered_course_id,$cid);
        if($response['result']=='success'){
            StudentsCoursesAdvise::whereIn('id',$courses)
                                ->update(['status' => 1,
                                        'updated_at' => date('Y-m-d H:i:s')]);
        }
        return response()->json($response);
    }
    public function enrollSubmit(Request $request){
        $student_id = $request->student_id;
        $program_id = $request->program_id;
        $curriculum_id = $request->curriculum_id;
        $courses = $request->courses;
        $cid = $request->cid;
        $response = $this->enrollSubmitStudent($request,$student_id,$program_id,$curriculum_id,$courses,$cid);
        return response()->json($response);
    }
    private function enrollSubmitStudent($request,$student_id,$program_id,$curriculum_id,$courses,$cid){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $result = 'error';
        
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            if(count($courses)>=1){                
                $student = StudentsInfo::where('user_id',$student_id)->first();
                $student_program = StudentsProgram::where('user_id',$student_id)->where('program_id',$program_id)
                        ->where('from_school','Leyte Normal University')
                        ->orderBy('year_from','DESC')->first();
                $x = 0;
                foreach($courses as $course){
                    $query = EducOfferedCourses::where('id',$course)->first();
                    $school_year_id = $query->curriculum->offered_program->school_year->id;
                    if($cid[$x]!=$query->course_id){
                        $credit_course_id = $cid[$x];
                    }else{
                        $credit_course_id = NULL;
                    }
                    $insert = new StudentsCourses();
                    $insert->school_year_id = $school_year_id;
                    $insert->user_id = $student_id;
                    $insert->student_program_id = $student_program->id;
                    $insert->offered_course_id = $course;
                    $insert->course_id = $query->course_id;
                    $insert->course_code = $query->code;
                    $insert->course_desc = $query->course->name;
                    $insert->course_units = $query->course->units;
                    $insert->lab_units = $query->course->lab;
                    $insert->school_name = 'Leyte Normal University';
                    $insert->year_from = $query->curriculum->offered_program->school_year->year_from;
                    $insert->year_to = $query->curriculum->offered_program->school_year->year_to;
                    $insert->grade_period_id = $query->curriculum->offered_program->school_year->grade_period_id;
                    $insert->type_id = 1;
                    $insert->credit_course_id = $credit_course_id;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                    $x++;
                }
                $student_course_ids = StudentsCourses::where('student_program_id',$student_program->id)
                        ->where('school_year_id',$school_year_id)
                        ->pluck('offered_course_id')
                        ->toArray();
                $query = EducOfferedCourses::whereIn('id',$student_course_ids)
                        ->get()
                        ->map(function($query) {
                            return $query->course->grade_level_id;
                        })->toArray();
                $year_levels = array_count_values($query);
                $max_count = max($year_levels);
                $grade_level_id = array_search($max_count,$year_levels);

                $student_course = StudentsCourses::where('student_program_id',$student_program->id)
                        ->where('type_id',1)
                        ->where('school_year_id','<>',$school_year_id)->first();
                $year_from = StudentsCourses::where('student_program_id',$student_program->id)->orderBy('year_from','ASC')->first();
                $year_to = StudentsCourses::where('student_program_id',$student_program->id)->orderBy('year_to','DESC')->first();
                $student_status_id = $student->student_status_id;
                if($student_course!=NULL){
                    $student_status_id = 2;
                }
                $curriculum = EducOfferedCurriculum::where('id',$curriculum_id)->first();

                StudentsCourses::where('student_program_id',$student_program->id)
                                ->where('school_year_id',$school_year_id)
                                ->update(['grade_level_id' => $grade_level_id,
                                        'updated_by' => $updated_by,
                                        'updated_at' => date('Y-m-d H:i:s')]);

                StudentsProgram::where('id',$student_program->id)
                                ->update(['curriculum_id' => $curriculum->curriculum_id,
                                        'year_from' => $year_from->year_from,
                                        'year_to' => $year_to->year_to,
                                        'student_status_id' => $student_status_id,
                                        'updated_by' => $updated_by,
                                        'updated_at' => date('Y-m-d H:i:s')]);
                
                StudentsInfo::where('user_id',$student_id)
                                ->update(['program_id' => $program_id,
                                        'curriculum_id' => $curriculum->curriculum_id,
                                        'grade_level_id' => $grade_level_id,
                                        'student_status_id' => $student_status_id,
                                        'program_level_id' => $student_program->program_level_id,
                                        'updated_by' => $updated_by,
                                        'updated_at' => date('Y-m-d H:i:s')]);

                $result = 'success';
            }
        }
        $response = array('result' => $result,
                          'courses' => $grade_level_id
                        );
        return $response;        
    }
}