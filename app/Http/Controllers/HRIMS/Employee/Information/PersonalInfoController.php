<?php

namespace App\Http\Controllers\HRIMS\Employee\Information;
use App\Http\Controllers\Controller;
use App\Models\_PersonalInfo;
use App\Models\BloodType;
use App\Models\CivilStatuses;
use App\Models\Sexs;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonalInfoController extends Controller
{
    public function personalInfo(Request $request){
        return $this->_personalInfo($request);
    }
    public function infoSubmit(Request $request){
        return $this->_infoSubmit($request);
    }
    public function addressSubmit(Request $request){
        return $this->_addressSubmit($request);
    }
    public function idNoSubmit(Request $request){
        return $this->_idNoSubmit($request);
    }

    
    private function _personalInfo($request){
        $user_access_level = $request->session()->get('user_access_level');
        $id = $request->id;
        $query = Users::where('id',$id)->first();
        $sexs = Sexs::get();
        $civil_statuses = CivilStatuses::get();
        $blood_types = BloodType::get();
        $data = array(
            'query' => $query,
            'sexs' => $sexs,
            'civil_statuses' => $civil_statuses,
            'blood_types' => $blood_types,
            'user_access_level' => $user_access_level
        );
        return view('hrims/employee/information/personalInfo',$data);
    }
    private function _infoSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id_no = $request->id_no;
            $lastname = mb_strtoupper($request->lastname);
            $firstname = mb_strtoupper($request->firstname);
            $middlename = mb_strtoupper($request->middlename);
            $extname = mb_strtoupper($request->extname);
            $middlename_in_last = $request->middlename_in_last;
            $dob = date('Y-m-d',strtotime($request->dob));
            $place_birth = $request->place_birth;
            $sex = $request->sex;
            $civil_status = $request->civil_status;
            $height = $request->height;
            $weight = $request->weight;
            $blood_type = $request->blood_type;
            $telephone_no = $request->telephone_no;
            $contact_no = $request->contact_no;
            $contact_no_official = $request->contact_no_official;
            $email = $request->email;
            $email_official = $request->email_official;
            if($middlename==''){
                $middlename = NULL;
            }
            if($extname==''){
                $extname = NULL;
            }
            if($height==''){
                $height = NULL;
            }
            if($weight==''){
                $weight = NULL;
            }
            if($telephone_no==''){
                $telephone_no = NULL;
            }
            if($contact_no==''){
                $contact_no = NULL;
            }
            if($contact_no_official==''){
                $contact_no_official = NULL;
            }
            if($email==''){
                $email = NULL;
            }
            if($email_official==''){
                $email_official = NULL;
            }
            Users::where('id',$id_no)
            ->update(['lastname' => $lastname,
                    'firstname' => $firstname,
                    'middlename' => $middlename,
                    'extname' => $extname,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s')]);
            _PersonalInfo::where('user_id',$id_no)
                ->update(['middlename_in_last' => $middlename_in_last,
                        'dob' => $dob,
                        'place_birth' => $place_birth,
                        'sex' => $sex,
                        'civil_status_id' => $civil_status,
                        'height' => $height,
                        'weight' => $weight,
                        'blood_type_id' => $blood_type,
                        'telephone_no' => $telephone_no,
                        'contact_no' => $contact_no,
                        'contact_no_official' => $contact_no_official,
                        'email' => $email,
                        'email_official' => $email_official,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s')]);
            $result = 'success';
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
    private function _addressSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id_no = $request->id_no;
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
            _PersonalInfo::where('user_id',$id_no)
                ->update(['res_lot' => $res_lot,
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
            $result = 'success';
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
    private function _idNoSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id_no = $request->id_no;
            $bank_account_no = $request->bank_account_no;
            $tin_no = $request->tin_no;
            $gsis_bp_no = $request->gsis_bp_no;
            $philhealth_no = $request->philhealth_no;
            $sss_no = $request->sss_no;
            $pagibig_no = $request->pagibig_no;
            $pagibig2_no = $request->pagibig2_no;
            $pagibig_mpl_app_no = $request->pagibig_mpl_app_no;
            $pagibig_cal_app_no = $request->pagibig_cal_app_no;
            $pagibig_housing_app_no = $request->pagibig_housing_app_no;
            $pagibig_pag2_app_no = $request->pagibig_pag2_app_no;
            _PersonalInfo::where('user_id',$id_no)
                ->update(['bank_account_no' => $bank_account_no,
                        'tin_no' => $tin_no,
                        'gsis_bp_no' => $gsis_bp_no,
                        'philhealth_no' => $philhealth_no,
                        'sss_no' => $sss_no,
                        'pagibig_no' => $pagibig_no,
                        'pagibig2_no' => $pagibig2_no,
                        'pagibig_mpl_app_no' => $pagibig_mpl_app_no,
                        'pagibig_cal_app_no' => $pagibig_cal_app_no,
                        'pagibig_housing_app_no' => $pagibig_housing_app_no,
                        'pagibig_pag2_app_no' => $pagibig_pag2_app_no,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s')]);
            $result = 'success';
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
}