<?php

namespace App\Http\Controllers\FIS\Students;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedSchoolYear;
use App\Models\StudentsCourses;
use App\Models\StudentsInfo;
use App\Services\NameServices;
use Illuminate\Http\Request;

class LoadTableController extends Controller
{
    public function studentsTable(Request $request){
        $data = array();
        $name_services = new NameServices;
        $school_year = $request->school_year;
        $level = $request->level;
        $query = StudentsInfo::with('info','program.program_level','grade_level')
            ->whereHas('courses', function ($query) use ($school_year,$level) {
                $query->where('school_year_id',$school_year);
                if($level!=''){
                    foreach($level as $row){
                        $levels[] = $row;
                    }
                    $query->whereIn('program_level_id',$levels);
                }else{
                    $query->where('program_level_id','>',0);
                }
            })->get()
            ->map(function($query) use ($name_services) {
                $name = $name_services->lastname($query->info->lastname,$query->info->firstname,$query->info->middlename,$query->info->extname);
                return [
                    'id' => $query->user_id,
                    'program_level' => $query->program->program_level->name,                            
                    'program' => $query->program->name.' ('.$query->program->shorten.')',
                    'name' => $name,
                    'id_no' => $query->id_no,
                    'level' => $query->grade_level->name
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['id_no'];
                $data_list['f4'] = $r['program_level'];
                $data_list['f5'] = $r['program'];
                $data_list['f6'] = $r['level'];
                $data_list['f7'] = '<button class="btn btn-primary btn-primary-scan studentView"
                                        data-id="'.$r['id'].'"
                                        <span class="fa fa-eye"></span> View
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }    
    public function studentSchoolYearTable(Request $request){
        $data = array();
        $id = $request->id;
        $school_year_ids = StudentsCourses::where('user_id',$id)
            ->select('school_year_id')
            ->groupBY('school_year_id')
            ->pluck('school_year_id')
            ->toArray();
        $query = EducOfferedSchoolYear::with('grade_period')
            ->whereIn('id',$school_year_ids)
            ->orderBy('year_from','DESC')
            ->get()
            ->map(function($query) use ($id) {
                $course = StudentsCourses::with('program.program_level','program.program_info','grade_level')
                    ->where('user_id',$id)
                    ->where('school_year_id',$query->id);
                $course_count = $course->count();
                $course_first = $course->first();
                return [
                    'id' => $query->id,
                    'school_year' => $query->year_from.'-'.$query->year_to.' ('.$query->grade_period->name.')',                            
                    'program_level' => $course_first->program->program_level->name,
                    'program' => $course_first->program->program_info->name.' ('.$course_first->program->program_info->shorten.')',
                    'grade_level' => $course_first->grade_level->name,
                    'course_count' => $course_count
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;                
                $data_list['f2'] = $r['school_year'];
                $data_list['f3'] = $r['program_level'];
                $data_list['f4'] = $r['program'];
                $data_list['f5'] = $r['grade_level'];
                $data_list['f6'] = '<button class="btn btn-primary btn-primary-scan studentCoursesModal"
                                        data-id="'.$r['id'].'"
                                        <span class="fa fa-eye"></span> '.$r['course_count'].'
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
}

?>