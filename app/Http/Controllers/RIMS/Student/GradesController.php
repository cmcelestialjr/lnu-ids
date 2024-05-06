<?php

namespace App\Http\Controllers\RIMS\Student;

use App\Http\Controllers\Controller;
use App\Models\EducProgramLevel;
use App\Models\StudentsCourses;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GradesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id = $request->id;
        $student = Users::where('id',$id)
            ->where('stud_id','!=',NULL)
            ->first();

        // Check if the student exists
        if($student==NULL){
            return response()->json(['result' => 'error']);
        }

        $program_level = EducProgramLevel::whereHas('students_courses', function ($subQuery) use ($id) {
                $subQuery->where('user_id', $id);
            })->get();

        $data = array(
            'student' => $student,
            'program_level' => $program_level
        );
        return view('rims/student/gradesModal',$data);
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
        // Validate the request
        $validator = $this->showValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            // If validation fails, return a JSON response with validation errors and a 400 status code
            return response()->json(['result' => $validator->errors()], 400);
        }

        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");

        $id = $request->id;
        $program_level_id = $request->program_level;

        $student_courses = StudentsCourses::with('grade_period')
            ->where('user_id',$id)
            ->where('year_from','>',1)
            ->where('program_level_id',$program_level_id)
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
            'student_courses' => $student_courses
        );
        return view('rims/student/gradesDiv',$data);
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
    private function showValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'program_level' => 'required|string'
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'program_level.required' => 'Program Level is required.',
            'program_level.string' => 'Program Level must be a string.'
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }
}
