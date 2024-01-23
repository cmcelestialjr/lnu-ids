<?php

namespace App\Http\Controllers\RIMS\Student;
use App\Http\Controllers\Controller;
use App\Models\EducBranch;
use App\Models\EducCourses;
use App\Models\EducCurriculum;
use App\Models\EducPrograms;
use App\Models\EducProgramsCode;
use App\Models\EducYearLevel;
use App\Models\StudentsCourses;
use App\Models\StudentsCourseStatus;
use App\Models\StudentsInfo;
use App\Models\StudentsProgram;
use App\Models\StudentsTOR;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UpdateController extends Controller
{
    public function studentShiftModalSubmit(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $result = 'error';
        $id = $request->id;
        $shift_to = $request->shift_to;
        $branch = $request->branch;
        $curriculum = $request->curriculum;

        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $checkShift_to = EducPrograms::find($shift_to);
            $checkBranch = EducBranch::find($branch);
            $checkCurriculum = EducCurriculum::where('id',$curriculum)
                ->where('program_id',$shift_to)->first();
            if($checkShift_to!=NULL && $checkBranch!=NULL && $checkCurriculum!=NULL){
               // try{
                    $program_code = EducProgramsCode::where('program_id',$shift_to)
                        ->where('branch_id',$branch)
                        ->first();
                    if($program_code==NULL){
                        $program_code = EducProgramsCode::where('program_id',$shift_to)
                            ->where('branch_id',1)
                            ->first();
                    }
                    $program_code_id = $program_code->id;
                    $program_level_id = $checkShift_to->program_level_id;

                    $insert = new StudentsProgram(); 
                    $insert->user_id = $id;
                    $insert->program_id = $shift_to;
                    $insert->program_level_id = $program_level_id;
                    $insert->program_code_id = $program_code_id;
                    $insert->curriculum_id = $curriculum;
                    $insert->year_from = date('Y');
                    $insert->year_to = date('Y')+1;
                    $insert->from_school = 'Leyte Normal University';
                    $insert->student_status_id = 3;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                    $student_program_id = $insert->id;

                    $course_statuses = StudentsCourseStatus::where('option',1)
                        ->pluck('id')
                        ->toArray();
                    $courses = EducCourses::where('curriculum_id',$curriculum);
                    $courses_id = $courses->pluck('id')->toArray();
                    $courses = $courses->get();
                    if($courses->count()>0){
                        foreach($courses as $row){
                            $student_course = StudentsCourses::where('user_id',$id)
                                ->where('course_id','<>',$row->id)
                                ->where('course_id','>=',1)
                                ->where('course_code',$row->code)
                                ->whereIn('student_course_status_id',$course_statuses)
                                ->first();
                            if($student_course!=NULL){

                                StudentsCourses::where('id', $student_course->id)
                                    ->update([
                                    'credit_course_id' => $row->id,
                                    'updated_by' => $updated_by,
                                    'updated_at' => date('Y-m-d H:i:s')]);

                                // $insert = new StudentsCourses(); 
                                // $insert->student_program_id = $student_program_id;
                                // $insert->user_id = $id;
                                // $insert->grade_level_id = $row->grade_level_id;
                                // $insert->program_level_id = $program_level_id;
                                // $insert->grade_period_id = $row->grade_period_id;
                                // $insert->course_id = $row->id;
                                // $insert->course_code = $row->code;
                                // $insert->course_desc = $row->name;
                                // $insert->course_units = $row->units;
                                // $insert->lab_units = $row->lab;
                                // $insert->school_name = 'Leyte Normal University';
                                // $insert->program_name = $checkShift_to->name;
                                // $insert->program_shorten = $checkShift_to->shorten;                                
                                // $insert->type_id = 2;
                                // $insert->credit_course_id = $student_course->course_id;
                                // $insert->grade = $student_course->grade;
                                // $insert->final_grade = $student_course->final_grade;
                                // $insert->graded_by = $student_course->graded_by;
                                // $insert->student_course_status_id = $student_course->student_course_status_id;
                                // $insert->updated_by = $updated_by;
                                // $insert->save();
                            }
                        }
                    }
                    
                    $get_grade_level = StudentsCourses::select('grade_level_id')
                        ->where('user_id',$id)
                        ->whereIn('credit_course_id',$courses_id)
                        ->groupBy('grade_level_id')
                        ->havingRaw('COUNT(grade_level_id) > 2')
                        ->orderBy('grade_level_id', 'DESC')
                        ->first();
                    if($get_grade_level==NULL){
                        $get_program_level_id = $checkShift_to->program_level_id;
                        $get_year_level = EducYearLevel::
                            whereHas('program_level', function ($query) use ($get_program_level_id) {
                                $query->where('id', $get_program_level_id);
                            })->orderBy('level','ASC')->first();
                        $grade_level_id = $get_year_level->id;
                    }else{
                        $grade_level_id = $get_grade_level->grade_level_id;
                    }
                    
                    StudentsProgram::where('id', $student_program_id)
                        ->update([
                                  'program_level_id' => $program_level_id,
                                  'updated_by' => $updated_by,
                                  'updated_at' => date('Y-m-d H:i:s')]);

                    StudentsInfo::where('user_id', $id)
                        ->update(['program_id' => $shift_to,
                                  'program_code_id' => $program_code_id,
                                  'program_level_id' => $program_level_id,
                                  'curriculum_id' => $curriculum,
                                  'grade_level_id' => $grade_level_id,
                                  'student_status_id' => 3,
                                  'updated_by' => $updated_by,
                                  'updated_at' => date('Y-m-d H:i:s')]);
                    $result = 'success';
                // }catch(Exception $e){
                    
                // }
            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    public function studentCourseAddModalSubmit(Request $request){
        $user_access_level = $request->session()->get('user_access_level');        
        $result = 'error';

        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $user = Auth::user();
            $updated_by = $user->id;

            $id = $request->id;
            $student_program = $request->student_program;
            $level = $request->level;
            $in_list = $request->in_list;
            $from_school = $request->from_school;
            $program_name = $request->program_name;
            $program_shorten = $request->program_shorten;
            $year_from = $request->year_from;
            $period = $request->period;
            $option = $request->option;
            $course_codes = $request->course_codes;
            $course_descs = $request->course_descs;
            $units = $request->units;
            $labs = $request->labs;
            $statuses = $request->statuses;
            $ratings = $request->ratings;

            if($in_list=='No'){
                $get = StudentsCourses::find($student_program);
                $from_school = $get->school_name;
                $program_name = $get->program_name;
                $program_shorten = $get->program_shorten;
            }

            $get_student_program = StudentsProgram::where('user_id',$id)
                ->orderBy('year_from','DESC')
                ->first();

            $x = 0;
            foreach($course_codes as $course_code){
                $insert = new StudentsCourses(); 
                $insert->student_program_id = $get_student_program->id;
                $insert->user_id = $id;
                $insert->grade_level_id = $get_student_program->grade_level_id;
                $insert->program_level_id = $level;
                $insert->grade_period_id = $period;
                $insert->course_code = $course_codes[$x];
                $insert->course_desc = $course_descs[$x];
                $insert->course_units = $units[$x];
                $insert->lab_units = $labs[$x];
                $insert->school_name = $from_school;
                $insert->program_name = $program_name;
                $insert->program_shorten = $program_shorten;
                $insert->year_from = $year_from;
                $insert->year_to = $year_from+1;
                $insert->type_id = 3;
                $insert->grade = $ratings[$x];
                $insert->final_grade = $ratings[$x];
                $insert->student_course_status_id = $statuses[$x];
                $insert->option = $option;
                $insert->updated_by = $updated_by;
                $insert->save();
                $x++;
            }
            $result = 'success';

        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    public function studentCourseCreditSubmit(Request $request){
        $user_access_level = $request->session()->get('user_access_level');        
        $result = 'error';

        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $user = Auth::user();
            $updated_by = $user->id;

            $id = $request->id;
            $curriculumCourseID = $request->curriculumCourseID;
            $courseOtherID = $request->courseOtherID;

            StudentsCourses::where('user_id', $id)
                ->where('credit_course_id', $curriculumCourseID)
                                    ->update([
                                    'credit_course_id' => NULL,
                                    'credited_by_id' => NULL,
                                    'credited_at' => NULL]);
            
            StudentsCourses::where('id', $courseOtherID)
                                    ->update([
                                    'credit_course_id' => $curriculumCourseID,
                                    'credited_by_id' => $updated_by,
                                    'credited_at' => date('Y-m-d H:i:s')]);

            $level = $request->level;
            $program = $request->program;
            $branch = $request->branch;
            $curriculum = $request->curriculum;
            StudentsProgram::where('user_id', $id)
                ->where('program_id', $program)
                ->where('program_level_id', $level)
                ->where('program_code_id', $branch)
                                ->update([
                                    'curriculum_id' => $curriculum]);
                
            $program = StudentsProgram::where('user_id',$id)
                ->orderBy('year_from','DESC')
                ->orderBy('id','DESC')
                ->first();
            StudentsInfo::where('user_id', $id)
                                ->update([
                                    'program_id' => $program->program_id,
                                    'program_code_id' => $program->program_code_id,
                                    'program_level_id' => $program->program_level_id,
                                    'curriculum_id' => $program->curriculum_id,
                                    'student_status_id' => $program->student_status_id]);
            $result = 'success';
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    public function studentCreditRemove(Request $request){
        $user_access_level = $request->session()->get('user_access_level');        
        $result = 'error';

        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $user = Auth::user();
            $updated_by = $user->id;

            $id = $request->id;
            $cid = $request->cid;

            StudentsCourses::where('user_id', $id)
                ->where('credit_course_id', $cid)
                                    ->update([
                                    'credit_course_id' => NULL,
                                    'credited_by_id' => NULL,
                                    'credited_at' => NULL]);
                                    
            $result = 'success';
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    public function studentPrintSubmit(Request $request){
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $level = $request->level;
        $purpose = $request->purpose;
        $remarks = $request->remarks;
        $result = 'error';
        $dateTime = date('Y-m-d_H:i:s');
        $id_no = '';
        try{            
            $insert = new StudentsTOR(); 
            $insert->user_id = $id;
            $insert->level_id = $level;
            $insert->purpose_id = $purpose;
            $insert->remarks = $remarks;
            $insert->updated_by = $updated_by;
            $insert->save();

            $student = StudentsInfo::where('user_id',$id)->first();
            $id_no = $student->id_no;
            $result = 'success';
        }catch(Exception $e){

        }
        $response = array('result' => $result,
                          'id_no' => $id_no,
                          'level' => $level,
                          'dateTime' => $dateTime
                        );
        return response()->json($response);
    }
    public function specializationNameSubmit(Request $request){
        $user_access_level = $request->session()->get('user_access_level');        
        $result = 'error';

        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $user = Auth::user();
            $updated_by = $user->id;

            $id = $request->id;
            $program_level_id = $request->program_level;
            $specialization_name = $request->val;
            $student_program = StudentsProgram::where('user_id',$id)
                ->where('program_level_id',$program_level_id)
                ->orderBy('year_from','DESC')
                ->orderBy('id','DESC')
                ->first();
            $student_program_id = $student_program->id;
            StudentsProgram::where('id', $student_program_id)
                ->update([
                    'specialization_name' => $specialization_name,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s')]);
            StudentsInfo::where('user_id', $id)
                ->update([
                    'specialization_name' => $specialization_name,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s')]);
            $result = 'success';
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    public function useThisCurriculum(Request $request){
        $user_access_level = $request->session()->get('user_access_level');        
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $rules = [
                'id' => 'required|numeric',
                'level' => 'required|numeric',
                'program' => 'required|numeric',
                'curriculum' => 'required|numeric',
                'branch' => 'required|numeric',
            ];
        
            $customMessages = [
                'id.required' => 'ID is required.',
                'level.required' => 'Level is required.',
                'program.required' => 'Program is required.',
                'curriculum.required' => 'Branch is required.',
            ];
        
            $validator = Validator::make($request->all(), $rules, $customMessages);
        
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400); // Return validation errors
            }
            //DB::beginTransaction();
            try {                
                $x = 0;
                $id = $request->id;
                $level = $request->level;
                $program = $request->program;
                $branch = $request->branch;
                $curriculum = $request->curriculum;
                StudentsProgram::where('user_id', $id)
                    ->where('program_id', $program)
                    ->where('program_level_id', $level)
                    ->where('program_code_id', $branch)
                                    ->update([
                                        'curriculum_id' => $curriculum]);                    
                $program = StudentsProgram::where('user_id',$id)
                    ->orderBy('year_from','DESC')
                    ->orderBy('id','DESC')
                    ->first();
                StudentsInfo::where('user_id', $id)
                                    ->update([
                                        'program_id' => $program->program_id,
                                        'program_code_id' => $program->program_code_id,
                                        'program_level_id' => $program->program_level_id,
                                        'curriculum_id' => $program->curriculum_id,
                                        'student_status_id' => $program->student_status_id]);
                //DB::commit();
                $result = 'success';
            }catch(Exception $e){
                //DB::rollBack();
            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
}