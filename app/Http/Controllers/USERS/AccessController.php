<?php

namespace App\Http\Controllers\USERS;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Models\Users;
use App\Models\Systems;
use App\Models\SystemsNav;
use App\Models\SystemsNavSub;
use App\Models\UsersLevel;
use App\Models\UsersSystems;
use App\Models\UsersSystemsNav;
use App\Models\UsersSystemsNavSub;
use App\Services\EncryptServices;
use Exception;

class AccessController extends Controller
{
    public function access(Request $request){
        $encrypt = new EncryptServices;
        $decrypt_id = $encrypt->decrypt($request->id);
        $id = $request->id;
        $user = Users::where('id',$decrypt_id)->first();
        $system_selected = $request->session()->get('system_selected');
       // $system_selected = 'SIMS';
        if($user->level_id==1){
            $levels = UsersLevel::get();
        }else{
            $levels = UsersLevel::where('id','>',1)->get();
        }
        if($system_selected=='USERS'){
            $page = 'modal_access_users';
            $systems = Systems::with(['user_system' => function($query) use ($decrypt_id){
                                    $query->where('user_id',$decrypt_id);
                                }])->get();
            $systems_name = Systems::where('id',1)->first(); 
            $systems_id = $systems_name->id;
            $system_selected = $systems_name->shorten;
        }else{
            $page = 'modal_access_system';
            $systems = Systems::where('shorten',$system_selected)->first();   
            $systems_id = $systems->id;   
            $system_selected = $systems->shorten;     
        }       
        $systems_nav_selected = SystemsNav::where('system_id',$systems_id)->first(); 
        $systems_nav = SystemsNav::with(['user_nav' => function($query) use ($decrypt_id){
                            $query->where('user_id',$decrypt_id);
                        }])->where('system_id',$systems_id)->orderBy('order')->get();
        $systems_nav_sub = SystemsNavSub::with(['user_nav_sub' => function($query) use ($decrypt_id){
                            $query->where('user_id',$decrypt_id);
                        }])->whereHas('system_nav', function($query) use ($systems_id){
                            $query->where('system_id', '=', $systems_id);
                        })->where('system_nav_id',$systems_nav_selected->id)->orderBy('order')->get();
        
        if($systems_nav_selected!=NULL){
            $systems_nav_selected = '('.$systems_nav_selected->name.')';
        }else{
            $systems_nav_selected = '';
        }
        $data = array(
            'id' => $id,
            'systems' => $systems,
            'systems_nav' => $systems_nav,
            'systems_nav_sub' => $systems_nav_sub,
            'system_selected' => $system_selected,
            'systems_nav_selected' => $systems_nav_selected,
            'levels' => $levels
        );
        return view('users/'.$page,$data);        
    }
    public function update(Request $request){
        $result = 'error';       
        $val = $request->val; 
        $system_selected = $request->session()->get('system_selected');
        $system_id = $request->system_id;
        if($val=='system'){
            $from = 'system';
            if($system_selected=='USERS'){
                $result = $this->update_system($request);
            }else{            
                $system = Systems::where('shorten',$system_selected)->first();
                if($system_id==$system->id){
                    $result = $this->update_system($request);
                }
            }
        }elseif($val=='nav'){   
            $from = 'nav';  
            $result = $this->update_nav($request,'nav');
        }elseif($val=='nav_sub'){
            $system_nav_sub = SystemsNavSub::where('id',$system_id)->first();
            $system_id = $system_nav_sub->system_nav_id;
            $from = 'nav';
            $result = $this->update_nav_sub($request,'nav_sub');
        }
        $response = array('result' => $result,
                          'system_id' => $system_id,
                          'from' => $from);
        return response()->json($response);
    }
    public function listNav(Request $request){
        $encrypt = new EncryptServices;
        $user_id = $encrypt->decrypt($request->id);
        $system_id = $request->system_id;
        $user = Users::where('id',$user_id)->first();
        if($user->level_id==1){
            $levels = UsersLevel::get();
        }else{
            $levels = UsersLevel::where('id','>',1)->get();
        }
        if($request->from=='system'){
            $system_selected = Systems::where('id',$system_id)->first();
            $systems_nav = SystemsNav::with(['user_nav' => function($query) use ($user_id){
                                $query->where('user_id',$user_id);
                            }])->where('system_id',$system_id)->orderBy('order')->get();
        }else{
            $systems_nav = SystemsNav::where('id',$system_id)->first();
            $system_id = $systems_nav->system_id;
            $system_selected = Systems::where('id',$system_id)->first();
            $systems_nav = SystemsNav::with(['user_nav' => function($query) use ($user_id){
                                $query->where('user_id',$user_id);
                            }])->where('system_id',$system_id)->orderBy('order')->get();
        }
        $data = array(
            'system_selected' => $system_selected,
            'systems_nav' => $systems_nav,
            'levels' => $levels
        );
        return view('users/modal_list_nav',$data);  
    }
    public function listNavSub(Request $request){
        $encrypt = new EncryptServices;
        $user_id = $encrypt->decrypt($request->id);
        $system_id = $request->system_id;
        $user = Users::where('id',$user_id)->first();
        if($user->level_id==1){
            $levels = UsersLevel::get();
        }else{
            $levels = UsersLevel::where('id','>',1)->get();
        }
        if($request->from=='system'){
            $system_selected = SystemsNav::where('system_id',$system_id)->first();
        }else{
            $system_selected = SystemsNav::where('id',$system_id)->first(); 
        }
        $system_id = $system_selected->id;
        $systems_nav_sub = SystemsNavSub::with(['user_nav_sub' => function($query) use ($user_id){
                                $query->where('user_id',$user_id);
                            }])->where('system_nav_id',$system_id)->orderBy('order')->get();
        $data = array(
            'system_selected' => $system_selected,
            'systems_nav_sub' => $systems_nav_sub,
            'levels' => $levels
        );
        return view('users/modal_list_nav_sub',$data);  
    }
    private function update_system($request){
        $user = Auth::user();
        $updated_by = $user->id;
        $encrypt = new EncryptServices;
        $id = $encrypt->decrypt($request->id);
        $system_id = $request->system_id;
        $level_id = $request->level_id;
        $user_selected = Users::where('id',$id)->first();
        $error = 0;
        $result = 'error';
        if($user_selected->level_id!=1){
            if($level_id==1){
                $error++;
            }
        }
        if($error==0){
            try {
                $role_id = $user_selected->employee_default->role_id;
                if($level_id==''){
                    $delete = UsersSystems::where('system_id', $system_id)
                                ->where('user_id', $id)
                                ->where('role_id', $role_id)->delete();
                    $auto_increment = DB::update("ALTER TABLE users_systems AUTO_INCREMENT = 0;");
                }else{
                    if($level_id==1){
                        $delete = UsersSystems::where('user_id', $id)
                                        ->where('role_id', $role_id)->delete();
                        $auto_increment = DB::update("ALTER TABLE users_systems AUTO_INCREMENT = 0;");
                        $query = Systems::get()
                                        ->map(function($query) use ($id,$updated_by,$role_id) {
                                        return [
                                            'user_id' => $id,
                                            'system_id' => $query->id,
                                            'role_id' => $role_id,
                                            'level_id' => 1,
                                            'updated_by' => $updated_by,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')
                                        ];
                                    })->toArray();
                        UsersSystems::insert($query);
                    }else{
                        $check  = UsersSystems::where('system_id', $system_id)
                                    ->where('user_id', $id)
                                    ->where('role_id', $role_id)->first();
                        if($check==NULL){
                            $insert = new UsersSystems; 
                            $insert->user_id = $id;
                            $insert->system_id = $system_id;
                            $insert->role_id = $role_id;
                            $insert->level_id = $level_id;
                            $insert->updated_by = $user->id;                        
                            $insert->save();
                        }else{
                            UsersSystems::where('system_id', $system_id)
                            ->where('user_id', $id)
                            ->where('role_id', $role_id)
                            ->update(['level_id' => $level_id]);
                        }
                    }
                }
                $this->update_nav($request,'system');
                $result = 'success';
            }catch(Exception $e) {
                
            }
        }
        return $result;
    }
    private function update_nav($request,$from){
        $user = Auth::user();
        $updated_by = $user->id;
        $encrypt = new EncryptServices;
        $id = $encrypt->decrypt($request->id);
        $system_id = $request->system_id;
        $level_id = $request->level_id;
        $user_selected = Users::where('id',$id)->first();
        $error = 0;
        $result = 'error';
        if($user_selected->level_id!=1){
            if($level_id==1){
                $error++;
            }
        }
        if($error==0){
            try{
                $role_id = $user_selected->employee_default->role_id;
                if($from=='system'){
                    $system_nav_ids = SystemsNav::where('system_id',$system_id)->pluck('id')->toArray();
                    if($level_id=='' || $level_id==1 || $level_id==2){                        
                        $delete = UsersSystemsNav::whereIn('system_nav_id', $system_nav_ids)
                                    ->where('user_id', $id)
                                    ->where('role_id', $role_id)->delete();
                        $auto_increment = DB::update("ALTER TABLE users_systems_nav AUTO_INCREMENT = 0;");
                        if($level_id==2){
                            $query = SystemsNav::where('system_id',$system_id)
                                        ->get()
                                        ->map(function($query) use ($id,$updated_by,$role_id) {
                                        return [
                                            'user_id' => $id,
                                            'system_nav_id' => $query->id,
                                            'role_id' => $role_id,
                                            'level_id' => 2,
                                            'updated_by' => $updated_by,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')
                                        ];
                                    })->toArray();
                            UsersSystemsNav::insert($query);
                        }elseif($level_id==1){                                                        
                            $delete = UsersSystemsNav::where('user_id', $id)
                                        ->where('role_id', $role_id)->delete();
                            $auto_increment = DB::update("ALTER TABLE users_systems_nav AUTO_INCREMENT = 0;");
                            $query = SystemsNav::get()
                                        ->map(function($query) use ($id,$updated_by,$role_id) {
                                        return [
                                            'user_id' => $id,
                                            'system_nav_id' => $query->id,
                                            'role_id' => $role_id,
                                            'level_id' => 1,
                                            'updated_by' => $updated_by,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')
                                        ];
                                    })->toArray();
                            UsersSystemsNav::insert($query);
                        }
                    }else{
                        UsersSystemsNav::whereIn('system_nav_id', $system_nav_ids)
                            ->where('user_id', $id)
                            ->where('role_id', $role_id)
                            ->update(['level_id' => $level_id]);
                    }
                    $this->update_nav_sub($request,'system');
                }else{
                    if($level_id==1){
                        $delete = UsersSystems::where('user_id', $id)
                                    ->where('role_id', $role_id)->delete();
                        $auto_increment = DB::update("ALTER TABLE users_systems AUTO_INCREMENT = 0;");
                        $query = Systems::get()
                                            ->map(function($query) use ($id,$updated_by,$role_id) {
                                            return [
                                                'user_id' => $id,
                                                'system_id' => $query->id,
                                                'role_id' => $role_id,
                                                'level_id' => 1,
                                                'updated_by' => $updated_by,
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'updated_at' => date('Y-m-d H:i:s')
                                            ];
                                        })->toArray();
                        UsersSystems::insert($query);
                        $delete = UsersSystemsNav::where('user_id', $id)
                                        ->where('role_id', $role_id)->delete();
                        $auto_increment = DB::update("ALTER TABLE users_systems_nav AUTO_INCREMENT = 0;");
                        $query = SystemsNav::get()
                                        ->map(function($query) use ($id,$updated_by,$role_id) {
                                        return [
                                            'user_id' => $id,
                                            'system_nav_id' => $query->id,
                                            'role_id' => $role_id,
                                            'level_id' => 1,
                                            'updated_by' => $updated_by,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')
                                        ];
                                    })->toArray();
                        UsersSystemsNav::insert($query);
                    }elseif($level_id==''){
                        $delete = UsersSystemsNav::where('system_nav_id', $system_id)
                                    ->where('user_id', $id)
                                    ->where('role_id', $role_id)->delete();
                        $auto_increment = DB::update("ALTER TABLE users_systems_nav AUTO_INCREMENT = 0;");
                    }else{
                        $insert = new UsersSystemsNav();
                        $insert->user_id = $id;
                        $insert->system_nav_id = $system_id;
                        $insert->role_id = $role_id;
                        $insert->level_id = $level_id;
                        $insert->updated_by = $updated_by;
                        $insert->save();
                    }
                    UsersSystemsNav::where('system_nav_id', $system_id)
                            ->where('user_id', $id)
                            ->where('role_id', $role_id)
                            ->update(['level_id' => $level_id]);
                    $this->update_nav_sub($request,'nav');
                }
                $result = 'success';
            }catch(Exception $e){
                
            }
        }
        return $result;
    }
    private function update_nav_sub($request,$from){
        $user = Auth::user();
        $updated_by = $user->id;
        $encrypt = new EncryptServices;
        $id = $encrypt->decrypt($request->id);
        $system_id = $request->system_id;
        $level_id = $request->level_id;
        $user_selected = Users::where('id',$id)->first();
        $error = 0;
        $result = 'error';
        if($user_selected->level_id!=1){
            if($level_id==1){
                $error++;
            }
        }
        if($error==0){
            try{
                $role_id = $user_selected->employee_default->role_id;
                if($from=='system' || $from=='nav'){
                    if($from=='nav'){
                        $system_nav_ids = SystemsNav::where('id',$system_id)->pluck('id')->toArray();
                    }else{
                        $system_nav_ids = SystemsNav::where('system_id',$system_id)->pluck('id')->toArray();
                    }                    
                    $system_nav_sub_ids = SystemsNavSub::whereIn('system_nav_id',$system_nav_ids)->pluck('id')->toArray();
                    if($level_id=='' || $level_id==1){
                        $delete = UsersSystemsNavSub::whereIn('system_nav_sub_id', $system_nav_sub_ids)
                                    ->where('user_id', $id)
                                    ->where('role_id', $role_id)->delete();
                        $auto_increment = DB::update("ALTER TABLE users_systems_nav_sub AUTO_INCREMENT = 0;");
                        if($level_id==1){
                            $delete = UsersSystemsNavSub::where('user_id', $id)
                                        ->where('role_id', $role_id)->delete();
                            $auto_increment = DB::update("ALTER TABLE users_systems_nav_sub AUTO_INCREMENT = 0;");
                            $query = SystemsNavSub::get()
                                        ->map(function($query) use ($id,$updated_by,$role_id) {
                                        return [
                                            'user_id' => $id,
                                            'system_nav_sub_id' => $query->id,
                                            'role_id' => $role_id,
                                            'level_id' => 1,
                                            'updated_by' => $updated_by,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')
                                        ];
                                    })->toArray();
                            UsersSystemsNavSub::insert($query);
                        }
                    }else{
                        $delete = UsersSystemsNavSub::whereIn('system_nav_sub_id', $system_nav_sub_ids)
                                    ->where('user_id', $id)
                                    ->where('role_id', $role_id)->delete();
                        $auto_increment = DB::update("ALTER TABLE users_systems_nav_sub AUTO_INCREMENT = 0;");
                        $query = SystemsNavSub::whereIn('id', $system_nav_sub_ids)
                                        ->get()
                                        ->map(function($query) use ($id,$updated_by,$role_id,$level_id) {
                                        return [
                                            'user_id' => $id,
                                            'system_nav_sub_id' => $query->id,
                                            'role_id' => $role_id,
                                            'level_id' => $level_id,
                                            'updated_by' => $updated_by,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')
                                        ];
                                    })->toArray();
                         UsersSystemsNavSub::insert($query);
                    }
                }else{
                    if($level_id==''){
                        $delete = UsersSystemsNavSub::where('system_nav_sub_id', $system_id)
                                    ->where('user_id', $id)
                                    ->where('role_id', $role_id)->delete();
                        $auto_increment = DB::update("ALTER TABLE users_systems_nav_sub AUTO_INCREMENT = 0;");
                    }
                    UsersSystemsNavSub::where('system_nav_sub_id', $system_id)
                            ->where('user_id', $id)
                            ->where('role_id', $role_id)
                            ->update(['level_id' => $level_id]);
                }
                $result = 'success';
            }catch(Exception $e){
                
            }
        }
        return $result;
    }
}

?>