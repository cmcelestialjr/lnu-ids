<?php

namespace App\Http\Controllers\RIMS\SchoolYear;

use App\Http\Controllers\Controller;
use App\Models\EducBranch;
use App\Models\EducCourses;
use App\Models\EducCourseStatus;
use App\Models\EducDepartments;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedDepartment;
use App\Models\EducOfferedPrograms;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducProgramLevel;
use App\Models\EducPrograms;
use App\Models\EducProgramsCode;
use App\Models\EducTimeMax;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request The HTTP request object containing parameters.
     * @return \Illuminate\View\View The view displaying the listing.
     */
    public function index(Request $request)
    {
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

        // Retrieve the offered school year with its related grade period
        $query = EducOfferedSchoolYear::with('grade_period')->where('id',$id)->first();

        // Check if the offered school year with the specified ID exists
        if($query==NULL){
            // If the school year doesn't exist, return a 404 error view
            return view('layouts/error/404');
        }

        // Retrieve all educational branches
        $branch = EducBranch::get();

        // Prepare data to be passed to the view
        $data = array(
            'id' => $id,
            'query' => $query,
            'branch' => $branch,
            'user_access_level' => $user_access_level
        );

        // Return a view with the specified data
        return view('rims/schoolYear/programsViewModal',$data);
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
     *
     * @param Request $request The HTTP request object containing parameters.
     * @return \Illuminate\Http\JsonResponse The JSON response indicating the result of the operation.
     */
    public function store(Request $request){
        // Validate the request
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            // If validation fails, return a JSON response with validation errors and a 400 status code
            return response()->json(['result' => $validator->errors()], 400);
        }

        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $time_max = EducTimeMax::first();
        $result = 'error';

        try{
            // Retrieve the offered school year with its related grade period
            $school_year = EducOfferedSchoolYear::with('grade_period')->where('id',$id)->first();
            $grade_period = $school_year->grade_period_id;
            $grade_period_period = $school_year->grade_period->period;

            // Retrieve program level IDs based on the grade period period
            $program_level_ids = EducProgramLevel::where('period',$grade_period_period)->pluck('id')->toArray();

            // Query and map available programs to be offered in the school year
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
                                                'branch_id' => $query->branch_id,
                                                'name' => $query->name,
                                                'status_id' => 1,
                                                'updated_by' => $updated_by,
                                                'created_at' => now(), // Use 'now()' to get the current timestamp
                                                'updated_at' => now() // Use 'now()' to get the current timestamp
                                            ];
                                        })->toArray();

            // Insert the mapped program data into the database
            EducOfferedPrograms::insert($query);
            
            // Get the IDs of the inserted programs
            $programs_id = EducOfferedPrograms::where('school_year_id',$id)->pluck('id')->toArray();

            // Get the department IDs of the inserted programs
            $department_ids = EducOfferedPrograms::where('school_year_id',$id)->pluck('department_id')->toArray();

            // Query and map departments offered in the school year
            $query = EducDepartments::whereIn('id',$department_ids)->get()
                                    ->map(function($query) use ($id,$updated_by) {
                                        return [
                                            'school_year_id' => $id,
                                            'department_id' => $query->id,
                                            'name' => $query->name,
                                            'shorten' => $query->shorten,
                                            'code' => $query->code,
                                            'updated_by' => $updated_by,
                                            'created_at' => now(), // Use 'now()' to get the current timestamp
                                            'updated_at' => now() // Use 'now()' to get the current timestamp
                                        ];
                                    })->toArray();

            // Insert the mapped department data into the database
            EducOfferedDepartment::insert($query);

            // Query and map offered curriculums based on the inserted programs
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
                                                'status_id' => 1,
                                                'updated_by' => $updated_by,
                                                'created_at' => now(), // Use 'now()' to get the current timestamp
                                                'updated_at' => now() // Use 'now()' to get the current timestamp
                                            ];
                                        })->toArray();

            // Insert the mapped offered curriculum data into the database
            EducOfferedCurriculum::insert($query);

            // Initialize an array to store course data
            $datas = [];

            // Query offered curriculums and related courses to create offered courses
            $query = EducOfferedCurriculum::with('offered_program.program')->whereIn('offered_program_id', $programs_id)
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
                                'status_id' => 1,
                                'year_level' => $course->grade_level->level,
                                'section' => 1,
                                'hours' => $course->units,
                                'minutes' => 0,
                                'section_code' => $row->offered_program->name.$row->offered_program->program->code.'1'.$course->grade_level->level.$row->code,
                                'updated_by' => $updated_by,
                                'created_at' => now(), // Use 'now()' to get the current timestamp
                                'updated_at' => now() // Use 'now()' to get the current timestamp
                        ];
                }
            }

            // Insert the mapped course data into the database as offered courses
            EducOfferedCourses::insert($datas);

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

        // Prepare a JSON response with the result
        $response = array('result' => $result);
        return response()->json($response);
    }


    /**
     * Display the specified resource.
     *
     * @param Request $request The HTTP request object containing parameters.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the specified resource's data.
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

        $data = array();
        $id = $request->id;
        $branch = $request->branch;

        // Retrieve offered programs with related program level and department information
        $query = EducOfferedPrograms::with('program.program_level','department')
                        ->where('school_year_id',$id)
                        ->where('branch_id',$branch)
                        ->orderBy('program_id')
                        ->orderBY('name')->get();

        $count = $query->count();
        
        if($count > 0){
            $x = 1;            
            foreach($query as $r){
                // Prepare data for each program
                $data_list['f1'] = $x;
                $data_list['f2'] = $r->program->program_level->name;
                $data_list['f3'] = $r->department->shorten;
                $data_list['f4'] = $r->program->shorten;
                $data_list['f5'] = '<button class="btn btn-primary btn-primary-scan btn-xs coursesViewModal"
                                        data-id="'.$r->id.'">
                                        <span class="fa fa-eye"></span> View
                                    </button>';
                
                // Call a function to generate a status select dropdown for the program
                $data_list['f6'] = $this->statusSelect($r->status_id, $r->id);

                // Add the prepared data to the response array
                array_push($data, $data_list);
                $x++;
            }
        }

        // Return a JSON response containing the data for the specified resource
        return  response()->json($data);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request The HTTP request object containing parameters.
     * @return \Illuminate\View\View The view displaying the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        // Validate the request
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            // If validation fails, return a 404 error view
            return view('layouts/error/404');
        }

        $id = $request->id;

        // Retrieve the offered school year with its related grade period
        $query = EducOfferedSchoolYear::with('grade_period')->where('id',$id)->first();

        // Check if the offered school year with the specified ID exists
        if($query==NULL){
            // If the school year doesn't exist, return a 404 error view
            return view('layouts/error/404');
        }

        // Prepare data to be passed to the view
        $data = array(
            'id' => $id,
            'query' => $query
        );

        // Return a view for editing the specified resource
        return view('rims/schoolYear/modalPrograms',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request The HTTP request object containing parameters.
     * @return \Illuminate\Http\JsonResponse The JSON response indicating the result of the update operation.
     */
    public function update(Request $request)
    {
        // Validate the request
        $validator = $this->updateValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            // If validation fails, return a JSON response with validation errors and a 400 status code
            return response()->json(['result' => $validator->errors()], 400);
        }

        // Retrieve the currently authenticated user
        $user = Auth::user();
        $id = $request->id;
        $val = $request->val;
        $result = 'error';

        // Check if a program with the specified ID and status value exists
        $query = EducPrograms::where('id',$id)->where('status_id',$val)->first();

        // If a program with the specified conditions exists
        if($query!=NULL){
            try{
                // Determine the new status value based on the provided value
                if($val==2){
                    $status_id = 1;
                }else{
                    $status_id = 2;
                }

                // Update the program's status and other related attributes
                EducPrograms::where('id', $id)
                            ->update(['status_id' => $status_id,
                                    'updated_by' => $user->id,
                                    'updated_at' => now(), // Use 'now()' to get the current timestamp
                                    ]);

                // Set the result to 'success' after a successful update
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

        // Prepare a JSON response with the result of the update operation
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
     * Generates an HTML select dropdown with course statuses and preselects a specified status.
     *
     * @param int $status_selected The ID of the status to be preselected in the dropdown.
     * @param int $id The ID of the associated resource (e.g., program) for which the status is being selected.
     * @return string The HTML select dropdown as a string.
     */
    private function statusSelect($status_selected, $id)
    {
        // Retrieve all course statuses
        $statuses = EducCourseStatus::get();

        // Initialize the HTML for the select dropdown
        $selectHTML = '<select class="form-control select2-table selectStatus" style="width:100%">';

        // Iterate through each status to populate the dropdown
        foreach ($statuses as $status) {
            // Determine the color associated with the status (assuming there is a 'getStatusColor' function)
            $color = $this->getStatusColor($status->id);

            // Check if the current status matches the preselected status
            if ($status_selected == $status->id) {
                // If it matches, create an option with the 'selected' attribute
                $selectHTML .= '<option value="' . $status->id . '" data-id="' . $id . '" data-from="program" data-color="' . $color . '" selected>' . $status->name . '</option>';
            } else {
                // If it doesn't match, create a regular option
                $selectHTML .= '<option value="' . $status->id . '" data-id="' . $id . '" data-from="program" data-color="' . $color . '">' . $status->name . '</option>';
            }
        }

        // Close the select dropdown HTML
        $selectHTML .= '</select>';

        // Return the HTML select dropdown as a string
        return $selectHTML;
    }

    /**
     * Determines the color associated with a given course status.
     *
     * @param int $status The ID of the course status for which to determine the color.
     * @return string The color code (e.g., '#006400' for green or 'red') associated with the status.
     */
    private function getStatusColor($status)
    {
        // Check if the status ID matches a specific value (e.g., 1)
        if ($status == 1) {
            // If it matches, return the color code for 'green'
            return '#006400'; // Green color
        } else {
            // If it doesn't match, return the color code for 'red'
            return 'red'; // Red color
        }
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
    private function showValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'branch' => 'required|numeric'
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'branch.required' => 'Branch is required.',
            'branch.numeric' => 'Branch must be a number.'
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
            'val' => 'required|numeric'
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'val.required' => 'Val is required.',
            'val.numeric' => 'Val must be a number.'
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
