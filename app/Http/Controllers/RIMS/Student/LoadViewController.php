<?php

namespace App\Http\Controllers\RIMS\Student;
use App\Http\Controllers\Controller;
use App\Models\EducCourses;
use App\Models\EducGradePeriod;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducProgramLevel;
use App\Models\EducPrograms;
use App\Models\StudentsCourses;
use App\Models\StudentsInfo;
use App\Models\StudentsProgram;
use App\Models\Users;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoadViewController extends Controller
{
    public function searchStudent(Request $request){
        $search = $request->input('search');
        $name_services = new NameServices;
        $results = Users::where(function($query) use ($search) {
                        $query->where('lastname', 'LIKE', "%$search%")
                            ->orWhere('firstname', 'LIKE', "%$search%")
                            ->orWhere('middlename', 'LIKE', "%$search%");
                    })
                    ->limit(10)
                    ->get();

        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {
                $name = $name_services->lastname($result->lastname,$result->firstname,$result->middlename,$result->extname);
                $data[] = ['id' => $result->id, 'text' => $name];
            }
        }
        return response()->json($data);
    }
    public function searchStudents(Request $request){
        $name_services = new NameServices;
        $search = $request->input('search');
        $school_year_id = $request->school_year_id;
        $school_year = EducOfferedSchoolYear::where('id',$school_year_id)->first();
        $period = $school_year->grade_period->period;
        if($period=='sum'){
            $program_ids = EducPrograms::pluck('id')->toArray();
        }else{
            $program_ids = EducPrograms::whereHas('program_level', function ($query) use ($period) {
                            $query->where('period', $period);
                        })->pluck('id')->toArray();
        }        
        $results = Users::where(function($query) use ($search) {
                        $query->where('lastname', 'LIKE', "%$search%")
                            ->orWhere('firstname', 'LIKE', "%$search%")
                            ->orWhere('middlename', 'LIKE', "%$search%");
                    })
                    ->whereHas('student_info', function ($query) use ($program_ids) {
                        $query->whereIn('program_id', $program_ids);
                    })
                    ->limit(10)
                    ->get();
        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {
                $name = $name_services->lastname($result->lastname,$result->firstname,$result->middlename,$result->extname);
                $data[] = ['id' => $result->id, 'text' => $name];
            }
        }
        return response()->json($data);
    }   
    public function studentTORDiv(Request $request){
        $id = $request->id;
        $program_level = $request->program_level;
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $query = StudentsProgram::where('user_id',$id)
            ->where('program_level_id',$program_level)
            ->select('from_school')
            ->groupBy('from_school')
            ->orderBy('year_from')
            ->get()
            ->map(function($query) use ($id,$program_level) {
                $program_ids = StudentsProgram::where('user_id',$id)
                    ->where('from_school',$query->from_school)
                    ->where('program_level_id',$program_level)
                    ->pluck('id')->toArray();
                $year_period = StudentsCourses::whereIn('student_program_id',$program_ids)
                    ->select('year_from','year_to','grade_period_id')
                    ->groupBy('year_from')
                    ->groupBy('grade_period_id')
                    ->orderBy('year_from','ASC')
                    ->orderBy('grade_period_id','ASC')
                    ->get()
                    ->map(function($query) use ($id){
                        $grade_period = EducGradePeriod::where('id',$query->grade_period_id)->first();
                        $courses = StudentsCourses::where('user_id',$id)
                            ->where('grade_period_id',$query->grade_period_id)
                            ->where('year_from',$query->year_from)
                            ->get();
                        return [
                            'grade_period' => $grade_period->name,
                            'period' => $query->year_from.'-'.$query->year_to,
                            'courses' => $courses
                        ];
                    });
                return [
                    'from_school' => $query->from_school,
                    'year_period' => $year_period
                ];
            })->toArray();
        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('rims/student/studentTORDiv',$data);
    } 
    public function studentCurriculumDiv(Request $request){
        $id = $request->id;
        $program_level_id = $request->program_level;
        $student_program = StudentsProgram::where('user_id',$id)
            ->where('program_level_id',$program_level_id)
            ->orderBy('year_from','DESC')
            ->first();
        $curriculum_id = $student_program->curriculum_id;
        $query = EducCourses::where('curriculum_id',$curriculum_id)
            ->select('grade_level_id')
            ->groupBy('grade_level_id')
            ->orderBy('grade_level_id','ASC')
            ->get()
            ->map(function($query) use ($id,$curriculum_id) {
                $grade_level_id = $query->grade_level_id;
                $grade_period = EducCourses::where('curriculum_id',$curriculum_id)
                    ->select('grade_period_id')
                    ->groupBy('grade_period_id')
                    ->orderBy('grade_period_id','ASC')
                    ->get()
                    ->map(function($query) use ($id,$curriculum_id,$grade_level_id) {
                        $grade_period_id = $query->grade_period_id;
                        $courses = EducCourses::where('curriculum_id',$curriculum_id)
                            ->where('grade_level_id',$grade_level_id)
                            ->where('grade_period_id',$grade_period_id)
                            ->get()
                            ->map(function($query) use ($id,$curriculum_id,$grade_level_id,$grade_period_id) {
                                $status = '<button class="btn btn-default btn-xs">Untaken</button>';
                                $check = StudentsCourses::where('user_id',$id)
                                    ->where('course_id',$query->id)
                                    ->orderBy('year_from','DESC')
                                    ->first();
                                if($check!=NULL){
                                    if($check->student_course_status_id==NULL){
                                        $status = '<button class="btn btn-info btn-info-scan btn-xs">NG</button>';
                                    }else{
                                        if($check->student_course_status_id==1){
                                            $status = '<button class="btn btn-success btn-success-scan btn-xs">'.$check->status->name.'</button>';
                                        }else{
                                            $status = '<button class="btn btn-danger btn-danger-scan btn-xs">'.$check->status->name.'</button>';
                                        }
                                    }
                                }
                                return [
                                    'code' => $query->code,
                                    'name' => $query->name,
                                    'units' => $query->units,
                                    'lab' => $query->lab,
                                    'pre_name' => $query->pre_name,
                                    'status' => $status
                                ];
                            })->toArray();
                        return [
                            'grade_period' => $query->grade_period->name,
                            'courses' => $courses
                        ];
                    })->toArray();
                return [
                    'year_level' => $query->grade_level->name,
                    'grade_period' => $grade_period
                ];
            })->toArray();
        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('rims/student/studentCurriculumDiv',$data);
    }
}