<?php

namespace App\Http\Controllers\HRIMS\Employee\Information;

use App\Http\Controllers\Controller;
use App\Models\_Eligibility;
use App\Models\Eligibilities;
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

class EligInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user_access_level = $request->session()->get('user_access_level');

        $id = $request->id;

        $query = _Eligibility::with('eligibility')
            ->where('user_id',$id)
            ->get();

        $data = array(
            'query' => $query,
            'user_access_level' => $user_access_level
        );
        return view('hrims/employee/information/EligInfo',$data);
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

        $eligibilities = Eligibilities::get();

        $data = array(
            'eligibilities' => $eligibilities
        );
        return view('hrims/employee/information/EligInfoNew',$data);
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

        $check = _Eligibility::where('user_id',$request->sid)
            ->where('eligibility_id',$request->eligibility)
            ->where('date',$request->date)
            ->first();

        if($check){
            return  response()->json(['result' => 'Already exists!']);
        }

        try{
            $user = Auth::user();
            $user_id = $user->id;

            $employee = Users::find($request->sid);

            if($request->elig_check==1){
                $checkElig = Eligibilities::where('name',$request->elig_name)
                    ->where('shorten',$request->elig_shorten)
                    ->first();
                if(!$checkElig){
                    $insert = new Eligibilities();
                    $insert->name = $request->elig_name;
                    $insert->shorten = $request->elig_shorten;
                    $insert->updated_by = $user_id;
                    $insert->save();
                    $eligibility_id = $insert->id;
                }else{
                    $eligibility_id = $checkElig->id;
                }
            }else{
                $elig = Eligibilities::find($request->eligibility);
                $eligibility_id = $elig->id;
            }

            $date_validity = NULL;
            if ($timestamp = strtotime($request->date_validity)) {
                $date_validity = date('Y-m-d', $timestamp);
            }

            $doc = NULL;
            if($request->total_files>0){
                $file_merge = new FileMergeServices;
                $path = 'storage\hrims\employee/'.$employee->id_no.'\elig';
                $doc = $file_merge->getDoc($request,$request->total_files,$employee->id_no,$path);
            }

            $insert = new _Eligibility();
            $insert->user_id = $request->sid;
            $insert->eligibility_id = $eligibility_id;
            $insert->rating = $request->rating;
            $insert->date = date('Y-m-d',strtotime($request->date));
            $insert->place = $request->place;
            $insert->license_no = $request->license_no;
            $insert->date_validity = $date_validity;
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

        $query = _Eligibility::with('eligibility')
            ->where('user_id',$id)
            ->orderBy('date','DESC')
            ->get()
            ->map(function($query) {
                $date_validity = '-';
                if($query->date_validity){
                    $date_validity = date('m/d/Y',strtotime($query->date));
                }
                return [
                    'id' => $query->id,
                    'eligibility' => $query->eligibility->name,
                    'rating' => $query->rating,
                    'date' => date('m/d/Y',strtotime($query->date)),
                    'place' => $query->place,
                    'license_no' => $query->license_no,
                    'date_validity' => $date_validity,
                    'doc' => $query->doc
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['eligibility'];
                $data_list['f3'] = $r['rating'];
                $data_list['f4'] = $r['date'];
                $data_list['f5'] = $r['place'];
                $data_list['f6'] = $r['license_no'];
                $data_list['f7'] = $r['date_validity'];
                if($r['doc']){
                    $button_options = '<button class="btn btn-primary btn-primary-scan doc-elig"
                                    data-id="'.$r['id'].'">
                                    <span class="fa fa-file"></span></button>';
                }else{
                    $button_options = '<button class="btn btn-warning btn-warning-scan doc-elig"
                                    data-id="'.$r['id'].'">
                                    <span class="fa fa-file"></span></button>';
                }

                if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
                    $button_options .= '<button class="btn btn-info btn-info-scan edit-elig"
                                                data-id="'.$r['id'].'">
                                                <span class="fa fa-edit"></span></button>
                                        <button class="btn btn-danger btn-danger-scan delete-elig"
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

        $check = _Eligibility::with('eligibility')
            ->where('user_id',$id)
            ->where('id',$fid)
            ->first();

        if(!$check){
            return view('layouts/error/404');
        }

        $eligibilities = Eligibilities::get();

        $data = array(
            'query' => $check,
            'eligibilities' => $eligibilities
        );
        return view('hrims/employee/information/eligInfoEdit',$data);
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

        $check = _Eligibility::where('user_id',$id)
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
        return view('hrims/employee/information/eligInfoDoc',$data);
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

        $check = _Eligibility::where('user_id',$request->sid)
            ->where('id',$request->id)
            ->first();
        if(!$check){
            return  response()->json(['result' => 'error']);
        }

        $check_exists = _Eligibility::where('user_id',$request->sid)
            ->where('id','!=',$request->id)
            ->where('eligibility_id',$request->eligibility_id)
            ->where('date',date('Y-m-d',strtotime($request->date)))
            ->first();

        if($check_exists){
            return  response()->json(['result' => 'Already exists!']);
        }

        try{
            $user = Auth::user();
            $user_id = $user->id;

            $employee = Users::find($request->sid);

            if($request->elig_check==1){
                $checkElig = Eligibilities::where('name',$request->elig_name)
                    ->where('shorten',$request->elig_shorten)
                    ->first();
                if(!$checkElig){
                    $insert = new Eligibilities();
                    $insert->name = $request->elig_name;
                    $insert->shorten = $request->elig_shorten;
                    $insert->updated_by = $user_id;
                    $insert->save();
                    $eligibility_id = $insert->id;
                }else{
                    $eligibility_id = $checkElig->id;
                }
            }else{
                $elig = Eligibilities::find($request->eligibility);
                $eligibility_id = $elig->id;
            }

            $date_validity = NULL;
            if ($timestamp = strtotime($request->date_validity)) {
                $date_validity = date('Y-m-d', $timestamp);
            }

            $doc = $check->doc;
            if($request->total_files>0){
                $file_merge = new FileMergeServices;
                $path = 'storage\hrims\employee/'.$employee->id_no.'\elig';
                $doc = $file_merge->getDoc($request,$request->total_files,$employee->id_no,$path);
            }

            $update = _Eligibility::find($request->id);
            $update->eligibility_id = $eligibility_id;
            $update->rating = $request->rating;
            $update->date = date('Y-m-d',strtotime($request->date));
            $update->place = $request->place;
            $update->license_no = $request->license_no;
            $update->date_validity = $date_validity;
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

        $check = _Eligibility::where('user_id',$id)
            ->where('id',$fid)
            ->first();

        if(!$check){
            return view('layouts/error/404');
        }

        $data = array(
            'query' => $check
        );
        return view('hrims/employee/information/eligInfoDelete',$data);
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

        $check = _Eligibility::with('user')
            ->where('user_id',$id)
            ->where('id',$fid)
            ->first();

        if(!$check){
            return  response()->json(['result' => 'error']);
        }

        if(File::exists($check->doc)){
            File::delete($check->doc);
        }

        $delete = _Eligibility::find($fid);
        $delete->delete();

        DB::statement("ALTER TABLE _eligibility AUTO_INCREMENT = 1;");

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
            'eligibility' => 'required|numeric',
            'elig_name' => 'nullable|string',
            'elig_shorten' => 'nullable|string',
            'elig_check' => 'required|numeric',
            'rating' => 'required|string',
            'date' => 'required|date',
            'place' => 'required|string',
            'license_no' => 'required|string',
            'date_validity' => 'nullable|date',
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
