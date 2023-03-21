<?php

namespace App\Http\Controllers\RIMS\Sections;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EducOfferedPrograms;

class LoadTableController extends Controller
{
    public function viewTable(Request $request){
        $data = array();
        $id = $request->id;
        $program_id = $request->program_id;
        $offered_curriculum_ids = EducOfferedCurriculum::where('offered_program_id',$program_id)->pluck('id')->toArray();
        $query = EducOfferedCourses::select('year_level','offered_curriculum_id','section')
                    ->whereIn('offered_curriculum_id',$offered_curriculum_ids)
                    ->groupBy('year_level')->groupBy('section')->groupBy('offered_curriculum_id')
                    ->orderBy('year_level')->orderBy('offered_curriculum_id','DESC')
                    ->get()
                    ->map(function($query) use ($offered_curriculum_ids) {
                        $courses = EducOfferedCourses::with('course','curriculum.curriculum')
                                    ->where('year_level',$query->year_level)
                                    ->where('offered_curriculum_id',$query->offered_curriculum_id)
                                    ->where('section',$query->section)->first();
                        return [
                            'grade_level' => $courses->course->grade_level->name,
                            'section' => $courses->section,
                            'section_code' => $courses->section_code,
                            'curriculum' => $courses->curriculum->curriculum->year_from.' - '.$courses->curriculum->curriculum->year_to,
                            'id' => $courses->id
                        ];
                    })->toArray();
        if(count($query)>0){
            $x = 1;            
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['grade_level'];
                $data_list['f3'] = $r['section'];
                $data_list['f4'] = $r['section_code'];
                $data_list['f5'] = $r['curriculum'];
                // $data_list['f3'] = $r->section;
                // $data_list['f1'] = $r->section_code;
                // $data_list['f5'] = '<button class="btn btn-info btn-info-scan editModal"
                //                         data-id="'.$r->id.'">
                //                         <span class="fa fa-edit"></span> Edit
                //                     </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
}

?>