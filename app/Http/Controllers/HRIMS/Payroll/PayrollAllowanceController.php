<?php

namespace App\Http\Controllers\HRIMS\Payroll;

use App\Http\Controllers\Controller;
use App\Models\HRAllowance;
use App\Models\HRPayrollList;
use App\Models\HRPayrollAllowance;
use App\Services\PayrollUpdateServices;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class PayrollAllowanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = array();

        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($data);
        }

        $id = $request->id;
        $payroll = HRPayrollList::find($id);

        if ($payroll==NULL) {
            return  response()->json($data);
        }

        $payroll_type = $payroll->payroll->payroll_type_id;
        $emp_stat = $payroll->emp_stat_id;
        $query = HRAllowance::
            whereHas('payroll_type', function ($query) use ($payroll_type) {
                    $query->where('payroll_type_id',$payroll_type);
            })
            ->whereHas('emp_stat', function ($query) use ($emp_stat) {
                $query->where('emp_stat_id',$emp_stat);
            });
        if($payroll->payroll->include_pera=='No'){
            $query = $query->where('id','>',1);
        }
        $query = $query->get()
            ->map(function($query) use ($id){
                $check = HRPayrollAllowance::where('payroll_list_id',$id)
                    ->where('allowance_id',$query->id)
                    ->first();
                $checked = '';
                if($check){
                    $checked = 'checked';
                }
                return [
                    'id' => $query->id,
                    'name' => $query->name,
                    'amount' => $query->amount,
                    'checked' => $checked
                ];
            })->toArray();
            if(count($query)>0){
                $x = 1;
                foreach($query as $r){
                    $data_list['f1'] = $x;
                    $data_list['f2'] = $r['name'];
                    $data_list['f3'] = $r['amount'];
                    $data_list['f4'] = '<input type="checkbox" class="form-control allowance" data-id="'.$r['id'].'" '.$r['checked'].'>';
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
        $aid = $request->aid;
        $check = $request->check;

        $query = HRPayrollList::find($id);

        if($query==NULL){
            return  response()->json($data);
        }

        $user = Auth::user();
        $updated_by = $user->id;

        try{
            if($check=='yes' && $query!=NULL){
                $allowance = HRAllowance::find($aid);

                // Update or create the deduction employee record
                HRPayrollAllowance::updateOrCreate(
                    [
                        'payroll_list_id' => $id,
                        'allowance_id' => $aid,
                    ],
                    [
                        'payroll_id' => $query->payroll_id,
                        'user_id' => $query->user_id,
                        'amount' => $allowance->amount,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]
                );
            }else{
                $delete = HRPayrollAllowance::where('payroll_list_id', $id)
                    ->where('allowance_id',$aid)->delete();
                $auto_increment = DB::update("ALTER TABLE `hr_payroll_allowance` AUTO_INCREMENT = 0;");
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
            'aid' => 'required|numeric',
            'check' => 'required|string'
        ];

        $customMessages = [
            'id.required' => 'ID is required',
            'id.numeric' => 'ID must be a number',
            'aid.required' => 'AID is required',
            'aid.numeric' => 'AID must be a number',
            'check.required' => 'Check is required',
            'check.string' => 'Check must be a string',
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
