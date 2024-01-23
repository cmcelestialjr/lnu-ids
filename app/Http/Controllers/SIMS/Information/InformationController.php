<?php

namespace App\Http\Controllers\SIMS\Information;

use App\Http\Controllers\Controller;
use App\Models\_EducationBg;
use App\Models\_FamilyBg;
use App\Models\_PersonalInfo;
use App\Models\BloodType;
use App\Models\CivilStatuses;
use App\Models\EducProgramLevel;
use App\Models\Religion;
use App\Models\Sexs;
use App\Models\Users;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PDOException;

class InformationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $id = $request->id;

        $data = array(
            'id' => $id
        );
        return view('sims/information/informationEdit',$data);
    }

    /**
     * Proceed User password
     */
    public function proceed(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->proceedValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => 'error']);
        }

        $result = 'error';

        $password = $request->password;

        $user = Auth::user();
        $user_id = $user->id;
        $user_password = $user->password;

        if ($this->checkPassword($user_password, $password)) {
            $result = 'success';
        }

        // Prepare the response array
        $response = array('result' => $result);

        // Return a JSON response
        return response()->json($response);
    }    

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $id = $request->id;

        $data = array(
            'id' => $id
        );
        return view('sims/information/informationEditModal',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function showDiv(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $id = $request->id;

        $user = Auth::user();
        $user_id = $user->id;

        $data = NULL;

        if($id=='personalInfoEdit'){
            $query = Users::with('personal_info.per_brgy',
                             'personal_info.per_city_muns',
                             'personal_info.per_province',
                             'personal_info.res_brgy',
                             'personal_info.res_city_muns',
                             'personal_info.res_province',
                             'personal_info.religion')
            ->find($user_id);
            $sexs = Sexs::get();
            $civil_statuses = CivilStatuses::get();
            $blood_types = BloodType::get();
            $religions = Religion::get();
            $data = array(
                'query' => $query,
                'sexs' => $sexs,
                'civil_statuses' => $civil_statuses,
                'blood_types' => $blood_types,
                'religions' => $religions
            );
        }elseif($id=="educationalBgEdit"){
            $education_level = EducProgramLevel::with(['education_bg' => function ($query) use ($user_id){
                    $query->where('user_id',$user_id)
                        ->with('program');
                }])
                ->whereHas('education_bg', function ($query) use ($user_id) {
                    $query->where('user_id',$user_id);
                })->orderBy('id','DESC')->get();
            $data = array(
                'education_level' => $education_level,
            );
        }elseif($id=="familyBgEdit"){
            $family_bg = _FamilyBg::with('fam_relation')
                ->where('user_id',$user_id)
                ->orderBy('relation_id','ASC')
                ->get();
            $data = array(
                'id' => $id,
                'family_bg' => $family_bg
            );
        }

        if ($data==NULL) {
            return view('layouts/error/404');
        }

        return view('sims/information/'.$id,$data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $user = Auth::user();
        $user_id = $user->id;

        $info = Users::with('personal_info.sexs',
                            'personal_info.religion',
                            'personal_info.civil_statuses')
            ->where('id',$user_id)->first();
        
        $data = array(    
            'info' => $info
        );
        return view('sims/information/informationPersonalInfo',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->updateValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => 'errors',
                                     'email' => $validator->errors()->get('email'),
                                     'email_official' => $validator->errors()->get('email_official'),
                                     'contact_no' => $validator->errors()->get('contact_no'),
                                     'contact_no_official' => $validator->errors()->get('contact_no_official')
                                    ]);
        }
        
        $result = 'success';
        DB::beginTransaction();
        try{
            $user = Auth::user();
            $updated_by = $user->id;

            $sex = $request->sex;
            $civil_status = $request->civil_status;
            $blood_type = $request->blood_type;
            $contact_no = $request->contact_no;
            $contact_no_official = $request->contact_no_official;
            $email = $request->email;
            $email_official = $request->email_official;
            $religion = $request->religion;
            $res_lot = $request->res_lot;
            $res_street = $request->res_street;
            $res_subd = $request->res_subd;
            $res_zip_code = $request->res_zip_code;
            $res_brgy_id = $request->res_brgy_id;
            $res_municipality_id = $request->res_municipality_id;
            $res_province_id = $request->res_province_id;
            $same_res = $request->same_res;
            $per_lot = $request->per_lot;
            $per_street = $request->per_street;
            $per_subd = $request->per_subd;
            $per_zip_code = $request->per_zip_code;
            $per_brgy_id = $request->per_brgy_id;
            $per_municipality_id = $request->per_municipality_id;
            $per_province_id = $request->per_province_id;
            $check_religion = $request->check_religion;
            $new_religion = $request->new_religion;

            if($contact_no_official==''){
                $contact_no_official = NULL;
            }
            if($email_official==''){
                $email_official = NULL;
            }

            if($check_religion==1){
                $insert = new Religion();
                $insert->name = $new_religion;
                $insert->updated_by = $updated_by;
                $insert->save();
                $religion = $insert->id;
            }

            _PersonalInfo::where('user_id',$updated_by)
                ->update(['sex' => $sex,
                        'civil_status_id' => $civil_status,
                        'blood_type_id' => $blood_type,
                        'contact_no' => $contact_no,
                        'contact_no_official' => $contact_no_official,
                        'email' => $email,
                        'email_official' => $email_official,
                        'religion_id' => $religion,
                        'res_lot' => $res_lot,
                        'res_street' => $res_street,
                        'res_subd' => $res_subd,
                        'res_zip_code' => $res_zip_code,
                        'res_brgy_id' => $res_brgy_id,
                        'res_municipality_id' => $res_municipality_id,
                        'res_province_id' => $res_province_id,
                        'same_res' => $same_res,
                        'per_lot' => $per_lot,
                        'per_street' => $per_street,
                        'per_subd' => $per_subd,
                        'per_zip_code' => $per_zip_code,
                        'per_brgy_id' => $per_brgy_id,
                        'per_municipality_id' => $per_municipality_id,
                        'per_province_id' => $per_province_id,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s')]);

            // Commit the database transaction
            DB::commit();
            // Set the result to 'success'
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

        $response = array('result' => $result);
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function checkPassword($user_password,$password){
        $decrypt = Crypt::decryptString($user_password);
        $remove_first = substr($decrypt, 4);
        $hash_password = substr($remove_first, 0, -4);
        $isValid = Hash::check($password, $hash_password);
        return $isValid;
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
            'id' => 'required|string'
        ];

        $customMessages = [
            'id.required' => 'Id is required.',
            'id.string' => 'Id must be a string.'
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function proceedValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|string'
        ];

        $customMessages = [
            'id.required' => 'Id is required.',
            'id.string' => 'Id must be a string.'
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
            'sex' => 'required|numeric',
            'civil_status' => 'required|numeric',
            'contact_no' => 'required|string|size:12',
            'contact_no_official' => 'nullable|string|size:12',
            'email' => 'required|email:rfc,dns',
            'email_official' => 'nullable|email:rfc,dns',
            'blood_type' => 'required|numeric',
            'religion' => 'required|numeric',
            'check_religion' => 'required|numeric',
            'new_religion' => 'nullable|string',
        ];

        $customMessages = [
            'sex.required' => 'Sex is required',
            'sex.numeric' => 'Sex must be a number',
            'civil_status.required' => 'Civil Statut is required',
            'civil_status.numeric' => 'Civil Statut must be a number',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be valid',
            'email_official.email' => 'Email must be valid',
            'contact_no.required' => 'Contact No is required',
            'contact_no.size' => 'Contact No must be valid',
            'contact_no_official.size' => 'Contact No must be valid',
            'blood_type.required' => 'Blood Type is required',
            'blood_type.numeric' => 'Blood Type must be a number',
            'religion.required' => 'Religion is required',
            'religion.numeric' => 'Religion must be a number',
            'check_religion.required' => 'Check Religion is required',
            'check_religion.numeric' => 'Check Religion must be a number',
            'new_religion.string' => 'New Religion must be a string',
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
