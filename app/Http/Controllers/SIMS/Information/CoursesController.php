<?php

namespace App\Http\Controllers\SIMS\Information;

use App\Http\Controllers\Controller;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducProgramLevel;
use App\Models\StudentsCourses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CoursesController extends Controller
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

        $school_year = EducOfferedSchoolYear::with('grade_period')
            ->whereHas('student_courses',function($query) use ($id,$user_id){
                $query->where('user_id',$user_id);
                $query->where('program_level_id',$id);
            })->get();
        
        // Check if fails
        if (count($school_year)<=0) {
            return view('layouts/error/404');
        }

        $data = array(
            'school_year' => $school_year
        );
        return view('sims/information/coursesSelect',$data);
    }

    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $id = $request->id;

        $courses = StudentsCourses::with('course',
                                        'course_info',
                                        'status')
            ->where('school_year_id',$id)
            ->get();
        
        $data = array(
            'courses' => $courses
        );
        return view('sims/information/coursesList',$data);
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
}
