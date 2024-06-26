<?php

namespace App\Http\Controllers\HRIMS\Employee\Information;

use App\Http\Controllers\Controller;
use App\Models\_FamilyBg;
use App\Models\FamRelations;
use App\Models\Users;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class FamilyInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user_access_level = $request->session()->get('user_access_level');
        $id = $request->id;
        $query = Users::with('family.fam_relation')
            ->find($id);

        $data = array(
            'query' => $query,
            'user_access_level' => $user_access_level
        );
        return view('hrims/employee/information/familyInfo',$data);
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

        $relations = FamRelations::get();

        $data = array(
            'relations' => $relations
        );
        return view('hrims/employee/information/familyInfoNew',$data);
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

        $check = _FamilyBg::where('user_id',$request->sid)
            ->where('lastname',$request->lastname)
            ->where('firstname',$request->firstname)
            ->where('relation_id',$request->relation)
            ->where('dob',date('Y-m-d',strtotime($request->dob)))
            ->first();

        if($check){
            return  response()->json(['result' => 'Already exists!']);
        }

        try{
            $user = Auth::user();
            $user_id = $user->id;

            $insert = new _FamilyBg();
            $insert->user_id = $request->sid;
            $insert->lastname = $request->lastname;
            $insert->firstname = $request->firstname;
            $insert->middlename = $request->middlename;
            $insert->extname = $request->extname;
            $insert->relation_id = $request->relation;
            $insert->dob = date('Y-m-d',strtotime($request->dob));
            $insert->contact_no = $request->contact_no;
            $insert->email = $request->email;
            $insert->occupation = $request->occupation;
            $insert->employer = $request->employer;
            $insert->employer_contact = $request->employer_contact;
            $insert->employer_address = $request->employer_address;
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

        $name_services = new NameServices;

        $id = $request->id;

        $query = _FamilyBg::with('fam_relation')
            ->where('user_id',$id)
            ->orderBy('dob','ASC')
            ->get()
            ->map(function($query) use ($name_services) {
                $name = $name_services->lastname($query->lastname,$query->firstname,$query->middlename,$query->extname);
                $contact_no = null;
                if($query->contact_no){
                    $contact_no = '0'.$query->contact_no;
                }
                return [
                    'id' => $query->id,
                    'name' => $name,
                    'relation' => $query->fam_relation->name,
                    'dob' => date('m/d/Y', strtotime($query->dob)),
                    'contact' => $contact_no,
                    'email' => $query->email
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['relation'];
                $data_list['f4'] = $r['dob'];
                $data_list['f5'] = $r['contact'];
                $data_list['f6'] = $r['email'];
                $button_options = '<button class="btn btn-primary btn-primary-scan more-info-fam"
                                    data-id="'.$r['id'].'">
                                    <span class="fa fa-bars"></span></button>';
                if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
                    $button_options .= ' <button class="btn btn-info btn-info-scan edit-fam"
                                                data-id="'.$r['id'].'">
                                                <span class="fa fa-edit"></span></button>
                                        <button class="btn btn-danger btn-danger-scan delete-fam"
                                                data-id="'.$r['id'].'">
                                                <span class="fa fa-trash"></span></button>';
                }
                $data_list['f7'] = $button_options;

                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }


    /**
     * Display the specified resource.
     */
    public function showMore(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->showMoreValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $id = $request->id;
        $fid = $request->fid;

        $check = _FamilyBg::where('user_id',$id)
            ->where('id',$fid)
            ->first();

        if(!$check){
            return view('layouts/error/404');
        }

        $data = array(
            'query' => $check
        );
        return view('hrims/employee/information/familyInfoMore',$data);
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

        $check = _FamilyBg::where('user_id',$id)
            ->where('id',$fid)
            ->first();

        if(!$check){
            return view('layouts/error/404');
        }

        $relations = FamRelations::get();

        $data = array(
            'query' => $check,
            'relations' => $relations
        );
        return view('hrims/employee/information/familyInfoEdit',$data);
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

        $check = _FamilyBg::where('user_id',$request->sid)
            ->where('id',$request->id)
            ->first();
        if(!$check){
            return  response()->json(['result' => 'error']);
        }

        $check = _FamilyBg::where('user_id',$request->sid)
            ->where('id','!=',$request->id)
            ->where('lastname',$request->lastname)
            ->where('firstname',$request->firstname)
            ->where('relation_id',$request->relation)
            ->where('dob',date('Y-m-d',strtotime($request->dob)))
            ->first();

        if($check){
            return  response()->json(['result' => 'Already exists!']);
        }

        try{
            $user = Auth::user();
            $user_id = $user->id;

            $update = _FamilyBg::find($request->id);
            $update->user_id = $request->sid;
            $update->lastname = $request->lastname;
            $update->firstname = $request->firstname;
            $update->middlename = $request->middlename;
            $update->extname = $request->extname;
            $update->relation_id = $request->relation;
            $update->dob = date('Y-m-d',strtotime($request->dob));
            $update->contact_no = $request->contact_no;
            $update->email = $request->email;
            $update->occupation = $request->occupation;
            $update->employer = $request->employer;
            $update->employer_contact = $request->employer_contact;
            $update->employer_address = $request->employer_address;
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

        $check = _FamilyBg::with('fam_relation')
            ->where('user_id',$id)
            ->where('id',$fid)
            ->first();

        if(!$check){
            return view('layouts/error/404');
        }

        $data = array(
            'query' => $check
        );
        return view('hrims/employee/information/familyInfoDelete',$data);
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

        $check = _FamilyBg::where('user_id',$id)
            ->where('id',$fid)
            ->first();

        if(!$check){
            return  response()->json(['result' => 'error']);
        }

        $delete = _FamilyBg::find($fid);
        $delete->delete();

        DB::statement("ALTER TABLE _family_bg AUTO_INCREMENT = 1;");

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
            'relation' => 'required|numeric',
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'middlename' => 'nullable|string',
            'extname' => 'nullable|string',
            'dob' => 'required|date',
            'contact_no' => 'nullable|string',
            'email' => 'nullable|string|email',
            'occupation' => 'nullable|string',
            'employer' => 'nullable|string',
            'employer_contact' => 'nullable|string',
            'employer_address' => 'nullable|string',
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
