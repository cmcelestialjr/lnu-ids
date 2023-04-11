<?php

namespace App\Http\Controllers\RIMS\SchoolYear;
use App\Http\Controllers\Controller;
use App\Models\EducCourses;
use App\Models\EducCourseStatus;
use App\Models\EducCurriculum;
use App\Models\EducGradePeriod;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedPrograms;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducProgramsCode;
use App\Models\EducYearLevel;
use Illuminate\Http\Request;

class LoadViewController extends Controller
{
    public function curriculumSelect(Request $request){
        $id = $request->id;
        $program = $request->program;
        $offered_program_ids = EducOfferedPrograms::where('school_year_id',$id)->where('program_code_id',$program)->pluck('id')->toArray();
        $offered_curriculum_ids = EducOfferedCurriculum::whereIn('offered_program_id',$offered_program_ids)->pluck('id')->toArray();
        $offered_course_ids = EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)->pluck('course_id')->toArray();
        $program = EducProgramsCode::where('id',$program)->first();
        $curriculum_ids = EducCurriculum::where('program_id',$program->program_id)->pluck('id')->toArray();
        $curriculum_ids1 = EducCourses::whereNotIn('id',$offered_course_ids)->whereIn('curriculum_id',$curriculum_ids)->pluck('curriculum_id')->toArray();        
        $curriculums = EducCurriculum::whereIn('id',$curriculum_ids1)->orderBy('year_from','DESC')->get();
        $data = array(
            'curriculums' => $curriculums
        );
        return view('rims/schoolYear/curriculumSelect',$data);
    }
    public function curriculumList(Request $request){
        $id = $request->id;
        $curriculum = $request->curriculum;
        $user_access_level = $request->session()->get('user_access_level');
        $school_year = EducOfferedSchoolYear::where('id',$id)->first();
        $offered_curriculum_ids = EducOfferedCurriculum::where('curriculum_id',$curriculum)
                                    ->pluck('id')->toArray();
        $offered_course_ids = EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)
                                    ->pluck('course_id')->toArray();
        $year_level_ids = EducCourses::where('curriculum_id',$curriculum)
                                    ->where('status_id','<>',1)
                                    ->whereNotIn('id',$offered_course_ids)
                                    ->pluck('grade_level_id')->toArray();
      
        $year_level = EducYearLevel::whereIn('id',$year_level_ids)->get();
        $period = EducGradePeriod::with(['courses' => function ($query) 
                            use ($curriculum,$offered_course_ids) {
                            $query->where('curriculum_id', $curriculum);
                            $query->whereNotIn('id',$offered_course_ids);
                            $query->where('status_id','<>',1);
                            $query->orderBy('grade_period_id','ASC');
                            $query->orderBy('grade_level_id','ASC');
                        }])->where('id',$school_year->grade_period_id)->get();
        $data = array(
            'id' => $id,
            'user_access_level' => $user_access_level,
            'year_level' => $year_level,
            'period' => $period,
            'curriculum' => $curriculum
        );
        return view('rims/schoolYear/curriculumList',$data);
    }
    public function curriculumViewList(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $id = $request->id;
        $type = $request->type;
        $offered_program = EducOfferedPrograms::with('school_year')->where('id',$id)->first();
        if($type=='modal'){
            $curriculum_ids = EducOfferedCurriculum::where('offered_program_id',$offered_program->id)
                                        ->pluck('curriculum_id')->toArray();
            $curriculum = EducCurriculum::whereIn('id',$curriculum_ids)
                                        ->orderBy('year_from','DESC')->first();  
            $curriculum_id = $curriculum->id;
            $offered_curriculum = EducOfferedCurriculum::where('offered_program_id',$offered_program->id)
                                        ->where('curriculum_id',$curriculum_id)->first();  
            $offered_curriculum_id = $offered_curriculum->id;
        }else{
            $offered_curriculum_id = $request->curriculum_id;
            $offered_curriculum = EducOfferedCurriculum::where('id',$request->curriculum_id)->first();
            $curriculum_id = $offered_curriculum->curriculum_id;
        }
        
        $offered_courses = EducOfferedCourses::with('course','status')
                                ->where('offered_curriculum_id',$offered_curriculum_id)->get();
        $offered_course_ids = $offered_courses->pluck('course_id')->toArray();
        $year_level_ids = $offered_courses->pluck('course.grade_level_id')->toArray();
        $year_level = EducYearLevel::whereIn('id',$year_level_ids)->get();
        $period = EducGradePeriod::where('id',$offered_program->school_year->grade_period_id)->get();
        $statuses = EducCourseStatus::get();
        $data = array(
            'id' => $id,
            'user_access_level' => $user_access_level,
            'year_level' => $year_level,
            'period' => $period,
            'offered_courses' => $offered_courses,
            'statuses' => $statuses
        );
        return view('rims/schoolYear/curriculumViewList',$data);
    }
}