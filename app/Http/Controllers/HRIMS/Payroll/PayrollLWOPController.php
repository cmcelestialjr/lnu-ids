<?php

namespace App\Http\Controllers\HRIMS\Payroll;

use App\Http\Controllers\Controller;
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

class PayrollLWOPController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data_response = array('result' => 'error');

        // Validate the incoming request data
        $validator = $this->indexValidateRequest($request);

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

        $payroll_update_services = new PayrollUpdateServices;
        $result = 'error';
        $values = NULL;
        $lwop = NULL;

        $id = $request->id;
        $n = $request->n;
        $val = $request->val;

        if($val==NULL || $val<=0){
            $val = 0;
        }

        if($n=='day_accu'){
            if($val>=22){
                $val = 22;
            }
        }

        $user = Auth::user();
        $updated_by = $user->id;

        try{
            HRPayrollList::where('id', $id)
                    ->update([
                        $n => $val,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s'),
                ]);

            $lwop = $payroll_update_services->updateLwop($id,$updated_by);
            $values = $payroll_update_services->updatePayrollList($id,$updated_by);

            return response()->json(['result' => 'success',
                                    'lwop' => $lwop,
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
    public function update(Request $request, string $id)
    {
        //
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
    private function indexValidateRequest($request)
    {
        $rules = [
            'id' => 'required|numeric',
            'n' => 'nullable|numeric',
            'val' => 'required|string'
        ];
        
        $customMessages = [
            'id.required' => 'ID is required',
            'id.numeric' => 'ID must be a number',
            'n.numeric' => 'N must be a number',
            'val.required' => 'Val is required',
            'val.string' => 'Val must be a string',
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
