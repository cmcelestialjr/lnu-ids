<?php

namespace App\Http\Controllers\SIMS\Teachers;

use App\Http\Controllers\Controller;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducProgramLevel;
use App\Models\StudentsCourses;
use App\Models\Users;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TeachersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Initialize data array
        $data = array();

        // Validate the incoming request data
        $validator = $this->indexValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($data);
        }

        $name_services = new NameServices;

        $user = Auth::user();
        $user_id = $user->id;
        
        $program_level_id = $request->program_level;
        $school_year_id = $request->school_year;

        $query = Users::with(['courses' => function ($query) use ($user_id) {
                $query->whereHas('students',function($query) use ($user_id){
                    $query->where('user_id', $user_id);
                });
            }],['students' => function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            }])
            ->whereHas('courses.students',function($query) use ($program_level_id,$school_year_id,$user_id){ 
                $query->where('user_id',$user_id);
                if($program_level_id>0){
                    $query = $query->where('program_level_id',$program_level_id);
                }
                if($school_year_id>0){
                    $query = $query->where('school_year_id',$school_year_id);
                }
            })->orderBy('lastname','ASC')
            ->orderBy('firstname','ASC')
            ->get()->map(function($query) use ($name_services) {
                $name = $name_services->lastname($query->lastname,$query->firstname,$query->middlename,$query->extname);
                foreach($query->courses as $row){
                    foreach($row->students as $subRow){
                        $levels[] = $subRow->program_level->name;
                    }
                }
                $unique_levels = array_unique($levels);
                $level = implode(', ',$unique_levels);
                return [
                    'id' => $query->id,
                    'name' => $name,
                    'level' => $level,
                    'no' => count($query->courses)
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['level'];
                $data_list['f4'] = $r['no'];
                $data_list['f5'] = '<button class="btn btn-primary btn-primary-scan courseView"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-eye"></span> View
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
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
    public function show(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->showValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $name_services = new NameServices;

        $user = Auth::user();
        $user_id = $user->id;

        $id = $request->id;
        $program_level_id = $request->program_level;
        $school_year_id = $request->school_year;

        $teacher = Users::find($id);

        // Check if validation fails
        if ($teacher==NULL) {
            return view('layouts/error/404');
        }

        $teacher_name = $name_services->lastname($teacher->lastname,$teacher->firstname,$teacher->middlename,$teacher->extname);

        $program_level = EducProgramLevel::whereHas('students_courses', function ($query) use ($user_id) {
            $query->where('user_id',$user_id);
        })->orderBy('id','DESC')->get();

        $school_year = EducOfferedSchoolYear::with('grade_period')
            ->whereHas('student_courses', function ($query) use ($user_id) {
                $query->where('user_id',$user_id);
            })->orderBy('year_from','DESC')
            ->orderBy('grade_period_id','DESC')
            ->get()->map(function($query)  {
                return [
                    'id' => $query->id,
                    'name' => $query->year_from.'-'.$query->year_to.' '.$query->grade_period->name_no
                ];
            })->toArray();

        $data = array(
            'id' => $id,
            'teacher_name' => $teacher_name,
            'program_level_id' => $program_level_id,
            'school_year_id' => $school_year_id,
            'program_level' => $program_level,
            'school_year' => $school_year
        );
        return view('sims/teachers/courseViewModal',$data);
    }

    /**
     * Display the specified resource.
     */
    public function showTable(Request $request)
    {
        // Initialize data array
        $data = array();

        // Validate the incoming request data
        $validator = $this->showValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($data);
        }

        $user = Auth::user();
        $user_id = $user->id;

        $id = $request->id;
        $program_level_id = $request->program_level;
        $school_year_id = $request->school_year;
        $courses = StudentsCourses::with('course.course','status','grade_level')
            ->where('user_id',$user_id);
        if($program_level_id>0){
            $courses = $courses->where('program_level_id',$program_level_id);
        }
        if($school_year_id>0){
            $courses = $courses->where('school_year_id',$school_year_id);
        }            
        $courses = $courses->whereHas('course',function($query) use ($id){
                $query->where('instructor_id',$id);
            })->get()
            ->map(function($query) {
                $status = 'Ongoing';
                if($query->student_course_status_id){
                    $status = $query->status->name;
                }
                return [
                    'id' => $query->id,
                    'section_code' => $query->course->section_code,
                    'course_code' => $query->course_code,
                    'course_desc' => $query->course_desc,
                    'course_units' => $query->course_units,
                    'final_grade' => $query->final_grade,
                    'status' => $status,
                    'level' => $query->grade_level->name
                ];
            })->toArray();

        if (count($courses)) {
            $x = 1;
            foreach($courses as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['section_code'];
                $data_list['f3'] = $r['course_code'];
                $data_list['f4'] = $r['course_desc'];
                $data_list['f5'] = $r['course_units'];
                $data_list['f6'] = $r['final_grade'];
                $data_list['f7'] = $r['status'];
                $data_list['f8'] = $r['level'];
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
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
    private function indexValidateRequest(Request $request)
    {
        $rules = [
            'program_level' => 'required|numeric',
            'school_year' => 'required|numeric'
        ];

        $customMessages = [
            'school_year.required' => 'School Year is required.',
            'school_year.numeric' => 'School Year must be a number.',
            'program_level.required' => 'Program Level is required.',
            'program_level.numeric' => 'Program Level must be a number.'
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function showValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'program_level' => 'required|numeric',
            'school_year' => 'required|numeric'
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'school_year.required' => 'School Year is required.',
            'school_year.numeric' => 'School Year must be a number.',
            'program_level.required' => 'Program Level is required.',
            'program_level.numeric' => 'Program Level must be a number.'
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }
}
