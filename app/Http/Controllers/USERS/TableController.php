<?php

namespace App\Http\Controllers\USERS;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Users;
use App\Services\NameServices;
use App\Services\EncryptServices;

class TableController extends Controller
{
    public function table(Request $request){
        $data = array();
        $user = Auth::user();
        $name_services = new NameServices;
        $encrypt = new EncryptServices;
        $system_selected = $request->session()->get('system_selected');
        $val = $request->val;        
        if($user->level_id==1){
            if($val=='Employee'){
                $role_id = 2;
            }elseif($val=='Student'){
                $role_id = 1;
            }
            $query = Users::with('statuses')
                        ->whereHas('user_role', function($query) use ($role_id){
                            $query->where('role_id', $role_id);
                        })
                        ->get();
        }else{
            $role_id = 2;
            $query = Users::with('statuses')
                        ->whereHas('user_role', function($query) use ($role_id){
                            $query->where('role_id', $role_id);
                        })
                        ->where('level_id','>',1)->get();
        }
        $count = $query->count();
        if($count>0){
            $x = 1;            
            foreach($query as $r){
                $name = $name_services->lastname($r->lastname,$r->firstname,$r->middlename,$r->extname);
                $encrypt_id = $encrypt->encrypt($r->id);
                if($r->level_id==1){
                    $user_access = '<button class="btn btn-success btn-success-scan"><span class="fa fa-check"></span> With</button>';
                }else{
                    $user_access = '<button class="btn btn-danger btn-danger-scan"><span class="fa fa-times"></span> None</button>';
                }
                $data_list['f1'] = $x;
                $data_list['f2'] = $name;
                if($role_id==2){
                    $data_list['f3'] = '<button class="btn btn-primary btn-primary-scan access" data-id="'.$encrypt_id.'">
                                            <span class="fa fa-edit"></span> Access</button>';
                }
                if($system_selected=='USERS'){
                    $data_list['f4'] = $user_access;
                }
                if($user->level_id==1){
                    $class = 'status';
                    $data_id = 'data-id="'.$encrypt_id.'"';
                }else{
                    $class = '';
                    $data_id = '';
                }
                $data_list['f5'] = '<button class="'.$r->statuses->button.' '.$class.'"
                                        '.$data_id.'>
                                        <span class="'.$r->statuses->icon.'"></span> '.$r->statuses->name.'</button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
}