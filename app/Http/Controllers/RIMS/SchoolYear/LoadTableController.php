<?php

namespace App\Http\Controllers\RIMS\SchoolYear;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedPrograms;
use Illuminate\Http\Request;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducProgramsCode;

class LoadTableController extends Controller
{
    public function viewTable(Request $request){
        $data = array();
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
                $data_list['f7'] = '<button class="btn btn-primary btn-primary-scan btn-sm programsViewModal"
                                        data-id="'.$r->id.'">
                                        <span class="fa fa-eye"></span> View
                                    </button>';
                $data_list['f8'] = '<button class="btn btn-info btn-info-scan btn-sm schoolYearEdit"
                                        data-id="'.$r->id.'">
                                        <span class="fa fa-edit"></span>
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    public function programsViewTable(Request $request){
        $data = array();
        $id = $request->id;
        $query = EducOfferedPrograms::with('program.program_level','department')
                        ->where('school_year_id',$id)
                        ->orderBy('program_id')
                        ->orderBY('name')->get();
        $count = $query->count();
        if($count>0){
            $x = 1;            
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r->program->program_level->name;
                $data_list['f3'] = $r->department->shorten;
                $data_list['f4'] = $r->program->shorten;
                $data_list['f5'] = $r->name;
                $data_list['f6'] = '<button class="btn btn-primary btn-primary-scan btn-sm coursesViewModal"
                                        data-id="'.$r->id.'">
                                        <span class="fa fa-eye"></span> View
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
}

?>