<?php

namespace App\Http\Controllers\RIMS\Programs;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EducCourses;
use App\Models\EducCoursesPre;
use App\Models\EducCourseStatus;
use App\Models\EducCurriculum;
use App\Models\EducYearLevel;
use App\Models\EducGradePeriod;
use App\Models\EducLabGroup;

class LoadViewController extends Controller
{
    public function curriculumTable(Request $request){
        $data = array();
        $user_access_level = $request->session()->get('user_access_level');
        if(isset($request->modal)){
            $id = $request->id;
            $curriculum = EducCurriculum::with('status')->where('program_id',$id)->orderBy('year_from','DESC')->first();
            if($curriculum!=NULL){
                $id = $curriculum->id;
            }else{
                $id = 0;
            }
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
        $period_ids = EducCourses::where('curriculum_id',$id)
                        ->$where_level('grade_level_id',$value_level)
                        ->$where_status('status_id',$value_status)->pluck('grade_period_id')->toArray();
        $year_level_ids = EducCourses::where('curriculum_id',$id)
                        ->$where_level('grade_level_id',$value_level)
                        ->$where_status('status_id',$value_status)->pluck('grade_level_id')->toArray();
        $year_level = EducYearLevel::whereIn('id',$year_level_ids)->get();
        if($query->count()>0){
            $period = EducGradePeriod::with(['courses' => function ($query) 
                            use ($where_status,$id,$value_status) {
                            $query->where('curriculum_id', $id);
                            $query->$where_status('status_id', $value_status);
                            $query->orderBy('grade_period_id','ASC');
                            $query->orderBy('grade_level_id','ASC');
                        }])->whereIn('id',$period_ids)->get();
        }else{
            $period = NULL;
        }
        $data = array(
            'id' => $id,
            'query' => $query,
            'user_access_level' => $user_access_level,
            'year_level' => $year_level,
            'period' => $period,
            'pre_req' => array()
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

        $period_ids = EducCourses::where('curriculum_id',$id)
                        ->$where_level('grade_level_id',$value_level)
                        ->$where_status('status_id',$value_status)->pluck('grade_period_id')->toArray();

        $year_level_ids = EducCourses::where('curriculum_id',$id)
                        ->$where_level('grade_level_id',$value_level)
                        ->$where_status('status_id',$value_status)->pluck('grade_level_id')->toArray();

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
            'period' => $period,
            'pre_req' => array()
        );
        return view('rims/programs/curriculumTablePre',$data);
    }
    public function courseTablePre(Request $request){
        $data = array();
        $user_access_level = $request->session()->get('user_access_level');
        $course_id = $request->id;
        $query = EducCourses::where('id',$course_id)->first();
        $id = $query->curriculum_id;
        $pre_req = EducCoursesPre::where('course_id',$course_id)->pluck('pre_id')->toArray();
        $query = EducCourses::with('grade_period','grade_level','status')
            ->where('curriculum_id',$id)
            ->orderBy('grade_period_id','ASC')
            ->orderBy('grade_level_id','ASC')->get();
        $period_ids = EducCourses::where('curriculum_id',$id)->pluck('grade_period_id')->toArray();
        $year_level_ids = EducCourses::where('curriculum_id',$id)->pluck('grade_level_id')->toArray();
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
            'period' => $period,
            'pre_req' => $pre_req
        );
        return view('rims/programs/curriculumTablePre',$data);
    }
    public function curriculumInfo(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $id = $request->id;
        $curriculum = EducCurriculum::with('status')->where('id',$id)->orderBy('year_from','DESC')->first();
        $curriculums = EducCurriculum::with('status')->where('program_id',$curriculum->programs->id)->orderBy('year_from','DESC')->get();
        $status = EducCourseStatus::get();
        $year_level = EducYearLevel::where('program_level_id',$curriculum->programs->program_level_id)->orderBy('level','ASC')->get();       
        $data = array(
            'id' => $id,
            'curriculum' => $curriculum,
            'curriculums' => $curriculums,
            'year_level' => $year_level,
            'status' => $status,
            'user_access_level' => $user_access_level            
        );
        return view('rims/programs/curriculumInfo',$data);
    }
    public function courseInfo(Request $request){
        $id = $request->id;
        $course = EducCourses::where('code',$id)->first();
        $year_level = EducYearLevel::where('program_level_id',$course->curriculum->programs->program_level_id)->orderBy('level','ASC')->get();
        $grade_period = EducGradePeriod::get();
        $lab_group = EducLabGroup::get();
        $data = array(
            'id' => $id,
            'year_level' => $year_level,
            'grade_period' => $grade_period,
            'course' => $course,
            'lab_group' => $lab_group
        );
        return view('rims/programs/courseInfo',$data);
    }
}