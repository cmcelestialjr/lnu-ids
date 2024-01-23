<?php

namespace App\Http\Controllers\HRIMS\Payroll\PayrollType;

use App\Http\Controllers\Controller;
use App\Models\HRPayrollType;
use App\Models\HRPayrollTypeGuideline;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class GuidelineController extends Controller
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
    public function create(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $id = $request->id;
        $query = HRPayrollType::find($id);
        if($query==NULL){
            return view('layouts/error/404');
        }

        $data = array(
            'query' => $query
        );

        return view('hrims/payroll/payrollType/newGuidelineModal',$data);
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
            return  response()->json(array('result' => 'error'));
        }

        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;

        $payroll = HRPayrollType::find($id);
        if($payroll==NULL){
            return  response()->json(array('result' => 'error'));
        }

        $name = $request->name;
        $w_salary_percent = $request->w_salary_percent;
        $amount = $this->amountValue($payroll,$request->amount,'amount');
        $percent = $this->percentValue($request->percent);
        $amount2 = $this->amountValue($payroll,$request->amount2,'amount2');
        $percent2 = $this->percentValue($request->percent2);
        $from = $request->from;
        $to = $request->to;
        $grant_separated = $request->grant_separated;

        $check = HRPayrollTypeGuideline::where('payroll_type_id', $id)
                ->where('name', $name)
                ->first();
        if ($check) {
            return  response()->json(array('result' => 'Already Exists!'));
        }

        DB::beginTransaction();
        try {
            // Create a new EducDepartments instance and save it to the database
            $insert = new HRPayrollTypeGuideline();
            $insert->payroll_type_id = $id;
            $insert->name = $name;
            $insert->w_salary_percent = $w_salary_percent;
            $insert->amount = $amount;
            $insert->percent = $percent;
            $insert->amount2 = $amount2;
            $insert->percent2 = $percent2;
            $insert->from = $from;
            $insert->to = $to;
            $insert->grant_separated = $grant_separated;
            $insert->updated_by = $updated_by;
            $insert->save();

            // Commit the database transaction
            DB::commit();
            // Set the result as 'success' if the record is successfully created
            return response()->json(array('result' => 'success'));
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
    public function show(int $id)
    {
        $query = HRPayrollTypeGuideline::where('payroll_type_id',$id)->get();
        $data = array(
            'query' => $query
        );
        return view('hrims/payroll/payrollType/tableGuideline',$data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $query = HRPayrollTypeGuideline::with('payroll_type')->
            where('id',$id)->first();
        $data = array(
            'query' => $query
        );
        return view('hrims/payroll/payrollType/editGuidelineModal',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        // Validate the incoming request data
        $validator = $this->storeValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json(array('result' => 'error'));
        }

        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        
        $guideline = HRPayrollTypeGuideline::find($id);
        if($guideline==NULL){
            return  response()->json(array('result' => 'error'));
        }
        
        $payroll = HRPayrollType::find($guideline->payroll_type_id);
        if($payroll==NULL){
            return  response()->json(array('result' => 'error'));
        }

        $name = $request->name;
        $w_salary_percent = $request->w_salary_percent;
        $amount = $this->amountValue($payroll,$request->amount,'amount');
        $percent = $this->percentValue($request->percent);
        $amount2 = $this->amountValue($payroll,$request->amount2,'amount2');
        $percent2 = $this->percentValue($request->percent2);
        $from = $request->from;
        $to = $request->to;
        $grant_separated = $request->grant_separated;

        $check = HRPayrollTypeGuideline::where('payroll_type_id', $payroll->id)
                ->where('name',$name)
                ->where('id','!=',$id)
                ->first();
        if ($check) {
            return  response()->json(array('result' => 'Already Exists!'));
        }

        DB::beginTransaction();
        try {
            // Create a new EducDepartments instance and save it to the database
            $update = HRPayrollTypeGuideline::find($id);
            $update->name = $name;
            $update->w_salary_percent = $w_salary_percent;
            $update->amount = $amount;
            $update->percent = $percent;
            $update->amount2 = $amount2;
            $update->percent2 = $percent2;
            $update->from = $from;
            $update->to = $to;
            $update->grant_separated = $grant_separated;
            $update->updated_by = $updated_by;
            $update->save();

            // Commit the database transaction
            DB::commit();
            // Set the result as 'success' if the record is successfully created
            return response()->json(array('result' => 'success'));
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
     * Display the confirmation resource.
     */
    public function delete(int $id)
    {
        $query = HRPayrollTypeGuideline::find($id);

        // Check if validation fails
        if ($query==NULL) {
            return view('layouts/error/404'); // Return a 404 error view if validation fails
        }

        $data = array(
            'query' => $query
        );
        return view('hrims/payroll/payrollType/deleteGuidelineModal',$data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            // Create a new EducDepartments instance and save it to the database
            $insert = HRPayrollTypeGuideline::find($id);
            $insert->delete();
            DB::statement("ALTER TABLE hr_payroll_type_guideline AUTO_INCREMENT = 0;");
            // Set the result as 'success' if the record is successfully created
            return response()->json(array('result' => 'success'));
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

    private function percentValue($value){
        if($value>=100){
            return 100;
        }elseif($value==''){
            return NULL;
        }elseif($value<=0){
            return 0;
        }else{
            return $value;
        }
    }

    private function amountValue($payroll,$amount,$column){ 
        if($amount>=$payroll->$column){
            $amount = $payroll->$column;
        }elseif($amount==''){
            return NULL;
        }elseif($amount<=0){
            $amount = 0;
        }
        return $amount;
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
    private function storeValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'name' => 'nullable|string',
            'w_salary_percent' => 'nullable|numeric',
            'amount' => 'nullable|numeric',
            'percent' => 'nullable|numeric',
            'amount2' => 'nullable|numeric',
            'percent2' => 'nullable|numeric',
            'from' => 'required|numeric',
            'to' => 'required|numeric',
        ];
        
        $customMessages = [
            'id.required' => 'ID is required',
            'id.numeric' => 'ID must be a number',
            'name.numeric' => 'Name must be a string',
            'w_salary_percent.numeric' => 'With Salary Percent must be a number',
            'amount.numeric' => 'Amount must be a number',
            'percent.numeric' => 'Percent must be a number',
            'amount2.numeric' => 'Amount2 must be a number',
            'percent2.numeric' => 'Percent2 must be a number',
            'from.required' => 'No. of Months From is required',
            'from.numeric' => 'No. of Months From must be a number',
            'to.required' => 'No. of Months To is required',
            'to.numeric' => 'No. of Months To must be a number',
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
