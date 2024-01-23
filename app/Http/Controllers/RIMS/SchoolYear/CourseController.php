<?php

namespace App\Http\Controllers\RIMS\SchoolYear;

use App\Http\Controllers\Controller;
use App\Models\EducCourseStatus;
use App\Models\EducCurriculum;
use App\Models\EducGradePeriod;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedPrograms;
use App\Models\EducYearLevel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View The view 
     */
    public function index(Request $request){
        // Validate the request
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            // If validation fails, return a 404 error view
            return view('layouts/error/404');
        }
        
        // Get the 'id' parameter from the request
        $id = $request->id;
        
        // Retrieve the educational program with its related branch
        $program = EducOfferedPrograms::with('program.branch')->find($id);

        // Check if the program with the specified ID exists
        if($program == NULL){
            // If the program doesn't exist, return a 404 error view
            return view('layouts/error/404');
        }
        
        // Retrieve curriculums related to the specified program, ordered by year and code
        $curriculums = EducCurriculum::whereHas('offered_curriculums.offered_program', function ($subQuery) use ($id) {
            $subQuery->where('offered_program_id', $id);
        })->orderBy('year_from','DESC')
        ->orderBy('code','DESC')->get();
        
        // Retrieve grade levels related to courses offered in the specified program
        $grade_level = EducYearLevel::whereHas('courses.courses.curriculum.offered_program', function ($subQuery) use ($id) {
            $subQuery->where('offered_program_id', $id);
        })->get();
        
        // Prepare data to be passed to the view
        $data = array(
            'id' => $id,
            'program' => $program,
            'curriculums' => $curriculums,
            'grade_level' => $grade_level
        );
        
        // Return a view with the specified data
        return view('rims/schoolYear/coursesViewModal',$data);
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
     *
     * @return \Illuminate\View\View The view 
     */
    public function show(Request $request){
        // Validate the request
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            // If validation fails, return a 404 error view
            return view('layouts/error/404');
        }

        // Get the user's access level from the session
        $user_access_level = $request->session()->get('user_access_level');
        
        // Get the 'id', 'type', and 'curriculum_id' parameters from the request
        $id = $request->id;
        $type = $request->type;
        $curriculum_id = $request->curriculum_id;

        // Retrieve the educational program with its related school year
        $offered_program = EducOfferedPrograms::with('school_year')->where('id',$id)->first();

        // Check if the offered program with the specified ID exists
        if($offered_program==NULL){
            // If the program doesn't exist, return a 404 error view
            return view('layouts/error/404');
        }

        // Check if the request type is 'modal'
        if($type=='modal'){
            // Retrieve the first curriculum related to the specified program, ordered by year and code
            $curriculum = EducCurriculum::whereHas('offered_curriculums.offered_program', function ($subQuery) use ($id) {
                    $subQuery->where('offered_program_id', $id);
                })->orderBy('year_from','DESC')
                ->orderBy('code','DESC')->first();
            
            // Retrieve the offered curriculum related to the program and the selected curriculum
            $offered_curriculum = EducOfferedCurriculum::where('offered_program_id',$id)
                                        ->where('curriculum_id',$curriculum->id)->first();  
            $offered_curriculum_id = $offered_curriculum->id;
            $grade_levels = [];
        }else{
            // Retrieve the offered curriculum related to the program and the selected curriculum
            $offered_curriculum = EducOfferedCurriculum::where('curriculum_id',$curriculum_id)
                ->where('offered_program_id', $id)->first();
            $offered_curriculum_id = $offered_curriculum->id;            
            $grade_levels = $request->grade_levels;
        }
        
        // Retrieve offered courses related to the specified offered curriculum
        $offered_courses = EducOfferedCourses::with('course','status')
                                ->where('offered_curriculum_id',$offered_curriculum_id);

        // Check if grade levels are specified and filter the courses accordingly
        if($grade_levels){
            $offered_courses = $offered_courses->whereHas('course', function ($query) use ($grade_levels) {
                    $query->whereIn('grade_level_id',$grade_levels);
                });
        }               
        $offered_courses = $offered_courses->get();

        // Get year levels related to the offered courses
        $year_level_ids = $offered_courses->pluck('course.grade_level_id')->toArray();
        $year_level = EducYearLevel::whereIn('id',$year_level_ids)->get();

        // Retrieve the grade period related to the school year
        $period = EducGradePeriod::where('id',$offered_program->school_year->grade_period_id)->get();

        // Get all available course statuses
        $statuses = EducCourseStatus::get();
        
        // Prepare data to be passed to the view
        $data = array(
            'id' => $id,
            'user_access_level' => $user_access_level,
            'year_level' => $year_level,
            'period' => $period,
            'offered_courses' => $offered_courses,
            'statuses' => $statuses,
            'offered_curriculum' => $offered_curriculum
        );
        
        // Return a view with the specified data
        return view('rims/schoolYear/curriculumViewList',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View The view 
     */
    public function edit(Request $request){
        // Validate the request
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            // If validation fails, return a 404 error view
            return view('layouts/error/404');
        }

        // Get the user's access level from the session
        $user_access_level = $request->session()->get('user_access_level');
        
        // Get the 'id' parameter from the request
        $id = $request->id;

        // Retrieve the offered course with its related course details
        $course = EducOfferedCourses::with('course')->where('id',$id)->first();

        // Check if the offered course with the specified ID exists
        if($course==NULL){
            // If the course doesn't exist, return a 404 error view
            return view('layouts/error/404');
        }

        // Get all available course statuses
        $statuses = EducCourseStatus::get();

        // Prepare data to be passed to the view
        $data = array(
            'id' => $id,
            'course' => $course,
            'statuses' => $statuses,
            'user_access_level' => $user_access_level
        );
        
        // Return a view with the specified data
        return view('rims/schoolYear/courseViewStatusModal',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request){
        // Validate the request
        $validator = $this->updateValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            // If validation fails, return a JSON response with validation errors and a 400 status code
            return response()->json(['result' => $validator->errors()], 400);
        }

        $result = 'error';

        // Get the user's access level from the session
        $user_access_level = $request->session()->get('user_access_level');

        // Check if the user has the required access level (1 or 2)
        if($user_access_level == 1 || $user_access_level == 2){
            $user = Auth::user();
            $updated_by = $user->id;

            // Get the 'id' and 'status_id' parameters from the request
            $id = $request->id;
            $status_id = $request->status_id;

            // Start a database transaction
            DB::beginTransaction();

            try{
                // Update the status and other details of the offered course
                EducOfferedCourses::where('id', $id)
                            ->update(['status_id' => $status_id,
                                    'updated_by' => $updated_by,
                                    'updated_at' => now(),
                                    ]);

                // Commit the database transaction
                DB::commit();

                $result = 'success';
            } catch (QueryException $e) {
                // Handle database query exceptions
                return $this->handleDatabaseError($e);
            } catch (PDOException $e) {
                // Handle PDO exceptions
                return $this->handleDatabaseError($e);
            } catch (Exception $e) {
                // Handle other exceptions
                return $this->handleOtherError($e);
            }
        }

        // Prepare a JSON response with the result
        $response = array('result' => $result);
        return response()->json($response);
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
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.'
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function updateValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'status_id' => 'required|numeric'
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'status_id.required' => 'Status is required.',
            'status_id.numeric' => 'Status must be a number.'
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    /**
     * Handle database errors during the transaction.
     *
     * @param Exception $e The exception object.
     * @return \Illuminate\Http\JsonResponse The JSON response with error details.
     */
    private function handleDatabaseError($e)
    {
        DB::rollback();
        return response()->json(['result' => $e->getMessage()], 400);
    }

    /**
     * Handle other errors during the transaction.
     *
     * @param Exception $e The exception object.
     * @return \Illuminate\Http\JsonResponse The JSON response with error details.
     */
    private function handleOtherError($e)
    {
        DB::rollback();
        return response()->json(['result' => $e->getMessage()], 500);
    }
}
