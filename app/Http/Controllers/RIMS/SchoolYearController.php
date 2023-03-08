<?php

namespace App\Http\Controllers\RIMS;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\EducCourses;
use App\Models\EducDepartments;
use App\Models\EducGradePeriod;
use App\Models\EducPrograms;
use App\Models\EducProgramsCode;
use App\Models\EducCurriculum;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedDepartment;
use App\Models\EducOfferedPrograms;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducTimeMax;
use Exception;

class SchoolYearController extends Controller
{
    public function new(Request $request){
        $user = Auth::user();
        $updated_by = $user->id;
        $year_from = $request->year_from;
        $year_to = $request->year_to;
        $grade_period = $request->grade_period;
        $date_duration = $request->date_duration;
        $date_extension = $request->date_extension;
        $enrollment_duration = $request->enrollment_duration;
        $enrollment_extension = $request->enrollment_extension;
        $add_dropping_duration = $request->add_dropping_duration;
        $add_dropping_extension = $request->add_dropping_extension;
        $exp_date_duration = explode(' - ',$date_duration);
        $date_from = $exp_date_duration[0];
        $date_to = $exp_date_duration[0];
        $exp_enrollment_duration = explode(' - ',$enrollment_duration);
        $enrollment_from = $exp_enrollment_duration[0];
        $enrollment_to = $exp_enrollment_duration[0];
        $exp_add_dropping_duration = explode(' - ',$add_dropping_duration);
        $add_dropping_from = $exp_add_dropping_duration[0];
        $add_dropping_to = $exp_add_dropping_duration[0];
        $id = '';
        $check = EducOfferedSchoolYear::where('year_from',$year_from)
                    ->where('year_to',$year_to)
                    ->where('grade_period_id',$grade_period)
                    ->first();
        if($check==NULL){
            try{
                $insert = new EducOfferedSchoolYear; 
                $insert->year_from = $year_from;
                $insert->year_to = $year_to;
                $insert->grade_period_id = $grade_period;
                $insert->date_from = $date_from;
                $insert->date_to = $date_to;
                $insert->date_extension = $date_extension;
                $insert->enrollment_from = $enrollment_from;
                $insert->enrollment_to = $enrollment_to;
                $insert->enrollment_extension = $enrollment_extension;
                $insert->add_dropping_from = $add_dropping_from;
                $insert->add_dropping_to = $add_dropping_to;
                $insert->add_dropping_extension = $add_dropping_extension;
                $insert->updated_by = $updated_by;
                $insert->save();
                $id = $insert->id;
                //$id = 17;
                $result = 'success';
            }catch(Exception $e){
                $result = 'error';
            }
        }else{
            $result = 'exists';
        }
        $response = array('result' => $result,
                          'id' => $id);
        return response()->json($response);
    }
    public function programs(Request $request){
        $id = $request->id;
        $query = EducOfferedSchoolYear::with('grade_period')->where('id',$id)->first();
        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('rims/schoolYear/modal_programs',$data);
    }
    public function editView(Request $request){
        $id = $request->id;
        $query = EducOfferedSchoolYear::with('grade_period')->where('id',$id)->first();
        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('rims/schoolYear/modal_edit',$data);
    }
    public function viewTable(Request $request){
        $data = array();
        $user = Auth::user();
        $query = EducOfferedSchoolYear::with('grade_period')->orderBy('id','DESC')->get();
        $count = $query->count();
        if($count>0){
            $x = 1;            
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r->year_from.' - '.$r->year_to;
                $data_list['f3'] = $r->grade_period->name;
                $data_list['f4'] = date('M d, y',strtotime($r->date_from)).' - '.date('M d, y',strtotime($r->date_to)).'<br> Extension:<br>'.date('M d, y',strtotime($r->date_extension));
                $data_list['f5'] = date('M d, y',strtotime($r->enrollment_from)).' - '.date('M d, y',strtotime($r->enrollment_to)).'<br> Extension:<br>'.date('M d, y',strtotime($r->enrollment_extension));
                $data_list['f6'] = date('M d, y',strtotime($r->add_dropping_from)).' - '.date('M d, y',strtotime($r->add_dropping_to)).'<br> Extension:<br>'.date('M d, y',strtotime($r->add_dropping_extension));
                $data_list['f7'] = '<button class="btn btn-info btn-info-scan schoolYearEdit"
                                        data-id="'.$r->id.'">
                                        <span class="fa fa-edit"></span>
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    public function moveProgram(Request $request){
        $user = Auth::user();
        $id = $request->id;
        $val = $request->val;
        $result = 'error';
        $query = EducPrograms::where('id',$id)->where('status_id',$val)->first();
        if($query!=NULL){
            try{
                if($val==2){
                    $status_id = 1;
                }else{
                    $status_id = 2;
                }
                EducPrograms::where('id', $id)
                            ->update(['status_id' => $status_id,
                                      'updated_by' => $user->id,
                                      'updated_at' => date('Y-m-d H:i:s'),
                                    ]);
                $result = 'success';
            }catch(Exception $e){
                
            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    public function offerPrograms(Request $request){
        $user = Auth::user();
        $updated_by = $user->id;
        $id = $request->id;
        $time_max = EducTimeMax::first();
        $result = 'error';
        try{
            $school_year = EducOfferedSchoolYear::where('id',$id)->first();
            $grade_period = $school_year->grade_period_id;
            $query = EducProgramsCode::with('program')->where('status_id', 1)
                                            ->get()
                                            ->map(function($query) use ($id,$updated_by) {
                                            return [
                                                'school_year_id' => $id,
                                                'program_id' => $query->program_id,
                                                'department_id' => $query->program->department_id,
                                                'name' => $query->name,
                                                'updated_by' => $updated_by,
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'updated_at' => date('Y-m-d H:i:s')
                                            ];
                                        })->toArray();
            EducOfferedPrograms::insert($query);
            
            $programs_id = EducOfferedPrograms::where('school_year_id',$id)->pluck('id')->toArray();
            $department_ids = EducOfferedPrograms::where('school_year_id',$id)->pluck('department_id')->toArray();
            
            $query = EducDepartments::whereIn('id',$department_ids)->get()
                                        ->map(function($query) use ($id,$updated_by) {
                                        return [
                                                'school_year_id' => $id,
                                                'department_id' => $query->id,
                                                'name' => $query->name,
                                                'shorten' => $query->shorten,
                                                'code' => $query->code,
                                                'updated_by' => $updated_by,
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'updated_at' => date('Y-m-d H:i:s')
                                        ];
                                    })->toArray();
            EducOfferedDepartment::insert($query);

            $query = EducOfferedPrograms::join('educ_curriculum', 'educ__offered_programs.program_id', '=', 'educ_curriculum.program_id')
                                        ->select('educ_curriculum.id', 
                                                DB::raw('educ__offered_programs.id as offered_program_id'))
                                        ->where('educ__offered_programs.school_year_id', $id)
                                        ->where('educ_curriculum.status_id',1)
                                        ->get()
                                        ->map(function($query) use ($updated_by) {
                                        return [
                                                'offered_program_id' => $query->offered_program_id,
                                                'curriculum_id' => $query->id,
                                                'updated_by' => $updated_by,
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'updated_at' => date('Y-m-d H:i:s')
                                        ];
                                    })->toArray();
            EducOfferedCurriculum::insert($query);

            $query = EducOfferedCurriculum::with('offered_program','courses.grade_level')
                                        ->whereIn('offered_program_id', $programs_id)
                                        ->whereHas('courses', function($query) use ($grade_period){
                                            $query->where('status_id', '=', 1);
                                            $query->where('grade_period_id', '=', $grade_period);
                                        })
                                        ->get()
                                        ->map(function($query) use ($time_max,$updated_by) {
                                            foreach($query->courses as $row){
                                                return [
                                                        'offered_curriculum_id' => $query->id,
                                                        'course_id' => $row->id,
                                                        'min_student' => $time_max->min_student,
                                                        'max_student' => $time_max->max_student,
                                                        'code' => $row->code,
                                                        'status_id' => $row->status_id,
                                                        'section' => 1,
                                                        'section_code' => $query->offered_program->name.'1'.$row->grade_level->level,
                                                        'updated_by' => $updated_by,
                                                        'created_at' => date('Y-m-d H:i:s'),
                                                        'updated_at' => date('Y-m-d H:i:s')
                                                ];
                                            }    
                                    })->toArray();
            EducOfferedCourses::insert($query);
            $result = 'success';
        }catch(Exception $e){
            
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
}

?>