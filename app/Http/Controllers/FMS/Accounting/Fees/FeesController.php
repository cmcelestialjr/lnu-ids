<?php

namespace App\Http\Controllers\FMS\Accounting\Fees;
use App\Http\Controllers\Controller;
use App\Models\EducFees;
use App\Models\EducFeesList;
use App\Models\EducFeesPeriod;
use App\Models\EducFeesType;
use App\Models\EducGradePeriod;
use App\Models\EducProgramLevel;
use App\Models\FundServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FeesController extends Controller
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
    public function feesSubmit(Request $request){
        return $this->_feesSubmit($request);
    }
    public function feesAllSubmit(Request $request){
        return $this->_feesAllSubmit($request);
    }
    public function labFeeModal(Request $request){
        return $this->_labFeeModal($request);
    }

    private function _table($request){
        $list = EducFeesList::where('branch_id',$request->branch)
            ->where('program_level_id',$request->level)
            ->get();
        $level = EducProgramLevel::find($request->level);
        $period = EducGradePeriod::where('period',$level->period)->get();
        $data = array(
            'list' => $list,
            'period' => $period
        );
        return view('fms/accounting/fees/fees/table',$data);
    }
    private function _newModal($request){
        $fees_ids = EducFeesList::where('branch_id',$request->branch)
            ->where('program_level_id',$request->level)
            ->pluck('fees_id')->toArray();
        $fees = EducFees::whereNotIn('id',$fees_ids)
            // ->where('type_id','!=',3)
            ->get();
        $data = array(
            'fees' => $fees
        );
        return view('fms/accounting/fees/fees/newModal',$data);
    }
    private function _labFeeModal($request){
        $fees_ids = EducFeesList::where('branch_id',$request->branch)
            ->where('program_level_id',$request->level)
            ->pluck('fees_id')->toArray();
        $fees = EducFees::whereNotIn('id',$fees_ids)
            ->where('type_id','!=',3)
            ->get();
        $data = array(
            'fees' => $fees
        );
        return view('fms/accounting/fees/fees/labFeeModal',$data);
    }
    private function _newSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $fees = $request->fees;
            $branch = $request->branch;
            $level = $request->level;
            $check = EducFeesList::where('fees_id',$fees)
                ->where('branch_id',$branch)
                ->where('program_level_id',$level)
                ->first();
            if($check==NULL){
                $user = Auth::user();
                $updated_by = $user->id;
                $insert = new EducFeesList(); 
                $insert->fees_id = $fees;
                $insert->branch_id = $branch;
                $insert->program_level_id = $level;
                $insert->updated_by = $updated_by;
                $insert->save();
                $result = 'success';
            }
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
    private function _feesSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $fees = $request->fees;
            $branch = $request->branch;
            $level = $request->level;
            $period = $request->period;
            $value = $request->value;
            $get_fees = EducFeesList::find($fees);
            $fees_id = $get_fees->fees_id;
            $check = EducFeesPeriod::where('fees_list_id',$fees)
                ->where('grade_period_id',$period)
                ->first();
            $user = Auth::user();
            $updated_by = $user->id;
            if($value<=0){
                $delete = EducFeesPeriod::where('fees_list_id',$fees)
                ->where('grade_period_id',$period)->delete();
                $auto_increment = DB::update("ALTER TABLE educ_fees_period AUTO_INCREMENT = 0;");
            }else{
                if($check!=NULL){                
                    EducFeesPeriod::where('fees_list_id',$fees)
                    ->where('grade_period_id',$period)
                    ->update(['amount' => $value,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);                
                }else{
                    $insert = new EducFeesPeriod(); 
                    $insert->fees_list_id = $fees;
                    $insert->fees_id = $fees_id;
                    $insert->branch_id = $branch;
                    $insert->program_level_id = $level;
                    $insert->grade_period_id = $period;
                    $insert->amount = $value;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                }
            }
            $result = 'success';
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
    private function _feesAllSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $fees = $request->fees;
            $branch = $request->branch;
            $level = $request->level;
            $value = $request->value;
            $get_fees = EducFeesList::find($fees);
            $fees_id = $get_fees->fees_id;
            $levels = EducProgramLevel::find($level);
            $periods = EducGradePeriod::where('period',$levels->period)->get();
            $user = Auth::user();
            $updated_by = $user->id;
            $period_list = [];
            if($periods->count()>0){
                foreach($periods as $r){
                    $period = $r->id;
                    $check = EducFeesPeriod::where('fees_list_id',$fees)
                        ->where('grade_period_id',$period)
                        ->first();
                    if($check!=NULL){
                        EducFeesPeriod::where('fees_list_id',$fees)
                        ->update(['amount' => $value,
                            'updated_by' => $updated_by,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);                
                    }else{
                        $insert = new EducFeesPeriod(); 
                        $insert->fees_list_id = $fees;
                        $insert->fees_id = $fees_id;
                        $insert->branch_id = $branch;
                        $insert->program_level_id = $level;
                        $insert->grade_period_id = $period;
                        $insert->amount = $value;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                    }
                    $period_list[] = 'period'.$fees.$period;
                }
            }
            $result = 'success';
        }
        $response = array('result' => $result,
                          'period_list' => $period_list
                        );
        return response()->json($response);
    }
}