<?php

namespace App\Http\Controllers\HRIMS\Office;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\OfficeType;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PDOException;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user_access_level = $request->session()->get('user_access_level');
        $office_type = OfficeType::get();
        $data = array(
            'office_type' => $office_type,
            'user_access_level' => $user_access_level
        );
        return view('hrims/office/officeDiv',$data);
    }

    /**
     * Show the listing table.
     */
    public function table(Request $request)
    {
        $data = array();

        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($data);
        }

        $office_type_id = $request->id;

        $query = Office::with('office_type','office_parent');
        if($office_type_id>0){
            $query = $query->where('office_type_id',$office_type_id);
        }
        $query = $query->get()
            ->map(function($query) {
                $office_parent = '';
                if($query->office_parent){
                    $office_parent = $query->office_parent->name;
                }
                return [
                    'id' => $query->id,
                    'name' => $query->name,
                    'shorten' => $query->shorten,
                    'office_type' => $query->office_type->name,
                    'office_parent' => $office_parent
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['shorten'];
                $data_list['f4'] = $r['office_type'];
                $data_list['f5'] = $r['office_parent'];
                $data_list['f7'] = '<button class="btn btn-info btn-info-scan btn-sm update"
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
        $office_type = OfficeType::get();
        $parent_office = Office::get();
        $data = array(
            'office_type' => $office_type,
            'parent_office' => $parent_office
        );
        return view('hrims/office/officeNew',$data);
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
        $office_type = $request->office_type;
        $parent_office = $request->parent_office;

        if($parent_office==0){
            $parent_office = NULL;
        }

        try{

            $insert = new Office(); 
            $insert->name = $name;
            $insert->shorten = $shorten;
            $insert->office_type_id = $office_type;
            $insert->parent_office_id = $parent_office;
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
    public function edit(int $id)
    {
        $office = Office::find($id);

        if ($office == null){
            return view('layouts/error/404');
        }

        $office_type = OfficeType::get();
        $parent_office = Office::whereNotIn('id',[$id])->get();
        $data = array(
            'office' => $office,
            'office_type' => $office_type,
            'parent_office' => $parent_office
        );
        return view('hrims.office.officeUpdate',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
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
        $office_type = $request->office_type;
        $parent_office = $request->parent_office;

        if($parent_office==0){
            $parent_office = NULL;
        }
        
        try{

            $insert = Office::find($id); 
            $insert->name = $name;
            $insert->shorten = $shorten;
            $insert->office_type_id = $office_type;
            $insert->parent_office_id = $parent_office;
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
    private function idValidateRequest($request)
    {
        $rules = [
            'id' => 'required|numeric'
        ];
        
        $customMessages = [
            'id.required' => 'ID is required',
            'id.numeric' => 'ID must be a number',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
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
            'office_type' => 'required|numeric',
            'parent_office' => 'required|numeric'
        ];
        
        $customMessages = [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'shorten.required' => 'Shorten is required',
            'shorten.string' => 'Shorten must be a string',
            'office_type.required' => 'Office Type is required',
            'office_type.numeric' => 'Office Type must be a number',
            'parent_office.required' => 'Parent Office is required',
            'parent_office.numeric' => 'Parent Office must be a number',
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
