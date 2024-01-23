<?php

namespace App\Http\Controllers\HRIMS\Payroll\PayrollType;
use App\Http\Controllers\Controller;
use App\Models\HRPayrollType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PayrollTypeController extends Controller
{
    public function table(Request $request){
        return $this->_table($request);
    }
    public function newModal(Request $request){
        return $this->_newModal($request);
    }
    public function newSubmit(Request $request){
        return $this->_newSubmit($request);
    }
    public function updateModal(Request $request){
        return $this->_updateModal($request);
    }
    public function updateSubmit(Request $request){
        return $this->_updateSubmit($request);
    }
    private function _table($request){
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
    private function _newModal($request){
        return view('hrims/payroll/payrollType/newModal');
    }
    private function _updateModal($request){
        $query = HRPayrollType::with('guideline')
            ->where('id',$request->id)
            ->first();
        $data = array(
            'query' => $query
        );
        return view('hrims/payroll/payrollType/editModal',$data);
    }
    private function _newSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $name = $request->name;
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

            $check = HRPayrollType::where(function ($query) use ($name) {
                    $query->where('name',$name);
                })->first();
            if($check==NULL){
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
                $result = 'success';
            }
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }    
    private function _updateSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $name = $request->name;
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

            $check = HRPayrollType::where('id','!=',$id)
                ->where(function ($query) use ($name) {
                    $query->where('name',$name);
                })->first();
            if($check==NULL){
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
                $result = 'success';
            }
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
}