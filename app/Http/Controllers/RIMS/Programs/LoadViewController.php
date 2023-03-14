<?php

namespace App\Http\Controllers\RIMS\Programs;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EducCourses;
use App\Models\EducCurriculum;
use App\Models\EducYearLevel;
use App\Models\EducGradePeriod;

class LoadViewController extends Controller
{
    public function curriculumTable(Request $request){
        $data = array();
        $user_access_level = $request->session()->get('user_access_level');
        if(isset($request->modal)){
            $id = $request->id;
            $curriculum = EducCurriculum::with('status')->where('program_id',$id)->orderBy('year_from','DESC')->first();
            $id = $curriculum->id;
        }else{
            $id = $request->id;
        }
        
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
}