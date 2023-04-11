<?php

namespace App\Http\Controllers\RIMS\SchoolYear;
use App\Http\Controllers\Controller;
use App\Models\EducCourses;
use App\Models\EducCurriculum;
use App\Models\EducDepartments;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedDepartment;
use App\Models\EducOfferedPrograms;
use App\Models\EducProgramLevel;
use App\Models\EducPrograms;
use App\Models\EducProgramsCode;
use App\Models\EducTimeMax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;


class UpdateController extends Controller
{
    public function moveProgram(Request $request){
        $user = Auth::user();
        $id = $request->id;
        $val = $request->val;
        $result = 'error';
        $query = EducPrograms::where('id',$id)->where('status_id',$val)->first();
        if($query!=NULL){
            try{
                if($val==2){
                    $status_id = 1;
                }else{
                    $status_id = 2;
                }
                EducPrograms::where('id', $id)
                            ->update(['status_id' => $status_id,
                                      'updated_by' => $user->id,
                                      'updated_at' => date('Y-m-d H:i:s'),
                                    ]);
                $result = 'success';
            }catch(Exception $e){
                
            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }    
    public function courseStatus(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $course_id = $request->course_id;
        $program_id = $request->program_id;
        $curriculum_id = $request->curriculum_id;
        $result = 'error';
        $btn_class = '';
        $btn_html = '';
        $time_max = EducTimeMax::first();
        if($user_access_level==1 || $user_access_level==2){
            try{
                $course = EducCourses::with('grade_level')->where('id',$course_id)->first();
                $program = EducProgramsCode::with('program')->where('id',$program_id)->first();
                $offered_program_ids = EducOfferedPrograms::where('school_year_id', $id)->pluck('id')->toArray();
                $offered_curriculum_ids = EducOfferedCurriculum::whereIn('offered_program_id', $offered_program_ids)->pluck('id')->toArray();
                $offered_course = EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)->where('course_id',$course_id)->first();
                if($offered_course!=NULL){
                    $delete = EducOfferedCourses::where('offered_curriculum_id',$offered_course->offered_curriculum_id)->where('course_id',$course_id)->delete();
                    $auto_increment = DB::update("ALTER TABLE educ__offered_courses AUTO_INCREMENT = 0;");
                    $check_offered_curriculum = EducOfferedCourses::where('offered_curriculum_id',$offered_course->offered_curriculum_id)->first();
                    if($check_offered_curriculum==NULL){
                        $offered_curriculum = EducOfferedCurriculum::where('id',$offered_course->offered_curriculum_id)->first();
                        $delete = EducOfferedCurriculum::where('id',$offered_course->offered_curriculum_id)->delete();
                        $auto_increment = DB::update("ALTER TABLE educ__offered_curriculum AUTO_INCREMENT = 0;");

                        $check_offered_program = EducOfferedCurriculum::where('offered_program_id',$offered_curriculum->offered_program_id)->first();
                        if($check_offered_program==NULL){
                            $delete = EducOfferedPrograms::where('id',$offered_curriculum->offered_program_id)->delete();
                            $auto_increment = DB::update("ALTER TABLE educ__offered_programs AUTO_INCREMENT = 0;");
                        }
                    }
                    $btn_class = 'btn-danger btn-danger-scan';
                    $btn_html = ' Closed';
                }else{
                    $offered_program = EducOfferedPrograms::where('school_year_id', $id)->where('program_code_id',$program_id)->first();
                    $offered_curriculum = EducOfferedCurriculum::whereIn('offered_program_id', $offered_program_ids)->where('curriculum_id',$curriculum_id)->first();
                    if ($offered_program==NULL) {
                        $insert = new EducOfferedPrograms(); 
                        $insert->school_year_id = $id;
                        $insert->program_id = $program->program_id;
                        $insert->program_code_id = $program->id;
                        $insert->department_id = $program->program->department_id;
                        $insert->name = $program->name;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                        $offered_program_id = $insert->id;                        
                    }else{
                        $offered_program_id = $offered_program->id;                        
                    }
                    
                    if ($offered_curriculum==NULL) {
                        $insert = new EducOfferedCurriculum; 
                        $insert->offered_program_id = $offered_program_id;
                        $insert->curriculum_id = $course->curriculum_id;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                        $offered_curriculum_id = $insert->id;
                    }else{
                        $offered_curriculum_id = $offered_curriculum->id;
                    }
                    if($offered_course==NULL){
                        $insert = new EducOfferedCourses(); 
                        $insert->offered_curriculum_id = $offered_curriculum_id;
                        $insert->course_id = $course_id;
                        $insert->min_student = $time_max->min_student;
                        $insert->max_student = $time_max->max_student;
                        $insert->code = $course->code;
                        $insert->status_id = 1;
                        $insert->year_level = $course->grade_level->level;
                        $insert->section = 1;
                        $insert->section_code = $offered_program->name.'1'.$course->grade_level->level;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                        $btn_class = 'btn-success btn-success-scan';
                        $btn_html = ' Open';
                    }
                }
                $result = 'success';
                  
            }catch(Exception $e){
                    
            }
        }
        $response = array('result' => $result,
                          'btn_class' => $btn_class,
                          'btn_html' => $btn_html);
        return response()->json($response);
    }
    public function courseViewStatusSubmit(Request $request){
        $result = 'error';
        $user_access_level = $request->session()->get('user_access_level');
        if($user_access_level==1 || $user_access_level==2){
            $user = Auth::user();
            $updated_by = $user->id;
            $id = $request->id;
            $status_id = $request->status_id;
            try{
                EducOfferedCourses::where('id', $id)
                            ->update(['status_id' => $status_id,
                                      'updated_by' => $updated_by,
                                      'updated_at' => date('Y-m-d H:i:s'),
                                    ]);
                $result = 'success';
            }catch(Exception $e){
                        
            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
}