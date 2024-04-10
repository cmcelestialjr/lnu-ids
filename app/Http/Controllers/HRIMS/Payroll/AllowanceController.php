<?php

namespace App\Http\Controllers\HRIMS\Payroll;

use App\Http\Controllers\Controller;
use App\Models\EmploymentStatus;
use App\Models\HRadgEmpStat;
use App\Models\HRadgPayrollType;
use App\Models\HRAllowance;
use App\Models\HRPayrollType;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class AllowanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array();
        $query = HRAllowance::get()
            ->map(function($query) {
                $amount = '';
                if($query->amount!=NULL){
                    $amount = number_format($query->amount,2);
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
                    'amount' => $amount,
                    'monthly' => $query->monthly,
                    'emp_stat' => $emp_stat,
                    'payroll_type' => $payroll_type
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['amount'];
                $data_list['f4'] = $r['monthly'];
                $data_list['f5'] = $r['emp_stat'];
                $data_list['f6'] = $r['payroll_type'];
                $data_list['f7'] = '<button class="btn btn-primary btn-primary-scan btn-sm update"
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
        $emp_stat = EmploymentStatus::get();
        $payroll_type = HRPayrollType::get();
        $data = array(
            'emp_stat' => $emp_stat,
            'payroll_type' => $payroll_type
        );
        return view('hrims/payroll/allowance/newModal',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $response_data = array('result' => 'error');

        // Validate the incoming request data
        $validator = $this->storeValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($response_data);
        }

        $user_access_level = $request->session()->get('user_access_level');
        $user_access_levels = array(1,2,3);

        // Check user access level
        if (!in_array($user_access_level, $user_access_levels)) {
            return  response()->json($response_data);
        }

        $name = mb_strtoupper($request->name);
        $amount = $request->amount;
        $monthly = $request->monthly;
        $check = HRAllowance::where(function ($query) use ($name) {
                $query->where('name',$name);
            })->first();
        if($check==NULL){
            $user = Auth::user();
            $updated_by = $user->id;

            try{
                $insert = new HRAllowance(); 
                $insert->name = $name;
                $insert->amount = $amount;
                $insert->monthly = $monthly;
                $insert->updated_by = $updated_by;
                $insert->save();
                $allowance_id = $insert->id;
                foreach($request->emp_stat as $emp_stat){
                    $insert = new HRadgEmpStat(); 
                    $insert->allowance_id = $allowance_id;
                    $insert->emp_stat_id = $emp_stat;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                }
                foreach($request->payroll_type as $payroll_type){
                    $insert = new HRadgPayrollType(); 
                    $insert->allowance_id = $allowance_id;
                    $insert->payroll_type_id = $payroll_type;
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
        $response = array('result' => 'success'
                        );
        return response()->json($response);
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

        $query = HRAllowance::where('id',$request->id)->first();

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
        return view('hrims/payroll/allowance/updateModal',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $response_data = array('result' => 'error');

        // Validate the incoming request data
        $validator = $this->storeValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($response_data);
        }
        
        $user_access_level = $request->session()->get('user_access_level');
        $user_access_levels = array(1,2,3);

        // Check user access level
        if (!in_array($user_access_level, $user_access_levels)) {
            return  response()->json($response_data);
        }

        $allowance_id = $request->id;
        $name = mb_strtoupper($request->name);
        $amount = $request->amount;
        $monthly = $request->monthly;
        $check = HRAllowance::where('id','!=',$allowance_id)
                ->where(function ($query) use ($name) {
                    $query->where('name',$name);
            })->first();
        if($check==NULL){
            $user = Auth::user();
            $updated_by = $user->id;
                HRAllowance::where('id', $allowance_id)
                ->update([
                    'name' => $name,
                    'amount' => $amount,
                    'monthly' => $monthly,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            $delete = HRadgEmpStat::whereNotIn('emp_stat_id', $request->emp_stat)
                ->where('allowance_id', $allowance_id)->delete();
            $auto_increment = DB::update("ALTER TABLE hr_adg_emp_stat AUTO_INCREMENT = 0;");
            foreach($request->emp_stat as $emp_stat){
                $check = HRadgEmpStat::where('emp_stat_id',$emp_stat)
                    ->where('allowance_id',$allowance_id)->first();
                if($check==NULL){
                    $insert = new HRadgEmpStat(); 
                    $insert->allowance_id = $allowance_id;
                    $insert->emp_stat_id = $emp_stat;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                }
            }
            $delete = HRadgPayrollType::whereNotIn('payroll_type_id', $request->payroll_type)
                ->where('allowance_id', $allowance_id)->delete();
            $auto_increment = DB::update("ALTER TABLE hr_adg_payroll_type AUTO_INCREMENT = 0;");
            foreach($request->payroll_type as $payroll_type){
                $check = HRadgPayrollType::where('payroll_type_id',$payroll_type)
                    ->where('allowance_id',$allowance_id)->first();
                if($check==NULL){
                    $insert = new HRadgPayrollType();
                    $insert->allowance_id = $allowance_id;
                    $insert->payroll_type_id = $payroll_type;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                }
            }
        }
        $response = array('result' => 'success');
        return response()->json($response);
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
    private function storeValidateRequest($request)
    {
        $rules = [
            'name' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'monthly' => 'nullable|string'
        ];
        
        $customMessages = [
            'name.string' => 'Name must be a string',
            'amount.numeric' => 'Amount must be a number',
            'monthly.string' => 'Monthly must be a string',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
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
