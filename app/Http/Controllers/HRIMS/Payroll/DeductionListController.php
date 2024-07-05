<?php

namespace App\Http\Controllers\HRIMS\Payroll;

use App\Http\Controllers\Controller;
use App\Models\EmploymentStatus;
use App\Models\HRadgEmpStat;
use App\Models\HRadgPayrollType;
use App\Models\HRDeduction;
use App\Models\HRDeductionEmployee;
use App\Models\HRDeductionGroup;
use App\Models\HRPayrollType;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class DeductionListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array();
        $query = HRDeduction::orderBy('group_id')->get()
            ->map(function($query) {
                $group = '';
                if($query->group_id!=NULL){
                    $group = $query->group->name;
                }
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
                    'group' => $group,
                    'emp_stat' => $emp_stat,
                    'payroll_type' => $payroll_type
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['group'];
                $data_list['f5'] = $r['emp_stat'];
                $data_list['f6'] = $r['payroll_type'];
                $data_list['f4'] = '<button class="btn btn-primary btn-primary-scan btn-sm update"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-edit"></span>
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
        $group = HRDeductionGroup::get();
        $emp_stat = EmploymentStatus::get();
        $payroll_type = HRPayrollType::get();
        $data = array(
            'group' => $group,
            'emp_stat' => $emp_stat,
            'payroll_type' => $payroll_type
        );
        return view('hrims/payroll/deduction/list/newModal',$data);
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
            return  response()->json(['result' => 'error']);
        }

        $user_access_level = $request->session()->get('user_access_level');
        $user_access_levels = array(1,2,3);

        // Check user access level
        if (!in_array($user_access_level, $user_access_levels)) {
            return  response()->json(['result' => 'error']);
        }

        $user = Auth::user();
        $updated_by = $user->id;
        $name = mb_strtoupper($request->name);
        $group = $request->group;
        $payroll_type = $request->payroll_type;
        $emp_stat = $request->emp_stat;

        if($group=='None'){
            $group = NULL;
        }

        $check = HRDeduction::where('name',$name)
            ->where('group_id',$group)->first();
        if($check){
            return  response()->json(['result' => 'error']);
        }

        try{
            $insert = new HRDeduction();
            $insert->name = $name;
            $insert->group_id = $group;
            $insert->updated_by = $updated_by;
            $insert->save();
            $deduction_id = $insert->id;
            if($group!=NULL){
                $payroll_type = HRadgEmpStat::where('group_id',$group)->pluck('payroll_type_id')->toArray();
                $emp_stat = HRadgEmpStat::where('group_id',$group)->pluck('emp_stat_id')->toArray();
            }
            foreach($payroll_type as $payroll_type_id){
                $insert = new HRadgPayrollType();
                $insert->deduction_id = $deduction_id;
                $insert->payroll_type_id = $payroll_type_id;
                $insert->updated_by = $updated_by;
                $insert->save();
            }
            foreach($emp_stat as $emp_stat_id){
                $insert = new HRadgEmpStat();
                $insert->deduction_id = $deduction_id;
                $insert->emp_stat_id = $emp_stat_id;
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
    public function show(string $id)
    {
        //
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
        $query = HRDeduction::where('id',$id)->first();

        if ($query==NULL) {
            return view('layouts/error/404');
        }

        $group = HRDeductionGroup::get();
        $emp_stat = EmploymentStatus::get();
        $payroll_type = HRPayrollType::get();
        $data = array(
            'query' => $query,
            'group' => $group,
            'emp_stat' => $emp_stat,
            'payroll_type' => $payroll_type
        );
        return view('hrims/payroll/deduction/list/updateModal',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $data_response = array('result' => 'error');

        // // Validate the incoming request data
        // $validator = $this->updateValidateRequest($request);

        // // Check if validation fails
        // if ($validator->fails()) {
        //     return  response()->json($data_response);
        // }

        $user_access_level = $request->session()->get('user_access_level');
        $user_access_levels = array(1,2,3);

        // Check user access level
        if (!in_array($user_access_level, $user_access_levels)) {
            return  response()->json($data_response);
        }

        $user = Auth::user();
        $updated_by = $user->id;
        $name = mb_strtoupper($request->name);
        $id = $request->id;
        $name = $request->name;
        $group = $request->group;
        $percent = $request->percent;
        $percent_employer = $request->percent_employer;
        $ceiling = $request->ceiling;
        $emp_stat = $request->emp_stat;
        $payroll_type = $request->payroll_type;

        if($group=='None'){
            $group = NULL;
        }
        $check = HRDeduction::where('id','!=',$id)
            ->where(function ($query) use ($name,$group) {
                $query->where('name',$name)
                ->where('group_id',$group);
            })->first();
        if($check){
            return  response()->json($data_response);
        }

        try{
            $user = Auth::user();
            $updated_by = $user->id;
            HRDeduction::where('id', $id)
                ->update([
                    'name' => $name,
                    'group_id' => $group,
                    'percent' => $percent,
                    'percent_employer' => $percent_employer,
                    'ceiling' => $ceiling,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            HRDeductionEmployee::where('deduction_id', $id)
                ->update([
                    'percent' => $percent,
                    'percent_employer' => $percent_employer,
                    'ceiling' => $ceiling,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            if($group!=NULL){
                $emp_stat = HRadgEmpStat::where('group_id',$group)->pluck('emp_stat_id')->toArray();
                $payroll_type = HRadgPayrollType::where('group_id',$group)->pluck('payroll_type_id')->toArray();
            }

            $delete = HRadgEmpStat::whereNotIn('emp_stat_id', $emp_stat)
                ->where('deduction_id', $id)->delete();
            $auto_increment = DB::update("ALTER TABLE `hr_adg_emp_stat` AUTO_INCREMENT = 0;");

            foreach($emp_stat as $emp_stat_id){
                $check = HRadgEmpStat::where('emp_stat_id',$emp_stat)
                    ->where('deduction_id',$id)->first();
                if($check==NULL){
                    $insert = new HRadgEmpStat();
                    $insert->deduction_id = $id;
                    $insert->group_id = NULL;
                    $insert->emp_stat_id = $emp_stat_id;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                }
            }

            $delete = HRadgPayrollType::whereNotIn('payroll_type_id', $payroll_type)
                ->where('deduction_id', $id)->delete();
            $auto_increment = DB::update("ALTER TABLE `hr_adg_payroll_type` AUTO_INCREMENT = 0;");

            foreach($payroll_type as $payroll_type_id){
                $check = HRadgPayrollType::where('payroll_type_id',$payroll_type)
                    ->where('deduction_id',$id)->first();
                if($check==NULL){
                    $insert = new HRadgPayrollType();
                    $insert->deduction_id = $id;
                    $insert->group_id = NULL;
                    $insert->payroll_type_id = $payroll_type_id;
                    $insert->updated_by = $updated_by;
                    $insert->save();
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
            'group' => 'nullable|numeric',
            'emp_stat' => 'required|array',
            'payroll_type' => 'required|array'
        ];

        $customMessages = [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'group.numeric' => 'Group must be a number',
            'emp_stat.required' => 'Employment Status is required',
            'emp_stat.array' => 'Employment Status must be an array',
            'payroll_type.required' => 'Payroll Type is required',
            'payroll_type.array' => 'Payroll Type must be an array',
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
            'group' => 'nullable|numeric',
            'emp_stats' => 'required|array',
            'payroll_types' => 'required|array',
            'percent' => 'nullable|numeric',
            'percent_employer' => 'nullable|numeric',
            'ceiling' => 'nullable|numeric',
        ];

        $customMessages = [
            'id.required' => 'ID is required',
            'id.numeric' => 'ID must be a number',
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'group.numeric' => 'Group must be a number',
            'emp_stats.required' => 'Employment Status is required',
            'emp_stats.array' => 'Employment Status must be an array',
            'payroll_types.required' => 'Payroll Type is required',
            'payroll_types.array' => 'Payroll Type must be an array',
            'percent.numeric' => 'Percent must be a number',
            'percent_employer.numeric' => 'Percent Employer must be a number',
            'ceiling.numeric' => 'Ceiling must be a number',
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
