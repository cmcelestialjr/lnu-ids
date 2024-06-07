<?php

namespace App\Http\Controllers\HRIMS\Employee\Deduction;

use App\Http\Controllers\Controller;
use App\Models\EducGradePeriod;
use App\Models\HRDeduction;
use App\Models\HRDeductionDocs;
use App\Models\HRDeductionEmployee;
use App\Models\Users;
use App\Services\MergeImportServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocsController extends Controller
{
    public function modal(Request $request){
        return $this->_modal($request); // Call private _deductionModal function
    }
    public function table(Request $request){
        return $this->_table($request); // Call private _deductionTable function
    }
    public function submit(Request $request){
        return $this->_submit($request); // Call private _deductionTable function
    }
    public function viewModal(Request $request){
        return $this->_viewModal($request); // Call private _viewModal function
    }
    private function _modal($request){
        $did = $request->did;
        $deduction = HRDeduction::find($did);
        $grade_period = EducGradePeriod::get();
        $data = array(
            'grade_period' => $grade_period,
            'deduction' => $deduction
        );
        return view('hrims/employee/deduction/docsModal',$data); // Return view with data
    }
    private function _table($request){
        $data = array(); // Initialize empty array
        $id = $request->id; // Get ID from request
        $payroll_type = $request->payroll_type; // Get payroll_type from request
        $emp_stat = $request->emp_stat; // Get emp_stat from request
        $did = $request->did; // Get did from request
        if(isset($request->year)){
            $year = $request->year;
        }else{
            $year = date('Y');
        }
        $query = HRDeductionDocs::
                whereHas('employee', function ($query) use ($id,$payroll_type,$emp_stat,$did) {
                    $query->where('user_id',$id);
                    $query->where('deduction_id',$did);
                    $query->where('payroll_type_id',$payroll_type);
                    $query->where('emp_stat_id',$emp_stat);
                })
                ->whereYear('created_at',$year)
                ->orderBy('date_from','DESC')
                ->get()
                ->map(function($query){
                    $date_from = NULL;
                    $date_to = NULL;
                    if($query->date_from){
                        $date_from = date('M d, Y', strtotime($query->date_from));
                    }
                    if($query->date_to){
                        $date_to = date('M d, Y', strtotime($query->date_to));
                    }
                    return [
                        'id' => $query->id,
                        'amount' => $query->amount,
                        'date_from' => $date_from,
                        'date_to' => $date_to,
                        'remarks' => $query->remarks,
                        'updated_by' => $query->get_updated_by->lastname.', '.$query->get_updated_by->firstname,
                        'dateTime' => date('M d, Y h:ia', strtotime($query->created_at))
                    ];
                })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = '<button class="btn btn-info btn-info-scan viewDoc"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-file"></span>
                                    </button>';
                $data_list['f3'] = $r['amount'];
                $data_list['f4'] = $r['date_from'];
                $data_list['f5'] = $r['date_to'];
                $data_list['f6'] = $r['remarks'];
                $data_list['f7'] = $r['updated_by'];
                $data_list['f8'] = $r['dateTime'];
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data); // Return JSON response
    }
    private function _submit(Request $request){
        $uploadedFiles = [];
        $extension = ['jpg','jpeg','png','pdf'];
        $result = 'error';
       // if ($request->hasFile('files')) {
            // try{
                $id = $request->id;
                $emp_stat = $request->emp_stat;
                $payroll_type = $request->payroll_type;
                $did = $request->did;
                $account_no = $request->account_no;
                $date_from = $request->date_from;
                $date_to = $request->date_to;
                $amount = $request->amount;
                $total_amount = $request->total_amount;
                $remarks = $request->remarks;

                $check = HRDeductionDocs::where('account_no',$account_no)
                    ->where('date_from',date('Y-m-d',strtotime($date_from)))
                    ->first();
                // if($check){
                //     return response()->json([
                //         'result' => 'Already exists'
                //     ]);
                // }

                $user = Auth::user();
                $updated_by = $user->id;

                $date_from = empty($date_from) ? NULL : date('Y-m-d', strtotime($date_from));
                $date_to = empty($date_to) ? NULL : date('Y-m-d', strtotime($date_to));

                $getDeductionID = HRDeductionEmployee::firstOrCreate(
                        [
                            'user_id' => $id,
                            'deduction_id' => $did,
                            'payroll_type_id' => $payroll_type,
                            'emp_stat_id' => $emp_stat,
                        ],
                        [
                            'amount' => $amount,
                            'date_from' => $date_from,
                            'date_to' => $date_to,
                            'remarks' => $remarks,
                            'updated_by' => $updated_by,
                        ]
                );

                $deduction_employee_id = $getDeductionID->id;
                $doc = NULL;
                if ($request->hasFile('files')) {
                    $employee = Users::find($id);
                    $path = 'public/hrims/employee/'.$employee->id_no.'/deduction/docs/'.$deduction_employee_id.'/';
                    $path_retrieve = 'storage/hrims/employee/'.$employee->id_no.'/deduction/docs/'.$deduction_employee_id.'/';
                    $name = 'docs';
                    $imageNameNew = $amount.'_'.date('Y-m-d_H-i-s');
                    $merge_import_services = new MergeImportServices;
                    $doc = $merge_import_services->do($request,$path,$path_retrieve,$extension,$name,$imageNameNew);
                }
                // foreach ($files as $file) {
                //     $filename = $file->getClientOriginalName();
                //     $extension = $file->getClientOriginalExtension();
                //     $filename = 'abc.'.$extension;
                //     $path = 'public/hrims/employee/deduction/docs/';
                //     Storage::putFileAs($path, $file, $filename);

                //     $uploadedFiles[] = $filename;
                // }

                $insert = new HRDeductionDocs();
                $insert->deduction_employee_id = $deduction_employee_id;
                $insert->account_no = $account_no;
                $insert->date_from = $date_from;
                $insert->date_to = $date_to;
                $insert->amount = $amount;
                $insert->total_amount = $total_amount;
                $insert->remarks = $remarks;
                $insert->doc = $doc;
                $insert->updated_by = $updated_by;
                $insert->save();

                $total_deduction = HRDeductionDocs::where('deduction_employee_id',$deduction_employee_id)
                    ->where('date_to','<=',date('Y-m-d',strtotime($date_to)))
                    ->sum('amount');

                $update = HRDeductionEmployee::find($deduction_employee_id);
                $update->amount = $total_deduction;
                $update->save();
                dd($total_deduction.' '.$deduction_employee_id);
                $result = 'success';
            // } catch (\Exception $e) {
            //     $result = 'error';
            // }
      //  }

        return response()->json([
            'result' => $result
        ]);
    }
    private function _viewModal($request){
        $id = $request->id;
        $docs = HRDeductionDocs::find($id);
        $data = array(
            'docs' => $docs
        );
        return view('hrims/employee/deduction/docsViewModal',$data); // Return view with data
    }
}
