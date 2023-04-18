<?php

namespace App\Http\Controllers\RIMS\Programs;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\EducPrograms;
use App\Models\EducCourses;
use App\Models\EducCoursesPre;
use App\Models\EducCurriculum;
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
        $check = EducCourses::where(function ($query) use ($code,$name) {
                        $query->where('code', $code)
                            ->orWhere('name', $name);
                    })->where('id','<>',$id)->first();
        $result = 'error';
        if($check==NULL){
            if($courses==NULL){
                $pre_name = 'None';
            }else{
                $pre_name = $request->pre_name;
            }
            EducCourses::where('id', $id)
                    ->update(['code' => $code,
                              'name' => $name,
                              'units' => $units,
                              'lab' => $lab,
                              'pre_name' => $pre_name,
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
            $result = 'success';  
        }else{
            $result = 'exists';
        }
        $response = array('result' => $result);
        return response()->json($response);
    }    
    public function programStatusSubmit(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $result = 'error';
        $btn_class = '';
        $btn_html = '';
        if($user_access_level==1 || $user_access_level==2){
            try{
                $check = EducPrograms::where('id',$id)->first();
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
                    $curriculum_id = EducCurriculum::where('program_id',$id)->pluck('id')->toArray();
                    EducPrograms::where('id', $id)
                                ->update(['status_id' => $status_id,
                                        'updated_by' => $updated_by,
                                        'updated_at' => date('Y-m-d H:i:s')]);
                    EducProgramsCode::where('program_id', $id)
                                ->update(['status_id' => $status_id,
                                        'updated_by' => $updated_by,
                                        'updated_at' => date('Y-m-d H:i:s')]);
                    EducCurriculum::where('program_id', $id)
                                ->update(['status_id' => $status_id,
                                        'updated_by' => $updated_by,
                                        'updated_at' => date('Y-m-d H:i:s')]);
                    EducCourses::whereIn('curriculum_id', $curriculum_id)
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
            $check = EducProgramsCode::where('name',$name)->where('id','<>',$id)->first();
            if($check==NULL){
                try{
                    EducProgramsCode::where('id', $id)
                                ->update(['name' => $name,
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
}