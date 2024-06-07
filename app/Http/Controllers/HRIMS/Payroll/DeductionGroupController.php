<?php

namespace App\Http\Controllers\HRIMS\Payroll;

use App\Http\Controllers\Controller;
use App\Models\EmploymentStatus;
use App\Models\HRadgEmpStat;
use App\Models\HRadgPayrollType;
use App\Models\HRDeduction;
use App\Models\HRDeductionGroup;
use App\Models\HRPayrollType;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class DeductionGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array();
        $query = HRDeductionGroup::orderBy('name')->get()
            ->map(function($query) {
                $emp_stat = '';
                $payroll_type = '';
                if(isset($query->emp_stat)){
                    $emp_stat_array = array();
                    foreach($query->emp_stat as $row){
                        $emp_stat_array[] = $row->emp_stat->name;
                    }
                    $emp_stat = implode(',',$emp_stat_array);
                }
                if(isset($query->payroll_type)){
                    $payroll_type_array = array();
                    foreach($query->payroll_type as $row){
                        $payroll_type_array[] = $row->payroll_type->name;
                    }
                    $payroll_type = implode(',',$payroll_type_array);
                }
                return [
                    'id' => $query->id,
                    'name' => $query->name,
                    'emp_stat' => $emp_stat,
                    'payroll_type' => $payroll_type
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f5'] = $r['emp_stat'];
                $data_list['f6'] = $r['payroll_type'];
                $data_list['f3'] = '<button class="btn btn-primary btn-primary-scan btn-sm update"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-edit"></span>
                                    </button>';
                $data_list['f4'] = '<button class="btn btn-success btn-success-scan btn-sm view"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-eye"></span>
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
        $emp_stat = EmploymentStatus::get();
        $payroll_type = HRPayrollType::get();
        $data = array(
            'emp_stat' => $emp_stat,
            'payroll_type' => $payroll_type
        );
        return view('hrims/payroll/deduction/group/newModal',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data_response = array('result' => 'error');

        // Validate the incoming request data
        $validator = $this->storeValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($data_response);
        }

        $user_access_level = $request->session()->get('user_access_level');
        $user_access_levels = array(1,2,3);

        // Check user access level
        if (!in_array($user_access_level, $user_access_levels)) {
            return  response()->json($data_response);
        }

        $name = mb_strtoupper($request->name);
        $emp_stats = $request->emp_stat;
        $payroll_types = $request->payroll_type;

        $check = HRDeductionGroup::where('name',$name)->first();
        if($check){
            return  response()->json($data_response);
        }

        try{
            $user = Auth::user();
            $updated_by = $user->id;
            $insert = new HRDeductionGroup();
            $insert->name = $name;
            $insert->updated_by = $updated_by;
            $insert->save();
            $group_id = $insert->id;
            foreach($emp_stats as $emp_stat){
                $insert = new HRadgEmpStat();
                $insert->group_id = $group_id;
                $insert->emp_stat_id = $emp_stat;
                $insert->updated_by = $updated_by;
                $insert->save();
            }
            foreach($payroll_types as $payroll_type){
                $insert = new HRadgPayrollType();
                $insert->group_id = $group_id;
                $insert->payroll_type_id = $payroll_type;
                $insert->updated_by = $updated_by;
                $insert->save();
            }

            $data_response = array('result' => 'success');
            return response()->json($data_response);
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
        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $id = $request->id;
        $query = HRDeductionGroup::where('id',$id)->first();

        if($query==NULL){
            return view('layouts/error/404');
        }

        $data = array(
            'query' => $query
        );
        return view('hrims/payroll/deduction/group/viewModal',$data);
    }

    /**
     * Display the specified resource.
     */
    public function showTable(Request $request)
    {
        $data_response = array('result' => 'error');

        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($data_response);
        }

        $data = array();
        $query = HRDeduction::where('group_id',$request->id)->get()
            ->map(function($query) {
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
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $id = $request->id;
        $query = HRDeductionGroup::where('id',$id)->first();

        if ($query==NULL) {
            return view('layouts/error/404');
        }

        $emp_stat = EmploymentStatus::get();
        $payroll_type = HRPayrollType::get();

        $data = array(
            'query' => $query,
            'emp_stat' => $emp_stat,
            'payroll_type' => $payroll_type
        );
        return view('hrims/payroll/deduction/group/updateModal',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $data_response = array('result' => 'error');

        // Validate the incoming request data
        $validator = $this->updateValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($data_response);
        }

        $user_access_level = $request->session()->get('user_access_level');
        $user_access_levels = array(1,2,3);

        // Check user access level
        if (!in_array($user_access_level, $user_access_levels)) {
            return  response()->json($data_response);
        }

        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $name = mb_strtoupper($request->name);
        $emp_stats = $request->emp_stat;
        $payroll_types = $request->payroll_type;

        $check = HRDeductionGroup::where('id','!=',$id)
            ->where(function ($query) use ($name) {
                $query->where('name',$name);
            })->first();
        if($check){
            return  response()->json($data_response);
        }

        try{
            HRDeductionGroup::where('id', $id)
                ->update([
                    'name' => $name,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            $delete = HRadgEmpStat::whereNotIn('emp_stat_id', $emp_stats)
                ->where('group_id', $id)->delete();
            $auto_increment = DB::update("ALTER TABLE hr_adg_emp_stat AUTO_INCREMENT = 1;");

            $deductions = HRDeduction::where('group_id',$id)->pluck('id')->toArray();

            foreach($emp_stats as $emp_stat){
                $check = HRadgEmpStat::where('emp_stat_id',$emp_stat)
                    ->where('group_id',$id)->first();
                if($check==NULL){
                    $insert = new HRadgEmpStat();
                    $insert->group_id = $id;
                    $insert->emp_stat_id = $emp_stat;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                }
                if(count($deductions)>0){
                    foreach($deductions as $deduction_id){
                        $delete = HRadgEmpStat::where('deduction_id', $deduction_id)
                            ->whereNotIn('emp_stat_id',$emp_stats)->delete();
                        $auto_increment = DB::update("ALTER TABLE hr_adg_emp_stat AUTO_INCREMENT = 1;");
                        $check = HRadgEmpStat::where('emp_stat_id',$emp_stat)
                            ->where('deduction_id',$deduction_id)->first();
                        if($check==NULL){
                            $insert = new HRadgEmpStat();
                            $insert->deduction_id = $deduction_id;
                            $insert->emp_stat_id = $emp_stat;
                            $insert->updated_by = $updated_by;
                            $insert->save();
                        }
                    }
                }
            }

            $delete = HRadgPayrollType::whereNotIn('payroll_type_id', $payroll_types)
                ->where('group_id', $id)->delete();
            $auto_increment = DB::update("ALTER TABLE hr_adg_payroll_type AUTO_INCREMENT = 1;");

            foreach($payroll_types as $payroll_type){
                $check = HRadgPayrollType::where('payroll_type_id',$payroll_type)
                    ->where('group_id',$id)->first();
                if($check==NULL){
                    $insert = new HRadgPayrollType();
                    $insert->payroll_type_id = $payroll_type;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                }
                if(count($deductions)>0){
                    foreach($deductions as $deduction_id){
                        $delete = HRadgPayrollType::where('deduction_id', $deduction_id)
                            ->whereNotIn('payroll_type_id',$payroll_types)->delete();
                        $auto_increment = DB::update("ALTER TABLE hr_adg_payroll_type AUTO_INCREMENT = 1;");
                        $check = HRadgPayrollType::where('payroll_type_id',$payroll_type)
                            ->where('deduction_id',$deduction_id)->first();
                        if($check==NULL){
                            $insert = new HRadgPayrollType();
                            $insert->deduction_id = $deduction_id;
                            $insert->payroll_type_id = $payroll_type;
                            $insert->updated_by = $updated_by;
                            $insert->save();
                        }
                    }
                }
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
            // 'emp_stats' => 'required|array',
            // 'payroll_types' => 'required|array'
        ];

        $customMessages = [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            // 'emp_stats.required' => 'Employment Status is required',
            // 'emp_stats.array' => 'Employment Status must be an array',
            // 'payroll_types.required' => 'Payroll Type is required',
            // 'payroll_types.array' => 'Payroll Type must be an array',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function updateValidateRequest($request)
    {
        $rules = [
            'id' => 'required|numeric',
            'name' => 'required|string',
            // 'emp_stats' => 'required|array',
            // 'payroll_types' => 'required|array'
        ];

        $customMessages = [
            'id.required' => 'ID is required',
            'id.numeric' => 'ID must be a number',
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            // 'emp_stats.required' => 'Employment Status is required',
            // 'emp_stats.array' => 'Employment Status must be an array',
            // 'payroll_types.required' => 'Payroll Type is required',
            // 'payroll_types.array' => 'Payroll Type must be an array',
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
