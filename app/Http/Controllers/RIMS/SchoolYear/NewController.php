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
use App\Models\EducProgramsCode;
use App\Models\EducTimeMax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;


class NewController extends Controller
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
            $time_max = EducTimeMax::first();
            try{
                $insert = new EducOfferedSchoolYear; 
                $insert->year_from = $year_from;
                $insert->year_to = $year_to;
                $insert->grade_period_id = $grade_period;
                $insert->date_from = $date_from;
                $insert->date_to = $date_to;
                $insert->time_from = $time_max->time_from;
                $insert->time_to = $time_max->time_to;
                $insert->min_student = $time_max->min_student;
                $insert->max_student = $time_max->max_student;
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
                $this->offerProgramsSubmit($id);
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
    private function offerProgramsSubmit($id){
        $user = Auth::user();
        $updated_by = $user->id;
        $time_max = EducTimeMax::first();
        $result = 'error';
        try{
            $school_year = EducOfferedSchoolYear::with('grade_period')->where('id',$id)->first();
            $grade_period = $school_year->grade_period_id;
            $grade_period_period = $school_year->grade_period->period;
            $program_level_ids = EducProgramLevel::where('period',$grade_period_period)->pluck('id')->toArray();

            // $curriculum_ids = EducCourses::where('status_id','1')->pluck('curriculum_id')->toArray();
            // if(!empty($curriculum_ids)){
            //     $program_idss = EducCurriculum::where('id',$curriculum_ids)->pluck('program_id')->toArray();                
                $query = EducProgramsCode::with('program')->where('status_id', 1)
                                                ->whereHas('program', function($query) use ($program_level_ids){
                                                    $query->whereIn('program_level_id', $program_level_ids);
                                                })
                                                //->whereIn('program_id',$program_idss)
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
                                                    'educ_curriculum.code', 
                                                    DB::raw('educ__offered_programs.id as offered_program_id'))
                                            ->where('educ__offered_programs.school_year_id', $id)
                                            ->where('educ_curriculum.status_id',1)
                                            ->get()
                                            ->map(function($query) use ($updated_by) {
                                            return [
                                                    'offered_program_id' => $query->offered_program_id,
                                                    'curriculum_id' => $query->id,
                                                    'code' => $query->code,
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
                                    ->where('grade_period_id',$grade_period)
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
                                    'section_code' => $row->code.$row->offered_program->name.'1'.$course->grade_level->level,
                                    'updated_by' => $updated_by,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                            ];
                    }
                }
                EducOfferedCourses::insert($datas);
            //}
            $result = 'success';
        }catch(Exception $e){
            
        }
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

            // $curriculum_ids = EducCourses::where('status_id','1')->pluck('curriculum_id')->toArray();
            // if(!empty($curriculum_ids)){
            //     $program_idss = EducCurriculum::where('id',$curriculum_ids)->pluck('program_id')->toArray();                
                $query = EducProgramsCode::with('program')->where('status_id', 1)
                                                ->whereHas('program', function($query) use ($program_level_ids){
                                                    $query->whereIn('program_level_id', $program_level_ids);
                                                })
                                                //->whereIn('program_id',$program_idss)
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
                                                    'educ_curriculum.code', 
                                                    DB::raw('educ__offered_programs.id as offered_program_id'))
                                            ->where('educ__offered_programs.school_year_id', $id)
                                            ->where('educ_curriculum.status_id',1)
                                            ->get()
                                            ->map(function($query) use ($updated_by) {
                                            return [
                                                    'offered_program_id' => $query->offered_program_id,
                                                    'curriculum_id' => $query->id,
                                                    'code' => $query->code,
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
                                    ->where('grade_period_id',$grade_period)
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
                                    'section_code' => $row->code.$row->offered_program->name.'1'.$course->grade_level->level,
                                    'updated_by' => $updated_by,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                            ];
                    }
                }
                EducOfferedCourses::insert($datas);
            //}
            $result = 'success';
        }catch(Exception $e){
            
        }
        $response = array('result' => $result);
        return response()->json($response);
    }   
}