<?php

namespace App\Http\Controllers\RIMS\Programs;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EducPrograms;

class LoadTableController extends Controller
{
    public function viewTable(Request $request){
        $user_access_level = $request->session()->get('user_access_level'); 
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
                if($user_access_level==1 || $user_access_level==2){
                    if($r->status->id==1){
                        $status = '<button class="btn btn-success programStatus"
                                        id="programStatus'.$r->id.'"
                                        data-id="'.$r->id.'"
                                        >'.$r->status->name.'</button>';
                    }else{
                        $status = '<button class="btn btn-danger programStatus"
                                        id="programStatus'.$r->id.'"
                                        data-id="'.$r->id.'"
                                        >'.$r->status->name.'</button>';
                    }
                }else{
                    if($r->status->id==1){
                        $status = '<button class="btn btn-success">'.$r->status->name.'</button>';
                    }else{
                        $status = '<button class="btn btn-danger">'.$r->status->name.'</button>';
                    }
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
}