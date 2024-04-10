<?php

namespace App\Http\Controllers\HRIMS\Payroll;

use App\Http\Controllers\Controller;
use App\Models\HRPayrollType;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array();
        $query = HRPayrollType::get()
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
                $data_list['f3'] = '<button class="btn btn-primary btn-primary-scan btn-sm update"
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
        return view('hrims/payroll/payrollType/newModal');
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

        $name = $request->name;

        $check = HRPayrollType::where(function ($query) use ($name) {
            $query->where('name',$name);
        })->first();

        if($check){
            return  response()->json($data_response);
        }
        
        $gov_service = $request->gov_service;
        $w_guideline = $request->w_guideline;
        $w_salary = $request->w_salary;
        $w_salary_name = $request->w_salary_name;
        $column_name = $request->column_name;
        $amount = $request->amount;
        $column_name2 = $request->column_name2;
        $amount2 = $request->amount2;
        $month_no = $request->month_no;
        $month_as_of = $request->month_as_of;
        $day_as_of = $request->day_as_of;
        $month_from = $request->month_from;
        $day_from = $request->day_from;
        $aggregate = $request->aggregate;
        $preceding_year = $request->preceding_year;
        $grant_separated = $request->grant_separated;

        if($gov_service=='all'){
            $gov_service = NULL;
        }
        if($amount<=0){
            $amount = NULL;
        }
        if($amount2<=0){
            $amount2 = NULL;
        }
        if($month_no==''){
            $month_no = NULL;
        }

        try{            
            $user = Auth::user();
            $updated_by = $user->id;
            $insert = new HRPayrollType(); 
            $insert->name = $name;
            $insert->gov_service = $gov_service;
            $insert->w_guideline = $w_guideline;
            $insert->w_salary = $w_salary;
            $insert->w_salary_name = $w_salary_name;
            $insert->column_name = $column_name;
            $insert->amount = $amount;
            $insert->column_name2 = $column_name2;
            $insert->amount2 = $amount2;
            $insert->month_no = $month_no;
            $insert->month_as_of = $month_as_of;
            $insert->day_as_of = $day_as_of;
            $insert->month_from = $month_from;
            $insert->day_from = $day_from;
            $insert->aggregate = $aggregate;
            $insert->preceding_year = $preceding_year;
            $insert->grant_separated = $grant_separated;
            $insert->updated_by = $updated_by;
            $insert->save();
            
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

        $query = HRPayrollType::with('guideline')
            ->where('id',$request->id)
            ->first();
        $data = array(
            'query' => $query
        );
        return view('hrims/payroll/payrollType/editModal',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
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

        $id = $request->id;
        $name = $request->name;

        $check = HRPayrollType::where('id','!=',$id)
            ->where(function ($query) use ($name) {
                $query->where('name',$name);
            })->first();
        
        if($check){
            return  response()->json($data_response);
        }
            
        $gov_service = $request->gov_service;
        $w_guideline = $request->w_guideline;
        $w_salary = $request->w_salary;
        $w_salary_name = $request->w_salary_name;
        $column_name = $request->column_name;
        $amount = $request->amount;
        $column_name2 = $request->column_name2;
        $amount2 = $request->amount2;
        $month_no = $request->month_no;
        $month_as_of = $request->month_as_of;
        $day_as_of = $request->day_as_of;
        $month_from = $request->month_from;
        $day_from = $request->day_from;
        $aggregate = $request->aggregate;
        $preceding_year = $request->preceding_year;
        $grant_separated = $request->grant_separated;

        if($gov_service=='all'){
            $gov_service = NULL;
        }
        if($amount<=0){
            $amount = NULL;
        }
        if($amount2<=0){
            $amount2 = NULL;
        }
        if($month_no==''){
            $month_no = NULL;
        }

        try{
            $user = Auth::user();
            $updated_by = $user->id;
            HRPayrollType::where('id', $id)
                ->update([
                    'name' => $name,
                    'gov_service' => $gov_service,
                    'w_guideline' => $w_guideline,
                    'w_salary' => $w_salary,
                    'w_salary_name' => $w_salary_name,
                    'column_name' => $column_name,
                    'amount' => $amount,
                    'column_name2' => $column_name2,
                    'amount2' => $amount2,
                    'month_no' => $month_no,
                    'month_as_of' => $month_as_of,
                    'day_as_of' => $day_as_of,
                    'month_from' => $month_from,
                    'day_from' => $day_from,
                    'aggregate' => $aggregate,
                    'preceding_year' => $preceding_year,
                    'grant_separated' => $grant_separated,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

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
            'name' => 'required|string',
            'gov_service' => 'required|string',
            'w_guideline' => 'required|string',
            'w_salary' => 'string',
            'w_salary_name' => 'nullable|string',
            'column_name' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'column_name2' => 'nullable|string',
            'amount2' => 'nullable|numeric',
            'month_no' => 'nullable|numeric',
            'month_as_of' => 'nullable|string',
            'day_as_of' => 'nullable|numeric',
            'month_from' => 'nullable|string',
            'day_from' => 'nullable|numeric',
            'aggregate' => 'nullable|string',
            'preceding_year' => 'nullable|string',
            'grant_separated' => 'nullable|string',
        ];
        
        $customMessages = [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'gov_service.required' => 'Gov Service is required',
            'gov_service.string' => 'Gov Service must be a string',
            'w_guideline.required' => 'W/ Guideline is required',
            'w_guideline.string' => 'W/ Guideline must be a string',
            'w_salary.string' => 'W/ Salary must be a string',
            'w_salary_name.string' => 'W/ Salary Name must be a string',
            'column_name.string' => 'Column Name must be a string',
            'amount.numeric' => 'Amount be a number',
            'column_name2.string' => 'Column Name2 must be a string',
            'amount2.numeric' => 'Amount2 must be a number',
            'month_no.numeric' => 'Month No must be a number',
            'month_as_of.string' => 'Month as of must be a string',
            'day_as_of.numeric' => 'Day as of must be a number',
            'month_from.string' => 'Month from must be a string',
            'day_from.numeric' => 'Day from must be a number',
            'aggregate.string' => 'Aggregate must be a string',
            'preceding_year.string' => 'Preceding Year must be a string',
            'grant_separated.string' => 'Grant Separated must be a string',
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
            'gov_service' => 'required|string',
            'w_guideline' => 'required|string',
            'w_salary' => 'string',
            'w_salary_name' => 'nullable|string',
            'column_name' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'column_name2' => 'nullable|string',
            'amount2' => 'nullable|numeric',
            'month_no' => 'nullable|numeric',
            'month_as_of' => 'nullable|string',
            'day_as_of' => 'nullable|numeric',
            'month_from' => 'nullable|string',
            'day_from' => 'nullable|numeric',
            'aggregate' => 'nullable|string',
            'preceding_year' => 'nullable|string',
            'grant_separated' => 'nullable|string',
        ];
        
        $customMessages = [
            'id.required' => 'ID is required',
            'id.numeric' => 'ID must be a numeric',
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'gov_service.required' => 'Gov Service is required',
            'gov_service.string' => 'Gov Service must be a string',
            'w_guideline.required' => 'W/ Guideline is required',
            'w_guideline.string' => 'W/ Guideline must be a string',
            'w_salary.string' => 'W/ Salary must be a string',
            'w_salary_name.string' => 'W/ Salary Name must be a string',
            'column_name.string' => 'Column Name must be a string',
            'amount.numeric' => 'Amount be a number',
            'column_name2.string' => 'Column Name2 must be a string',
            'amount2.numeric' => 'Amount2 must be a number',
            'month_no.numeric' => 'Month No must be a number',
            'month_as_of.string' => 'Month as of must be a string',
            'day_as_of.numeric' => 'Day as of must be a number',
            'month_from.string' => 'Month from must be a string',
            'day_from.numeric' => 'Day from must be a number',
            'aggregate.string' => 'Aggregate must be a string',
            'preceding_year.string' => 'Preceding Year must be a string',
            'grant_separated.string' => 'Grant Separated must be a string',
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
            'id' => 'required|numeric',
        ];
        
        $customMessages = [
            'id.required' => 'ID is required',
            'id.numeric' => 'ID must be a numeric',
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
