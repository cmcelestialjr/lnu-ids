<?php

namespace App\Http\Controllers\RIMS\Programs;
use App\Http\Controllers\Controller;
use App\Models\EducBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\EducPrograms;
use App\Models\EducCourses;
use App\Models\EducCoursesPre;
use App\Models\EducCurriculum;
use App\Models\EducLabCourses;
use App\Models\EducLabGroup;
use App\Models\EducProgramsCode;
use Exception;

class UpdateController extends Controller
{    
    public function curriculumStatus(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $result = 'error';
        $btn_class = '';
        $btn_html = '';
        if($user_access_level==1 || $user_access_level==2){
            try{
                $check = EducCurriculum::where('id', $id)->first();
                if($check!=NULL){
                    if($check->status_id==1){
                        $status_id = 2;
                        $btn_class = 'btn-danger btn-danger-scan';
                        $btn_html = ' Closed';
                    }else{
                        $status_id = 1;
                        $btn_class = 'btn-success btn-success-scan';
                        $btn_html = ' Open';
                    }
                    EducCurriculum::where('id', $id)
                                ->update(['status_id' => $status_id,
                                        'updated_by' => $updated_by,
                                        'updated_at' => date('Y-m-d H:i:s')]);
                    EducCourses::where('curriculum_id', $id)
                                ->update(['status_id' => $status_id,
                                        'updated_by' => $updated_by,
                                        'updated_at' => date('Y-m-d H:i:s')]);
                    $result = 'success';                    
                }                
            }catch(Exception $e){
                    
            }
        }
        $response = array('result' => $result,
                          'btn_class' => $btn_class,
                          'btn_html' => $btn_html);
        return response()->json($response);
    }
    public function courseStatus(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $result = 'error';
        $btn_class = '';
        $btn_html = '';
        
        if($user_access_level==1 || $user_access_level==2){
            try{
                $check = EducCourses::where('id', $id)->first();
                if($check!=NULL){
                    if($check->status_id==1){
                        $status_id = 2;
                        $btn_class = 'btn-danger btn-danger-scan';
                        $btn_html = ' Closed';
                    }else{
                        $status_id = 1;
                        $btn_class = 'btn-success btn-success-scan';
                        $btn_html = ' Open';
                    }
                    EducCourses::where('id', $id)
                                ->update(['status_id' => $status_id,
                                        'updated_by' => $updated_by,
                                        'updated_at' => date('Y-m-d H:i:s')]);
                    $result = 'success';                    
                }                
            }catch(Exception $e){
                    
            }
        }
        $response = array('result' => $result,
                          'btn_class' => $btn_class,
                          'btn_html' => $btn_html);
        return response()->json($response);
    }
    public function courseUpdateSubmit(Request $request){
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $code = mb_strtoupper($request->code);
        $name = $request->name;
        $units = $request->units;        
        $courses = $request->courses;
        $lab = $request->lab;
        $pay_units = $request->pay_units;
        $lab_group = $request->lab_group;
        $course_type = $request->course_type;
        $specialization_name = $request->specialization_name;
        $course = EducCourses::find($id);
        $check = EducCourses::
            where(function ($query) use ($code,$name) {
                $query->where('code',$code)
                    ->orWhere('name',$name);
            })
            ->where('curriculum_id',$course->curriculum_id)
            ->where('id','<>',$id)->first();
        $result = 'error';
        if($check==NULL){
            if($courses==NULL){
                $pre_name = 'None';
            }else{
                $pre_name = $request->pre_name;
            }
            if($course_type!=3 || $specialization_name==''){
                $specialization_name = NULL;
            }
            EducCourses::where('id', $id)
                    ->update(['code' => $code,
                              'shorten' => $code,
                              'name' => $name,
                              'units' => $units,
                              'lab' => $lab,
                              'pay_units' => $pay_units,
                              'pre_name' => $pre_name,
                              'course_type_id' => $course_type,
                              'specialization_name' => $specialization_name,
                              'updated_by' => $updated_by,
                              'updated_at' => date('Y-m-d H:i:s')]);
            $delete = EducCoursesPre::where('course_id', $id)->delete();
            $auto_increment = DB::update("ALTER TABLE educ_courses_pre AUTO_INCREMENT = 0;");
            if($courses!=NULL){                
                foreach($courses as $course){
                    $insert = new EducCoursesPre(); 
                    $insert->course_id = $id;
                    $insert->pre_id = $course;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                }
            }
            if($lab_group=='None'){
                $delete = EducLabCourses::where('course_code', $code)->delete();
                $auto_increment = DB::update("ALTER TABLE educ_lab_courses AUTO_INCREMENT = 0;");
            }else{
                $check = EducLabGroup::where('id',$lab_group)->first();
                $check_code = EducLabCourses::where('course_code',$code)->first();
                $program_level_id = $course->curriculum->programs->program_level_id;
                if($check!=NULL){
                    if($check_code==NULL){
                        $insert = new EducLabCourses(); 
                        $insert->lab_group_id = $lab_group;
                        $insert->program_level_id = $program_level_id;
                        $insert->course_code = $code;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                    }else{
                        EducLabCourses::where('course_code', $code)
                            ->update(['code' => $code,
                                    'lab_group_id' => $lab_group,
                                    'updated_by' => $updated_by,
                                    'updated_at' => date('Y-m-d H:i:s')]);
                    }
                }
            }
            $result = 'success';  
        }else{
            $result = 'exists';
        }
        $response = array('result' => $result);
        return response()->json($response);
    } 
    public function programCodeEditSubmit(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $result = 'error';
        $id = $request->id;
        $name = mb_strtoupper($request->name);
        $remarks = $request->remarks;
        $program_id = '';
        if($user_access_level==1 || $user_access_level==2){
            $query = EducProgramsCode::where('id',$id)->first();
            $program_id = $query->program_id;
            $branch_id = $query->branch_id;
            $check = EducProgramsCode::where('name',$name)->where('id','<>',$id)->first();
            if($check==NULL){
                try{
                    $branch = EducBranch::find($branch_id);
                    EducProgramsCode::where('id', $id)
                        ->update(['name' => $name.$branch->code,
                                  'remarks' => $remarks,
                                  'updated_by' => $updated_by,
                                  'updated_at' => date('Y-m-d H:i:s')]);
                    $result = 'success';
                }catch(Exception $e){
                    
                }
            }else{
                $result = 'exists';
            }
        }
        $response = array('result' => $result,
                          'id' => $program_id);
        return response()->json($response);
    }
    public function programCodeStatus(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $result = 'error';
        $btn_class = '';
        $btn_html = '';
        if($user_access_level==1 || $user_access_level==2){            
            try{
                $check = EducProgramsCode::where('id',$id)->first();
                if($check!=NULL){
                    if($check->status_id==1){
                        $status_id = 2;
                        $btn_class = 'btn-danger btn-danger-scan';
                        $btn_html = ' Closed';
                    }else{
                        $status_id = 1;
                        $btn_class = 'btn-success btn-success-scan';
                        $btn_html = ' Open';
                    }
                    EducProgramsCode::where('id', $id)
                                ->update(['status_id' => $status_id,
                                        'updated_by' => $updated_by,
                                        'updated_at' => date('Y-m-d H:i:s')]);
                    if($status_id==2){
                        $program_id = $check->program_id;
                        $check = EducProgramsCode::where('program_id',$program_id)->where('status_id',1)->first();
                        if($check==NULL){
                            $curriculum_id = EducCurriculum::where('program_id',$program_id)->pluck('id')->toArray();
                            EducPrograms::where('id', $program_id)
                                        ->update(['status_id' => $status_id,
                                                'updated_by' => $updated_by,
                                                'updated_at' => date('Y-m-d H:i:s')]);
                            EducCurriculum::where('program_id', $program_id)
                                        ->update(['status_id' => $status_id,
                                                'updated_by' => $updated_by,
                                                'updated_at' => date('Y-m-d H:i:s')]);
                            EducCourses::whereIn('curriculum_id', $curriculum_id)
                                        ->update(['status_id' => $status_id,
                                                'updated_by' => $updated_by,
                                                'updated_at' => date('Y-m-d H:i:s')]);
                        }
                    }
                    $result = 'success';  
                }
            }catch(Exception $e){

            }
        }        
        $response = array('result' => $result,
                          'btn_class' => $btn_class,
                          'btn_html' => $btn_html);
        return response()->json($response);
    }
    public function curriculumInputUpdate(Request $request){
        $user_access_level = $request->session()->get('user_access_level');        
        $result = 'error';
        if($user_access_level==1 || $user_access_level==2){            
            try{
                $user = Auth::user();
                $updated_by = $user->id;
                $id = $request->id;
                $n = $request->n;
                $val = $request->val;
                if($n=='name'){
                    $column = 'name';
                }else{
                    $column = 'remarks';
                }
                EducCurriculum::where('id', $id)
                    ->update([$column => $val,
                        'updated_by' => $updated_by,
                        'updated_at' => date('Y-m-d H:i:s')]);
                $result = 'success';

            }catch(Exception $e){

            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
}