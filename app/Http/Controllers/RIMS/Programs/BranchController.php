<?php

namespace App\Http\Controllers\RIMS\Programs;

use App\Http\Controllers\Controller;
use App\Models\EducBranch;
use App\Models\EducPrograms;
use App\Models\EducProgramsCode;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;
use PDOException;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {   
        // Find the EducPrograms record based on the requested ID
        $program = EducPrograms::find($request->id);

        // Create a data array with the program
        $data = array('program' => $program);

        // Return a view with the program data
        return view('rims/programs/branchModal', $data);
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        // Get the user's access level from the session
        $user_access_level = $request->session()->get('user_access_level'); 

        // Initialize an empty data array
        $data = array();

        // Get the requested ID from the request
        $id = $request->id;

        // Query the EducBranch model with programs related to the specified program ID
        $query = EducBranch::with(['program' => function ($query) use ($id) {
            $query->where('program_id', $id);
        }])->get();

        // Get the count of results
        $count = $query->count();

        if ($count > 0) {
            $x = 1;
            foreach ($query as $r) {
                $data_list['f1'] = $x;
                $data_list['f2'] = $r->name;
                $data_list['f3'] = $r->code;

                // Check if a program exists for this branch
                if ($r->program) {
                    $program = $r->program->first();

                    // Check the user's access level to determine how to display the program status
                    if ($user_access_level == 1 || $user_access_level == 2) {
                        if ($program->status_id == 1) {
                            $status = '<button class="btn btn-success btn-success-scan branchStatus"
                                        data-id="'.$program->id.'">'.$program->status->name.'</button>';
                        } else {
                            $status = '<button class="btn btn-danger btn-danger-scan branchStatus"
                                        data-id="'.$program->id.'">'.$program->status->name.'</button>';
                        }
                    } else {
                        if ($program->status_id == 1) {
                            $status = '<button class="btn btn-success">'.$program->status->name.'</button>';
                        } else {
                            $status = '<button class="btn btn-danger">'.$program->status->name.'</button>';
                        }
                    }
                } else {
                    $status = '';
                }

                $data_list['f4'] = $status;
                array_push($data, $data_list);
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
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        // Get user access level
        $user_access_level = $request->session()->get('user_access_level');

        // Check user access level
        if (!in_array($user_access_level, [1, 2, 3])) {
            return response()->json(['result' => 'error']);
        }

        // Validate the request
        $validator = $this->updateValidateRequest($request);
        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors()], 400); // Return validation errors
        }

        // Start a database transaction
        DB::beginTransaction();  
        try{
            $user = Auth::user();
            $updated_by = $user->id;
            $id = $request->id;
            
            // Check if the program code exists
            $check = EducProgramsCode::find($id);
            
            if($check){
                $check_status = $check->status_id;
                $program_id = $check->program_id;
                $status_id = 1;
                $btn = 'btn btn-success btn-success-scan';
                $text = 'Open';
                
                // Toggle the status and update
                if($check_status == 1){
                    $status_id = 2;
                    $btn = 'btn btn-danger btn-danger-scan';
                    $text = 'Close';
                }

                EducProgramsCode::where('id', $id)
                    ->update([
                        'status_id' => $status_id,
                        'updated_by' => $updated_by,
                        'updated_at' => now(), // Use the now() function to get the current timestamp
                    ]);
                
                // Update the associated program
                $this->updateProgram($program_id);

                // Commit the database transaction
                DB::commit();
                return response()->json(['result' => 'success', 'btn' => $btn, 'text' => $text]);
            } else {
                DB::rollback();
                return response()->json(['result' => 'exists']);
            }
        } catch (QueryException $e) {
            return $this->handleDatabaseError($e);
        } catch (PDOException $e) {
            return $this->handleDatabaseError($e);
        } catch (Exception $e) {
            return $this->handleOtherError($e);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Update the status of the associated program based on program codes.
     *
     * @param int $id The ID of the program to update
     * @return void
     */
    private function updateProgram($id)
    {
        try{
            // Find the program by ID
            $program = EducPrograms::find($id);
            
            if($program){
                // Find program codes associated with the program that have status 1 (Open)
                $program_code = EducProgramsCode::where('program_id', $id)->where('status_id', 1)->get();
                
                if($program_code->count() > 0){
                    // If there are open program codes, set the program status to 1 (Open)
                    EducPrograms::where('id', $id)
                        ->update([
                            'status_id' => 1,
                            'updated_at' => now(), // Use the now() function to get the current timestamp
                        ]);
                } else {
                    // If there are no open program codes, set the program status to 2 (Closed)
                    EducPrograms::where('id', $id)
                        ->update([
                            'status_id' => 2,
                            'updated_at' => now(), // Use the now() function to get the current timestamp
                        ]);
                }
            }
        } catch (QueryException $e) {
            return $this->handleDatabaseError($e);
        } catch (PDOException $e) {
            return $this->handleDatabaseError($e);
        } catch (Exception $e) {
            return $this->handleOtherError($e);
        }
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
            'id' => 'required|numeric'
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.'
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
