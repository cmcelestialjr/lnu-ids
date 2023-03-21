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
    public function new(Request $request){
        $user = Auth::user();
        $updated_by = $user->id;
        $year_from = $request->year_from;
        $year_to = $request->year_to;
        $grade_period = $request->grade_period;
        $date_duration = $request->date_duration;
        $date_extension = $request->date_extension;
        $enrollment_duration = $request->enrollment_duration;
        $enrollment_extension = $request->enrollment_extension;
        $add_dropping_duration = $request->add_dropping_duration;
        $add_dropping_extension = $request->add_dropping_extension;
        $exp_date_duration = explode(' - ',$date_duration);
        $date_from = $exp_date_duration[0];
        $date_to = $exp_date_duration[0];
        $exp_enrollment_duration = explode(' - ',$enrollment_duration);
        $enrollment_from = $exp_enrollment_duration[0];
        $enrollment_to = $exp_enrollment_duration[0];
        $exp_add_dropping_duration = explode(' - ',$add_dropping_duration);
        $add_dropping_from = $exp_add_dropping_duration[0];
        $add_dropping_to = $exp_add_dropping_duration[0];
        $id = '';
        $check = EducOfferedSchoolYear::where('year_from',$year_from)
                    ->where('year_to',$year_to)
                    ->where('grade_period_id',$grade_period)
                    ->first();
        if($check==NULL){
            try{
                $insert = new EducOfferedSchoolYear; 
                $insert->year_from = $year_from;
                $insert->year_to = $year_to;
                $insert->grade_period_id = $grade_period;
                $insert->date_from = $date_from;
                $insert->date_to = $date_to;
                $insert->date_extension = $date_extension;
                $insert->enrollment_from = $enrollment_from;
                $insert->enrollment_to = $enrollment_to;
                $insert->enrollment_extension = $enrollment_extension;
                $insert->add_dropping_from = $add_dropping_from;
                $insert->add_dropping_to = $add_dropping_to;
                $insert->add_dropping_extension = $add_dropping_extension;
                $insert->updated_by = $updated_by;
                $insert->save();
                $id = $insert->id;
                //$id = 17;
                $result = 'success';
            }catch(Exception $e){
                $result = 'error';
            }
        }else{
            $result = 'exists';
        }
        $response = array('result' => $result,
                          'id' => $id);
        return response()->json($response);
    }
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
    public function offerPrograms(Request $request){
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $time_max = EducTimeMax::first();
        $result = 'error';
        try{
            $school_year = EducOfferedSchoolYear::with('grade_period')->where('id',$id)->first();
            $grade_period = $school_year->grade_period_id;
            $grade_period_period = $school_year->grade_period->period;
            $program_level_ids = EducProgramLevel::where('period',$grade_period_period)->pluck('id')->toArray();

            $query = EducProgramsCode::with('program')->where('status_id', 1)
                                            ->whereHas('program', function($query) use ($program_level_ids){
                                                $query->whereIn('program_level_id', $program_level_ids);
                                            })
                                            ->get()
                                            ->map(function($query) use ($id,$updated_by) {
                                            return [
                                                'school_year_id' => $id,
                                                'program_id' => $query->program_id,
                                                'program_code_id' => $query->id,
                                                'department_id' => $query->program->department_id,
                                                'name' => $query->name,
                                                'updated_by' => $updated_by,
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'updated_at' => date('Y-m-d H:i:s')
                                            ];
                                        })->toArray();
            EducOfferedPrograms::insert($query);
            
            $programs_id = EducOfferedPrograms::where('school_year_id',$id)->pluck('id')->toArray();
            $department_ids = EducOfferedPrograms::where('school_year_id',$id)->pluck('department_id')->toArray();
            
            $query = EducDepartments::whereIn('id',$department_ids)->get()
                                        ->map(function($query) use ($id,$updated_by) {
                                        return [
                                                'school_year_id' => $id,
                                                'department_id' => $query->id,
                                                'name' => $query->name,
                                                'shorten' => $query->shorten,
                                                'code' => $query->code,
                                                'updated_by' => $updated_by,
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'updated_at' => date('Y-m-d H:i:s')
                                        ];
                                    })->toArray();
            EducOfferedDepartment::insert($query);

            $query = EducOfferedPrograms::join('educ_curriculum', 'educ__offered_programs.program_id', '=', 'educ_curriculum.program_id')
                                        ->select('educ_curriculum.id', 
                                                DB::raw('educ__offered_programs.id as offered_program_id'))
                                        ->where('educ__offered_programs.school_year_id', $id)
                                        ->where('educ_curriculum.status_id',1)
                                        ->get()
                                        ->map(function($query) use ($updated_by) {
                                        return [
                                                'offered_program_id' => $query->offered_program_id,
                                                'curriculum_id' => $query->id,
                                                'updated_by' => $updated_by,
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'updated_at' => date('Y-m-d H:i:s')
                                        ];
                                    })->toArray();
            EducOfferedCurriculum::insert($query);
                                    
            // $query = EducOfferedCurriculum::with('offered_program','courses')
            //                             ->whereIn('offered_program_id', $programs_id)
            //                             ->get()
            //                             ->map(function($query) use ($time_max,$updated_by,$grade_period) {
            //                                 foreach($query->courses as $row){
            //                                     //if($row->status_id==1 && $row->grade_period_id==$grade_period){
            //                                         return [
            //                                                 'offered_curriculum_id' => $query->id,
            //                                                 'course_id' => $row->id,
            //                                                 'min_student' => $time_max->min_student,
            //                                                 'max_student' => $time_max->max_student,
            //                                                 'code' => $row->code,
            //                                                 'status_id' => $row->status_id,
            //                                                 'section' => 1,
            //                                                 'section_code' => $query->offered_program->name.'1'.$row->grade_level->level,
            //                                                 'updated_by' => $updated_by,
            //                                                 'created_at' => date('Y-m-d H:i:s'),
            //                                                 'updated_at' => date('Y-m-d H:i:s')
            //                                         ];
            //                                     //}
            //                                 }    
            //                         })->toArray();

            $query = EducOfferedCurriculum::with('offered_program')->whereIn('offered_program_id', $programs_id)
                        ->get();
            foreach($query as $row){
                $courses = EducCourses::with('grade_level')->where('curriculum_id', $row->curriculum_id)
                                ->get();
                foreach($courses as $course){
                    $datas[] = [
                                'offered_curriculum_id' => $row->id,
                                'course_id' => $course->id,
                                'min_student' => $time_max->min_student,
                                'max_student' => $time_max->max_student,
                                'code' => $course->code,
                                'status_id' => $course->status_id,
                                'year_level' => $course->grade_level->level,
                                'section' => 1,
                                'section_code' => $row->offered_program->name.'1'.$course->grade_level->level,
                                'updated_by' => $updated_by,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                        ];
                }
            }
            EducOfferedCourses::insert($datas);
            $result = 'success';
        }catch(Exception $e){
            
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