<?php

namespace App\Http\Controllers\HRIMS\Payroll;

use App\Http\Controllers\Controller;
use App\Models\_Work;
use App\Models\HRDeduction;
use App\Models\HRDeductionEmployee;
use App\Models\HRPayroll;
use App\Models\HRPayrollAllowance;
use App\Models\HRPayrollDeduction;
use App\Models\HRPayrollList;
use App\Models\HRPayrollMonths;
use App\Models\HRPayrollType;
use App\Models\HRPayrollTypeGuideline;
use App\Models\Tracking;
use App\Services\NameServices;
use App\Services\PayrollUpdateServices;
use App\Services\WorkServices;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class PayrollDeductionController extends Controller
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
        $query = HRPayrollList::find($id);

        if($query==NULL){
            return view('layouts/error/404');
        }
        $payroll_update_services = new PayrollUpdateServices;
        $salaries = _Work::where('user_id',$query->user_id)
            ->where('emp_stat_id',$query->emp_stat_id)
            ->groupBy('salary')
            ->pluck('salary')
            ->toArray();
        $allowance = $query->allowance; 
        $deductions = $query->deductions; 
        $per_salary = $payroll_update_services->getPerSalary($query->salary);
        $data = array(
            'query' => $query,
            'salaries' => $salaries,
            'allowance' => $allowance,
            'deductions' => $deductions,
            'per_salary' => $per_salary
        );
        return view('hrims/payroll/view/deductionModal',$data);
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
    public function show(Request $request)
    {
        $data = array();

        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($data);
        }

        $id = $request->id;
        $employee = HRPayrollList::find($id);

        if ($employee==NULL) {
            return  response()->json($data);
        }
        
        $emp_stat = $employee->emp_stat_id;
        $query = HRDeduction::orderBy('group_id')
                ->whereHas('emp_stat', function ($query) use ($emp_stat) {
                    $query->where('emp_stat_id',$emp_stat);
                })
                ->orderBY('group_id')
                ->orderBy('id')
                ->get()
                ->map(function($query) use ($id,$employee){
                    $employee_deduction = HRPayrollDeduction::where('payroll_list_id',$id)
                        ->where('deduction_id',$query->id)
                        ->first();
                    $employee_deduction_main = HRDeductionEmployee::where('user_id',$employee->user_id)
                        ->where('payroll_type_id',$employee->payroll->payroll_type_id)
                        ->where('emp_stat_id',$employee->emp_stat_id)
                        ->where('deduction_id',$query->id)
                        ->first();
                    $group = $query->group_id ? $query->group->name : '';
                    $amount = NULL;
                    $date_from = NULL;
                    $date_to = NULL;
                    $docs = NULL;
                    $remarks = NULL;                    
                    if($employee_deduction){
                        $amount = $employee_deduction->amount;                        
                    }
                    if($employee_deduction_main){
                        $date_from = $employee_deduction_main->date_from;
                        $date_to = $employee_deduction_main->date_to;
                        $remarks = $employee_deduction_main->remarks;
                        $docs = $employee_deduction_main->docs;
                    }
                    if($date_from){
                        $date_from = date('m/d/Y',strtotime($date_from));
                    }
                    if($date_to){
                        $date_to = date('m/d/Y',strtotime($date_to));
                    }
                    
                    return [
                        'id' => $query->id,
                        'name' => $query->name,
                        'group' => $group,
                        'amount' => $amount,
                        'date_from' => $date_from,
                        'date_to' => $date_to,
                        'docs' => $docs,
                        'remarks' => $remarks,
                    ];
                })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $docs = '';
                if($r['docs']){
                    if(count($r['docs'])>0){
                        $docs = '<button class="btn btn-success btn-success-scan btn-xs docs"
                        data-id="'.$r['id'].'"><span class="fa fa-file"></span></button>';
                    }
                }
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['group'];
                $data_list['f4'] = '<input type="number" class="form-control input"
                    data-did="'.$r['id'].'"
                    value="'.$r['amount'].'">';
                $data_list['f5'] = $r['date_from'];
                $data_list['f6'] = $r['date_to'];
                $data_list['f7'] = $docs;
                $data_list['f8'] = $r['remarks'];
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
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
    public function update(Request $request)
    {
        $data = array('result' => 'error');

        // Validate the incoming request data
        $validator = $this->updateValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($data);
        }

        $user_access_level = $request->session()->get('user_access_level');
        $user_access_levels = array(1,2,3);

        // Check user access level
        if (!in_array($user_access_level, $user_access_levels)) {
            return  response()->json($data);
        }

        $payroll_update_services = new PayrollUpdateServices;
        $values = NULL;        
        $id = $request->id;
        $did = $request->did;
        $amount = $request->amount;

        $query = HRPayrollList::find($id);
        if ($query==NULL) {
            return  response()->json($data);
        }
            
        $user = Auth::user();
        $updated_by = $user->id;

        try{ 
            if($amount>0){
                $percent = NULL;
                $percent_employer = NULL;
                $ceiling = NULL;

                $deduction = HRDeductionEmployee::where('user_id',$query->user_id)
                    ->where('deduction_id',$did)
                    ->where('payroll_type_id',$query->payroll->payroll_type_id)
                    ->where('emp_stat_id',$query->emp_stat_id)
                    ->first();               

                if($deduction){
                    $percent = $deduction->percent;
                    $percent_employer = $deduction->percent_employer;
                    $ceiling = $deduction->ceiling;
                }
                // Update or create the deduction employee record
                HRPayrollDeduction::updateOrCreate(
                    [
                        'payroll_list_id' => $id,
                        'deduction_id' => $did,
                    ],
                    [
                        'payroll_id' => $query->payroll_id,
                        'user_id' => $query->user_id,
                        'amount' => $amount,
                        'percent' => $percent,
                        'percent_employer' => $percent_employer,
                        'ceiling' => $ceiling,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]
                );
            }else{
                $delete = HRPayrollDeduction::where('payroll_list_id', $id)
                    ->where('deduction_id',$did)->delete();                    
                $auto_increment = DB::update("ALTER TABLE `hr_payroll_deduction` AUTO_INCREMENT = 0;");
            }

            $values = $payroll_update_services->updatePayrollList($id,$updated_by);

            return response()->json(['result' => 'success', 
                                     'values' => $values]);
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
    private function updateValidateRequest($request)
    {
        $rules = [
            'id' => 'required|numeric',
            'did' => 'required|numeric',
            'amount' => 'nullable|numeric'
        ];
        
        $customMessages = [
            'id.required' => 'ID is required',
            'id.numeric' => 'ID must be a number',
            'did.required' => 'DID is required',
            'did.numeric' => 'DID must be a number',
            'amount.numeric' => 'Amount ID must be a number',
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
