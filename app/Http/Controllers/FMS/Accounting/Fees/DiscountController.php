<?php

namespace App\Http\Controllers\FMS\Accounting\Fees;
use App\Http\Controllers\Controller;
use App\Models\EducDiscount;
use App\Models\EducDiscountFeesType;
use App\Models\EducDiscountList;
use App\Models\EducDiscountOption;
use App\Models\EducFees;
use App\Models\EducFeesType;
use App\Models\EducProgramLevel;
use App\Models\EducPrograms;
use App\Models\FundServices;
use App\Models\Users;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
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
    public function programList(Request $request){
        return $this->_programList($request);
    }
    public function programOption(Request $request){
        return $this->_programOption($request);
    }
    public function studentAdd(Request $request){
        return $this->_studentAdd($request);
    }
    public function updateModal(Request $request){
        return $this->_updateModal($request);
    }
    public function updateSubmit(Request $request){
        return $this->_updateSubmit($request);
    }
    public function statusUpdate(Request $request){
        return $this->_statusUpdate($request);
    }
    private function _table($request){
        $data = array();
        $query = EducDiscount::orderBy('status_id','ASC')
            ->get()
            ->map(function($query) {
                $fees_type = '';
                foreach($query->fees_type as $r){
                    $fees_type_get[] = $r->fees_type->name;
                }
                $fees_type = implode('<br>',$fees_type_get);
                return [
                    'id' => $query->id,
                    'name' => $query->name,
                    'percent' => $query->percent.'%',
                    'fees_type' => $fees_type,
                    'option' => $query->option->name,
                    'status' => $query->status_id,
                    'status_name' => $query->status->name,
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                if($r['status']==1){
                    $status = '<button class="btn btn-success btn-success-scan btn-xs discountStatus" data-id="'.$r['id'].'"><span class="fa fa-check"></span> '.$r['status_name'].'</button>';
                }else{
                    $status = '<button class="btn btn-danger btn-danger-scan btn-xs discountStatus" data-id="'.$r['id'].'"><span class="fa fa-times"></span> '.$r['status_name'].'</button>';
                }
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['percent'];
                $data_list['f4'] = $r['fees_type'];
                $data_list['f5'] = $r['option'];
                $data_list['f6'] = $status;
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
    private function _newModal($request){
        $option = EducDiscountOption::get();
        $fees_type = EducFeesType::where('id','!=',4)->get();
        $data = array(
            'option' => $option,
            'fees_type' => $fees_type
        );
        return view('fms/accounting/fees/discount/newModal',$data);
    }    
    private function _updateModal($request){
        $id = $request->id;
        $query = EducDiscount::find($id);
        $fees_type_get = EducDiscountFeesType::where('discount_id',$id)->pluck('fees_type_id')->toArray();
        $option = EducDiscountOption::get();
        $fees_type = EducFeesType::where('id','!=',4)->get();
        
        $levels = NULL;
        $programs = NULL;
        $program_level_name = NULL;
        $programs_discount = NULL;
        if($query->option_id==1){
            $programs_discount = EducDiscountList::where('discount_id',$id)->pluck('program_id')->toArray();
            $program_level = EducDiscountList::where('discount_id',$id)->first();
            $program_level_id = $program_level->program->program_level_id;
            $program_level_name = $program_level->program->program_level->name;
            $levels = EducProgramLevel::get();
            $programs = EducPrograms::where('program_level_id',$program_level_id)
                ->where('status_id',1)->get();
        }
        
        $data = array(
            'query' => $query,
            'option' => $option,
            'fees_type' => $fees_type,
            'fees_type_get' => $fees_type_get,
            'levels' => $levels,
            'programs' => $programs,
            'program_level_name' => $program_level_name,
            'programs_discount' => $programs_discount
        );
        return view('fms/accounting/fees/discount/updateModal',$data);
    }
    private function _programOption($request){
        $option = $request->option;
        if($option==1){
            $levels = EducProgramLevel::get();
            $programs = EducPrograms::where('program_level_id',6)
                ->where('status_id',1)->get();
            $data = array(
                'levels' => $levels,
                'programs' => $programs
            );
            return view('fms/accounting/fees/discount/programSelect',$data);
        }else{
            return view('fms/accounting/fees/discount/studentSelect');
        }        
    }
    private function _programList($request){
        $programs = EducPrograms::where('program_level_id',$request->level)
            ->where('status_id',1)->get();
        $data = array(
            'programs' => $programs
        );
        return view('fms/accounting/fees/discount/programList',$data);
    }
    private function _newSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $name = $request->name;
            $percent = $request->percent;
            $fees_type = $request->fees_type;
            $option = $request->option;
            $programs = $request->programs;
            $students = $request->students;

            $check = EducDiscount::where('name',$name)
                ->first();
            if($check==NULL && $fees_type!='' && ($programs!='' || $students!='')){
                $user = Auth::user();
                $updated_by = $user->id;

                $insert = new EducDiscount(); 
                $insert->name = $name;
                $insert->percent = $percent;
                $insert->option_id = $option;
                $insert->status_id = 1;
                $insert->updated_by = $updated_by;
                $insert->save();
                $discount_id = $insert->id;
                
                foreach($fees_type as $r){
                    $insert = new EducDiscountFeesType(); 
                    $insert->discount_id = $discount_id;
                    $insert->fees_type_id = $r;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                }
                
                if($option==1){
                    foreach($programs as $r){
                        $insert = new EducDiscountList(); 
                        $insert->discount_id = $discount_id;
                        $insert->program_id = $r;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                    }
                }elseif($option==2){
                    foreach($students as $r){
                        $insert = new EducDiscountList(); 
                        $insert->discount_id = $discount_id;
                        $insert->user_id = $r;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                    }
                }
                
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
            $percent = $request->percent;
            $fees_type = $request->fees_type;
            $option = $request->option;
            $programs = $request->programs;
            $students = $request->students;

            $check = EducDiscount::where('name',$name)
                ->where('id','!=',$id)
                ->first();
            if($check==NULL && $fees_type!='' && ($programs!='' || $students!='')){
                $user = Auth::user();
                $updated_by = $user->id;

                EducDiscount::where('id', $id)
                ->update(['name' => $name,
                    'percent' => $percent,
                    'updated_by' => $updated_by,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                
                $delete = EducDiscountFeesType::where('discount_id', $id)
                    ->whereNotIn('fees_type_id', $fees_type)->delete();
                $auto_increment = DB::update("ALTER TABLE `educ_discount_fees_type` AUTO_INCREMENT = 0;");
                
                foreach($fees_type as $r){
                    EducDiscountFeesType::updateOrCreate(
                        [
                            'discount_id' => $id,
                            'fees_type_id' => $r,
                        ],
                        [
                            'updated_by' => $updated_by,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]
                    );
                }

                if($option==1){
                    $delete = EducDiscountList::where('discount_id', $id)
                        ->whereNotIn('program_id', $programs)->delete();                    
                    $auto_increment = DB::update("ALTER TABLE `educ_discount_list` AUTO_INCREMENT = 0;");
                    foreach($programs as $r){
                        EducDiscountList::updateOrCreate(
                            [
                                'discount_id' => $id,
                                'program_id' => $r,
                            ],
                            [
                                'updated_by' => $updated_by,
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]
                        );
                    }
                }elseif($option==2){
                    $delete = EducDiscountList::where('discount_id', $id)
                        ->whereNotIn('user_id', $students)->delete();                    
                    $auto_increment = DB::update("ALTER TABLE `educ_discount_list` AUTO_INCREMENT = 0;");
                    foreach($students as $r){
                        EducDiscountList::updateOrCreate(
                            [
                                'discount_id' => $id,
                                'user_id' => $r,
                            ],
                            [
                                'updated_by' => $updated_by,
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]
                        );
                    }
                }
                
                $result = 'success';
            }
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
    private function _studentAdd($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        $datas = [];
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $name_services = new NameServices;
            $id = $request->val;
            $student = Users::where('id',$id);
            if($request->students!=''){
                $student = $student->whereNotIn('id',$request->students);
            }
            $student = $student->first();
            if($student!=NULL){
                $name = $name_services->lastname($student->lastname,$student->firstname,$student->middlename,$student->extname);
                $datas['id'] = $student->id;
                $datas['id_no'] = $student->id_no;
                $datas['name'] = $name;
                $datas['program'] = $student->student_info->program->shorten;
                $result = 'success';
            }
            
        }
        $response = array('result' => $result,
                           'datas' => $datas
                        );
        return response()->json($response);
    }
    private function _statusUpdate($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        $datas = [];
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $user = Auth::user();
            $updated_by = $user->id;
            $query = EducDiscount::find($id);
            $status = $query->status_id;
            if($status==1){
                $status_id = 2;
                $datas['class'] = 'btn-danger btn-danger-scan';
                $datas['span'] = '<span class="fa fa-times"></span> Closed';
            }else{
                $status_id = 1;
                $datas['class'] = 'btn-success btn-success-scan';
                $datas['span'] = '<span class="fa fa-check"></span> Open';
            }
            EducDiscount::where('id',$id)
                ->update(['status_id' => $status_id,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $result = 'success';
        }
        $response = array('result' => $result,
                           'datas' => $datas
                        );
        return response()->json($response);
    }
}