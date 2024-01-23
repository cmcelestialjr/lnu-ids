<?php

namespace App\Http\Controllers\RIMS\Programs;

use App\Http\Controllers\Controller;
use App\Models\EducBranch;
use App\Models\EducCourses;
use App\Models\EducCourseStatus;
use App\Models\EducCurriculum;
use App\Models\EducDepartments;
use App\Models\EducDepartmentUnit;
use App\Models\EducProgramLevel;
use App\Models\EducPrograms;
use App\Models\EducProgramsCode;
use App\Models\EducYearLevel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;
use PDOException;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request The HTTP request object
     * @return \Illuminate\Http\JsonResponse A JSON response containing the program data
     */
    public function index(Request $request)
    {
        // Get the user access level from the session
        $user_access_level = $request->session()->get('user_access_level');
        
        // Initialize data array
        $data = array();

        // Validate the incoming request data
        $validator = $this->indexValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($data);
        }
        
        // Get the authenticated user
        $user = Auth::user();
        
        // Get the status ID from the request
        $status_id = $request->status_id;
        
        // Query the programs with related data
        $query = EducPrograms::with('departments', 'program_level', 'status')->where('status_id', $status_id)->get();
        
        // Count the results
        $count = $query->count();
        
        // Process the query results
        if ($count > 0) {
            $x = 1;
            foreach ($query as $r) {
                $data_list['f1'] = $x;
                $data_list['f2'] = $r->program_level->name;
                $data_list['f3'] = $r->departments->shorten;
                $data_list['f4'] = $r->name;
                $data_list['f5'] = $r->shorten;
                
                if ($user_access_level == 1 || $user_access_level == 2) {
                    $data_list['f6'] = '<button class="btn btn-primary btn-primary-scan programEdit"
                                            data-id="'.$r->id.'"
                                            ><span class="fa fa-edit"></span> Edit</button>';
                    $data_list['f9'] = '<button class="btn btn-primary btn-primary-scan branch"
                                            data-id="'.$r->id.'"
                                            ><span class="fa fa-eye"></span> Branches</button>';
                    if ($r->status->id == 1) {
                        $status = '<button class="btn btn-success btn-success-scan programStatus"
                                        id="programStatus'.$r->id.'"
                                        data-id="'.$r->id.'"
                                        >'.$r->status->name.'</button>';
                    } else {
                        $status = '<button class="btn btn-danger btn-danger-scan programStatus"
                                        id="programStatus'.$r->id.'"
                                        data-id="'.$r->id.'"
                                        >'.$r->status->name.'</button>';
                    }
                } else {
                    if ($r->status->id == 1) {
                        $status = '<button class="btn btn-success">'.$r->status->name.'</button>';
                    } else {
                        $status = '<button class="btn btn-danger">'.$r->status->name.'</button>';
                    }
                }
                
                $data_list['f7'] = $status;
                $data_list['f8'] = '<button class="btn btn-info btn-info-scan viewModal"
                                        data-id="'.$r->id.'">
                                        <span class="fa fa-eye"></span> Courses
                                    </button>';
                
                array_push($data, $data_list);
                $x++;
            }
        }
        
        // Return the data as a JSON response
        return response()->json($data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View The view for creating a new program
     */
    public function create()
    {
        // Get program levels
        $levels = EducProgramLevel::get();
        
        // Get departments and order them by name
        $departments_query = EducDepartments::orderBy('name');
        $departments = $departments_query->get();
        $department = $departments_query->first();
        $unit = EducDepartmentUnit::where('department_id',$department->id)->get();

        // Prepare data for the view
        $data = array(
            'levels' => $levels,
            'departments' => $departments,
            'unit' => $unit
        );
        
        // Return the view for creating a new program
        return view('rims/programs/programNewModal', $data);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Get the user's access level and ID
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;

        // Validate the incoming request data
        $validator = $this->updateValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors()], 400); // Return validation errors
        }

        // Initialize the result variable
        $result = 'error';

        // Retrieve data from the request
        $level = $request->level;
        $department = $request->department;
        $unit = $request->unit;
        $name = $request->name;
        $shorten = $request->shorten;
        $code = mb_strtoupper($request->code);

        // Check user access level
        if ($user_access_level == 1 || $user_access_level == 2) {
            // Check if a program with the same name, shorten, or code already exists
            $check = EducPrograms::where('name', $name)
                ->orWhere('shorten', $shorten)
                ->orWhere('code', $code)
                ->first();

            if ($check == NULL) {
                // Start a database transaction
                DB::beginTransaction();
                try {
                    // Check if the unit is empty and set it to NULL if necessary
                    if ($unit == '') {
                        $unit = NULL;
                    }
                    // Create a new EducPrograms record
                    $insert = new EducPrograms();
                    $insert->department_id = $department;
                    $insert->program_level_id = $level;
                    $insert->name = $name;
                    $insert->department_unit_id = $unit;
                    $insert->shorten = mb_strtoupper($shorten);
                    $insert->status_id = 1;
                    $insert->updated_by = $updated_by;
                    $insert->save();

                    // Get the program ID
                    $program_id = $insert->id;

                    // Create a new EducProgramsCode record for the program
                    $insert = new EducProgramsCode();
                    $insert->program_id = $program_id;
                    $insert->name = $code;
                    $insert->branch_id = 1;
                    $insert->status_id = 1;
                    $insert->updated_by = $updated_by;
                    $insert->save();

                    // Iterate through branches and create corresponding program code records
                    $branches = EducBranch::where('id', '!=', 1)->get();
                    if ($branches->count() > 0) {
                        foreach ($branches as $branch) {
                            $insert = new EducProgramsCode();
                            $insert->program_id = $program_id;
                            $insert->name = $branch->code;
                            $insert->branch_id = $branch->id;
                            $insert->status_id = 1;
                            $insert->updated_by = $updated_by;
                        }
                    }
                    // Commit the database transaction
                    DB::commit();
                    // Set the result to 'success'
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
            } else {
                // Set the result to 'exists' if a program with the same data already exists
                $result = 'exists';
            }
        }

        // Prepare the response array
        $response = array('result' => $result);

        // Return a JSON response
        return response()->json($response);
    }


    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->showValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }
        // Get the program ID and user access level from the request
        $id = $request->id;
        $user_access_level = $request->session()->get('user_access_level');

        // Retrieve program details with associated data
        $program = EducPrograms::with('codes', 'departments', 'program_level', 'status')
            ->where('id', $id)
            ->first();

        // Retrieve the latest curriculum for the program
        $curriculum = EducCurriculum::with('status')
            ->where('program_id', $id)
            ->orderBy('year_from', 'DESC')
            ->first();

        // Retrieve all curriculums for the program
        $curriculums = EducCurriculum::with('status')
            ->where('program_id', $id)
            ->orderBy('year_from', 'DESC')
            ->get();

        // Retrieve available course statuses
        $status = EducCourseStatus::get();

        // Retrieve year levels associated with the program's program level
        $year_level = EducYearLevel::where('program_level_id', $program->program_level_id)
            ->orderBy('level', 'ASC')
            ->get();

        // Prepare data for the view
        $data = array(
            'id' => $id,
            'program' => $program,
            'curriculum' => $curriculum,
            'curriculums' => $curriculums,
            'year_level' => $year_level,
            'status' => $status,
            'user_access_level' => $user_access_level
        );

        // Return the view with the data
        return view('rims/programs/programViewModal', $data);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->showValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }
        // Get the program ID from the request
        $id = $request->id;

        // Retrieve program details by its ID
        $query = EducPrograms::find($id);

        // Check if query exists
        if ($query==NULL) {
            return view('layouts/error/404');
        }

        // Retrieve all departments and department units
        $department = EducDepartments::get();
        $unit = EducDepartmentUnit::where('department_id',$query->department_id)->get();

        // Prepare data for the view
        $data = array(
            'query' => $query,
            'department' => $department,
            'unit' => $unit
        );

        // Return the view for program editing with the data
        return view('rims/programs/programEdit', $data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        // Get user access level from the session
        $user_access_level = $request->session()->get('user_access_level');

        // Check if the user access level is valid
        if (!in_array($user_access_level, [1, 2, 3])) {
            return response()->json(['result' => 'error']);
        }

        // Validate the incoming request data
        $validator = $this->updateValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors()], 400); // Return validation errors
        }

        // Start a database transaction
        DB::beginTransaction();  
        try {
            // Get the authenticated user
            $user = Auth::user();
            $updated_by = $user->id;
            $id = $request->id;
            $department = $request->department;
            $unit = $request->unit;
            $name = $request->name;
            $shorten = $request->shorten;
            $code = mb_strtoupper($request->code);
            $remarks = $request->remarks;

            // Check if a program with the same shorten or code exists, excluding the current program
            $check = EducPrograms::where('id', '!=', $id)
                ->where(function ($subQuery) use ($shorten, $code) {
                    $subQuery->where('shorten', $shorten)
                        ->orWhere('code', $code);
                })
                ->first();

            // If no duplicate program is found, proceed with the update
            if ($check == NULL) {
                // Check if the unit is empty and set it to NULL if necessary
                if ($unit == '') {
                    $unit = NULL;
                }

                // Update the program details in the database
                EducPrograms::where('id', $id)
                    ->update([
                        'department_id' => $department,
                        'department_unit_id' => $unit,
                        'name' => $name,
                        'shorten' => $shorten,
                        'code' => $code,
                        'remarks' => $remarks,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                
                // Commit the database transaction
                DB::commit();
                return response()->json(['result' => 'success']);
            } else {
                // If a duplicate program is found, rollback the transaction
                DB::rollback();
                return response()->json(['result' => 'exists']);
            }
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


    /**
     * Show the form for changing the status of the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function status(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->showValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }
        // Get the program ID from the request
        $id = $request->id;

        // Query the database to retrieve the program details
        $query = EducPrograms::where('id', $id)->first();

        // Determine the program's current status and set the appropriate class, button, and status text
        if ($query->status_id == 1) {
            $class = 'danger'; // CSS class for styling (e.g., red)
            $btn = 'success'; // Button class for styling (e.g., green)
            $status = 'Closed'; // Text indicating the program is closed
        } else {
            $class = 'success'; // CSS class for styling (e.g., green)
            $btn = 'danger'; // Button class for styling (e.g., red)
            $status = 'Open'; // Text indicating the program is open
        }

        // Prepare data to be passed to the view
        $data = [
            'id' => $id,
            'query' => $query,
            'class' => $class,
            'status' => $status,
            'btn' => $btn
        ];

        // Load the view and pass the data to it
        return view('rims/programs/programStatusModal', $data);
    }


    /**
     * Update the status of the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function statusUpdate(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->showValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors()], 400); // Return validation errors
        }
        // Get user access level from the session
        $user_access_level = $request->session()->get('user_access_level');
        
        // Get the currently authenticated user
        $user = Auth::user();
        
        // Get the user's ID for tracking the update
        $updated_by = $user->id;
        
        // Get the program ID from the request
        $id = $request->id;
        
        // Initialize variables to store the result, button class, and button HTML
        $result = 'error';
        $btn_class = '';
        $btn_html = '';
        
        // Check if the user has sufficient access level to perform the status update
        if ($user_access_level == 1 || $user_access_level == 2) {
            // Start a database transaction
            DB::beginTransaction();  
            try {
                // Retrieve the program record from the database
                $check = EducPrograms::where('id', $id)->first();
                
                // Check if the program record exists
                if ($check != null) {
                    // Determine the new status and associated button styles
                    if ($check->status_id == 1) {
                        $status_id = 2; // Program is currently open, so set it to closed
                        $btn_class = 'btn-danger btn-danger-scan';
                        $btn_html = ' Closed';
                    } else {
                        $status_id = 1; // Program is currently closed, so set it to open
                        $btn_class = 'btn-success btn-success-scan';
                        $btn_html = ' Open';
                    }
                    
                    // Retrieve the curriculum IDs associated with the program
                    $curriculum_id = EducCurriculum::where('program_id', $id)->pluck('id')->toArray();
                    $now = date('Y-m-d H:i:s');
                    // Update the program status
                    EducPrograms::where('id', $id)
                        ->update(['status_id' => $status_id,
                                'updated_by' => $updated_by,
                                'updated_at' => $now]);
                    
                    // Update the program code status
                    EducProgramsCode::where('program_id', $id)
                        ->update(['status_id' => $status_id,
                                'updated_by' => $updated_by,
                                'updated_at' => $now]); 
                    
                    // Update the curriculum status
                    EducCurriculum::where('program_id', $id)
                        ->update(['status_id' => $status_id,
                                'updated_by' => $updated_by,
                                'updated_at' => $now]);
                    
                    // Update the course status associated with the curriculum
                    EducCourses::whereIn('curriculum_id', $curriculum_id)
                        ->update(['status_id' => $status_id,
                                'updated_by' => $updated_by,
                                'updated_at' => $now]);
                    // Commit the database transaction
                    DB::commit();
                    // Set the result to success
                    $result = 'success';  
                }else{
                    DB::rollback();
                }
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
        
        // Prepare the response data as an array
        $response = [
            'result' => $result,
            'btn_class' => $btn_class,
            'btn_html' => $btn_html
        ];
        
        // Return the response as JSON
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
    private function indexValidateRequest(Request $request)
    {
        $rules = [
            'status_id' => 'required|numeric'
        ];

        $customMessages = [
            'status_id.required' => 'StatusID is required.',
            'status_id.numeric' => 'StatusID must be a number.'
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
            'department' => 'required|numeric',
            'name' => 'required|string',
            'unit' => 'nullable|numeric',
            'shorten' => 'required|string',
            'code' => 'required|string'
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'department.required' => 'Department is required.',
            'department.numeric' => 'Department must be a number.',
            'name.required' => 'Name is required.',
            'shorten.required' => 'Shorten is required.',
            'code.required' => 'Code is required.',
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
