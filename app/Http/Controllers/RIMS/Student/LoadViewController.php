<?php

namespace App\Http\Controllers\RIMS\Student;
use App\Http\Controllers\Controller;
use App\Models\EducCourses;
use App\Models\EducCurriculum;
use App\Models\EducGradePeriod;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducProgramLevel;
use App\Models\EducPrograms;
use App\Models\EducProgramsCode;
use App\Models\StudentsCourses;
use App\Models\StudentsCourseStatus;
use App\Models\StudentsInfo;
use App\Models\StudentsProgram;
use App\Models\Users;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoadViewController extends Controller
{
    public function searchStudent(Request $request){
        $search = $request->input('search');
        $name_services = new NameServices;
        $results = Users::select('id','lastname','firstname','middlename','extname')
                    ->where(function($query) use ($search) {
                        $query->where('lastname', 'LIKE', "%$search%")
                            ->orWhere('firstname', 'LIKE', "%$search%")
                            ->orWhere('middlename', 'LIKE', "%$search%");
                    })
                    ->limit(10)
                    ->get();

        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {
                $name = $name_services->lastname($result->lastname,$result->firstname,$result->middlename,$result->extname);
                $data[] = ['id' => $result->id, 'text' => $name];
            }
        }
        return response()->json($data);
    }
    public function searchStudents(Request $request){
        $name_services = new NameServices;
        $search = $request->input('search');
        $school_year_id = $request->school_year_id;
        $school_year = EducOfferedSchoolYear::with('grade_period')
            ->select('grade_period_id')
            ->where('id',$school_year_id)->first();
        $period = $school_year->grade_period->period;
        // if($period=='sum'){
        //     $program_ids = EducPrograms::pluck('id')->toArray();
        // }else{
        //     $program_ids = EducPrograms::whereHas('program_level', function ($query) use ($period) {
        //                     $query->where('period', $period);
        //                 })->pluck('id')->toArray();
        // }        
        $results = Users::select('id','lastname','firstname','middlename','extname','stud_id')
                    ->where(function($query) use ($search) {
                        $query->where('lastname', 'LIKE', "%$search%")
                            ->orWhere('firstname', 'LIKE', "%$search%")
                            ->orWhere('middlename', 'LIKE', "%$search%")
                            ->orWhere('middlename', 'LIKE', "%$search%")
                            ->orWhere('stud_id', 'LIKE', "%$search%")
                            ->orWhereRaw('CONCAT(lastname, ", ", firstname) LIKE ?', ["%$search%"]);
                    })
                    ->whereHas('student_info', function ($query) use ($period) {
                        if($period=='sum'){
                            $query->where('program_id','>',0);
                        }else{
                            $query->whereHas('program_level', function ($query) use ($period) {
                                $query->where('period', $period);
                            });
                        }
                        //$query->whereIn('program_id', $program_ids);
                    })
                    ->limit(10)
                    ->get();
        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {
                $name = $name_services->lastname($result->lastname,$result->firstname,$result->middlename,$result->extname);
                $data[] = ['id' => $result->id, 'text' => $result->stud_id.'-'.$name];
            }
        }
        return response()->json($data);
    }   
    public function studentTORDiv(Request $request){
        $id = $request->id;
        $program_level = $request->program_level;
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");

        // $query = StudentsProgram::where('user_id',$id)
        //     ->where('program_level_id',$program_level)
        //     ->orderBy('year_from')
        //     ->get()
        //     ->map(function($query) use ($id,$program_level) {
        //         // $program_ids = StudentsProgram::where('user_id',$id)
        //         //     ->where('from_school',$query->from_school)
        //         //     ->where('program_level_id',$program_level)
        //         //     ->pluck('id')->toArray();
        //         $program_shorten = $query->program_shorten;
        //         if($query->program_id!=NULL){
        //             $program_shorten = $query->program_info->shorten;
        //         }
        //         $year_period = StudentsCourses::where('student_program_id',$query->id)
        //             ->where('year_from','>',1)
        //             ->select('year_from','year_to','grade_period_id')
        //             ->groupBy('year_from')
        //             ->groupBy('grade_period_id')
        //             ->orderBy('year_from','ASC')
        //             ->orderBy('grade_period_id','ASC')
        //             ->get()
        //             ->map(function($query) use ($id){
        //                 $grade_period = EducGradePeriod::where('id',$query->grade_period_id)->first();
        //                 $courses = StudentsCourses::where('user_id',$id)
        //                     ->where('grade_period_id',$query->grade_period_id)
        //                     ->where('year_from',$query->year_from)
        //                     ->get();
        //                 return [
        //                     'grade_period' => $grade_period->name,
        //                     'period' => $query->year_from.'-'.$query->year_to,
        //                     'courses' => $courses
        //                 ];
        //             });
        //         return [
        //             'from_school' => $query->from_school,
        //             'year_period' => $year_period,
        //             'program_shorten' => $program_shorten
        //         ];
        //     })->toArray();
        $query = StudentsCourses::with('grade_period')
            ->where('user_id',$id)
            ->where('year_from','>',1)
            ->select('year_from','year_to','grade_period_id','program_shorten','school_name')
            ->groupBy('year_from')
            ->groupBy('grade_period_id')
            ->orderBy('year_from','ASC')
            ->orderBy('grade_period_id','ASC')
            ->get()
            ->map(function($query) use ($id){
                $courses = StudentsCourses::select('option','course_code','course_desc','final_grade','grade','course_units')
                    ->where('user_id',$id)
                    ->where('grade_period_id',$query->grade_period_id)
                    ->where('year_from',$query->year_from)
                    ->get();
                return [
                    'school_name' => $query->school_name,
                    'grade_period' => $query->grade_period->name,
                    'period' => $query->year_from.'-'.$query->year_to,
                    'courses' => $courses,
                    'program_shorten' => $query->program_shorten
                ];
            })->toArray();
            
        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('rims/student/studentTORDiv',$data);
    }
    public function studentCurriculumDiv(Request $request){
        $system_selected = $request->session()->get('system_selected');
        $id = $request->id;
        $program_level_id = $request->program_level;
        $student_program = StudentsProgram::where('user_id',$id)
            ->where('program_level_id',$program_level_id)
            ->select('curriculum_id','specialization_name')
            ->orderBy('year_from','DESC')
            ->orderBy('id','DESC')
            ->first();
        $curriculum_id = $student_program->curriculum_id;
        $specialization_name = $student_program->specialization_name;
        $specialization = EducCourses::where('curriculum_id',$curriculum_id)
            ->where('specialization_name','!=','')
            ->select('specialization_name')
            ->groupBy('specialization_name')
            ->get();
        $query = EducCourses::with('grade_level')->where('curriculum_id',$curriculum_id)
            ->select('grade_level_id')
            ->groupBy('grade_level_id')
            ->orderBy('grade_level_id','ASC')
            ->get()
            ->map(function($query) use ($id,$curriculum_id,$student_program) {
                $grade_level_id = $query->grade_level_id;
                $grade_period = EducCourses::with('grade_period')
                    ->where('curriculum_id',$curriculum_id)
                    ->select('grade_period_id')
                    ->groupBy('grade_period_id')
                    ->orderBy('grade_period_id','ASC')
                    ->get()
                    ->map(function($query) use ($id,$curriculum_id,$grade_level_id,$student_program) {
                        $grade_period_id = $query->grade_period_id;
                        $courses = EducCourses::where('curriculum_id',$curriculum_id)
                            ->where('grade_level_id',$grade_level_id)
                            ->where('grade_period_id',$grade_period_id)
                            ->where(function ($query) use ($student_program){
                                $query->where('specialization_name',$student_program->specialization_name);
                                $query->orWhere('specialization_name',NULL);
                                $query->orWhere('specialization_name','');
                            })
                            ->select('id','code','name','units','lab','pre_name','pre_req')
                            ->get()
                            ->map(function($query) use ($id,$curriculum_id,$grade_level_id,$grade_period_id) {
                                $status = '<button class="btn btn-default btn-xs" style="font-size:10px">Required</button>';
                                $student_course_status = NULL;
                                $course_id = $query->id;
                                $check = StudentsCourses::where('user_id',$id)
                                    ->where(function ($query) use ($course_id){
                                        $query->where('course_id',$course_id)
                                        ->orWhere('credit_course_id',$course_id);
                                    })
                                    ->select('student_course_status_id')
                                    ->orderBy('year_from','DESC')
                                    ->first();
                                if($check!=NULL){
                                    if($check->student_course_status_id==NULL){
                                        $status = '<button class="btn btn-info btn-info-scan btn-xs" style="font-size:10px">NG</button>';
                                    }else{
                                        $student_course_status = $check->student_course_status_id;
                                        if($check->status->option==1){
                                            $status = '<button class="btn btn-success btn-success-scan btn-xs" style="font-size:10px">'.$check->status->name.'</button>';
                                        }else{
                                            $status = '<button class="btn btn-danger btn-danger-scan btn-xs" style="font-size:10px">'.$check->status->name.'</button>';
                                        }
                                    }
                                }
                                $course_other = StudentsCourses::where('user_id',$id)
                                    ->where('credit_course_id',$course_id)
                                    ->select('course_code','course_desc','course_units','lab_units')
                                    ->orderBy('year_from','DESC')
                                    ->first();
                                return [
                                    'id' => $query->id,
                                    'code' => $query->code,
                                    'name' => $query->name,
                                    'units' => $query->units,
                                    'lab' => $query->lab,
                                    'pre_name' => $query->pre_name,
                                    'status' => $status,
                                    'pre_req' => $query->pre_req,
                                    'student_course_status' => $student_course_status,
                                    'course_other' => $course_other
                                ];
                            })->toArray();
                        return [
                            'grade_period' => $query->grade_period->name,
                            'courses' => $courses
                        ];
                    })->toArray();
                return [
                    'year_level' => $query->grade_level->name,
                    'grade_period' => $grade_period
                ];
            })->toArray();
        $courses_id = EducCourses::where('curriculum_id',$curriculum_id)
            ->pluck('id')
            ->toArray();
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $course_other = StudentsCourses::where('user_id',$id)
            ->where('year_from','>',1)
            ->select('year_from','year_to','grade_period_id','program_shorten','school_name')
            ->groupBy('year_from')
            ->groupBy('grade_period_id')
            ->orderBy('year_from','ASC')
            ->orderBy('grade_period_id','ASC')
            ->get()
            ->map(function($query) use ($id,$courses_id){
                $grade_period = EducGradePeriod::where('id',$query->grade_period_id)->first();
                $courses = StudentsCourses::where('user_id',$id)
                    ->where('grade_period_id',$query->grade_period_id)
                    ->where('year_from',$query->year_from)
                    ->where(function ($query) use ($courses_id){
                        $query->where('course_id',NULL)
                        ->orWhereNotIn('course_id',$courses_id);
                    })
                    ->get();
                return [
                    'school_name' => $query->school_name,
                    'grade_period' => $grade_period->name,
                    'period' => $query->year_from.'-'.$query->year_to,
                    'courses' => $courses,
                    'program_shorten' => $query->program_shorten
                ];
            })->toArray();
        $passed_statuses = StudentsCourseStatus::where('option',1)->pluck('id')->toArray();
        $btn_user_this = '';
        if($student_program->curriculum_id!=$curriculum_id){
            $btn_user_this = '1';
        }
        $data = array(
            'id' => $id,
            'query' => $query,
            'course_other' => $course_other,
            'passed_statuses' => $passed_statuses,
            'specialization' => $specialization,
            'system_selected' => $system_selected,
            'specialization_name' => $specialization_name,
            'btn_user_this' => $btn_user_this
        );
        return view('rims/student/studentCurriculumDiv',$data);
    }
    public function studentShiftModalCurriculum(Request $request){
        $result = 'error';
        $id = $request->val;
        $curriculum = EducCurriculum::where('program_id',$id)
            ->get();
        $datas = [];
        if($curriculum->count()>0){
            $result = 'success';
            foreach($curriculum as $r){
                $data_list['value'] = $r->id;
                $data_list['text'] = $r->year_from.'-'.$r->year_to.' ('.$r->code.')';
                array_push($datas,$data_list);
            }
            
        }
        $response = array('result' => $result,
                          'datas' => $datas);
        return response()->json($response);
    }
    public function studentCurriculumList(Request $request){
        $result = 'error';
        $id = $request->id;
        $level = $request->level;
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $student_program = StudentsProgram::with('program_info')
            ->where('user_id',$id)
            ->where('program_level_id',$level)
            ->orderBy('year_from','DESC')
            ->first();
        $branch_ids = StudentsProgram::select('program_code_id')
            ->where('user_id',$id)
            ->where('program_level_id',$level)
            ->orderBy('year_from','DESC')
            ->groupBy('program_code_id')
            ->pluck('program_code_id')
            ->toArray();
        
        $program_id = $student_program->program_id;
        $curriculum_id = $student_program->curriculum_id;
        $curriculum = EducCurriculum::where('program_id',$program_id)
            ->orderBy('year_from','DESC')
            ->orderBy('id','DESC')
            ->get();
        $curriculums = [];
        if($curriculum->count()>0){
            $result = 'success';
            foreach($curriculum as $r){
                $data_list['value'] = $r->id;
                $data_list['text'] = $r->year_from.'-'.$r->year_to.' ('.$r->code.')';
                array_push($curriculums,$data_list);
            }            
        }
        $branch = EducProgramsCode::with('branch')->whereIn('id',$branch_ids)->get();
        $branches = [];
        if($branch->count()>0){
            $result = 'success';
            foreach($branch as $r){
                $data_list['value'] = $r->id;
                $data_list['text'] = $r->name.'-'.$r->branch->name;
                array_push($branches,$data_list);
            }            
        }
        $response = array('result' => $result,
                          'curriculum_id' => $curriculum_id,
                          'curriculums' => $curriculums,
                          'branches' => $branches,
                          'program_text' => $student_program->program_info->shorten.'-'.$student_program->program_info->name,
                          'program_value' => $student_program->program_id);
        return response()->json($response);
    }
    public function studentCurriculumLoad(Request $request){
        $system_selected = $request->session()->get('system_selected');
        $id = $request->id;
        $program_level_id = $request->level;
        $curriculum_id = $request->curriculum;
        $student_program = StudentsProgram::where('user_id',$id)
            ->where('program_level_id',$program_level_id)
            ->orderBy('year_from','DESC')
            ->orderBy('id','DESC')
            ->first();
        $specialization_name = $student_program->specialization_name;
        $specialization = EducCourses::where('curriculum_id',$curriculum_id)
            ->where('specialization_name','!=','')
            ->select('specialization_name')
            ->groupBy('specialization_name')
            ->get();
        $query = EducCourses::with('grade_level')->where('curriculum_id',$curriculum_id)
            ->select('grade_level_id')
            ->groupBy('grade_level_id')
            ->orderBy('grade_level_id','ASC')
            ->get()
            ->map(function($query) use ($id,$curriculum_id,$student_program) {
                $grade_level_id = $query->grade_level_id;
                $grade_period = EducCourses::with('grade_period')->where('curriculum_id',$curriculum_id)
                    ->select('grade_period_id')
                    ->groupBy('grade_period_id')
                    ->orderBy('grade_period_id','ASC')
                    ->get()
                    ->map(function($query) use ($id,$curriculum_id,$grade_level_id,$student_program) {
                        $grade_period_id = $query->grade_period_id;
                        $courses = EducCourses::where('curriculum_id',$curriculum_id)
                            ->where('grade_level_id',$grade_level_id)
                            ->where('grade_period_id',$grade_period_id)
                            ->where(function ($query) use ($student_program){
                                $query->where('specialization_name',$student_program->specialization_name);
                                $query->orWhere('specialization_name',NULL);
                                $query->orWhere('specialization_name','');
                            })
                            ->get()
                            ->map(function($query) use ($id,$curriculum_id,$grade_level_id,$grade_period_id) {
                                $status = '<button class="btn btn-default btn-xs" style="font-size:10px">Required</button>';
                                $student_course_status = NULL;
                                $course_id = $query->id;
                                $check = StudentsCourses::with('status')->where('user_id',$id)
                                    ->where(function ($query) use ($course_id){
                                        $query->where('course_id',$course_id)
                                        ->orWhere('credit_course_id',$course_id);
                                    })
                                    ->orderBy('year_from','DESC')
                                    ->first();
                                if($check!=NULL){
                                    if($check->student_course_status_id==NULL){
                                        $status = '<button class="btn btn-info btn-info-scan btn-xs" style="font-size:10px">NG</button>';
                                    }else{
                                        $student_course_status = $check->student_course_status_id;
                                        if($check->status->option==1){
                                            $status = '<button class="btn btn-success btn-success-scan btn-xs" style="font-size:10px">'.$check->status->name.'</button>';
                                        }else{
                                            $status = '<button class="btn btn-danger btn-danger-scan btn-xs" style="font-size:10px">'.$check->status->name.'</button>';
                                        }
                                    }
                                }
                                $course_other = StudentsCourses::where('user_id',$id)
                                    ->where('credit_course_id',$course_id)
                                    ->orderBy('year_from','DESC')
                                    ->first();
                                return [
                                    'id' => $query->id,
                                    'code' => $query->code,
                                    'name' => $query->name,
                                    'units' => $query->units,
                                    'lab' => $query->lab,
                                    'pre_name' => $query->pre_name,
                                    'status' => $status,
                                    'pre_req' => $query->pre_req,
                                    'student_course_status' => $student_course_status,
                                    'course_other' => $course_other
                                ];
                            })->toArray();
                        return [
                            'grade_period' => $query->grade_period->name,
                            'courses' => $courses
                        ];
                    })->toArray();
                return [
                    'year_level' => $query->grade_level->name,
                    'grade_period' => $grade_period
                ];
            })->toArray();
        $courses_id = EducCourses::where('curriculum_id',$curriculum_id)
            ->pluck('id')
            ->toArray();
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $course_other = StudentsCourses::where('user_id',$id)
            ->where('year_from','>',1)
            ->select('year_from','year_to','grade_period_id','program_shorten','school_name')
            ->groupBy('year_from')
            ->groupBy('grade_period_id')
            ->orderBy('year_from','ASC')
            ->orderBy('grade_period_id','ASC')
            ->get()
            ->map(function($query) use ($id,$courses_id){
                $grade_period = EducGradePeriod::where('id',$query->grade_period_id)->first();
                $courses = StudentsCourses::where('user_id',$id)
                    ->where('grade_period_id',$query->grade_period_id)
                    ->where('year_from',$query->year_from)
                    ->where(function ($query) use ($courses_id){
                        $query->where('course_id',NULL)
                        ->orWhereNotIn('course_id',$courses_id);
                    })
                    ->get();
                return [
                    'school_name' => $query->school_name,
                    'grade_period' => $grade_period->name,
                    'period' => $query->year_from.'-'.$query->year_to,
                    'courses' => $courses,
                    'program_shorten' => $query->program_shorten
                ];
            })->toArray();
        $passed_statuses = StudentsCourseStatus::where('option',1)->pluck('id')->toArray();
        $btn_user_this = '';
        if($student_program->curriculum_id!=$curriculum_id){
            $btn_user_this = '1';
        }
        $data = array(
            'id' => $id,
            'query' => $query,
            'course_other' => $course_other,
            'passed_statuses' => $passed_statuses,
            'specialization' => $specialization,
            'system_selected' => $system_selected,
            'specialization_name' => $specialization_name,
            'btn_user_this' => $btn_user_this
        );
        return view('rims/student/studentCurriculumDiv',$data);
    }
}