<?php

namespace App\Http\Controllers\HRIMS\Employee\Information;

use App\Http\Controllers\Controller;
use App\Models\_OtherOrganization;
use App\Models\_OtherRecognition;
use App\Models\_OtherSkill;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PDOException;

class OtherInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user_access_level = $request->session()->get('user_access_level');

        $id = $request->id;

        $skills = _OtherSkill::where('user_id',$id)
            ->get();
        $recognitions = _OtherRecognition::where('user_id',$id)
            ->get();
        $organizations = _OtherOrganization::where('user_id',$id)
            ->get();

        $data = array(
            'skills' => $skills,
            'recognitions' => $recognitions,
            'organizations' => $organizations,
            'user_access_level' => $user_access_level
        );
        return view('hrims/employee/information/otherInfo',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user_access_level = $request->session()->get('user_access_level');
        if($user_access_level!=1 && $user_access_level!=2 && $user_access_level!=3){
            return view('layouts/error/404');
        }

        $options = array('skills','recognition','organization');

        $data = array(
            'options' => $options
        );
        return view('hrims/employee/information/otherInfoNew',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->submitValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json(['result' => 'error']);
        }

        $user_access_level = $request->session()->get('user_access_level');
        if($user_access_level!=1 && $user_access_level!=2 && $user_access_level!=3){
            return  response()->json(['result' => 'error']);
        }

        $table = $this->getTable($request->option);

        $check = $table::where('user_id',$request->sid)
                ->where('name',$request->name)
                ->first();

        if($check){
            return  response()->json(['result' => 'Already exists!']);
        }

        try{
            $user = Auth::user();
            $user_id = $user->id;

            $insert = $table;
            $insert->user_id = $request->sid;
            $insert->name = $request->name;
            $insert->updated_by = $user_id;
            $insert->save();

            return  response()->json(['result' => 'success']);

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
    public function showSkill(Request $request)
    {
        $id = $request->id;

        $query = _OtherSkill::where('user_id',$id)->get();

        $data = $this->showTable($request,$query,'skills');

        return  response()->json($data);
    }

    /**
     * Display the specified resource.
     */
    public function showRecognition(Request $request)
    {
        $id = $request->id;

        $query = _OtherRecognition::where('user_id',$id)->get();

        $data = $this->showTable($request,$query,'recognition');

        return  response()->json($data);
    }

    /**
     * Display the specified resource.
     */
    public function showOrganization(Request $request)
    {
        $id = $request->id;

        $query = _OtherOrganization::where('user_id',$id)->get();

        $data = $this->showTable($request,$query,'organization');

        return  response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->showMoreValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $user_access_level = $request->session()->get('user_access_level');
        if($user_access_level!=1 && $user_access_level!=2 && $user_access_level!=3){
            return view('layouts/error/404');
        }

        $id = $request->id;
        $fid = $request->fid;
        $option = $request->option;

        $table = $this->getTable($request->option);

        $check = $table::where('user_id',$id)
            ->where('id',$fid)
            ->first();

        if(!$check){
            return view('layouts/error/404');
        }

        $options = array('skills','recognition','organization');

        $data = array(
            'query' => $check,
            'option' => $option,
            'options' => $options
        );
        return view('hrims/employee/information/otherInfoEdit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Validate the incoming request data
        $validatorId = $this->idValidateRequest($request);
        $validator = $this->submitValidateRequest($request);

        // Check if validation fails
        if ($validator->fails() && $validatorId->fails()) {
            return  response()->json(['result' => 'error']);
        }

        $user_access_level = $request->session()->get('user_access_level');
        if($user_access_level!=1 && $user_access_level!=2 && $user_access_level!=3){
            return  response()->json(['result' => 'error']);
        }

        $table = $this->getTable($request->option);

        $check = $table::where('user_id',$request->sid)
            ->where('id',$request->id)
            ->first();
        if(!$check){
            return  response()->json(['result' => 'error']);
        }

        $check_exists = $table::where('user_id',$request->sid)
            ->where('id','!=',$request->id)
            ->where('name',$request->name)
            ->first();

        if($check_exists){
            return  response()->json(['result' => 'Already exists!']);
        }

        try{
            $user = Auth::user();
            $user_id = $user->id;

            $update = $table::find($request->id);
            $update->name = $request->name;
            $update->updated_by = $user_id;
            $update->save();
            return  response()->json(['result' => 'success']);

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
     * Remove confirmation.
     */
    public function delete(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->showMoreValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $id = $request->id;
        $fid = $request->fid;
        $option = $request->option;

        $table = $this->getTable($option);

        $check = $table::where('user_id',$id)
            ->where('id',$fid)
            ->first();

        if(!$check){
            return view('layouts/error/404');
        }

        $data = array(
            'query' => $check,
            'option' => $option
        );
        return view('hrims/employee/information/otherInfoDelete',$data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->showMoreValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json(['result' => 'error']);
        }

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json(['result' => 'error']);
        }

        $user_access_level = $request->session()->get('user_access_level');
        if($user_access_level!=1 && $user_access_level!=2 && $user_access_level!=3){
            return  response()->json(['result' => 'error']);
        }

        $id = $request->id;
        $fid = $request->fid;

        $table = $this->getTable($request->option);

        $check = $table::where('user_id',$id)
            ->where('id',$fid)
            ->first();

        if(!$check){
            return  response()->json(['result' => 'error']);
        }

        $delete = $table::find($fid);
        $delete->delete();

        return  response()->json(['result' => 'success']);
    }

    private function showTable($request,$query,$option){
        $user_access_level = $request->session()->get('user_access_level');

        $data = array();

        $query = $query->map(function($query) {
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

                if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
                    $button_options = '<button class="btn btn-info btn-info-scan edit-other"
                                                data-id="'.$r['id'].'"
                                                data-o="'.$option.'">
                                                <span class="fa fa-edit"></span></button>
                                        <button class="btn btn-danger btn-danger-scan delete-other"
                                                data-id="'.$r['id'].'"
                                                data-o="'.$option.'">
                                                <span class="fa fa-trash"></span></button>';

                }
                $data_list['f3'] = $button_options;
                array_push($data,$data_list);
                $x++;
            }
        }
        return $data;
    }

    private function getTable($option){
        if($option=='skills'){
            $table = new _OtherSkill;
        }elseif($option=='recognition'){
            $table = new _OtherRecognition;
        }else{
            $table = new _OtherOrganization;
        }
        return $table;
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
            'id' => 'required|numeric',
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
    private function showMoreValidateRequest($request)
    {
        $rules = [
            'id' => 'required|numeric',
            'fid' => 'required|numeric',
            'option' => 'required|string',
        ];

        $customMessages = [
            'id.required' => 'ID is required',
            'id.numeric' => 'ID must be a number',
            'fid.required' => 'FID is required',
            'fid.numeric' => 'FID must be a number',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function submitValidateRequest($request)
    {
        $rules = [
            'sid' => 'required|numeric',
            'name' => 'required|string',
            'option' => 'required|string',
        ];

        $customMessages = [
            'sid.required' => 'ID is required',
            'sid.numeric' => 'ID must be a number',
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
