<?php

namespace App\Http\Controllers\FMS\Accounting\Fees;
use App\Http\Controllers\Controller;
use App\Models\EducCourses;
use App\Models\EducLabCourses;
use App\Models\EducLabGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LabController extends Controller
{
    public function tableGroup(Request $request){
        return $this->_tableGroup($request);
    }
    public function tableCourses(Request $request){
        return $this->_tableCourses($request);
    }
    public function tableGroupCourses(Request $request){
        return $this->_tableGroupCourses($request);
    }
    public function newGroupModal(Request $request){
        return $this->_newGroupModal($request);
    }
    public function newGroupModalSubmit(Request $request){
        return $this->_newGroupModalSubmit($request);
    }
    public function updateGroupModal(Request $request){
        return $this->_updateGroupModal($request);
    }
    public function updateGroupModalSubmit(Request $request){
        return $this->_updateGroupModalSubmit($request);
    }
    public function groupCoursesModal(Request $request){
        return $this->_groupCoursesModal($request);
    }
    public function groupCourseAdd(Request $request){
        return $this->_groupCourseAdd($request);
    }
    public function groupCourseRemove(Request $request){
        return $this->_groupCourseRemove($request);
    }
    public function labCoursesAmount(Request $request){
        return $this->_labCoursesAmount($request);
    }
    private function _tableGroup($request){
        $data = array();
        $query = EducLabGroup::where('program_level_id',$request->level)
            ->get()
            ->map(function($query) {
                return [
                    'id' => $query->id,
                    'name' => $query->name,
                    'remarks' => $query->remarks
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['remarks'];
                $data_list['f4'] = '<button class="btn btn-primary btn-primary-scan btn-sm update"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-edit"></span>
                                    </button>
                                    <button class="btn btn-info btn-info-scan btn-sm courses"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-eye"></span>
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    private function _tableCourses($request){
        $data = array();
        $query = EducLabCourses::where('program_level_id',$request->level)
            ->orderBy('lab_group_id','ASC')
            ->get()
            ->map(function($query) {
                return [
                    'id' => $query->id,
                    'code' => $query->course_code,
                    'group' => $query->group->name,
                    'amount' => $query->amount,
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['code'];
                $data_list['f3'] = $r['group'];
                $data_list['f4'] = '<input type="number" class="form-control labCoursesAmount" data-id="'.$r['id'].'" value="'.$r['amount'].'">';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    private function _tableGroupCourses($request){
        $data = array();
        $query = EducLabCourses::where('lab_group_id',$request->id)
            ->get()
            ->map(function($query) {
                return [
                    'id' => $query->id,
                    'code' => $query->course_code,
                    'name' => $query->course->name,
                    'amount' => $query->amount,
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['code'];
                $data_list['f3'] = $r['name'];
                $data_list['f4'] = '<input type="number" class="form-control labCoursesAmount" data-id="'.$r['id'].'" value="'.$r['amount'].'">';
                $data_list['f5'] = '<button class="btn btn-danger btn-danger-scan btn-sm remove"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-times"></span>
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    private function _newGroupModal($request){
        $data = array(
            '' => ''
        );
        return view('fms/accounting/fees/fees/newGroupModal',$data);
    }
    private function _updateGroupModal($request){
        $group = EducLabGroup::find($request->id)
            ->first();
        $data = array(
            'group' => $group
        );
        return view('fms/accounting/fees/fees/updateGroupModal',$data);
    }
    private function _groupCoursesModal($request){
        $group = EducLabGroup::find($request->id)
            ->first();
        $data = array(
            'group' => $group
        );
        return view('fms/accounting/fees/fees/groupCoursesModal',$data);
    }
    private function _newGroupModalSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $name = $request->name;
            $remarks = $request->remarks;
            $level = $request->level;
            $check = EducLabGroup::where('name',$name)
                ->where('program_level_id',$level)
                ->first();
            if($check==NULL){
                $user = Auth::user();
                $updated_by = $user->id;
                $insert = new EducLabGroup(); 
                $insert->program_level_id = $level;
                $insert->name = $name;
                $insert->remarks = $remarks;
                $insert->updated_by = $updated_by;
                $insert->save();
                $result = 'success';
            }
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
    private function _updateGroupModalSubmit($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $name = $request->name;
            $remarks = $request->remarks;
            $level = $request->level;
            $check = EducLabGroup::where('name',$name)
                ->where('program_level_id',$level)
                ->where('id','!=',$id)
                ->first();
            if($check==NULL){
                $user = Auth::user();
                $updated_by = $user->id;
                EducLabGroup::where('id',$id)
                    ->update(['name' => $name,
                        'remarks' => $remarks,
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
    public function _groupCourseAdd($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $course_code = $request->course_code;
            $level = $request->level;
            $course = EducCourses::where('code',$course_code)->first();
            $level_id = $course->curriculum->programs->program_level_id;
            if($level==$level_id){
                $check = EducLabCourses::where('course_code',$course_code)
                    ->first();
                if($check==NULL){
                    $user = Auth::user();
                    $updated_by = $user->id;
                    $insert = new EducLabCourses(); 
                    $insert->lab_group_id = $id;
                    $insert->course_code = $course_code;
                    $insert->program_level_id = $level;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                    $result = 'success';
                }
            }else{
                $result = 'Not the same level';
            }
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
    private function _groupCourseRemove($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $delete = EducLabCourses::where('id',$id)->delete();
            $auto_increment = DB::update("ALTER TABLE educ_lab_courses AUTO_INCREMENT = 0;");
            $result = 'success';
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
    private function _labCoursesAmount($request){
        $user_access_level = $request->session()->get('user_access_level');
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id = $request->id;
            $val = $request->val;
            $user = Auth::user();
            $updated_by = $user->id;
            EducLabCourses::where('id',$id)
                    ->update(['amount' => $val,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]); 
            $result = 'success';
        }
        $response = array('result' => $result
                        );
        return response()->json($response);
    }
}