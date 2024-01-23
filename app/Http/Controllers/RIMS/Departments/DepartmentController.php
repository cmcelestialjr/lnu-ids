<?php

namespace App\Http\Controllers\RIMS\Departments;

use App\Http\Controllers\Controller;
use App\Models\EducDepartments;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Initialize an empty array to store the data
        $data = array();
        
        // Query the EducDepartments model with 'programs' relationship, ordered by 'name'
        $query = EducDepartments::with('programs')->orderBy('name')->get();
        
        // Get the count of records returned by the query
        $count = $query->count();
        
        // Check if there are records in the result
        if ($count > 0) {
            $x = 1;
            
            // Loop through each result
            foreach ($query as $r) {
                // Create an associative array to store field values for each record
                $data_list['f1'] = $x;
                $data_list['f2'] = $r->name;
                $data_list['f3'] = $r->shorten;
                $data_list['f4'] = $r->code;
                
                // Create a button with a 'programsModal' class and 'data-id' attribute
                $data_list['f5'] = '<button class="btn btn-primary btn-primary-scan programsModal"
                                        data-id="'.$r->id.'">
                                        <span class="fa fa-graduation-cap"></span> Programs
                                    </button>';
                
                // Create a button with an 'editModal' class and 'data-id' attribute
                $data_list['f6'] = '<button class="btn btn-info btn-info-scan editModal"
                                        data-id="'.$r->id.'">
                                        <span class="fa fa-edit"></span> Edit
                                    </button>';
                
                // Push the associative array into the 'data' array
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        // Return a view for creating a new resource
        return view('rims/departments/newModal');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request data using a custom validation method
        $validator = $this->storeValidateRequest($request);

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

        // Get the 'name', 'shorten', and 'code' fields from the request
        $name = $request->name;
        $shorten = $request->shorten;
        $code = mb_strtoupper($request->code);

        // Check if the user has the required access level to perform the operation
        if ($user_access_level == 1 || $user_access_level == 2) {
            // Check if a record with the same 'name', 'shorten', or 'code' already exists
            $check = EducDepartments::where('name', $name)
                ->orWhere('shorten', $shorten)
                ->orWhere('code', $code)
                ->first();

            if ($check == NULL) {
                // Start a database transaction
                DB::beginTransaction();
                try {
                    // Create a new EducDepartments instance and save it to the database
                    $insert = new EducDepartments();
                    $insert->name = $name;
                    $insert->shorten = $shorten;
                    $insert->code = $code;
                    $insert->updated_by = $updated_by;
                    $insert->save();

                    // Commit the database transaction
                    DB::commit();
                    // Set the result as 'success' if the record is successfully created
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
                // Set the result as 'exists' if a record with the same data already exists
                $result = 'exists';
            }
        }

        // Create a response array with the result
        $response = array('result' => $result);

        // Return the response as a JSON response
        return response()->json($response);
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

        // Retrieve the EducDepartments record with the specified 'id'
        $department = EducDepartments::where('id', $id)->first();

        if ($department) {
            // Create a data array with 'id', 'department', and 'user_access_level' for the view
            $data = array(
                'id' => $id,
                'department' => $department,
                'user_access_level' => $user_access_level
            );

            // Return the editModal view with the data
            return view('rims/departments/editModal', $data);
        } else {
            return view('layouts/error/404'); // Return a 404 error view if the specified record is not found
        }
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

        // Get the 'id', 'name', 'shorten', and 'code' fields from the request
        $id = $request->id;
        $name = $request->name;
        $shorten = $request->shorten;
        $code = mb_strtoupper($request->code);

        // Initialize the result as 'error'
        $result = 'error';

        // Check if the user has the required access level to perform the operation
        if ($user_access_level == 1 || $user_access_level == 2) {
            // Check if a record with the same 'name', 'shorten', or 'code' already exists
            $check = EducDepartments::where(function ($query) use ($name, $shorten, $code) {
                $query->where('name', $name)->orWhere('shorten', $shorten)->orWhere('code', $code);
            })->where('id', '<>', $id)->first();

            if ($check == NULL) {
                // Start a database transaction
                DB::beginTransaction();
                try {
                    // Update the EducDepartments record with the specified 'id'
                    EducDepartments::where('id', $id)
                        ->update([
                            'name' => $name,
                            'shorten' => $shorten,
                            'code' => $code,
                            'updated_by' => $updated_by,
                            'updated_at' => now() // Use Laravel's 'now()' to get the current date and time
                        ]);
                    // Commit the database transaction
                    DB::commit();
                    // Set the result as 'success' if the record is successfully updated
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
                // Set the result as 'exists' if a record with the same data already exists
                $result = 'exists';
            }
        }

        // Create a response array with the result
        $response = array('result' => $result);

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
    private function storeValidateRequest(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'shorten' => 'required|string',
            'code' => 'required|string'
        ];

        $customMessages = [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'shorten.required' => 'Shorten is required.',
            'shorten.string' => 'Shorten must be a string.',
            'code.required' => 'Code is required.',
            'code.string' => 'Code must be a string.'
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
            'name' => 'required|string',
            'shorten' => 'required|string',
            'code' => 'required|string'
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.string' => 'ID must be a number.',
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'shorten.required' => 'Shorten is required.',
            'shorten.string' => 'Shorten must be a string.',
            'code.required' => 'Code is required.',
            'code.string' => 'Code must be a string.'
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
