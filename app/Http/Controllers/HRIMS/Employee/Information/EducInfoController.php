<?php

namespace App\Http\Controllers\HRIMS\Employee\Information;

use App\Http\Controllers\Controller;
use App\Models\_EducationBg;
use App\Models\EducProgramLevel;
use App\Models\EducProgramsAll;
use App\Models\School;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class EducInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user_access_level = $request->session()->get('user_access_level');

        $id = $request->id;

        $query = _EducationBg::with('level')
            ->where('user_id',$id)
            ->get();

        $data = array(
            'query' => $query,
            'user_access_level' => $user_access_level
        );
        return view('hrims/employee/information/EducInfo',$data);
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

        $levels = EducProgramLevel::get();

        $data = array(
            'levels' => $levels
        );
        return view('hrims/employee/information/EducInfoNew',$data);
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

        $check = _EducationBg::where('user_id',$request->sid)
            ->where('period_from',$request->period_from)
            ->first();

        if($check){
            return  response()->json(['result' => 'Already exists!']);
        }

        try{
            $user = Auth::user();
            $user_id = $user->id;

            if($request->school_check==1){
                $checkSchool = School::where('name',$request->school_name)
                    ->where('shorten',$request->school_shorten)
                    ->first();
                if(!$checkSchool){
                    $insert = new School();
                    $insert->name = $request->school_name;
                    $insert->shorten = $request->school_shorten;
                    $insert->updated_by = $user_id;
                    $insert->save();
                    $school_id = $insert->id;
                    $school_name = $insert->name;
                }else{
                    $school_id = $checkSchool->id;
                    $school_name = $checkSchool->name;
                }
            }else{
                $school = School::find($request->school);
                $school_id = $school->id;
                $school_name = $school->name;
            }

            $program_id = $request->program;
            if($request->program_check==1){
                $checkProgram = EducProgramsAll::where('name',$request->program_name)
                    ->first();
                if(!$checkProgram){
                    $insert = new EducProgramsAll();
                    $insert->program_level_id = $request->level;
                    $insert->name = $request->program_name;
                    $insert->updated_by = $user_id;
                    $insert->save();
                    $program_id = $insert->id;
                }else{
                    $program_id = $checkProgram->id;
                }
            }

            $period_to = NULL;
            if($request->present_check==0){
                $period_to = date('Y-m-d',strtotime($request->period_to));
            }

            if($program_id==0){
                $program_id = NULL;
            }

            $insert = new _EducationBg();
            $insert->user_id = $request->sid;
            $insert->level_id = $request->level;
            $insert->school_id = $school_id;
            $insert->name = $school_name;
            $insert->program_id = $program_id;
            $insert->period_from = date('Y-m-d',strtotime($request->period_from));
            $insert->period_to = $period_to;
            $insert->units_earned = $request->units_earned;
            $insert->year_grad = $request->year_grad;
            $insert->honors = $request->honors;
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

        $query = _EducationBg::with('level')
            ->where('user_id',$id)
            ->orderBy('level_id','ASC')
            ->orderBy('period_from','desc')
            ->get()
            ->map(function($query) {
                $period_to = 'present';
                if($query->period_to){
                    $period_to = date('m/d/Y',strtotime($query->period_to));
                }
                return [
                    'id' => $query->id,
                    'level' => $query->level->name,
                    'name' => $query->name,
                    'period_from' => date('m/d/Y',strtotime($query->period_from)),
                    'period_to' => $period_to,
                    'units_earned' => $query->units_earned,
                    'year_grad' => $query->year_grad,
                    'honors' => $query->honors
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['level'];
                $data_list['f3'] = $r['name'];
                $data_list['f4'] = $r['period_from'];
                $data_list['f5'] = $r['period_to'];
                $data_list['f6'] = $r['units_earned'];
                $data_list['f7'] = $r['year_grad'];
                $data_list['f8'] = $r['honors'];
                if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
                    $button_options = ' <button class="btn btn-info btn-info-scan edit-educ"
                                                data-id="'.$r['id'].'">
                                                <span class="fa fa-edit"></span></button>
                                        <button class="btn btn-danger btn-danger-scan delete-educ"
                                                data-id="'.$r['id'].'">
                                                <span class="fa fa-trash"></span></button>';
                    $data_list['f9'] = $button_options;
                }

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

        $check = _EducationBg::with('level','program')
            ->where('user_id',$id)
            ->where('id',$fid)
            ->first();

        if(!$check){
            return view('layouts/error/404');
        }

        $levels = EducProgramLevel::get();

        $data = array(
            'query' => $check,
            'levels' => $levels
        );
        return view('hrims/employee/information/educInfoEdit',$data);
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

        $check = _EducationBg::where('user_id',$request->sid)
            ->where('id',$request->id)
            ->first();
        if(!$check){
            return  response()->json(['result' => 'error']);
        }

        $check = _EducationBg::where('user_id',$request->sid)
            ->where('id','!=',$request->id)
            ->where('period_from',date('Y-m-d',strtotime($request->period_from)))
            ->first();

        if($check){
            return  response()->json(['result' => 'Already exists!']);
        }

        try{
            $user = Auth::user();
            $user_id = $user->id;

            $school = School::find($request->school);
            $school_id = $school->id;
            $school_name = $school->name;

            if($request->school_check==1){
                $checkSchool = School::where('name',$request->school_name)
                    ->where('shorten',$request->school_shorten)
                    ->first();
                if(!$checkSchool){
                    $insert = new School();
                    $insert->name = $request->school_name;
                    $insert->shorten = $request->school_shorten;
                    $insert->updated_by = $user_id;
                    $insert->save();
                    $school_id = $insert->id;
                    $school_name = $insert->school_name;
                }else{
                    $school_id = $checkSchool->id;
                    $school_name = $checkSchool->name;
                }
            }

            $program_id = $request->program;
            if($request->program_check==1){
                $checkProgram = EducProgramsAll::where('name',$request->program_name)
                    ->first();
                if(!$checkProgram){
                    $insert = new EducProgramsAll();
                    $insert->program_level_id = $request->level;
                    $insert->name = $request->program_name;
                    $insert->updated_by = $user_id;
                    $insert->save();
                    $program_id = $insert->id;
                }else{
                    $program_id = $checkSchool->id;
                }
            }

            $period_to = NULL;
            if($request->present_check==0){
                $period_to = date('Y-m-d',strtotime($request->period_to));
            }

            if($program_id==0){
                $program_id = NULL;
            }

            $update = _EducationBg::find($request->id);
            $update->level_id = $request->level;
            $update->school_id = $school_id;
            $update->name = $school_name;
            $update->program_id = $program_id;
            $update->period_from = date('Y-m-d',strtotime($request->period_from));
            $update->period_to = $period_to;
            $update->units_earned = $request->units_earned;
            $update->year_grad = $request->year_grad;
            $update->honors = $request->honors;
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

        $check = _EducationBg::where('user_id',$id)
            ->where('id',$fid)
            ->first();

        if(!$check){
            return view('layouts/error/404');
        }

        $data = array(
            'query' => $check
        );
        return view('hrims/employee/information/educInfoDelete',$data);
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

        $check = _EducationBg::where('user_id',$id)
            ->where('id',$fid)
            ->first();

        if(!$check){
            return  response()->json(['result' => 'error']);
        }

        $delete = _EducationBg::find($fid);
        $delete->delete();

        DB::statement("ALTER TABLE _education_bg AUTO_INCREMENT = 1;");

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
            'level' => 'required|numeric',
            'school' => 'required|numeric',
            'school_name' => 'nullable|string',
            'school_shorten' => 'nullable|string',
            'program' => 'required|numeric',
            'program_name' => 'nullable|string',
            'period_from' => 'required|date',
            'period_to' => 'nullable|date',
            'units_earned' => 'nullable|string',
            'year_grad' => 'nullable|numeric',
            'honors' => 'nullable|string',
            'present_check' => 'required|numeric',
            'school_check' => 'required|numeric',
            'program_check' => 'required|numeric',
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
