<?php

namespace App\Http\Controllers\RIMS\AddDrop;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedSchoolYear;
use App\Models\StudentsCourses;
use App\Models\StudentsProgram;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddDropController extends Controller
{
    public function dropDiv(Request $request){
        return $this->_dropDiv($request);
    }
    public function dropSubmit(Request $request){
        return $this->_dropSubmit($request);
    }
    public function addSubmit(Request $request){
        return $this->_addSubmit($request);
    }

    
    private function _dropDiv(Request $request){
        $name_services = new NameServices;
        $school_year = $request->school_year;
        $student_id = $request->student_id;
        $school_year_info = EducOfferedSchoolYear::find($school_year);
        $coursesList = StudentsCourses::where('user_id',$student_id)
            ->where('school_year_id',$school_year)
            ->get()
            ->map(function($query) use ($name_services) {
                $schedule = 'TBA';
                $room = 'TBA';
                $instructor = 'TBA';
                if($query->instructor_id!=NULL){
                    $instructor = $name_services->firstname($query->course->instructor->lastname,
                                $query->course->instructor->firstname,
                                $query->course->instructor->middlename,
                                $query->course->instructor->extname);
                }
                if(count($query->course->schedule)>0){
                    foreach($query->course->schedule as $row){
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
                $status = NULL;
                $option = NULL;
                if($query->student_course_status_id!=NULL){
                    $option = $query->status->option;
                    $status = $query->status->shorten;
                }
                return [
                    'id' => $query->id,
                    'program' => $query->course->curriculum->offered_program->name.'-'.
                                $query->course->curriculum->offered_program->program->shorten,
                    'code' => $query->course->code,
                    'grade_level' => $query->course->course->grade_level->name,
                    'units' => $query->course->course->units,
                    'section' => $query->course->section,
                    'schedule' => $schedule,
                    'room' => $room,
                    'instructor' => $instructor,
                    'curriculum' => $query->course->curriculum->curriculum->year_from.'-'.
                                    $query->course->curriculum->curriculum->year_to.' ('.
                                    $query->course->curriculum->code.')',
                    'status' => $status,
                    'option' => $option
                ];
            })->toArray();
        $addDropStatus = $this->dateCheckStatus($school_year_info);
        $data = array(
            'coursesList' => $coursesList,
            'addDropStatus' => $addDropStatus
        );
        return view('rims/addDrop/dropDiv',$data);
    }    
    private function _dropSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');       
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $user = Auth::user();
            $updated_by = $user->id;

            $school_year = $request->school_year;
            $student_id = $request->student_id;
            $courses = $request->courses;

            $school_year_info = EducOfferedSchoolYear::find($school_year);
            $addDropStatus = $this->dateCheckStatus($school_year_info);
            if($addDropStatus==1){
                StudentsCourses::whereIn('id', $courses)
                ->update([
                    'student_course_status_id' => 9,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $result = 'success';
            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    private function _addSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');       
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $user = Auth::user();
            $updated_by = $user->id;

            $school_year = $request->school_year;
            $student_id = $request->student_id;
            $courses = $request->courses;
            $cid = $request->cid;

            $school_year_info = EducOfferedSchoolYear::find($school_year);
            $addDropStatus = $this->dateCheckStatus($school_year_info);
            if($addDropStatus==1){
                $x = 0;
                $getStudentProgram = StudentsProgram::where('user_id',$student_id)
                    ->orderBy('year_from','DESC')
                    ->first();
                foreach($courses as $course){                
                    $check = StudentsCourses::where('user_id',$student_id)
                        ->where('offered_course_id',$course)->first();
                    if($check==NULL){
                        if($cid[$x]==''){
                            $credit_course_id = NULL;
                        }else{
                            $credit_course_id = $cid[$x];
                        }
                        //dd($credit_course_id);
                        $offered_course = EducOfferedCourses::where('id',$course)->first();                  
                        $insert = new StudentsCourses(); 
                        $insert->student_program_id = $getStudentProgram->id;
                        $insert->user_id = $student_id;
                        $insert->school_year_id = $school_year;                        
                        $insert->offered_course_id = $course;
                        $insert->course_id = $offered_course->course_id;
                        $insert->grade_level_id = $getStudentProgram->grade_level_id;
                        $insert->program_level_id = $getStudentProgram->program_level_id;
                        $insert->grade_period_id = $school_year_info->grade_period_id;
                        $insert->course_code = $offered_course->course->code;
                        $insert->course_desc = $offered_course->course->name;
                        $insert->course_units = $offered_course->course->units;
                        $insert->lab_units = $offered_course->course->lab;
                        $insert->school_name = 'Leyte Normal University';
                        $insert->program_name = $getStudentProgram->program_info->name;
                        $insert->program_shorten = $getStudentProgram->program_info->shorten;
                        $insert->year_from = $school_year_info->year_from;
                        $insert->year_to = $school_year_info->year_to;
                        $insert->type_id = 1;
                        $insert->add_status = 'Add';
                        $insert->credit_course_id = $credit_course_id;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                    }
                    $x++;
                }
                $result = 'success';
            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    private function dateCheckStatus($school_year_info){
        $addDropStatus = 0; 
        if($school_year_info->add_dropping_from<=date('Y-m-d') && ($school_year_info->add_dropping_to>=date('Y-m-d')) || $school_year_info->add_dropping_extension>=date('Y-m-d')){
            $addDropStatus = 1;
        }
        return $addDropStatus;
    }
}

?>