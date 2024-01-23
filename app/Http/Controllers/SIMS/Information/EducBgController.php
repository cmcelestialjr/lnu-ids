<?php

namespace App\Http\Controllers\SIMS\Information;

use App\Http\Controllers\Controller;
use App\Models\_EducationBg;
use App\Models\EducProgramLevel;
use App\Models\EducPrograms;
use App\Models\EducProgramsAll;
use App\Models\School;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class EducBgController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $program_level = EducProgramLevel::orderBy('id','DESC')->get();
        $data = array(
            'program_level' => $program_level
        );
        return view('sims/information/educBgNewModal',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->storeValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => 'error']);
        }

        $user = Auth::user();
        $user_id = $user->id;

        $level = $request->level;
        $school = $request->school;
        $check_school = $request->check_school;
        $new_school = trim($request->new_school);
        $program = $request->program;
        $check_program = $request->check_program;
        $new_program = trim($request->new_program);
        $period_from = date('Y-m-d',strtotime($request->period_from));
        $period_to = date('Y-m-d',strtotime($request->period_to));
        $period_to_present = $request->period_to_present;
        $units_earned = $request->units_earned;
        $year_grad = $request->year_grad;
        $honors = $request->honors;       

        $check = _EducationBg::where('user_id',$user_id)
            ->where('period_from',$period_from)
            ->first();        
        // Check if exists
        if ($check) {
            return response()->json(['result' => 'Period from already exists!']);
        }
   
        $get_program_level = EducProgramLevel::find($level);
        // Check if exists
        if ($get_program_level==NULL) {
            return response()->json(['result' => 'error']);
        }

        DB::beginTransaction();
        try{
            $p = $get_program_level->program;

            if($p=='w'){
                if($check_program==1){
                    $check_program_all = EducProgramsAll::where('name',$new_program)
                        ->first();
                    if($check_program_all){
                        $program = $check_program_all->id;
                    }else{
                        $insert = new EducProgramsAll();
                        $insert->program_level_id = $level;
                        $insert->name = $new_program;
                        $insert->updated_by = $user_id;
                        $insert->save();
                        $program = $insert->id;
                    }
                }else{
                    if($school>2){
                        $check_program_all = EducProgramsAll::where('id',$program)
                            ->first();
                        $program_name = $check_program_all->name;
                    }else{
                        $check_program = EducPrograms::find($program);
                        // Check if exists
                        if ($check_program==NULL) {
                            return response()->json(['result' => 'error']);
                        }
                        $program_name = $check_program->name;
                        
                        $check_program_all = EducProgramsAll::where('program_id',$program)
                            ->first();
                    }
                    if($check_program_all){
                        $program = $check_program_all->id;
                    }else{
                        $insert = new EducProgramsAll();
                        $insert->program_level_id = $level;
                        $insert->program_id = $program;
                        $insert->name = $program_name;
                        $insert->updated_by = $user_id;
                        $insert->save();
                        $program = $insert->id;
                    }
                }            
            }

            if($check_school==1){
                $check = School::where('name',$new_school)
                    ->first();
                if($check){
                    $school = $check->id;
                }else{
                    $insert = new School();
                    $insert->name = $new_school;
                    $insert->updated_by = $user_id;
                    $insert->save();
                    $school = $insert->id;
                }            
            }

            $getSchoolName = School::find($school);
            $school_name = $getSchoolName->name;

            if($year_grad==''){
                $year_grad = NULL;
            }

            if($honors==''){
                $honors = NULL;
            }

            if($period_to_present==1){
                $period_to = 'present';
            }

        

            $insert = new _EducationBg();
            $insert->user_id = $user_id;
            $insert->level_id = $level;
            $insert->school_id = $school;
            $insert->program_id = $program;
            $insert->name = $school_name;
            $insert->period_from = $period_from;
            $insert->period_to = $period_to;
            $insert->units_earned = $units_earned;
            $insert->year_grad = $year_grad;
            $insert->honors = $honors;
            $insert->updated_by = $user_id;
            $insert->save();
            // Commit the database transaction
            DB::commit();
            // Set the result to 'success'
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
    public function show(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $educ_bg = EducProgramLevel::with(['education_bg' => function ($query) use ($user_id){
                $query->where('user_id',$user_id);
            }])
            ->whereHas('education_bg', function ($query) use ($user_id) {
                $query->where('user_id',$user_id);
            })->orderBy('id','DESC')->get();

        $data = array(    
            'educ_bg' => $educ_bg
        );
        return view('sims/information/informationEducBg',$data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $user = Auth::user();
        $user_id = $user->id;

        $id = $request->id;

        $educ = _EducationBg::with('program','level')
            ->where('id',$id)
            ->where('user_id',$user_id)
            ->first();

        if($educ==NULL){
            return view('layouts/error/404');
        }

        $program_level = EducProgramLevel::orderBy('id','DESC')->get();
        
        $program_hide = 'hide';
        if($educ->level->program=='w'){
            $program_hide = '';
        }
        $data = array(
            'program_level' => $program_level,
            'id' => $id,
            'educ' => $educ,
            'program_hide' => $program_hide
        );
        return view('sims/information/educBgEditModal',$data);
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
            return response()->json(['result' => 'error']);
        }

        $user = Auth::user();
        $user_id = $user->id;

        $educ_id = $request->educ_id;
        $level = $request->level;
        $school = $request->school;
        $check_school = $request->check_school;
        $new_school = trim($request->new_school);
        $program = $request->program;
        $check_program = $request->check_program;
        $new_program = trim($request->new_program);
        $period_from = date('Y-m-d',strtotime($request->period_from));
        $period_to = date('Y-m-d',strtotime($request->period_to));
        $period_to_present = $request->period_to_present;
        $units_earned = $request->units_earned;
        $year_grad = $request->year_grad;
        $honors = $request->honors;       
        
        $check = _EducationBg::where('id',$educ_id)
            ->where('user_id',$user_id)
            ->first();
        // Check if exists
        if ($check==NULL) {
            return response()->json(['result' => 'Error!']);
        }

        $check = _EducationBg::where('user_id',$user_id)
            ->where('period_from',$period_from)
            ->where('id','<>',$educ_id)
            ->first();        
        // Check if exists
        if ($check) {
            return response()->json(['result' => 'Period from already exists!']);
        }
   
        $get_program_level = EducProgramLevel::find($level);
        // Check if exists
        if ($get_program_level==NULL) {
            return response()->json(['result' => 'error']);
        }

        DB::beginTransaction();
        try{
            $p = $get_program_level->program;

            if($p=='w'){
                if($check_program==1){
                    $check_program_all = EducProgramsAll::where('name',$new_program)
                        ->first();
                    if($check_program_all){
                        $program = $check_program_all->id;
                    }else{
                        $insert = new EducProgramsAll();
                        $insert->program_level_id = $level;
                        $insert->name = $new_program;
                        $insert->updated_by = $user_id;
                        $insert->save();
                        $program = $insert->id;
                    }
                }else{
                    if($school>2){
                        $check_program_all = EducProgramsAll::where('id',$program)
                            ->first();
                        $program_name = $check_program_all->name;
                    }else{
                        $check_program = EducPrograms::find($program);
                        // Check if exists
                        if ($check_program==NULL) {
                            return response()->json(['result' => 'error']);
                        }
                        $program_name = $check_program->name;
                        
                        $check_program_all = EducProgramsAll::where('program_id',$program)
                            ->first();
                    }
                    if($check_program_all){
                        $program = $check_program_all->id;
                    }else{
                        $insert = new EducProgramsAll();
                        $insert->program_level_id = $level;
                        $insert->program_id = $program;
                        $insert->name = $program_name;
                        $insert->updated_by = $user_id;
                        $insert->save();
                        $program = $insert->id;
                    }
                }            
            }

            if($check_school==1){
                $check = School::where('name',$new_school)
                    ->first();
                if($check){
                    $school = $check->id;
                }else{
                    $insert = new School();
                    $insert->name = $new_school;
                    $insert->updated_by = $user_id;
                    $insert->save();
                    $school = $insert->id;
                }            
            }

            $getSchoolName = School::find($school);
            $school_name = $getSchoolName->name;

            if($year_grad==''){
                $year_grad = NULL;
            }

            if($honors==''){
                $honors = NULL;
            }

            if($period_to_present==1){
                $period_to = 'present';
            }
        
            // Update the program details in the database
            _EducationBg::where('id', $educ_id)
                ->update([
                    'level_id' => $level,
                    'school_id' => $school,
                    'program_id' => $program,
                    'name' => $school_name,
                    'period_from' => $period_from,
                    'period_to' => $period_to,
                    'units_earned' => $units_earned,
                    'year_grad' => $year_grad,
                    'honors' => $honors,
                    'updated_by' => $user_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            // Commit the database transaction
            DB::commit();
            // Set the result to 'success'
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
     * Show the reponse for delete the specified resource.
     */
    public function delete(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $user = Auth::user();
        $user_id = $user->id;

        $id = $request->id;

        $educ = _EducationBg::with('program','level')
            ->where('id',$id)
            ->where('user_id',$user_id)->first();

        if($educ==NULL){
            return view('layouts/error/404');
        }

        $data = array(
            'id' => $id,
            'educ' => $educ
        );
        return view('sims/information/educBgDeleteModal',$data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => 'error']);
        }

        $user = Auth::user();
        $user_id = $user->id;

        $id = $request->id;

        $check = _EducationBg::where('id',$id)
            ->where('user_id',$user_id)
            ->first();
        // Check if exists
        if ($check==NULL) {
            return response()->json(['result' => 'Error!']);
        }

        try{

            $delete = _EducationBg::where('id', $id)->delete();
            $auto_increment = DB::update("ALTER TABLE _education_bg AUTO_INCREMENT = 0;");

            // Set the result to 'success'
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
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function idValidateRequest(Request $request)
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
    private function storeValidateRequest(Request $request)
    {
        $rules = [
            'level' => 'required|numeric',
            'school' => 'nullable|numeric',
            'new_school' => 'nullable|string',
            'check_school' => 'required|numeric',
            'p' => 'nullable|string',
            'program' => 'nullable|numeric',
            'new_program' => 'nullable|string',
            'check_program' => 'required|numeric',
            'period_from' => 'required|date',
            'period_to' => 'required|date',
            'period_to_present' => 'required|numeric',
            'units_earned' => 'nullable|string',
            'year_grad' => 'nullable|numeric|digits:4',
            'honors' => 'nullable|string',
        ];

        $customMessages = [
            'level.required' => 'Level is required',
            'level.numeric' => 'Level must be a number',
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
            'educ_id' => 'required|numeric',
            'level' => 'required|numeric',
            'school' => 'nullable|numeric',
            'new_school' => 'nullable|string',
            'check_school' => 'required|numeric',
            'p' => 'nullable|string',
            'program' => 'nullable|numeric',
            'new_program' => 'nullable|string',
            'check_program' => 'required|numeric',
            'period_from' => 'required|date',
            'period_to' => 'required|date',
            'period_to_present' => 'required|numeric',
            'units_earned' => 'nullable|string',
            'year_grad' => 'nullable|numeric|digits:4',
            'honors' => 'nullable|string',
        ];

        $customMessages = [
            'educ_id.required' => 'ID is required',
            'educ_id.numeric' => 'ID must be a number',
            'level.required' => 'Level is required',
            'level.numeric' => 'Level must be a number',
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
