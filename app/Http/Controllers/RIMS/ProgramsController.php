<?php

namespace App\Http\Controllers\RIMS;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\EducPrograms;
use App\Models\EducCourses;
use App\Models\EducCoursesPre;
use App\Models\EducCurriculum;
use App\Models\EducYearLevel;
use App\Models\EducCourseStatus;
use App\Models\EducGradePeriod;
use Exception;

class ProgramsController extends Controller
{
    public function viewTable(Request $request){
        $data = array();
        $user = Auth::user();
        $status_id = $request->status_id;
        $query = EducPrograms::with(['codes' => function ($query) use($status_id) {
                                        $query->where('status_id', $status_id);
                                    }],'departments','program_level','status')->where('status_id',$status_id)->get();
        $count = $query->count();
        if($count>0){
            $x = 1;            
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r->program_level->name;
                $data_list['f3'] = $r->departments->shorten;
                $data_list['f4'] = $r->name;
                $data_list['f5'] = $r->shorten;
                $codes = array();
                foreach($r->codes as $c){
                    $codes[] = $c->name;
                }
                $code = implode(', ',$codes);
                $data_list['f6'] = $code;
                if($r->status->id==1){
                    $status = '<button class="btn btn-success"
                                    data-id="'.$r->id.'"
                                    >'.$r->status->name.'</button>';
                }else{
                    $status = '<button class="btn btn-danger"
                                    data-id="'.$r->id.'"
                                    >'.$r->status->name.'</button>';
                }
                $data_list['f7'] = $status;
                $data_list['f8'] = '<button class="btn btn-info btn-info-scan viewModal"
                                        data-id="'.$r->id.'">
                                        <span class="fa fa-eye"></span>
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }    
    public function viewModal(Request $request){
        $id = $request->id;
        $user_access_level = $request->session()->get('user_access_level');
        $program = EducPrograms::with('codes','departments','program_level','status')->where('id',$id)->first();
        $curriculum = EducCurriculum::with('status')->where('program_id',$id)->orderBy('year_from','DESC')->first();
        $curriculums = EducCurriculum::with('status')->where('program_id',$id)->orderBy('year_from','DESC')->get();
        $status = EducCourseStatus::get();
        // $courses = EducCourses::where('curriculum_id',$curriculum->id)
        //                     ->where('grade_level_id',15)
        //                     ->orderBy('grade_level_id','ASC')
        //                     ->orderBy('grade_period_id','ASC')
        //                     ->orderBy('id','ASC')
        //                     ->get();
        $year_level = EducYearLevel::where('program_level_id',$program->program_level_id)->orderBy('level','ASC')->get();
        $data = array(
            'id' => $id,
            'program' => $program,
            'curriculum' => $curriculum,
            'curriculums' => $curriculums,
            'year_level' => $year_level,
            'status' => $status,
            'user_access_level' => $user_access_level
        );
        return view('rims/programs/viewModal',$data);
    }
    public function newCourse(Request $request){
        $id = $request->id;
        $curriculum = EducCurriculum::with('programs','status')->where('id',$id)->first();
        $year_level = EducYearLevel::where('program_level_id',$curriculum->programs->program_level_id)->orderBy('level','ASC')->get();
        $grade_period = EducGradePeriod::get();
        $courses = EducCourses::with('grade_period','grade_level')->where('curriculum_id',$id)
                        ->orderBy('grade_level_id','ASC')
                        ->orderBy('grade_period_id','ASC')->get();
        $data = array(
            'id' => $id,
            'curriculum' => $curriculum,
            'year_level' => $year_level,
            'grade_period' => $grade_period,
            'courses' => $courses
        );
        return view('rims/programs/newCourseModal',$data);
    }
    public function curriculumTable(Request $request){
        $data = array();
        $user_access_level = $request->session()->get('user_access_level');
        $id = $request->id;
        $level = $request->level;
        $status = $request->status;
        $where_level = 'whereIn';
        $value_level = [];
        if($level=='All' || $level==''){
            $where_level = 'whereNotIn';
            
        }else{
            foreach($level as $lev){
                $value_level[] = $lev;
            }
        }
        $where_status = 'whereIn';
        $value_status = [];
        if($status=='All' || $status==''){
            $where_status = 'whereNotIn';            
        }else{
            foreach($status as $stat){
                $value_status[] = $stat;
            }
        }
        $query = EducCourses::with('grade_period','grade_level','status')
                    ->where('curriculum_id',$id)
                    ->$where_level('grade_level_id',$value_level)
                    ->$where_status('status_id',$value_status)
                    ->orderBy('grade_period_id','ASC')
                    ->orderBy('grade_level_id','ASC')->get();
        $get_ids = EducCourses::where('curriculum_id',$id)
                    ->$where_level('grade_level_id',$value_level)
                    ->$where_status('status_id',$value_status)->pluck('grade_level_id','grade_period_id')->toArray();
        $year_level_ids = [];
        $period_ids = [];
        foreach($get_ids as $key => $row){
            $year_level_ids[] = $row;
            $period_ids[] = $key;
        }
        $year_level = EducYearLevel::whereIn('id',$year_level_ids)->get();
        $period = EducGradePeriod::with(['courses' => function ($query) 
                            use ($where_status,$id,$value_status) {
                            $query->where('curriculum_id', $id);
                            $query->$where_status('status_id', $value_status);
                            $query->orderBy('grade_period_id','ASC');
                            $query->orderBy('grade_level_id','ASC');
                        }])->whereIn('id',$period_ids)->get();
        
        $data = array(
            'id' => $id,
            'query' => $query,
            'user_access_level' => $user_access_level,
            'year_level' => $year_level,
            'period' => $period
        );
        return view('rims/programs/curriculumTable',$data);
    }
    public function curriculumTablePre(Request $request){
        $data = array();
        $user_access_level = $request->session()->get('user_access_level');
        $id = $request->id;
        $level = $request->level;
        $status = $request->status;
        $where_level = 'whereIn';
        $value_level = [];
        if($level=='All' || $level==''){
            $where_level = 'whereNotIn';
            
        }else{
            foreach($level as $lev){
                $value_level[] = $lev;
            }
        }
        $where_status = 'whereIn';
        $value_status = [];
        if($status=='All' || $status==''){
            $where_status = 'whereNotIn';            
        }else{
            foreach($status as $stat){
                $value_status[] = $stat;
            }
        }
        $query = EducCourses::with('grade_period','grade_level','status')
                    ->where('curriculum_id',$id)
                    ->$where_level('grade_level_id',$value_level)
                    ->$where_status('status_id',$value_status)
                    ->orderBy('grade_period_id','ASC')
                    ->orderBy('grade_level_id','ASC')->get();
        $get_ids = EducCourses::where('curriculum_id',$id)
                    ->$where_level('grade_level_id',$value_level)
                    ->$where_status('status_id',$value_status)->pluck('grade_level_id','grade_period_id')->toArray();
        $year_level_ids = [];
        $period_ids = [];
        foreach($get_ids as $key => $row){
            $year_level_ids[] = $row;
            $period_ids[] = $key;
        }
        $year_level = EducYearLevel::whereIn('id',$year_level_ids)->get();
        $period = EducGradePeriod::with(['courses' => function ($query) 
                            use ($where_status,$id,$value_status) {
                            $query->where('curriculum_id', $id);
                            $query->$where_status('status_id', $value_status);
                            $query->orderBy('grade_period_id','ASC');
                            $query->orderBy('grade_level_id','ASC');
                        }])->whereIn('id',$period_ids)->get();
        
        $data = array(
            'id' => $id,
            'query' => $query,
            'user_access_level' => $user_access_level,
            'year_level' => $year_level,
            'period' => $period
        );
        return view('rims/programs/curriculumTablePre',$data);
    }
    public function courseTablePre(Request $request){
        $data = array();
        $user_access_level = $request->session()->get('user_access_level');
        $course_id = $request->id;
        $query = EducCourses::where('id',$course_id)->first();
        $id = $query->curriculum_id;
        $query = EducCourses::with('grade_period','grade_level','status')
                    ->where('curriculum_id',$id)
                    ->orderBy('grade_period_id','ASC')
                    ->orderBy('grade_level_id','ASC')->get();
        $get_ids = EducCourses::where('curriculum_id',$id)->pluck('grade_level_id','grade_period_id')->toArray();
        $year_level_ids = [];
        $period_ids = [];
        foreach($get_ids as $key => $row){
            $year_level_ids[] = $row;
            $period_ids[] = $key;
        }
        $year_level = EducYearLevel::whereIn('id',$year_level_ids)->get();
        $period = EducGradePeriod::with(['courses' => function ($query) 
                            use ($id,$course_id) {
                            $query->where('curriculum_id', $id);
                            $query->where('id','<>', $course_id);
                            $query->orderBy('grade_period_id','ASC');
                            $query->orderBy('grade_level_id','ASC');
                        }])->whereIn('id',$period_ids)->get();
        
        $data = array(
            'id' => $id,
            'query' => $query,
            'user_access_level' => $user_access_level,
            'year_level' => $year_level,
            'period' => $period
        );
        return view('rims/programs/curriculumTablePre',$data);
    }
    public function courseUpdate(Request $request){
        $id = $request->id;
        $query = EducCourses::with('grade_level','grade_period')->where('id', $id)->first();
        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('rims/programs/courseUpdateModal',$data);
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
}

?>