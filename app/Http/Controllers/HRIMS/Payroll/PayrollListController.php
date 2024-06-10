<?php

namespace App\Http\Controllers\HRIMS\Payroll;

use App\Http\Controllers\Controller;
use App\Models\DTSDocs;
use App\Models\DTSDocsHistory;
use App\Models\HRPayroll;
use App\Models\HRPayrollAllowance;
use App\Models\HRPayrollBank;
use App\Models\HRPayrollDeduction;
use App\Models\HRPayrollEmpStat;
use App\Models\HRPayrollFundService;
use App\Models\HRPayrollFundSource;
use App\Models\HRPayrollList;
use App\Models\HRPayrollMonths;
use App\Services\CodeServices;
use App\Services\NameServices;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PDOException;

class PayrollListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $data_response = array();

        // Validate the incoming request data
        $validator = $this->indexValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($data_response);
        }

        $user_access_level = $request->session()->get('user_access_level');
        $name_services = new NameServices;

        $payroll_type = $request->payroll_type;
        $by = $request->by;
        $year = $request->year;
        $month = $request->month;
        $type = $request->type;

        $query = HRPayroll::with('emp_stat','bank')
            ->where('generate_option',$type)
            ->where('year',$year);
        if($payroll_type!='All'){
            $query = $query->where('payroll_type_id',$payroll_type);
        }
        if($by=='month'){
            $query = $query->where('month',$month);
        }
        $query = $query->get()
            ->map(function($query) use ($name_services) {
                $payroll_id = $query->id;
                $day_from = $query->day_from;
                $day_to = $query->day_to;
                foreach($query->emp_stat as $row){
                    $emp_stats[] = $row->gov;
                }
                $bank = 'Period:';
                if($query->bank){
                    foreach($query->bank as $r_bank){
                        if($r_bank->type==1){
                            $bank .= 'Period: '.$day_from.'-15 Date: '.date('m/d/Y h:ia',strtotime($r_bank->dateTime)).'<br>';
                        }elseif($r_bank->type==2){
                            $bank .= 'Period: 16-'.$day_to.' Date: '.date('m/d/Y h:ia',strtotime($r_bank->dateTime)).'<br>';
                        }elseif($r_bank->type==3){
                            $bank .= 'Period: '.$day_from.'-'.$day_to.' Date: '.date('m/d/Y h:ia',strtotime($r_bank->dateTime));
                        }
                    }
                }
                return [
                    'id' => $query->id,
                    'payroll_id' => $query->payroll_id,
                    'payroll_type_id' => $query->payroll_type_id,
                    'option' => $query->option_id,
                    'etal' => $query->etal,
                    'payroll_type' => $query->name.'<br>'.$query->period,
                    'amount' => 'OB:'.number_format($query->ob,2).'<br>DV:'.number_format($query->dv,2),
                    'emp_stats' => $emp_stats,
                    'year' => $query->year,
                    'month' => $query->month,
                    'day_from' => $query->day_from,
                    'day_to' => $query->day_to,
                    'bank' => $bank,
                    'generated_at' => $query->generated_at,
                ];
            })->toArray();

        if(count($query)>0){
            $x = 1;
            $code_services = new CodeServices;
            foreach($query as $r){
                $payroll_id = $r['payroll_id'];
                $encoded = $code_services->encode($payroll_id).'1';
                $data_list['f1'] = $x;
                $data_list['f2'] = '<form action="'.url('hrims/payroll/view/'.$payroll_id.'/'.$encoded).'" method="GET" target="_blank">
                                        <button type="submit" class="btn btn-primary btn-primary-scan">
                                        '.$r['payroll_id'].'</button>
                                    </form>';
                $data_list['f3'] = $r['etal'];
                $data_list['f4'] = $r['payroll_type'];
                $data_list['f5'] = $r['amount'];

                if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
                    $data_list['f6'] = '<button class="btn btn-info btn-info-scan btn-xs bank"
                                            id="bank'.$x.'"
                                            data-id="'.$r['id'].'"
                                            data-x="'.$x.'">
                                            '.$r['bank'].'
                                        </button>';
                }else{
                    $data_list['f6'] = $r['bank'];
                }

                $data_list['f7'] = '<button class="btn btn-danger btn-danger-scan btn-xs delete"
                                        id="deletePayroll'.$r['id'].'"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-trash"></span>
                                    </button>';
                array_push($data_response,$data_list);
                $x++;
            }
        }
        return  response()->json($data_response);
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
    public function bank(Request $request)
    {
        $user_access_level = $request->session()->get('user_access_level');

        $hide_1 = '';
        $hide_2 = 'hide';
        $hide_3 = 'hide';
        $date_1 = '';
        $date_2 = '';
        $date_3 = '';
        $time_1 = '';
        $time_2 = '';
        $time_3 = '';
        $time_view_1 = '';
        $time_view_2 = '';
        $time_view_3 = '';
        $day_from = '';
        $day_to = '';

        $query = HRPayroll::with('emp_stat','bank')
            ->where('id',$request->id)->first();

        if (!$query) {
            return view('layouts/error/404');
        }

        $day_from = $query->day_from;
        $day_to = $query->day_to;

        foreach($query->emp_stat as $emp_stat){
            $emp_stats[] = $emp_stat->gov;
        }

        if($query->bank){
            foreach($query->bank as $bank){
                if($bank->type==1){
                    $date_1 = date('Y-m-d', strtotime($bank->dateTime));
                    $time_1 = date('H:i:s', strtotime($bank->dateTime));
                    $time_view_1 = date('h:i:a', strtotime($bank->dateTime));
                }elseif($bank->type==2){
                    $date_2 = date('Y-m-d', strtotime($bank->dateTime));
                    $time_2 = date('H:i:s', strtotime($bank->dateTime));
                    $time_view_2 = date('h:i:a', strtotime($bank->dateTime));
                }elseif($bank->type==3){
                    $date_3 = date('Y-m-d', strtotime($bank->dateTime));
                    $time_3 = date('H:i:s', strtotime($bank->dateTime));
                    $time_view_3 = date('h:i:a', strtotime($bank->dateTime));
                }
            }
        }

        if($query->payroll_type_id==1 && $query->option_id==1 && in_array('Y', $emp_stats)){
            $hide_2 = '';
            $hide_3 = '';
        }

        $data = array(
            'user_access_level' => $user_access_level,
            'day_from' => $day_from,
            'day_to' => $day_to,
            'hide_1' => $hide_1,
            'hide_2' => $hide_2,
            'hide_3' => $hide_3,
            'date_1' => $date_1,
            'date_2' => $date_2,
            'date_3' => $date_3,
            'time_1' => $time_1,
            'time_2' => $time_2,
            'time_3' => $time_3,
            'time_view_1' => $time_view_1,
            'time_view_2' => $time_view_2,
            'time_view_3' => $time_view_3,
            'x' => $request->x,
            'id' => $query->id
        );
        return view('hrims/payroll/view/bank',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function bankSubmit(Request $request)
    {
        $query = HRPayroll::with('emp_stat','bank')
            ->where('id',$request->id)->first();

        if (!$query) {
            return  response()->json(['result' => 'error']);
        }

        $id = $request->id;
        $date_1 = $request->date_1;
        $time_1 = $request->time_1;
        $date_2 = $request->date_2;
        $time_2 = $request->time_2;
        $date_3 = $request->date_3;
        $time_3 = $request->time_3;

        $update_1 = $this->bankDateTime($id,$date_1,$time_1,1);
        $update_2 = $this->bankDateTime($id,$date_2,$time_2,2);
        $update_2 = $this->bankDateTime($id,$date_3,$time_3,3);

        $btn = 'Period:';
        $day_from = $query->day_from;
        $day_to = $query->day_to;
        $bank = HRPayrollBank::where('payroll_id',$id)
            ->orderBy('type','ASC')
            ->get();
        if($bank->count()>0){
            foreach($bank as $row){
                if($row->type==1){
                    $btn .= 'Period: '.$day_from.'-15 Date: '.date('m/d/Y h:ia',strtotime($row->dateTime)).'<br>';
                }elseif($row->type==2){
                    $btn .= 'Period: 16-'.$day_to.' Date: '.date('m/d/Y h:ia',strtotime($row->dateTime)).'<br>';
                }elseif($row->type==3){
                    $btn .= 'Period: '.$day_from.'-'.$day_to.' Date: '.date('m/d/Y h:ia',strtotime($row->dateTime));
                }
            }
        }

        return response()->json(['result' => 'success',
                                'btn' => $btn]);
    }

    private function bankDateTime($id,$date,$time,$type)
    {
        if($time){
            $user = Auth::user();
            $updated_by = $user->id;
            $check = HRPayrollBank::where('payroll_id', $id)->where('type',$type)->first();
            if($check){
                $update = HRPayrollBank::find($check->id);
            }else{
                $update = new HRPayrollBank();
                $update->payroll_id = $id;
            }
            $update->type = $type;
            $update->dateTime = date('Y-m-d H:i:s',strtotime($date.' '.$time));
            $update->updated_by = $updated_by;
            $update->save();
        }else{
            HRPayrollBank::where('payroll_id', $id)->where('type',$type)->delete();
            DB::update("ALTER TABLE `hr_payroll_bank` AUTO_INCREMENT = 1;");
        }
        return 'success';
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $id = $request->id;
        $query = HRPayroll::where('id',$id)->first();

        if ($query==NULL) {
            return view('layouts/error/404');
        }

        $data = array(
            'query' => $query
        );
        return view('hrims/payroll/view/deleteModal',$data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $data_response = array('result' => 'error');

        // Validate the incoming request data
        $validator = $this->idValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($data_response);
        }

        $id = $request->id;
        $query = HRPayroll::where('id',$id)->first();

        if ($query==NULL) {
            return  response()->json($data_response);
        }

        $tracking_id = $query->tracking_id;

        try{

            $delete = HRPayrollAllowance::where('payroll_id', $id)->delete();
            $auto_increment = DB::update("ALTER TABLE `hr_payroll_allowance` AUTO_INCREMENT = 1;");
            $delete = HRPayrollDeduction::where('payroll_id', $id)->delete();
            $auto_increment = DB::update("ALTER TABLE `hr_payroll_deduction` AUTO_INCREMENT = 1;");
            $delete = HRPayrollMonths::where('payroll_id', $id)->delete();
            $auto_increment = DB::update("ALTER TABLE `hr_payroll_months` AUTO_INCREMENT = 1;");
            $delete = HRPayrollList::where('payroll_id', $id)->delete();
            $auto_increment = DB::update("ALTER TABLE `hr_payroll_list` AUTO_INCREMENT = 1;");

            $delete = HRPayrollEmpStat::where('payroll_id', $id)->delete();
            $auto_increment = DB::update("ALTER TABLE `hr_payroll_emp_stat` AUTO_INCREMENT = 1;");
            $delete = HRPayrollFundSource::where('payroll_id', $id)->delete();
            $auto_increment = DB::update("ALTER TABLE `hr_payroll_fund_source` AUTO_INCREMENT = 1;");
            $delete = HRPayrollFundService::where('payroll_id', $id)->delete();
            $auto_increment = DB::update("ALTER TABLE `hr_payroll_fund_service` AUTO_INCREMENT = 1;");
            $delete = HRPayroll::where('id', $id)->delete();
            $auto_increment = DB::update("ALTER TABLE `hr_payroll` AUTO_INCREMENT = 1;");
            if($tracking_id){
                $delete = DTSDocsHistory::where('doc_id', $tracking_id)->delete();
                $auto_increment = DB::update("ALTER TABLE `dts_docs_history` AUTO_INCREMENT = 1;");
                $delete = DTSDocs::where('id', $tracking_id)->delete();
                $auto_increment = DB::update("ALTER TABLE `dts_docs` AUTO_INCREMENT = 1;");
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
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function indexValidateRequest($request)
    {

        $rules = [
            'payroll_type' => 'required',
            'by' => 'required|string',
            'year' => 'required|numeric',
            'month' => 'required|string',
            'type' => 'required|string'
        ];

        $customMessages = [
            'payroll_type.required' => 'Payroll Type is required',
            'by.required' => 'By is required',
            'by.string' => 'By must be a string',
            'year.required' => 'Year is required',
            'year.numeric' => 'Year must be a string',
            'month.required' => 'Month is required',
            'month.string' => 'Month must be a string',
            'type.required' => 'Type is required',
            'type.string' => 'Type must be a string',
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
