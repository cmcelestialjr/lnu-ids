<?php

namespace App\Http\Controllers\RIMS\Programs;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EducPrograms;
use App\Models\EducCourses;
use App\Models\EducCoursesPre;
use App\Models\EducCurriculum;
use App\Models\EducProgramsCode;
use Exception;

class NewController extends Controller
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
                $query = EducCurriculum::where('program_id',$id)->orderBy('year_from','DESC')->first();
                if($query!=NULL){
                    $code = $this->alphabet($query->code);
                }else{
                    $code = 'A';
                }                
                $insert = new EducCurriculum(); 
                $insert->program_id = $id;
                $insert->year_from = $year_from;
                $insert->year_to = $year_to;
                $insert->code = $code;
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
    public function newCourseSubmit(Request $request){
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $grade_period = $request->grade_period;
        $year_level = $request->year_level;
        $code = mb_strtoupper($request->code);
        $name = $request->name;
        $units = $request->units;
        $lab = $request->lab;       
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
                $insert->lab = $lab;
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
    public function programsNewSubmit(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $result = 'error';
        $level = $request->level;
        $department = $request->department;
        $name = $request->name;
        $shorten = $request->shorten;
        $code = mb_strtoupper($request->code);
        if($user_access_level==1 || $user_access_level==2){
            $check = EducPrograms::where('name',$name)->orWhere('shorten',$shorten)->orWhere('code',$code)->first();
            if($check==NULL){
                try{
                    $insert = new EducPrograms(); 
                    $insert->department_id = $department;
                    $insert->program_level_id = $level;
                    $insert->name = $name;
                    $insert->shorten = mb_strtoupper($shorten);
                    $insert->status_id = 1;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                    $program_id = $insert->id;

                    $insert = new EducProgramsCode(); 
                    $insert->program_id = $program_id;
                    $insert->name = $code;
                    $insert->status_id = 1;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                    $result = 'success';
                }catch(Exception $e){

                }
            }else{
                $result = 'exists';
            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    public function programCodeNewSubmit(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        $result = 'error';
        $id = $request->id;
        $name = mb_strtoupper($request->name);
        $remarks = $request->remarks;
        if($user_access_level==1 || $user_access_level==2){
            $check = EducProgramsCode::where('name',$name)->first();
            if($check==NULL){
                try{
                    $insert = new EducProgramsCode(); 
                    $insert->program_id = $id;
                    $insert->name = $name;
                    $insert->remarks = $remarks;
                    $insert->status_id = 1;
                    $insert->updated_by = $updated_by;
                    $insert->save();
                    $result = 'success';
                }catch(Exception $e){
                    
                }
            }else{
                $result = 'exists';
            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    private function alphabet($letter){
        $alphabet = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $add = 0;
        if(strlen($letter)>1){
            $add = $letter[1];
            $letter = $letter[0];            
        }        
        if($letter!=NULL){
            $key = array_search($letter, $alphabet);
        }else{
            $key = 0;
        }
        if($letter=='Z'){
            $add = $add+1;
            $key = -1;
        }
        if($add==0){
            $add = '';
        }
        $letter = $alphabet[$key+1].$add;
        return $letter;
    }
}