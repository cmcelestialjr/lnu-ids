<?php

namespace App\Http\Controllers\SIMS\Preenroll;
use App\Http\Controllers\Controller;
use App\Models\EducCourses;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedPrograms;
use App\Models\StudentsCoursesAdvise;
use App\Models\StudentsCoursesPreenroll;
use App\Models\StudentsInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PreenrollController extends Controller
{    
    public function preenrollSubmit(Request $request){
        $user = Auth::user();
        $student_id = $user->id;
        $school_year_id = $request->school_year_id;
        $course_id = $request->course_id;
        $course_option = $request->course_option;
        $result = 'error';

        $rules = [
            'school_year_id' => 'required|numeric',
            'course_id' => 'required|array',
            'course_option' => 'required|array',
        ];
    
        $customMessages = [
            'school_year_id.required' => 'The school year ID is required.',
            'course_id.required' => 'At least one course must be selected.',
            'course_id.array' => 'Invalid format for course ID.',
            'course_option.required' => 'The course option is required.',
            'course_option.array' => 'Invalid format for course option.',
        ];
    
        $validator = Validator::make($request->all(), $rules, $customMessages);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400); // Return validation errors
        }
        //DB::beginTransaction();
        try {
            
            $x = 0;
            $count_course = count($course_id);
            
            $check = StudentsCoursesPreenroll::select('id')
                ->where('user_id',$student_id)
                ->where('school_year_id',$school_year_id)->first();
            if($check==NULL){
                if (in_array(1, $course_option)) {
                    $option = 'Regular';
                }else{
                    $option = 'Irregular';
                }                
                // $get_course_id = $course_id[$count_course];

                $student_info = StudentsInfo::select('program_code_id')
                    ->where('user_id',$student_id)->first();                
                // $get_course = EducCourses::select('grade_level_id')->find($get_course_id);
                // $year_level_id = $get_course->grade_level_id;
                
                foreach($course_id as $course){
                    $educ_course = EducCourses::with('curriculum.programs')
                        ->select('curriculum_id','grade_level_id')
                        ->where('id',$course)->first();
                    $insert = new StudentsCoursesPreenroll(); 
                    $insert->school_year_id = $school_year_id;
                    $insert->user_id = $student_id;
                    $insert->department_id = $educ_course->curriculum->programs->department_id;
                    $insert->program_id = $educ_course->curriculum->program_id;
                    $insert->program_code_id = $student_info->program_code_id;
                    $insert->curriculum_id = $educ_course->curriculum_id;
                    $insert->course_id = $course;
                    $insert->year_level_id = $educ_course->grade_level_id;
                    $insert->option = $option;
                    $insert->updated_by = $student_id;
                    $insert->save();
                    
                    $x++;
                }
                if($x==$count_course){
                   // DB::commit();
                    $result = 'success';
                }else{
                    //DB::rollBack();
                }
            }          
        }catch(Exception $e){
           // DB::rollBack();
        }
        
        $response = array('result' => $result
                        );
        return response()->json($response);

    }
}