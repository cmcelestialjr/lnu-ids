<?php

namespace App\Http\Controllers\RIMS\Student;

use App\Http\Controllers\Controller;
use App\Models\_EducationBg;
use App\Models\_FamilyBg;
use App\Models\_PersonalInfo;
use App\Models\CivilStatuses;
use App\Models\Countries;
use App\Models\EducBranch;
use App\Models\EducCurriculum;
use App\Models\EducDepartments;
use App\Models\EducProgramLevel;
use App\Models\EducPrograms;
use App\Models\EducProgramsAll;
use App\Models\EducProgramsCode;
use App\Models\EducYearLevel;
use App\Models\FamRelations;
use App\Models\PSGCBrgys;
use App\Models\PSGCCityMuns;
use App\Models\PSGCProvinces;
use App\Models\Religion;
use App\Models\School;
use App\Models\Sexs;
use App\Models\StudentsInfo;
use App\Models\StudentsProgram;
use App\Models\StudentsStatus;
use App\Models\Users;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PDOException;
use Symfony\Component\ErrorHandler\Debug;

class InfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Validate the request
        $validator = $this->indexValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $array = array('Info','Contact','Educ','Fam');
        $id = $request->id;
        $val = $request->val;

        $query = Users::with('personal_info.country',
                             'personal_info.religion',
                             'personal_info.civil_statuses',
                             'personal_info.res_brgy',
                             'personal_info.res_city_muns',
                             'personal_info.res_province',
                             'personal_info.per_brgy',
                             'personal_info.per_city_muns',
                             'personal_info.per_province',
                             'student_info.program.departments',
                             'student_info.program_code',
                             'student_info.curriculum',
                             'student_info.grade_level',
                             'education.level',
                             'education.school',
                             'education.program',
                             'family.fam_relation')
            ->where('id',$id)
            ->first();

        if (!in_array($val, $array) || $query==NULL) {
            return view('layouts/error/404');
        }

        $sexs = Sexs::get();
        $civil_statuses = CivilStatuses::get();
        $countries = Countries::get();
        $religions = Religion::get();
        $departments = EducDepartments::get();
        $program_level = EducProgramLevel::get();
        $relations = FamRelations::get();
        $branches = EducBranch::get();
        $student_statuses = StudentsStatus::get();

        $data = array(
            'query' => $query,
            'val' => $val,
            'sexs' => $sexs,
            'civil_statuses' => $civil_statuses,
            'countries' => $countries,
            'religions' => $religions,
            'departments' => $departments,
            'program_level' => $program_level,
            'relations' => $relations,
            'branches' => $branches,
            'student_statuses' => $student_statuses
        );
        return view('rims/student/editInfo',$data);


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function infoUpdate(Request $request)
    {
        // Validate the request
        $validator = $this->infoUpdateValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => 'error']);
        }

        $id = $request->id;
        $sex = $request->sex;
        $civil_status = $request->civil_status;
        $religion = $request->religion;
        $country = $request->country;
        $branch = $request->branch;
        $department = $request->department;
        $program = $request->program;
        $curriculum = $request->curriculum;
        $grade_level = $request->grade_level;
        $student_status = $request->student_status;
        $lastname = $request->lastname;
        $firstname = $request->firstname;
        $middlename = $request->middlename;
        $extname = $request->extname;
        $nickname = $request->nickname;
        $sex = $request->sex;
        $civil_status = $request->civil_status;
        $dob = $request->dob;
        $birthplace = $request->birthplace;
        $citizenship = $request->citizenship;
        $religion_check = $request->religion_check;
        $religion_not_list = $request->religion_not_list;
        $nstp_serial_no = $request->nstp_serial_no;

        $user_check = Users::where('id',$id)
            ->first('id');
        $sex_check = Sexs::where('id',$sex)
            ->first('id');
        $civil_status_check = CivilStatuses::where('id',$civil_status)
            ->first('id');
        $religion_check = Religion::where('id',$religion)
            ->first('id');
        $country_check = Religion::where('id',$country)
            ->first('id');
        $branch_check = EducBranch::where('id',$branch)
            ->first('id');
        $department_check = EducDepartments::where('id',$department)
            ->first('id');
        $program_check = EducPrograms::where('id',$program)
            ->first();
        $curriculum_check = EducCurriculum::where('id',$curriculum)
            ->first('id');
        $grade_level_check = EducYearLevel::where('id',$grade_level)
            ->first('id');
        $student_status_check = StudentsStatus::where('id',$student_status)
            ->first('id');

        if ($user_check==NULL ||
            $sex_check==NULL ||
            $civil_status_check==NULL ||
            $religion_check==NULL ||
            $department_check==NULL ||
            $branch_check==NULL ||
            $student_status_check==NULL ||
            ($country!=NULL && $country_check==NULL) ||
            ($program!=NULL && $program_check==NULL) ||
            ($curriculum!=NULL && $curriculum_check==NULL) ||
            ($grade_level!=NULL && $grade_level_check==NULL)) {
            return response()->json(['result' => 'error']);
        }

        try{
            $user = Auth::user();
            $updated_by = $user->id;

            if($religion_check==1){
                // $religion_query = Religion::firstOrCreate(
                //     ['name' => trim($religion_not_list)],
                //     ['updated_by' => $updated_by,
                //     'updated_at' => date('Y-m-d H:i:s'),
                //     'created_at' => date('Y-m-d H:i:s')]
                // );
                // $religion = $religion_query->id;
            }

            Users::where('id', $id)
                ->update([
                        'lastname' => mb_strtoupper($lastname),
                        'firstname' => mb_strtoupper($firstname),
                        'middlename' => mb_strtoupper($middlename),
                        'extname' => mb_strtoupper($extname),
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s')]);

            _PersonalInfo::where('user_id', $id)
                ->update([
                        'nickname' => mb_strtoupper($nickname),
                        'dob' => date('Y-m-d', strtotime($dob)),
                        'place_birth' => mb_strtoupper($birthplace),
                        'sex' => $sex,
                        'civil_status_id' => $civil_status,
                        'religion_id' => $religion,
                        'citizenship' => $citizenship,
                        'country_id' => $country,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s')]);

            $student = Users::find($id);
            $get_program_code = EducProgramsCode::where('branch_id',$branch)
                ->where('program_id', $program)->first();

            $program_code_id = NULL;
            if($get_program_code){
                $program_code_id = $get_program_code->id;
            }

            $program_id = NULL;
            $program_level_id = NULL;
            $program_name = NULL;
            $program_shorten = NULL;
            if($program_check){
                $program_id = $program_check->id;
                $program_level_id = $program_check->program_level_id;
                $program_name = $program_check->name;
                $program_shorten = $program_check->shorten;
            }

            $curriculum_id = NULL;
            if($curriculum_check){
                $curriculum_id = $curriculum_check->id;
            }

            $grade_level_id = NULL;
            if($grade_level_check){
                $grade_level_id = $grade_level_check->id;
            }

            StudentsInfo::updateOrCreate(
                [
                    'user_id' => $id
                ],
                [
                    'id_no' => $student->stud_id,
                    'nstp_serial_no' => $nstp_serial_no,
                    'program_id' => $program_id,
                    'program_code_id' => $program_code_id,
                    'program_level_id' => $program_level_id,
                    'curriculum_id' => $curriculum_id,
                    'grade_level_id' => $grade_level_id,
                    'student_status_id' => $student_status,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            );

            $student_program_check = StudentsProgram::where('user_id',$id)
                ->orderBy('year_from','DESC')
                ->first();
            if($student_program_check){
                StudentsProgram::where('id', $student_program_check->id)
                    ->update([
                            'program_id' => $program_id,
                            'program_level_id' => $program_level_id,
                            'program_code_id' => $program_code_id,
                            'curriculum_id' => $curriculum_id,
                            'grade_level_id' => $grade_level_id,
                            'program_name' => $program_name,
                            'program_shorten' => $program_shorten,
                            'student_status_id' => $student_status,
                            'updated_by' => $updated_by,
                            'updated_at' => date('Y-m-d H:i:s')]);
            }else{
                $insert = new StudentsProgram();
                $insert->program_id = $program_id;
                $insert->program_level_id = $program_level_id;
                $insert->program_code_id = $program_code_id;
                $insert->curriculum_id = $curriculum_id;
                $insert->grade_level_id = $grade_level_id;
                $insert->program_name = $program_name;
                $insert->program_shorten = $program_shorten;
                $insert->year_from = date('Y');
                $insert->from_school = 'Leyte Normal University';
                $insert->student_status_id = $student_status;
                $insert->updated_by = $updated_by;
                $insert->save();
            }

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
     * Update the specified resource in storage.
     */
    public function contactUpdate(Request $request)
    {
        // Validate the request
        $validator = $this->contactUpdateValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => 'error']);
        }

        $id = $request->id;
        $contact_no_1 = $request->contact_no_1;
        $contact_no_2 = $request->contact_no_2;
        $email_official = $request->email_official;
        $email = $request->email;
        $telephone_no = $request->telephone_no;
        $res_lot = $request->res_lot;
        $res_street = $request->res_street;
        $res_subd = $request->res_subd;
        $res_province_id = $request->res_province_id;
        $res_municipality_id = $request->res_municipality_id;
        $res_brgy_id = $request->res_brgy_id;
        $res_zip_code = $request->res_zip_code;
        $same_res = $request->same_res;
        $per_lot = $request->per_lot;
        $per_street = $request->per_street;
        $per_subd = $request->per_subd;
        $per_province_id = $request->per_province_id;
        $per_municipality_id = $request->per_municipality_id;
        $per_brgy_id = $request->per_brgy_id;
        $per_zip_code = $request->per_zip_code;

        $user_check = Users::where('id',$id)
            ->first('id');
        $res_province_check = PSGCProvinces::where('id',$res_province_id)
            ->first('id');
        $res_muns_check = PSGCCityMuns::where('id',$res_municipality_id)
            ->first('id');
        $res_brgy_check = PSGCBrgys::where('id',$res_brgy_id)
            ->first('id');
        $per_province_check = PSGCProvinces::where('id',$res_province_id)
            ->first('id');
        $per_muns_check = PSGCCityMuns::where('id',$res_municipality_id)
            ->first('id');
        $per_brgy_check = PSGCBrgys::where('id',$res_brgy_id)
            ->first('id');

        if ($user_check==NULL ||
            $res_province_check==NULL ||
            $res_muns_check==NULL ||
            $res_brgy_check==NULL ||
            $per_province_check==NULL ||
            $per_muns_check==NULL ||
            $per_muns_check==NULL ||
            $per_brgy_check==NULL) {
            return response()->json(['result' => 'error']);
        }

        try{
            $user = Auth::user();
            $updated_by = $user->id;

            _PersonalInfo::where('user_id', $id)
                ->update([
                        'contact_no_official' => $contact_no_1,
                        'contact_no' => $contact_no_2,
                        'email_official' => $email_official,
                        'email' => $email,
                        'telephone_no' => $telephone_no,
                        'res_lot' => $res_lot,
                        'res_street' => $res_street,
                        'res_subd' => $res_subd,
                        'res_brgy_id' => $res_brgy_id,
                        'res_municipality_id' => $res_municipality_id,
                        'res_province_id' => $res_province_id,
                        'res_zip_code' => $res_zip_code,
                        'per_lot' => $per_lot,
                        'per_street' => $per_street,
                        'per_subd' => $per_subd,
                        'per_brgy_id' => $per_brgy_id,
                        'per_municipality_id' => $per_municipality_id,
                        'per_province_id' => $per_province_id,
                        'per_zip_code' => $per_zip_code,
                        'same_res' => $same_res,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s')]);

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
     * Update the specified resource in storage.
     */
    public function educUpdate(Request $request)
    {
        // Validate the request
        $validator = $this->educUpdateValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => 'error']);
        }

        $id = $request->id;
        $educ_id = $request->educ_id;
        $level = $request->level;
        $school_check = $request->school_check;
        $school = $request->school;
        $school_not_list = $request->school_not_list;
        $school_shorten_not_list = $request->school_shorten_not_list;
        $program_check = $request->program_check;
        $program_educ = $request->program_educ;
        $program_not_list = $request->program_not_list;
        $period_from = date('Y-m-d',strtotime($request->period_from));
        $period_to = date('Y-m-d',strtotime($request->period_to));
        $present = $request->present;
        $units_earned = $request->units_earned;
        $year_grad = $request->year_grad;
        $honors = $request->honors;
        $option = $request->option;
        $program_id = NULL;
        $program_name = NULL;

        $user_check = Users::where('id',$id)
            ->first('id');
        $program_level_check = EducProgramLevel::where('id',$level)
            ->first();
        $school_check_query = School::where('id',$school)
            ->first('id');
        $program_check_query = EducProgramsAll::where('id',$program_educ)
            ->first('id');

        if ($user_check==NULL ||
            $program_level_check==NULL ||
            ($school!=NULL && $school_check_query==NULL) ||
            ($program_educ!=NULL && $program_check_query==NULL)) {
            return response()->json(['result' => 'error']);
        }

        $user = Auth::user();
        $updated_by = $user->id;

        if($school_check==1){
            $school_query = School::firstOrCreate(
                ['name' => trim($school_not_list)],
                ['shorten' => trim($school_shorten_not_list),
                'updated_by' => $updated_by,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')]
            );
            $school_id = $school_query->id;
            $school_name = $school_query->name;
        }else{
            $school_query = School::find($school);
            $school_id = $school_query->id;
            $school_name = $school_query->name;
        }

        if($program_level_check->program=='w'){
            if($program_check==1){
                $program_query = EducProgramsAll::firstOrCreate(
                    ['name' => trim($program_not_list)],
                    ['program_level_id' => $level,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')]
                );
                $program_id = $program_query->id;
            }else{
                if($school<=2){
                    $program_info = EducPrograms::find($program_educ);
                    $program_name = $program_info->name;
                    $program_query = EducProgramsAll::firstOrCreate(
                        ['program_id' => $program_educ],
                        ['program_level_id' => $level,
                        'name' => $program_name,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s')]
                    );
                    $program_id = $program_query->id;
                }else{
                    $program_info = EducProgramsAll::find($program_educ);
                    $program_name = $program_info->name;
                    $program_id = $program_info->id;
                }
            }
        }

        if($present==1){
            $period_to = NULL;
        }

        try{
            $data = array('id' => $id,
                        'educ_id' => $educ_id,
                        'level' => $level,
                        'school_id' => $school_id,
                        'school_name' => $school_name,
                        'program_id' => $program_id,
                        'period_from' => $period_from,
                        'period_to' => $period_to,
                        'units_earned' => $units_earned,
                        'year_grad' => $year_grad,
                        'honors' => $honors,
                        'updated_by' => $updated_by
                        );

            if($option=='new'){
                return $this->educNewInfo($data);
            }else{
                return $this->educUpdateInfo($data);
            }
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
     * Update the specified resource in storage.
     */
    public function famUpdate(Request $request)
    {
        // Validate the request
        $validator = $this->famUpdateValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => 'error']);
        }

        $id = $request->id;
        $fam_id = $request->fam_id;
        $fam_relation = $request->fam_relation;
        $lastname = $request->lastname;
        $firstname = $request->firstname;
        $middlename = $request->middlename;
        $extname = $request->extname;
        $dob = $request->dob;
        $contact_no = $request->contact_no;
        $email = $request->email;
        $occupation = $request->occupation;
        $employer = $request->employer;
        $employer_address = $request->employer_address;
        $employer_contact = $request->employer_contact;
        $option = $request->option;

        $user_check = Users::where('id',$id)
            ->first('id');
        $fam_relation_check = FamRelations::where('id',$fam_relation)
            ->first();

        if ($user_check==NULL ||
            $fam_relation_check==NULL) {
            return response()->json(['result' => 'error']);
        }

        try{
            $user = Auth::user();
            $updated_by = $user->id;

            $data = array('id' => $id,
                        'fam_id' => $fam_id,
                        'fam_relation' => $fam_relation,
                        'lastname' => $lastname,
                        'firstname' => $firstname,
                        'middlename' => $middlename,
                        'extname' => $extname,
                        'dob' => $dob,
                        'contact_no' => $contact_no,
                        'email' => $email,
                        'occupation' => $occupation,
                        'employer' => $employer,
                        'employer_address' => $employer_address,
                        'employer_contact' => $employer_contact,
                        'updated_by' => $updated_by
                        );

            if($option=='new'){
                return $this->famNewInfo($data);
            }else{
                return $this->famUpdateInfo($data);
            }
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

    private function educNewInfo($data){
        $insert = new _EducationBg();
        $insert->user_id = $data['id'];
        $insert->level_id = $data['level'];
        $insert->school_id = $data['school_id'];
        $insert->name = $data['school_name'];
        $insert->program_id = $data['program_id'];
        $insert->period_from = $data['period_from'];
        $insert->period_to = $data['period_to'];
        $insert->units_earned = $data['units_earned'];
        $insert->year_grad = $data['year_grad'];
        $insert->honors = $data['honors'];
        $insert->updated_by = $data['updated_by'];
        $insert->save();

        return response()->json(['result' => 'success']);
    }

    private function educUpdateInfo($data){
        _EducationBg::where('id', $data['educ_id'])
            ->where('user_id', $data['id'])
            ->where('level_id', $data['level'])
            ->update([
                    'school_id' => $data['school_id'],
                    'name' => $data['school_name'],
                    'program_id' => $data['program_id'],
                    'period_from' => $data['period_from'],
                    'period_to' => $data['period_to'],
                    'units_earned' => $data['units_earned'],
                    'year_grad' => $data['year_grad'],
                    'honors' => $data['honors'],
                    'updated_by' => $data['updated_by'],
                    'updated_at' => date('Y-m-d H:i:s')]);

        return response()->json(['result' => 'success']);
    }

    private function famNewInfo($data){
        $insert = new _FamilyBg();
        $insert->user_id = $data['id'];
        $insert->relation_id = $data['fam_relation'];
        $insert->lastname = $data['lastname'];
        $insert->firstname = $data['firstname'];
        $insert->middlename = $data['middlename'];
        $insert->extname = $data['extname'];
        $insert->dob = date('Y-m-d', strtotime($data['dob']));
        $insert->contact_no = $data['contact_no'];
        $insert->email = $data['email'];
        $insert->occupation = $data['occupation'];
        $insert->employer = $data['employer'];
        $insert->employer_address = $data['employer_address'];
        $insert->employer_contact = $data['employer_contact'];
        $insert->updated_by = $data['updated_by'];
        $insert->save();

        return response()->json(['result' => 'success']);
    }

    private function famUpdateInfo($data){
        _FamilyBg::where('id', $data['fam_id'])
            ->where('user_id', $data['id'])
            ->where('relation_id', $data['fam_relation'])
            ->update([
                    'lastname' => $data['lastname'],
                    'firstname' => $data['firstname'],
                    'middlename' => $data['middlename'],
                    'extname' => $data['extname'],
                    'dob' => date('Y-m-d', strtotime($data['dob'])),
                    'contact_no' => $data['contact_no'],
                    'email' => $data['email'],
                    'occupation' => $data['occupation'],
                    'employer' => $data['employer'],
                    'employer_address' => $data['employer_address'],
                    'employer_contact' => $data['employer_contact'],
                    'updated_by' => $data['updated_by'],
                    'updated_at' => date('Y-m-d H:i:s')]);

        return response()->json(['result' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function programList(Request $request){
        // Validate the request
        $validator = $this->programListValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => 'error']);
        }

        $id = $request->id;
        $department = $request->department;
        $student_query = Users::with('student_info.program.departments',
                             'student_info.curriculum',
                             'student_info.grade_level')
            ->where('id',$id)
            ->first();
        $department_query = EducDepartments::find($department);

        // Check if the student exists
        if($student_query==NULL && $department_query==NULL){
            return response()->json(['result' => 'error']);
        }

        $first_program = EducPrograms::where('department_id',$department)->first();

        $program_id = $first_program->id;
        $grade_level_id = NULL;

        $programs = EducPrograms::where('department_id',$department)->get();
        $grade_levels = EducYearLevel::whereHas('program_level', function($query) use($department,$program_id){
                $query->whereHas('programs', function($query) use($department,$program_id){
                    $query->where('department_id',$department);
                    $query->where('id',$program_id);
                });
            })->get();

        if($student_query->student_info){
            $program_id = $student_query->student_info->program_id;
            $grade_level_id = $student_query->student_info->grade_level_id;
        }

        return response()->json(['result' => 'success',
                                 'programs' => $programs,
                                 'grade_levels' => $grade_levels,
                                 'program_id' => $program_id,
                                 'grade_level_id' => $grade_level_id
                                ]);
    }

    public function curriculumList(Request $request){
        // Validate the request
        $validator = $this->curriculumListValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => 'error']);
        }

        $id = $request->id;
        $department = $request->department;
        $program = $request->program;

        $student_query = Users::with('student_info.program.departments',
                             'student_info.curriculum',
                             'student_info.grade_level')
            ->where('id',$id)
            ->first();
        $program_query = EducPrograms::where('id',$program)
            ->where('department_id',$department)
            ->first();

        // Check if the student exists
        if($student_query==NULL && $program_query==NULL){
            return response()->json(['result' => 'error']);
        }

        $curriculums = EducCurriculum::where('program_id',$program)->get();

        $curriculum_id = NULL;

        if($student_query->student_info){
            $curriculum_id = $student_query->student_info->curriculum_id;
        }

        return response()->json(['result' => 'success',
                                 'curriculums' => $curriculums,
                                 'curriculum_id' => $curriculum_id
                                ]);
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

    private function indexValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'val' => 'required|string'
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'val.required' => 'Value is required.',
            'val.string' => 'Value must be a string.'
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    private function programListValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'department' => 'required|numeric'
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'department.required' => 'Department is required.',
            'department.numeric' => 'Department must be a number.'
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    private function curriculumListValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'department' => 'required|numeric',
            'program' => 'required|numeric'
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'department.required' => 'Department is required.',
            'department.numeric' => 'Department must be a number.',
            'program.required' => 'Program is required.',
            'program.numeric' => 'Program must be a number.'
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    private function infoUpdateValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'middlename' => 'nullable|string',
            'extname' => 'nullable|string',
            'nickname' => 'nullable|string',
            'sex' => 'required|numeric',
            'civil_status' => 'required|numeric',
            'dob' => 'required|date',
            'birthplace' => 'nullable|string',
            'country' => 'nullable|numeric',
            'citizenship' => 'nullable|string',
            'religion' => 'required|string',
            'religion_check' => 'required|numeric',
            'religion_not_list' => 'nullable|string',
            'nstp_serial_no' => 'nullable|string',
            'branch' => 'required|numeric',
            'department' => 'required|numeric',
            'program' => 'nullable|numeric',
            'curriculum' => 'nullable|numeric',
            'grade_level' => 'nullable|numeric',
            'student_status' => 'nullable|numeric',
            'url' => 'required|string',
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'lastname.required' => 'Lastname is required.',
            'lastname.string' => 'Lastname must be a string.',
            'firstname.required' => 'Firstname is required.',
            'firstname.string' => 'Firstname must be a string.',
            'middlename.string' => 'Middlename must be a string.',
            'extname.string' => 'Middlename must be a string.',
            'nickname.string' => 'Nickname must be a string.',
            'sex.required' => 'Sex is required.',
            'sex.numeric' => 'Sex must be a number.',
            'civil_status.required' => 'Civil Status is required.',
            'civil_status.numeric' => 'Civil Status must be a number.',
            'dob.required' => 'Birthdate is required.',
            'dob.date' => 'Birthdate must be a valid date.',
            'birthplace.string' => 'Birthplace must be a string.',
            'country.numeric' => 'Country must be a number.',
            'citizenship.string' => 'Citizenship must be a string.',
            'religion.required' => 'Religion is required.',
            'religion.numeric' => 'Religion must be a number.',
            'religion_check.required' => 'Religion Check is required.',
            'religion_check.numeric' => 'Religion Check must be a number.',
            'religion_not_list.string' => 'Religion not in the list must be a string.',
            'nstp_serial_no.string' => 'NSTP Serial No must be a string.',
            'branch.required' => 'Branch is required.',
            'branch.numeric' => 'Branch must be a number.',
            'department.required' => 'Department is required.',
            'department.numeric' => 'Department must be a number.',
            'program.numeric' => 'Program must be a number.',
            'curriculum.numeric' => 'Curriculum must be a number.',
            'grade_level.numeric' => 'Grade Level must be a number.',
            'student_status.required' => 'Status is required.',
            'student_status.numeric' => 'Status must be a number.',
            'url.required' => 'URL is required.',
            'url.string' => 'URL must be a string.',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    private function contactUpdateValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'contact_no_1' => 'required|string|regex:/^\d{3}-\d{3}-\d{4}$/',
            'contact_no_2' => 'nullable|string|regex:/^\d{3}-\d{3}-\d{4}$/',
            'email_official' => 'required|email',
            'email' => 'nullable|email',
            'telephone_no' => 'nullable|string',
            'res_lot' => 'nullable|string',
            'res_street' => 'nullable|string',
            'res_subd' => 'nullable|string',
            'res_province_id' => 'nullable|numeric',
            'res_municipality_id' => 'nullable|numeric',
            'res_brgy_id' => 'nullable|numeric',
            'res_zip_code' => 'nullable|string',
            'same_res' => 'nullable|string|in:Yes,No',
            'per_lot' => 'nullable|string',
            'per_street' => 'nullable|string',
            'per_subd' => 'nullable|string',
            'per_province_id' => 'nullable|numeric',
            'per_municipality_id' => 'nullable|numeric',
            'per_brgy_id' => 'nullable|numeric',
            'per_zip_code' => 'nullable|string',
            'url' => 'required|string',
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'contact_no_1.required' => 'Contact No. 1 is required.',
            'contact_no_1.regex' => 'Contact No. 1 must be a valid contact no.',
            'contact_no_2.regex' => 'Contact No. 2 must be a valid contact no.',
            'email_official.required' => 'Email 1 is required.',
            'email_official.email' => 'Email 1 must be a valid email.',
            'email.email' => 'Email 2 must be a valid email.',
            'telephone_no.string' => 'Telephone No. must be a string.',
            'res_lot.string' => 'Res Lot must be a string.',
            'res_street.string' => 'Res Street must be a string.',
            'res_subd.string' => 'Res Subd must be a string.',
            'res_province_id.numeric' => 'Res Province must be a number.',
            'res_municipality_id.numeric' => 'Res Municipality must be a number.',
            'res_brgy_id.numeric' => 'Res Brgy must be a number.',
            'res_zip_code.string' => 'Res Brgy must be a string.',
            'same_res.in' => 'The value of Same Res must be either "Yes" or "No".',
            'per_lot.string' => 'Per Lot must be a string.',
            'per_street.string' => 'Per Street must be a string.',
            'per_subd.string' => 'Per Subd must be a string.',
            'per_province_id.numeric' => 'Per Province must be a number.',
            'per_municipality_id.numeric' => 'Per Municipality must be a number.',
            'per_brgy_id.numeric' => 'Per Brgy must be a number.',
            'per_zip_code.string' => 'Per Brgy must be a string.',
            'url.required' => 'URL is required.',
            'url.string' => 'URL must be a string.',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    private function educUpdateValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'educ_id' => 'required|numeric',
            'level' => 'required|numeric',
            'school_check' => 'required|numeric|in:0,1',
            'school' => 'nullable|numeric',
            'school_not_list' => 'nullable|string',
            'program_check' => 'required|numeric|in:0,1',
            'program_educ' => 'nullable|numeric',
            'program_not_list' => 'nullable|string',
            'period_from' => 'required|date',
            'period_to' => 'required|date',
            'present' => 'required|numeric|in:0,1',
            'units_earned' => 'nullable|string',
            'year_grad' => 'nullable|string',
            'honors' => 'nullable|string',
            'option' => 'required|string|in:new,update',
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'educ_id.required' => 'Educ ID is required.',
            'educ_id.numeric' => 'Educ ID must be a number.',
            'level.required' => 'Level is required.',
            'level.numeric' => 'Level must be a number.',
            'school_check.required' => 'School check is required.',
            'school_check.in' => 'School check must be a number 0 or 1.',
            'school.numeric' => 'School must be a number.',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    private function famUpdateValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'fam_id' => 'required|numeric',
            'fam_relation' => 'required|numeric',
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'middlename' => 'nullable|string',
            'extname' => 'nullable|string',
            'dob' => 'required|date',
            'contact_no' => 'nullable|string|regex:/^\d{3}-\d{3}-\d{4}$/',
            'email' => 'nullable|email',
            'occupation' => 'nullable|string',
            'employer' => 'nullable|string',
            'employer_address' => 'nullable|string',
            'employer_contact' => 'nullable|string|regex:/^\d{3}-\d{3}-\d{4}$/',
            'option' => 'required|string|in:new,update',
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'fam_id.required' => 'Fam ID is required.',
            'fam_id.numeric' => 'Fam ID must be a number.',
            'fam_relation.required' => 'Fam Relation ID is required.',
            'fam_relation.numeric' => 'Fam Relation ID must be a number.',
            'lastname.required' => 'Lastname is required.',
            'lastname.string' => 'Lastname must be a string.',
            'firstname.required' => 'Firstname is required.',
            'firstname.string' => 'Firstname must be a string.',
            'middlename.string' => 'Middlename must be a string.',
            'extname.string' => 'Extname must be a string.',
            'dob.required' => 'Birthdate is required.',
            'dob.date' => 'Birthdate must be a valid birthdate.',
            'contact_no.regex' => 'Contact No must be a valid Contact No.',
            'email.email' => 'Email must be a valid Email Address.',
            'occupation.string' => 'Occupation must be a string',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }
}
