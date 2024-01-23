<?php

namespace App\Http\Controllers\RIMS\Departments;

use App\Http\Controllers\Controller;
use App\Models\EducDepartments;
use App\Models\EducPrograms;
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
     * Display a listing of programs associated with a specific department.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Validate the incoming request data using a custom validation method
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404'); // Return a 404 error view if validation fails
        }
        
        // Get the 'id' parameter from the request
        $id = $request->id;

        // Get the user's access level from the session
        $user_access_level = $request->session()->get('user_access_level');

        // Retrieve the department record with the specified 'id'
        $department = EducDepartments::where('id', $id)->first();

        // Retrieve the programs associated with the department
        $programs = EducPrograms::with('status')->where('department_id', $id)->get();

        // Create a data array with 'id', 'department', 'programs', and 'user_access_level' for the view
        $data = array(
            'id' => $id,
            'department' => $department,
            'programs' => $programs,
            'user_access_level' => $user_access_level
        );

        // Return the programsModal view with the data
        return view('rims/departments/programsModal', $data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        // Validate the incoming request data using a custom validation method
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors()], 400); // Return validation errors as JSON response
        }

        // Initialize an empty array to store the data
        $data = array();

        // Get the 'id' parameter from the request
        $id = $request->id;

        // Retrieve departments that are not the current department and order them by name
        $department = EducDepartments::where('id', '<>', $id)->orderBy('name')->pluck('id')->toArray();

        // Retrieve programs associated with the selected departments
        $query = EducPrograms::with('departments', 'status', 'codes')->whereIn('department_id', $department)->get();

        // Get the count of records returned by the query
        $count = $query->count();

        if ($count > 0) {
            $x = 1;

            // Loop through each result
            foreach ($query as $r) {
                // Create an associative array to store field values for each record
                $data_list['f1'] = '<input type="checkbox" class="form-control program" data-id="'.$r->id.'">';
                $data_list['f2'] = $x;
                $data_list['f3'] = '<span id="programDeptName'.$r->id.'">'.$r->departments->name.' ('.$r->departments->shorten.')</span>';
                $data_list['f4'] = $r->name;
                $data_list['f5'] = $r->shorten;
                $data_list['f6'] = $r->code;

                // Determine the status button based on the status_id
                if ($r->status_id == 1) {
                    $data_list['f7'] = '<button class="btn btn-success btn-success-scan">Open</button>';
                } else {
                    $data_list['f7'] = '<button class="btn btn-danger btn-danger-scan">'.$r->status->name.'</button>';
                }

                // Push the associative array into the 'data' array
                array_push($data, $data_list);
                $x++;
            }
        }

        // Return the data as a JSON response
        return response()->json($data);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        // Validate the incoming request data using a custom validation method
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors()], 400); // Return validation errors as JSON response
        }

        // Initialize an empty array to store the data
        $data = array();

        // Get the 'id' parameter from the request
        $id = $request->id;

        // Retrieve programs associated with the specified department
        $query = EducPrograms::with('status', 'codes')->where('department_id', $id)->get();

        // Get the count of records returned by the query
        $count = $query->count();

        if ($count > 0) {
            $x = 1;

            // Loop through each result
            foreach ($query as $r) {
                // Create an associative array to store field values for each record
                $data_list['f1'] = $x;
                $data_list['f2'] = $r->name;
                $data_list['f3'] = $r->shorten;
                $data_list['f4'] = $r->code;

                // Determine the status button based on the status_id
                if ($r->status_id == 1) {
                    $data_list['f5'] = '<button class="btn btn-success btn-success-scan">Open</button>';
                } else {
                    $data_list['f5'] = '<button class="btn btn-danger btn-danger-scan">'.$r->status->name.'</button>';
                }

                // Push the associative array into the 'data' array
                array_push($data, $data_list);
                $x++;
            }
        }

        // Return the data as a JSON response
        return response()->json($data);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        // Validate the incoming request data using a custom validation method
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404'); // Return a 404 error view if validation fails
        }

        // Get the 'id' parameter from the request
        $id = $request->id;

        // Get the user's access level from the session
        $user_access_level = $request->session()->get('user_access_level');

        // Retrieve the department record with the specified 'id'
        $department = EducDepartments::where('id', $id)->first();

        // Retrieve all departments except the current one
        $departments = EducDepartments::where('id', '<>', $id)->get();

        // Create a data array with 'id', 'department', 'departments', and 'user_access_level' for the view
        $data = array(
            'id' => $id,
            'department' => $department,
            'departments' => $departments,
            'user_access_level' => $user_access_level
        );

        // Return the programAddModal view with the data
        return view('rims/departments/programAddModal', $data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        // Validate the incoming request data using a custom validation method
        $validator = $this->updateValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors()], 400); // Return validation errors as JSON response
        }
        
        // Get the user's access level from the session
        $user_access_level = $request->session()->get('user_access_level');

        // Get the currently authenticated user
        $user = Auth::user();

        // Get the user ID for the user who updated the resource
        $updated_by = $user->id;

        // Get the 'id', 'program_id' from the request
        $id = $request->id;
        $program_id = $request->program_id;

        // Initialize the result as 'error'
        $result = 'error';

        // Initialize a variable to store the department name and shorten
        $dept = '';

        if ($user_access_level == 1 || $user_access_level == 2) {
            // Start a database transaction
            DB::beginTransaction();
            try {
                // Retrieve the department record with the specified 'id'
                $query = EducDepartments::where('id', $id)->first();

                // Get the department name and shorten
                $dept = $query->name . ' (' . $query->shorten . ')';

                // Update the EducPrograms record with the specified 'program_id' to link to the new department
                EducPrograms::where('id', $program_id)
                    ->update([
                        'department_id' => $id,
                        'updated_by' => $updated_by,
                        'updated_at' => now() // Use Laravel's 'now()' to get the current date and time
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

        // Create a response array with the result and department details
        $response = array('result' => $result, 'dept' => $dept);

        // Return the response as a JSON response
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
            'id' => 'required|numeric',
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
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
            'program_id' => 'required|numeric',
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'program_id.required' => 'Program is required.',
            'program_id.string' => 'Program must be a number.',
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
