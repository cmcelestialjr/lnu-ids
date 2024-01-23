<?php

namespace App\Http\Controllers\SIMS\Information;

use App\Http\Controllers\Controller;
use App\Models\EducCourses;
use App\Models\EducCurriculum;
use App\Models\EducGradePeriod;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducProgramLevel;
use App\Models\EducYearLevel;
use App\Models\StudentsCourses;
use App\Models\StudentsCourseStatus;
use App\Models\StudentsProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CurriculumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $id = $request->id;

        $checkProgramLevel = EducProgramLevel::find($id);

        // Check if fails
        if ($checkProgramLevel==NULL) {
            return view('layouts/error/404');
        }

        $user = Auth::user();
        $user_id = $user->id;
        
        $programs = StudentsProgram::where('user_id',$user_id)
            ->where('program_level_id',$id)
            ->orderBY('year_from','DESC')
            ->get();

        // Check if fails
        if (count($programs)<=0) {
            return view('layouts/error/404');
        }

        $program = StudentsProgram::with('program_info')
            ->where('user_id',$user_id)
            ->where('program_level_id',$id)
            ->orderBY('year_from','DESC')
            ->first();
        $program_id = $program->program_id;
        $curriculums = EducCurriculum::where('program_id',$program_id)
            ->whereHas('students',function($query) use ($user_id,$program_id){
                $query->where('user_id',$user_id);
                $query->where('program_id',$program_id);
            })
            ->orderBY('year_from','DESC')
            ->get();
        
        $year_level = EducYearLevel::where('program_level_id',$id)
            ->get();

        // Check if fails
        if (count($curriculums)<=0) {
            return view('layouts/error/404');
        }

        $data = array(
            'programs' => $programs,
            'curriculums' => $curriculums,
            'year_level' => $year_level
        );
        return view('sims/information/curriculumSelect',$data);
    }

    /**
     * Display a listing of the resource.
     */

    public function list(Request $request){
        // Validate the incoming request data
        $validator = $this->listValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $system_selected = $request->session()->get('system_selected');

        $user = Auth::user();
        $id = $user->id;

        $program_level_id = $request->program_level;
        $program_id = $request->program;
        $curriculum_id = $request->curriculum;        
        $year_level = $request->year_level;

        $student_program = StudentsProgram::where('user_id',$id)
            ->where('program_id',$program_id)
            ->where('program_level_id',$program_level_id)
            ->orderBy('year_from','DESC')
            ->orderBy('id','DESC')
            ->first();

        // Check if validation fails
        if ($student_program==NULL) {
            return view('layouts/error/404');
        }

        $specialization_name = $student_program->specialization_name;
        $specialization = EducCourses::where('curriculum_id',$curriculum_id)
            ->where('specialization_name','!=','')
            ->select('specialization_name')
            ->groupBy('specialization_name')
            ->get();
        $query = EducCourses::with('grade_level')->where('curriculum_id',$curriculum_id)
            ->select('grade_level_id');
        if($year_level){
            $query = $query->whereIn('grade_level_id',$year_level);
        }
        $query = $query->groupBy('grade_level_id')
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function idValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric'
        ];

        $customMessages = [
            'id.required' => 'Id is required.',
            'id.numeric' => 'Id must be a number.'
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function listValidateRequest(Request $request)
    {
        $rules = [
            'program_level' => 'required|numeric',
            'program' => 'required|numeric',
            'curriculum' => 'required|numeric',
            'year_level' => 'nullable|numeric'
        ];

        $customMessages = [
            'program_level.required' => 'Program Level is required.',
            'program_level.numeric' => 'Program Level must be a number.',
            'program.required' => 'Program Level is required.',
            'program.numeric' => 'Program Level must be a number.',
            'curriculum.required' => 'Curriculum is required.',
            'curriculum.numeric' => 'Curriculum must be a number.',
            'year_level.numeric' => 'Year Level must be a number.'
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }
}
