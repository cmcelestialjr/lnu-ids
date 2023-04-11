<?php

namespace App\Http\Controllers\RIMS\Departments;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EducDepartments;
use App\Models\EducPrograms;

class LoadTableController extends Controller
{
    public function viewTable(Request $request){
        $data = array();
        $query = EducDepartments::with('programs')->orderBy('name')->get();
        $count = $query->count();
        if($count>0){
            $x = 1;            
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r->name;
                $data_list['f3'] = $r->shorten;
                $data_list['f4'] = $r->code;
                $data_list['f5'] = '<button class="btn btn-primary btn-primary-scan programsModal"
                                        data-id="'.$r->id.'">
                                        <span class="fa fa-graduation-cap"></span> Programs
                                    </button>';
                $data_list['f6'] = '<button class="btn btn-info btn-info-scan editModal"
                                        data-id="'.$r->id.'">
                                        <span class="fa fa-edit"></span> Edit
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    public function programsList(Request $request){
        $data = array();
        $id = $request->id;
        $query = EducPrograms::with('status','codes')->where('department_id',$id)->get();
        $count = $query->count();
        if($count>0){
            $x = 1;            
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r->name;
                $data_list['f3'] = $r->shorten;
                $codes = array();
                foreach($r->codes as $c){
                    $codes[] = $c->name;
                }
                $code = implode(', ',$codes);
                $data_list['f4'] = $codes;
                if($r->status_id==1){
                    $data_list['f5'] = '<button class="btn btn-success btn-success-scan">
                                            Open
                                        </button>';
                }else{
                    $data_list['f5'] = '<button class="btn btn-danger btn-danger-scan">
                                            '.$r->status->name.'
                                        </button>';
                }                
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    public function programAddList(Request $request){
        $data = array();
        $id = $request->id;
        $department = EducDepartments::where('id','<>',$id)->orderBy('name')->pluck('id')->toArray();
        $query = EducPrograms::with('departments','status','codes')->whereIn('department_id',$department)->get();
        $count = $query->count();
        if($count>0){
            $x = 1;            
            foreach($query as $r){
                $data_list['f1'] = '<input type="checkbox" class="form-control program" data-id="'.$r->id.'">';
                $data_list['f2'] = $x;
                $data_list['f3'] = '<span id="programDeptName'.$r->id.'">'.$r->departments->name.' ('.$r->departments->shorten.')</span>';
                $data_list['f4'] = $r->name;
                $data_list['f5'] = $r->shorten;
                $codes = array();
                foreach($r->codes as $c){
                    $codes[] = $c->name;
                }
                $code = implode(', ',$codes);
                $data_list['f6'] = $codes;
                if($r->status_id==1){
                    $data_list['f7'] = '<button class="btn btn-success btn-success-scan">
                                            Open
                                        </button>';
                }else{
                    $data_list['f7'] = '<button class="btn btn-danger btn-danger-scan">
                                            '.$r->status->name.'
                                        </button>';
                }
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
}

?>