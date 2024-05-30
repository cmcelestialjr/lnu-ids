<?php

namespace App\Http\Controllers\HRIMS\Employee\Information;

use App\Http\Controllers\Controller;
use App\Models\_Learning;
use App\Models\_LearningType;
use App\Models\Users;
use App\Services\FileMergeServices;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use PDOException;

class LearnInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user_access_level = $request->session()->get('user_access_level');

        $id = $request->id;

        $query = _Learning::with('type')
            ->where('user_id',$id)
            ->get();

        $data = array(
            'query' => $query,
            'user_access_level' => $user_access_level
        );
        return view('hrims/employee/information/learnInfo',$data);
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

        $types = _LearningType::get();

        $data = array(
            'types' => $types
        );
        return view('hrims/employee/information/learnInfoNew',$data);
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

        $check = _Learning::where('user_id',$request->sid)
            ->where('name',$request->name)
            ->where('date_from',$request->date_from)
            ->first();

        if($check){
            return  response()->json(['result' => 'Already exists!']);
        }

        try{
            $user = Auth::user();
            $user_id = $user->id;

            $employee = Users::find($request->sid);

            $doc = NULL;
            if($request->total_files>0){
                $file_merge = new FileMergeServices;
                $path = 'storage\hrims\employee/'.$employee->id_no.'\learn';
                $doc = $file_merge->getDoc($request,$request->total_files,$employee->id_no,$path);
            }

            if($request->type_check==1){
                $checkType = _LearningType::where('name',$request->type_name)
                    ->first();
                if(!$checkType){
                    $insert = new _LearningType();
                    $insert->name = $request->type_name;
                    $insert->updated_by = $user_id;
                    $insert->save();
                    $type_id = $insert->id;
                }else{
                    $type_id = $checkType->id;
                }
            }else{
                $type_id = $request->type;
            }

            $insert = new _Learning();
            $insert->user_id = $request->sid;
            $insert->name = $request->name;
            $insert->date_from = date('Y-m-d',strtotime($request->date_from));
            $insert->date_to = date('Y-m-d',strtotime($request->date_to));
            $insert->hours = $request->hours;
            $insert->type_id = $type_id;
            $insert->conducted_by = $request->conducted_by;
            $insert->doc = $doc;
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
    public function show(Request $request)
    {
        $user_access_level = $request->session()->get('user_access_level');
        $data = array();

        $id = $request->id;

        $query = _Learning::where('user_id',$id)
            ->orderBy('date_from','DESC')
            ->get()
            ->map(function($query) {
                return [
                    'id' => $query->id,
                    'name' => $query->name,
                    'date_from' => date('m/d/Y',strtotime($query->date_from)),
                    'date_to' => date('m/d/Y',strtotime($query->date_to)),
                    'hours' => $query->hours,
                    'type' => $query->type->name,
                    'conducted_by' => $query->conducted_by,
                    'doc' => $query->doc
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['date_from'];
                $data_list['f4'] = $r['date_to'];
                $data_list['f5'] = $r['hours'];
                $data_list['f6'] = $r['type'];
                $data_list['f7'] = $r['conducted_by'];
                if($r['doc']){
                    $button_options = '<button class="btn btn-primary btn-primary-scan doc-learn"
                                    data-id="'.$r['id'].'">
                                    <span class="fa fa-file"></span></button>';
                }else{
                    $button_options = '<button class="btn btn-warning btn-warning-scan doc-learn"
                                    data-id="'.$r['id'].'">
                                    <span class="fa fa-file"></span></button>';
                }

                if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
                    $button_options .= '<button class="btn btn-info btn-info-scan edit-learn"
                                                data-id="'.$r['id'].'">
                                                <span class="fa fa-edit"></span></button>
                                        <button class="btn btn-danger btn-danger-scan delete-learn"
                                                data-id="'.$r['id'].'">
                                                <span class="fa fa-trash"></span></button>';

                }
                $data_list['f8'] = $button_options;
                array_push($data,$data_list);
                $x++;
            }
        }
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

        $check = _Learning::with('type')
            ->where('user_id',$id)
            ->where('id',$fid)
            ->first();

        if(!$check){
            return view('layouts/error/404');
        }

        $types = _LearningType::get();

        $data = array(
            'query' => $check,
            'types' => $types
        );
        return view('hrims/employee/information/learnInfoEdit',$data);
    }

    public function showDoc(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->showMoreValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $id = $request->id;
        $fid = $request->fid;

        $check = _Learning::where('user_id',$id)
            ->where('id',$fid)
            ->first();

        if(!$check){
            return view('layouts/error/404');
        }

        $doc = 'assets/pdf/pdf_error.pdf';
        if($check->doc){
            $doc = $check->doc;
        }

        $data = array(
            'doc' => $doc
        );
        return view('hrims/employee/information/learnInfoDoc',$data);
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

        $check = _Learning::where('user_id',$request->sid)
            ->where('id',$request->id)
            ->first();
        if(!$check){
            return  response()->json(['result' => 'error']);
        }

        $check_exists = _Learning::where('user_id',$request->sid)
            ->where('id','!=',$request->id)
            ->where('name',$request->name)
            ->where('date_from',date('Y-m-d',strtotime($request->date_from)))
            ->first();

        if($check_exists){
            return  response()->json(['result' => 'Already exists!']);
        }

        try{
            $user = Auth::user();
            $user_id = $user->id;

            $employee = Users::find($request->sid);

            $doc = $check->doc;
            if($request->total_files>0){
                $file_merge = new FileMergeServices;
                $path = 'storage\hrims\employee/'.$employee->id_no.'\learn';
                $doc = $file_merge->getDoc($request,$request->total_files,$employee->id_no,$path);
            }

            if($request->type_check==1){
                $checkType = _LearningType::where('name',$request->type_name)
                    ->first();
                if(!$checkType){
                    $insert = new _LearningType();
                    $insert->name = $request->type_name;
                    $insert->updated_by = $user_id;
                    $insert->save();
                    $type_id = $insert->id;
                }else{
                    $type_id = $checkType->id;
                }
            }else{
                $type_id = $request->type;
            }

            $update = _Learning::find($request->id);
            $update->name = $request->name;
            $update->date_from = date('Y-m-d',strtotime($request->date_from));
            $update->date_to = date('Y-m-d',strtotime($request->date_to));
            $update->hours = $request->hours;
            $update->type_id = $type_id;
            $update->conducted_by = $request->conducted_by;
            $update->doc = $doc;
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

        $check = _Learning::where('user_id',$id)
            ->where('id',$fid)
            ->first();

        if(!$check){
            return view('layouts/error/404');
        }

        $data = array(
            'query' => $check
        );
        return view('hrims/employee/information/learnInfoDelete',$data);
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

        $check = _Learning::with('user')
            ->where('user_id',$id)
            ->where('id',$fid)
            ->first();

        if(!$check){
            return  response()->json(['result' => 'error']);
        }

        if(File::exists($check->doc)){
            File::delete($check->doc);
        }

        $delete = _Learning::find($fid);
        $delete->delete();

        DB::statement("ALTER TABLE _learning AUTO_INCREMENT = 1;");

        return  response()->json(['result' => 'success']);
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
            'date_from' => 'required|date',
            'date_to' => 'nullable|date',
            'hours' => 'required|numeric',
            'type' => 'required|numeric',
            'type_check' => 'required|numeric',
            'type_name' => 'nullable|string',
            'conducted_by' => 'required|string',
            'total_files' => 'required|numeric',
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
