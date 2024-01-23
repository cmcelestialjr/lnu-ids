<?php

namespace App\Http\Controllers\FIS;

use App\Http\Controllers\Controller;
use App\Models\EducOfferedSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->indexValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $user = Auth::user();
        $instructor_id = $user->id;
        $school_year = $request->school_year;
        $level = $request->level;

        $query = EducOfferedSchedule::
            whereHas('course', function ($query) use ($instructor_id,$school_year,$level) {
                $query->where('instructor_id',$instructor_id);
                $query->whereHas('curriculum.offered_program', function ($query) use ($school_year) {
                    $query->where('school_year_id',$school_year);
                });
                $query->whereHas('course.grade_level', function ($query) use ($level) {
                    if($level==NULL){
                        $query->where('program_level_id','>',0);
                    }else{
                        $query->whereIn('program_level_id',$level);
                    }
                });
            })->orderBy('time_from','ASC')
            ->get();       

        $data = array(
            'query' => $query
        );
        return view('fis/schedule/scheduleTable',$data);
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
    private function indexValidateRequest(Request $request)
    {
        $rules = [
            'school_year' => 'required|numeric',
            'level' => 'nullable|array',
            'level.*' => 'numeric',
        ];
        
        $customMessages = [
            'school_year.required' => 'School Year is required',
            'school_year.numeric' => 'School Year must be numeric',
            'level.array' => 'Level must be an array',
            'level.*.numeric' => 'All elements in the "level" array must be numbers',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }
}
