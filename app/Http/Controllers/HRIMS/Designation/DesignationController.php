<?php

namespace App\Http\Controllers\HRIMS\Designation;

use App\Http\Controllers\Controller;
use App\Models\HRDesignation;
use App\Models\Office;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PDOException;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array();
        $query = HRDesignation::get()
            ->map(function($query) {
                return [
                    'id' => $query->id,
                    'name' => $query->name
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = '<button class="btn btn-primary btn-primary-scan btn-sm update"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-edit"></span> 
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $office = Office::doesntHave('designation')->get();
        $data = array(
            'office' => $office
        );
        return view('hrims/designation/new',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = array('result' => 'error');

        // Validate the incoming request data
        $validator = $this->storeValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($data);
        }

        $user_access_level = $request->session()->get('user_access_level');
        $user_access_levels = array(1,2,3);

        // Check user access level
        if (!in_array($user_access_level, $user_access_levels)) {
            return  response()->json($data);
        }

        $user = Auth::user();
        $updated_by = $user->id;

        $name = $request->name;
        $shorten = $request->shorten;
        $level = $request->level;
        $office = $request->office;

        $check_designation = HRDesignation::where(function ($query) use ($name,$office) {
                $query->where('name',$name);
                $query->where('office_id',$office);
            })->orWhere(function ($query) use ($shorten,$office) {
                $query->where('shorten',$shorten);
                $query->where('office_id',$office);
            })
            ->first();
        if($check_designation){
            return  response()->json($data);
        }

        try{

            $insert = new HRDesignation(); 
            $insert->name = $name;
            $insert->shorten = $shorten;
            $insert->role_id = 1;
            $insert->level = $level;
            $insert->office_id = $office;
            $insert->updated_by = $updated_by;
            $insert->save();
         
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
    private function storeValidateRequest($request)
    {
        $rules = [
            'name' => 'required|string',
            'shorten' => 'required|string',
            'level' => 'required|numeric',
            'office' => 'required|numeric'
        ];
        
        $customMessages = [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'shorten.required' => 'Shorten is required',
            'shorten.string' => 'Shorten must be a string',
            'level.required' => 'Office Type is required',
            'level.numeric' => 'Office Type must be a number',
            'office.required' => 'Parent Office is required',
            'office.numeric' => 'Parent Office must be a number',
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
        return response()->json(['result' => $e->getMessage()], 500);
    }
}
