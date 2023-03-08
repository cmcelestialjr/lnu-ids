<?php

namespace App\Http\Controllers\RIMS;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\EducPrograms;
use App\Models\EducCourses;
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
    public function courseStatus(Request $request){
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $result = 'error';
        try{

        }catch(Exception $e){
                
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
}

?>