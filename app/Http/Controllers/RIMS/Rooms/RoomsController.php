<?php

namespace App\Http\Controllers\RIMS\Rooms;

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

class RoomsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request The incoming HTTP request.
     * @return mixed JSON response containing a list of filtered rooms.
     */
    public function index(Request $request)
    {
        // Validate the incoming request data using a custom validation method
        $validator = $this->statusIdValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors()], 400); // Return validation errors as JSON response
        }

        $data = array();

        // Get the status_id from the request
        $status_id = $request->status_id;

        // Query the EducRoom model with eager loading of 'status' and 'building' relationships
        $query = EducRoom::with('status', 'building')
            ->where('status_id', $status_id)
            ->get()
            ->map(function ($query) {
                // Map the query results to a custom array format
                return [
                    'id' => $query->id,
                    'building' => $query->building->shorten,
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
                $data_list['f2'] = $r['building'];
                $data_list['f3'] = $r['name'];
                $data_list['f4'] = $r['shorten'];
                $data_list['f5'] = '<button class="' . $r['status_btn'] . '">' . $r['status_name'] . '</button>';
                $data_list['f6'] = $r['remarks'];
                $data_list['f7'] = '<button class="btn btn-primary btn-primary-scan roomsViewModal"
                                        data-id="' . $id . '">
                                        <span class="fa fa-eye"></span>
                                    </button>';
                $data_list['f8'] = '<button class="btn btn-info btn-info-scan roomsEditModal"
                                        data-id="' . $id . '">
                                        <span class="fa fa-edit"></span>
                                    </button>';
                array_push($data, $data_list);
                $x++;
            }
        }

        // Return the filtered room data
        return $data;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View The view for creating a new room.
     */
    public function create()
    {
        // Retrieve a list of existing buildings
        $buildings = EducBuilding::get();

        // Prepare data to be passed to the view
        $data = [
            'buildings' => $buildings
        ];

        // Return the view for creating a new room, passing the list of buildings
        return view('rims/rooms/roomsNewModal', $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request The incoming HTTP request.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the result of the store operation.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data using a custom validation method
        $validator = $this->storeValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors()], 400); // Return validation errors as JSON response
        }
        
        // Retrieve data from the request
        $building = $request->building;
        $name = $request->name;
        $shorten = $request->shorten;

        // Check if a room with the same name or shorten already exists
        $checkRoom = EducRoom::where('building_id', $building)
            ->where(function ($query) use ($shorten, $name) {
                $query->where('shorten', $shorten)
                    ->orWhere('name', $name);
            })
            ->first();

        if ($checkRoom) {
            return response()->json(['result' => 'Room Name or Shorten already exists!']);
        }

        // Start a database transaction
        DB::beginTransaction();
        try {
            
            // Get the authenticated user
            $user = Auth::user();
            $updated_by = $user->id;

            // Create a new EducRoom instance and save it to the database
            $insert = new EducRoom();
            $insert->building_id = $building;
            $insert->name = $name;
            $insert->shorten = $shorten;
            $insert->remarks = $request->remarks;
            $insert->status_id = 1;
            $insert->updated_by = $updated_by;
            $insert->save();

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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id The unique identifier of the room to be edited.
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response The view for editing a room or a 404 error view if the room is not found.
     */
    public function edit(string $id)
    {
        // Find the room with the specified ID
        $room = EducRoom::find($id);

        // Check if the room is not found
        if ($room == NULL) {
            return view('layouts/error/404'); // Return a 404 error view
        }

        // Retrieve a list of existing buildings and statuses
        $buildings = EducBuilding::get();
        $statuses = Status::whereHas('status_list', function ($query) {
                $query->where('table','bldg_rm');
            })->get();

        // Prepare data to be passed to the view, including the room, buildings, and statuses
        $data = [
            'room' => $room,
            'buildings' => $buildings,
            'statuses' => $statuses
        ];

        // Return the view for editing a room, passing the room and related data
        return view('rims/rooms/roomsEditModal', $data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request The incoming HTTP request.
     * @param int $id The unique identifier of the room to be updated.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the result of the update operation.
     */
    public function update(Request $request, int $id)
    {
        // Validate the incoming request data using a custom validation method
        $validator = $this->updateValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => $validator->errors()], 422); // Return validation errors as JSON response with a 422 status code (Unprocessable Entity)
        }

        // Retrieve data from the request
        $name = $request->name;
        $shorten = $request->shorten;
        $building = $request->building;

        // Check if a room with the same name or shorten already exists in the same building, excluding the current room
        $checkRoom = EducRoom::where('id', '<>', $id)
            ->where('building_id', $building)
            ->where(function ($query) use ($shorten, $name) {
                $query->where('shorten', $shorten)
                    ->orWhere('name', $name);
            })
            ->first();

        if ($checkRoom) {
            return response()->json(['result' => 'Room Name or Shorten already exists!']);
        }

        // Start a database transaction
        DB::beginTransaction();
        try {
            
            // Get the authenticated user
            $user = Auth::user();
            $updated_by = $user->id;

            // Update the room with the specified ID
            EducRoom::where('id', $id)
                ->update([
                    'building_id' => $building,
                    'name' => $name,
                    'shorten' => $shorten,
                    'remarks' => $request->remarks,
                    'status_id' => $request->status,
                    'updated_by' => $updated_by,
                    'updated_at' => now(), // Use Laravel's now() function to get the current timestamp
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
    private function storeValidateRequest(Request $request)
    {
        $rules = [
            'building' => 'required|numeric',
            'name' => 'required|string',
            'shorten' => 'required|string',
            'remarks' => 'nullable|string',
        ];

        $customMessages = [
            'building.required' => 'Building is required.',
            'building.numeric' => 'Building must be a number.',
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a String.',
            'shorten.required' => 'Shorten is required.',
            'shorten.string' => 'Shorten must be a String.',
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
            'building' => 'required|numeric',
            'name' => 'required|string',
            'shorten' => 'required|string',
            'remarks' => 'nullable|string',
            'status' => 'required|numeric',
        ];

        $customMessages = [
            'building.required' => 'Building is required.',
            'building.numeric' => 'Building must be a number.',
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a String.',
            'shorten.required' => 'Shorten is required.',
            'shorten.string' => 'Shorten must be a String.',
            'status.required' => 'Status is required.',
            'status.numeric' => 'Status must be a number.',
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
