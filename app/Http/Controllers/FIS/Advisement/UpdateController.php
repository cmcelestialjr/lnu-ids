<?php

namespace App\Http\Controllers\FIS\Advisement;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedPrograms;
use App\Models\StudentsCoursesAdvise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{    
    public function advisementSubmit(Request $request){
        $user = Auth::user();
        $instructor_id = $user->id;
        $school_year_id = $request->school_year_id;
        $student_id = $request->student_id;
        $code = $request->code;
        $curriculum_id = $request->curriculum_id;
        $section = $request->section;
        $courses = $request->courses;
        $cid = $request->cid;
        $result = 'error';
        try {
            $delete = StudentsCoursesAdvise::where('user_id',$student_id)
                ->where('school_year_id',$school_year_id)
                ->where('status',NULL)
                ->where('updated_by',$instructor_id)->delete();
            $auto_increment = DB::update("ALTER TABLE students_courses_advise AUTO_INCREMENT = 0;");
            $x = 0;
            foreach($courses as $course){                
                $check = StudentsCoursesAdvise::where('user_id',$student_id)
                    ->where('offered_course_id',$course)
                    ->where('status','<>',1)->first();
                if($check==NULL){
                    if($cid[$x]==''){
                        $credit_course_id = NULL;
                    }else{
                        $credit_course_id = $cid[$x];
                    }
                    $offered_course = EducOfferedCourses::where('id',$course)->first();                  
                    $insert = new StudentsCoursesAdvise(); 
                    $insert->school_year_id = $school_year_id;
                    $insert->user_id = $student_id;
                    $insert->offered_course_id = $course;
                    $insert->offered_curriculum_id = $offered_course->offered_curriculum_id;
                    $insert->program_id = $offered_course->curriculum->offered_program->program_id;
                    $insert->section = $offered_course->section;
                    $insert->credit_course_id = $credit_course_id;
                    $insert->updated_by = $instructor_id;
                    $insert->save();
                }
                $x++;
            }
            $result = 'success';
        }catch(Exception $e){
                            
        }
        $response = array('result' => $result
                        );
        return response()->json($response);

    }
}