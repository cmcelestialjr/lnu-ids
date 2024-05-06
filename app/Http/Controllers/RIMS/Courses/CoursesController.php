<?php

namespace App\Http\Controllers\RIMS\Courses;

use App\Http\Controllers\Controller;
use App\Models\EducCourses;
use App\Models\EducGradePeriod;
use App\Models\EducYearLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, int $id)
    {
        // Validate the incoming request data
        $validator = $this->indexValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $user_access_level = $request->session()->get('user_access_level');
        $level = $request->year_level;
        $status = $request->status_course;

        $where_level = 'whereIn';
        $value_level = [];
        if($level==''){
            $where_level = 'whereNotIn';

        }else{
            foreach($level as $lev){
                $value_level[] = $lev;
            }
        }
        $where_status = 'whereIn';
        $value_status = [];
        if($status==''){
            $where_status = 'whereNotIn';
        }else{
            foreach($status as $stat){
                $value_status[] = $stat;
            }
        }

        $period_ids = EducCourses::where('curriculum_id',$id)
                        ->$where_level('grade_level_id',$value_level)
                        ->$where_status('status_id',$value_status)->pluck('grade_period_id')->toArray();
        $year_level_ids = EducCourses::where('curriculum_id',$id)
                        ->$where_level('grade_level_id',$value_level)
                        ->$where_status('status_id',$value_status)->pluck('grade_level_id')->toArray();
        $year_level = EducYearLevel::whereIn('id',$year_level_ids)->get();

        $period = EducGradePeriod::with(['courses' => function ($query)
                            use ($where_status,$id,$value_status) {
                            $query->where('curriculum_id', $id);
                            $query->$where_status('status_id', $value_status);
                            $query->orderBy('grade_period_id','ASC');
                            $query->orderBy('grade_level_id','ASC');
                        }])->whereIn('id',$period_ids)->get();

        $data = array(
            'id' => $id,
            'user_access_level' => $user_access_level,
            'year_level' => $year_level,
            'period' => $period
        );

        return view('rims/courses/courses',$data);
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
    public function show(int $id)
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
            'year_level' => 'nullable|array',
            'year_level.*' => 'nullable|numeric',
            'status_course' => 'nullable|array',
            'status_course.*' => 'nullable|numeric'
        ];

        $customMessages = [
            'year_level.array' => 'Year level must be an array.',
            'year_level.*.numeric' => 'Year levels must be numbers.',
            'status_course.array' => 'Status Course must be an array.',
            'status_course.*.numeric' => 'Status Course must be numbers.',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }
}
