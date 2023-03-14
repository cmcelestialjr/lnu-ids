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
use Exception;

class UpdateController extends Controller
{
    public function curriculumNewSubmit(Request $request){
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $year_from = $request->year_from;
        $year_to = $request->year_to;
        $remarks = $request->remarks;
        $result = 'error';
        $check = EducCurriculum::where('year_from',$year_from)->where('program_id',$id)->first();
        if($check==NULL){
            try{
                $insert = new EducCurriculum(); 
                $insert->program_id = $id;
                $insert->year_from = $year_from;
                $insert->year_to = $year_to;
                $insert->remarks = $remarks;
                $insert->status_id = 1;
                $insert->updated_by = $updated_by;
                $insert->save();
                $result = 'success';
            }catch(Exception $e){

            }
        }
        if($result=='success'){
            $curriculums = EducCurriculum::with('status')->where('program_id',$id)->orderBy('year_from','DESC')->get();
            $data = array(
                'curriculums' => $curriculums
            );
            return view('rims/programs/curriculumSelect',$data);
        }else{
            return $result;
        }
    }
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
        $code = $request->code;
        $name = $request->name;
        $units = $request->units;        
        $courses = $request->courses;
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
    public function newCourseSubmit(Request $request){
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $grade_period = $request->grade_period;
        $year_level = $request->year_level;
        $code = $request->code;
        $name = $request->name;
        $units = $request->units;        
        $courses = $request->courses;
        $check = EducCourses::where('code',$code)->orWhere('name',$name)->first();
        $result = 'error';
        if($check==NULL){
            try{
                if($courses==NULL){
                    $pre_name = 'None';
                }else{
                    $pre_name = $request->pre_name;
                }
                $insert = new EducCourses(); 
                $insert->curriculum_id = $id;
                $insert->grade_level_id = $year_level;
                $insert->grade_period_id = $grade_period;
                $insert->name = $name;
                $insert->code = $code;
                $insert->units = $units;
                $insert->pre_name = $pre_name;
                $insert->status_id = 1;
                $insert->updated_by = $updated_by;
                $insert->save();
                $course_id = $insert->id;

                if($courses!=NULL){
                    foreach($courses as $course){
                        $insert = new EducCoursesPre(); 
                        $insert->course_id = $course_id;
                        $insert->pre_id = $course;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                    }
                }
                $result = 'success';
            }catch(Exception $e){

            }
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
}