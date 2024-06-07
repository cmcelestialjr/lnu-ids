<?php

namespace App\Http\Controllers\HRIMS\Payroll;
use App\Http\Controllers\Controller;
use App\Http\Requests\HRIMS\Payroll\BillingAssignSubmitRequest;
use App\Http\Requests\IdRequest;
use App\Imports\BillingImport;
use App\Models\HRBilling;
use App\Models\HRBillingList;
use App\Models\HRDeductionDocs;
use App\Models\HRDeductionEmployee;
use App\Models\HRDeductionGroup;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BillingController extends Controller
{
    public function index(Request $request){
        $data = array();
        $query = HRBilling::get()
            ->map(function($query) {
                return [
                    'id' => $query->id,
                    'group' => $query->group->name,
                    'year' => $query->year,
                    'month' => date('M', strtotime($query->year.'-'.$query->month.'-01')),
                    'by' => $query->updatedBy->lastname.', '.$query->updatedBy->firstname,
                    'date' => date('M d, Y h:i a', strtotime($query->created_at)),
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['group'];
                $data_list['f3'] = $r['year'];
                $data_list['f4'] = $r['month'];
                $data_list['f5'] = $r['by'];
                $data_list['f6'] = $r['date'];
                $data_list['f7'] = '<button class="btn btn-primary btn-primary-scan btn-sm update"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-eye"></span>
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }

    public function show(IdRequest $request)
    {
        $header = HRBillingList::where('billing_id',$request->id)
            ->where('option','header')
            ->get();
        $data = array(
            'header' => $header
        );
        return view('hrims/payroll/billing/show',$data);
    }

    public function showTable(IdRequest $request)
    {
        $data = array();
        $query = HRBillingList::where('billing_id',$request->id)
            ->where('option',NULL)
            ->orderBy('status','ASC')
            ->cursor();

        if ($query->isNotEmpty()) {
            $x = 1;
            foreach($query as $r){
                $status = '<button class="btn btn-danger btn-danger-scan assign" data-id="'.$r->id.'"><span class="fa fa-times"></span> Assign</button>';
                if($r->status==1){
                    $status = '<button class="btn btn-success btn-success-scan assign" data-id="'.$r->id.'"><span class="fa fa-check"></span> Done</button>';
                }
                $data_list['f1'] = $x;
                $data_list['f2'] = $status;
                $data_list['f3'] = $r->staff_no;
                $data_list['f4'] = $r->name;
                $data_list['f5'] = $r->amount;
                $data_list['f6'] = $r->total_amount;
                $data_list['f7'] = date('m/d/Y',strtotime($r->date_from));
                $data_list['f8'] = date('m/d/Y',strtotime($r->date_to));
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    public function assign(IdRequest $request)
    {
        $query = HRBillingList::find($request->id);

        $data = array(
            'query' => $query
        );
        return view('hrims/payroll/billing/assign',$data);
    }
    public function assignSubmit(BillingAssignSubmitRequest $request)
    {
        $update = HRBillingList::find($request->id);

        if(!$update){
            return response()->json(['result' => 'error']);
        }

        $user_id = $request->employee;
        $deduction_id = $update->deduction_id;
        $payroll_type_id = $update->billing->payroll_type_id;

        $employee = Users::with('employee_default')->where('id',$user_id)->first();

        if(!$employee){
            return response()->json(['result' => 'error']);
        }

        $user = Auth::user();
        $updated_by = $user->id;

        $deduction_employee = HRDeductionEmployee::where('user_id',$user_id)
            ->where('deduction_id',$deduction_id)
            ->where('payroll_type_id',$payroll_type_id)
            ->first();
        if(!$deduction_employee){
            $update_deduction = new HRDeductionEmployee;
            $update_deduction->user_id = $user_id;
            $update_deduction->deduction_id = $deduction_id;
            $update_deduction->payroll_type_id = $payroll_type_id;
            $update_deduction->emp_stat_id = $employee->employee_default->emp_stat_id;
            $update_deduction->payroll_type_id = $updated_by;
            $update_deduction->save();
            $deduction_employee_id = $update_deduction->id;
        }else{
            $deduction_employee_id = $deduction_employee->id;
        }

        $docs = HRDeductionDocs::where('deduction_employee_id',$deduction_employee_id)
            ->where('account_no',$update->staff_no)
            ->first();
        if($docs){
            $update_docs = HRDeductionDocs::find($docs->id);
            $update_docs->date_from = $update->date_from;
            $update_docs->date_to = $update->date_to;
            $update_docs->amount = $update->amount;
            $update_docs->total_amount = $update->total_amount;
            $update_docs->updated_by = $updated_by;
        }else{
            $update_docs = new HRDeductionDocs;
            $update_docs->deduction_employee_id = $deduction_employee_id;
            $update_docs->account_no = $update->staff_no;
            $update_docs->date_from = $update->date_from;
            $update_docs->date_to = $update->date_to;
            $update_docs->amount = $update->amount;
            $update_docs->total_amount = $update->total_amount;
            $update_docs->updated_by = $updated_by;
        }

        $update_docs->save();

        $total_deduction = HRDeductionDocs::where('date_to','>=',date('Y-m-d'))
            ->where('deduction_employee_id',$deduction_employee_id)
            ->sum('amount');
        $update_deduction = HRDeductionEmployee::find($deduction_employee_id);
        $update_deduction->amount = $total_deduction;
        $update_deduction->save();

        $update->user_id = $request->employee;
        $update->status = 1;
        $update->save();

        return response()->json(['result' => 'success',
                                'id' => $update->billing_id]);
    }
    public function import(Request $request)
    {
        $user = Auth::user();
        $updated_by = $user->id;

        // Retrieve HRDeductionGroup based on the provided group ID
        $group = HRDeductionGroup::where('id', $request->group)->first();

        if ($group) {
            // Get or create the billing and retrieve the billing ID
            $billing_id = $this->getOrCreateBilling($request, $updated_by);

            // Specify the sheet name or index you want to import
            $sheet = $group->name;
            // Import data using BillingImport class
            $import = new BillingImport($group, $billing_id, $request->payroll_type, $updated_by);
            $import->onlySheets($sheet);
            Excel::import($import, $request->file('file')->store('temp'));
        }

        return back();
    }

    private function getOrCreateBilling($request, $updated_by)
    {
        // Check if the billing already exists based on group, year, and month
        $billing = HRBilling::where('payroll_type_id',$request->payroll_type)
            ->where('group_id', $request->group)
            ->where('year', $request->year)
            ->where('month', $request->month)
            ->first();

        if ($billing) {
            // If the billing exists, delete related billing list entries and update the billing
            $delete = HRBillingList::where('billing_id', $billing->id)->delete();
            $auto_increment = DB::update("ALTER TABLE `hr_billing_list` AUTO_INCREMENT = 0;");
            $billing->updated_by = $updated_by;
            $billing->updated_at = date('Y-m-d H:i:s');
            $billing->save();
            $billing_id = $billing->id;
        } else {
            // If the billing doesn't exist, create a new billing entry
            $insert = new HRBilling();
            $insert->payroll_type_id = $request->payroll_type;
            $insert->group_id = $request->group;
            $insert->year = $request->year;
            $insert->month = $request->month;
            $insert->updated_by = $updated_by;
            $insert->save();
            $billing_id = $insert->id;
        }

        return $billing_id;
    }
}
