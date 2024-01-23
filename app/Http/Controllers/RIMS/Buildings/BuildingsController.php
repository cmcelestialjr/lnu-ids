<?php

namespace App\Http\Controllers\RIMS\Buildings;

use App\Http\Controllers\Controller;
use App\Models\EducBuilding;
use App\Models\EducRoom;
use App\Models\Status;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class BuildingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request The incoming HTTP request.
     * @return array An array containing building data to be used in the frontend.
     */
    public function index(Request $request)
    {
        // Validate the incoming request data using a custom validation method
        $validator = $this->statusIdValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors()], 400); // Return validation errors as JSON response with a 400 status code (Bad Request)
        }

        $data = array();
        
        // Retrieve the status_id parameter from the request
        $status_id = $request->status_id;

        // Query the database to fetch building data along with their status
        $query = EducBuilding::with('status')
                    ->where('status_id', $status_id)
                    ->get()
                    ->map(function ($query) {
                        return [
                            'id' => $query->id,
                            'name' => $query->name,
                            'shorten' => $query->shorten,
                            'status_name' => $query->status->name,
                            'status_btn' => $query->status->button,
                            'remarks' => $query->remarks,
                        ];
                    })->toArray();

        if (count($query) > 0) {
            $x = 1;
            foreach ($query as $r) {
                $id = $r['id'];
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['shorten'];
                $data_list['f4'] = '<button class="'.$r['status_btn'].'">'.$r['status_name'].'</button>';
                $data_list['f5'] = $r['remarks'];
                $data_list['f6'] = '<button class="btn btn-primary btn-primary-scan buildingsViewModal"
                                        data-id="'.$id.'">
                                        <span class="fa fa-eye"></span>
                                    </button>';
                $data_list['f7'] = '<button class="btn btn-info btn-info-scan buildingsEditModal"
                                        data-id="'.$id.'">
                                        <span class="fa fa-edit"></span>
                                    </button>';
                array_push($data, $data_list);
                $x++;
            }
        }

        return $data;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View The view representing the form for creating a new building resource.
     */
    public function create()
    {
        return view('rims/buildings/buildingsNewModal');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request The incoming HTTP request containing data to create a new building.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the result of the store operation.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data using a custom validation method
        $validator = $this->storeValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors()], 400); // Return validation errors as JSON response with a 400 status code (Bad Request)
        }

        $name = $request->name;
        $shorten = $request->shorten;

        // Check if a building with the same name or shorten already exists
        $checkBuilding = EducBuilding::where('shorten', $shorten)
            ->orWhere('name', $name)
            ->first();

        if ($checkBuilding) {
            return response()->json(['result' => 'Building Name or Shorten already exists!']);
        }

        // Start a database transaction
        DB::beginTransaction();
        try {
            // Get the authenticated user
            $user = Auth::user();
            $updated_by = $user->id;

            // Create a new EducBuilding instance and save it to the database
            $insert = new EducBuilding();
            $insert->name = $name;
            $insert->shorten = $shorten;
            $insert->remarks = $request->remarks;
            $insert->status_id = 1;
            $insert->updated_by = $updated_by;
            $insert->save();
            
            DB::commit(); // Commit the transaction
            return response()->json(['result' => 'success']);
            
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
     * Display the specified resource.
     *
     * @param int $id The unique identifier of the building to display.
     * @return \Illuminate\View\View The view representing the building's details.
     */
    public function show(int $id)
    {
        // Retrieve the building with its associated rooms
        $building = EducBuilding::with('rooms')->find($id);

        if ($building == null) {
            return view('layouts/error/404'); // Return a 404 error view if the building is not found
        }

        $data = ['building' => $building];

        return view('rims/buildings/buildingsViewModal', $data); // Return the view representing the building's details
    }


    /**
     * Display a listing of rooms for the specified building.
     *
     * @param int $id The unique identifier of the building for which to display rooms.
     * @return array An array containing room information to be used in the view.
     */
    public function showTable(int $id)
    {
        $data = array();
        
        // Retrieve rooms associated with the specified building along with their statuses
        $query = EducRoom::with('status')
                    ->where('building_id', $id)
                    ->get()
                    ->map(function ($query) {
                        return [
                            'id' => $query->id,
                            'name' => $query->name,
                            'shorten' => $query->shorten,
                            'status_name' => $query->status->name,
                            'status_btn' => $query->status->button,
                            'remarks' => $query->remarks,
                        ];
                    })->toArray();

        if (count($query) > 0) {
            $x = 1;
            foreach ($query as $r) {
                $id = $r['id'];
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['shorten'];
                $data_list['f4'] = '<button class="' . $r['status_btn'] . '">' . $r['status_name'] . '</button>';
                $data_list['f5'] = $r['remarks'];
                array_push($data, $data_list);
                $x++;
            }
        }
        return $data;
    }


    /**
     * Show the form for editing a building's information.
     *
     * @param int $id The unique identifier of the building to edit.
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response The view for editing the building or an error response.
     */
    public function edit(int $id)
    {
        // Retrieve the building by its unique identifier
        $building = EducBuilding::find($id);

        // Check if the building does not exist
        if ($building == NULL) {
            return view('layouts/error/404'); // Return a 404 error view
        }

        // Retrieve the statuses for buildings/rooms
        $statuses = Status::whereHas('status_list', function ($query) {
            $query->where('table','bldg_rm');
        })->get();

        // Prepare data to be passed to the view
        $data = array('building' => $building, 'statuses' => $statuses);

        // Return the view for editing the building
        return view('rims/buildings/buildingsEditModal', $data);
    }


    /**
     * Update the specified building resource in storage.
     *
     * @param \Illuminate\Http\Request $request The HTTP request instance containing the updated data.
     * @param int $id The unique identifier of the building to update.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the result of the update operation.
     */
    public function update(Request $request, int $id)
    {
        // Validate the incoming request data using a custom validation method
        $validator = $this->updateValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors()], 422); // Return validation errors as JSON response
        }

        $name = $request->name;
        $shorten = $request->shorten;

        // Check if another building with the same name or shorten already exists
        $checkBuilding = EducBuilding::where('id', '<>', $id)
            ->where(function ($query) use ($shorten, $name) {
                $query->where('shorten', $shorten)
                    ->orWhere('name', $name);
            })
            ->first();

        if ($checkBuilding) {
            return response()->json(['result' => 'Building Name or Shorten already exists!']);
        }

        // Start a database transaction
        DB::beginTransaction();
        try {
            // Get the authenticated user
            $user = Auth::user();
            $updated_by = $user->id;

            // Update the building's information in the database
            EducBuilding::where('id', $id)
                ->update([
                    'name' => $name,
                    'shorten' => $shorten,
                    'remarks' => $request->remarks,
                    'status_id' => $request->status,
                    'updated_by' => $updated_by,
                    'updated_at' => now(), // Use the current timestamp
                ]);

            DB::commit();
            return response()->json(['result' => 'success']);
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
    private function statusIdValidateRequest(Request $request)
    {
        $rules = [
            'status_id' => 'required|numeric',
        ];

        $customMessages = [
            'status_id.required' => 'Status is required.',
            'status_id.numeric' => 'Status must be a number.',
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
            'name' => 'required|string',
            'shorten' => 'required|string',
            'remarks' => 'nullable|string',
            'status' => 'required|numeric',
        ];

        $customMessages = [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a String.',
            'shorten.required' => 'Shorten is required.',
            'shorten.string' => 'Shorten must be a String.',
            'status.required' => 'Status is required.',
            'status.string' => 'Status must be a number.',
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
            'remarks' => 'nullable|string',
        ];

        $customMessages = [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a String.',
            'shorten.required' => 'Shorten is required.',
            'shorten.string' => 'Shorten must be a String.',
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
