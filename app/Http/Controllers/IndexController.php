<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use App\Models\UsersRole;
use App\Models\UsersRoleList;
use App\Models\UsersSystems;
use App\Models\UsersSystemsNav;
use App\Models\UsersSystemsNavSub;
use App\Models\Systems;
use App\Models\SystemsNav;
use App\Models\SystemsNavSub;
use App\Services\TokenServices;
use App\Services\NameServices;
use Session;

class IndexController extends Controller
{
    public function view(Request $request){        
        $role = UsersRole::get();
        $data = array(
            'role' => $role
            );
        return view('index/index',$data);
    }
    public function systempage()
    {
        if (Auth::check()) {
            return redirect('systems');
        }
        return redirect()->route('indexpage');
    }
    public function systems(){
        $user = Auth::user();
        $roles = UsersRoleList::where('user_id',$user->id)->pluck('role_id')->toArray();
        $user_systems = UsersSystems::where('user_id',$user->id)->pluck('system_id')->toArray();
        
        $systems = Systems::whereIn('id',$user_systems)->orderBy('order','ASC')->get();
        $count_systems = $systems->count();
        $encrypt = Crypt::encryptString('1234'.Hash::make('sample').'1234');
        $data = array(
            'user' => $user,
            'roles' => $roles,
            'systems' => $systems,
            'count_systems' => $count_systems,
            'encrypt' => $encrypt
            );
        return view('index/system',$data);
    }
    public function ids(Request $request){
        $user = Auth::user();
        $system_selected = mb_strtoupper($request->system_selected);
        $nav_selected = $request->nav_selected;
        $type = $request->type;
        $name_services = new NameServices;
        $user_systems = UsersSystems::where('user_id',$user->id)->pluck('system_id')->toArray();
        $user_systems_nav = UsersSystemsNav::where('user_id',$user->id)->pluck('system_nav_id')->toArray();
        $user_systems_nav_sub = UsersSystemsNavSub::where('user_id',$user->id)->pluck('system_nav_sub_id')->toArray();
        
        $systems = Systems::with(['navs' => function ($q) use($user_systems_nav) {
                            $q->whereIn('id', $user_systems_nav);
                        }],['navs.navSubs' => function ($q) use($user_systems_nav_sub) {
                            $q->whereIn('systems_nav_sub.id', $user_systems_nav_sub);
                        }])
                        ->whereIn('id',$user_systems)->orderBy('order','ASC')->get();
        $systems_nav_array = SystemsNav::whereIn('id',$user_systems_nav)->pluck('id')->toArray();
        
        $name = $name_services->firstname($user->lastname,$user->firstname,$user->middlename,$user->extname);
        
        if($user->picture==''){
            $profile_url = 'assets/images/icons/png/user.png';
        }else{
            $profile_url = $user->picture;
        }
        if($system_selected!='USERS'){
            $systems_selected = Systems::where('shorten',$system_selected)->first();
            if($type=='n'){
                $nav_selecteds = SystemsNav::where('system_id',$systems_selected->id)->where('url',$nav_selected)->first();
                $user_access = UsersSystemsNav::where('user_id',$user->id)
                                        ->where('system_nav_id',$nav_selecteds->id)->first();
                $request->session()->put('user_access_level',$user_access->level_id);
            }else{
                $nav_ids = SystemsNav::where('system_id',$systems_selected->id)->pluck('id')->toArray();
                $nav_selecteds = SystemsNavSub::whereIn('system_nav_id',$nav_ids)->where('url',$nav_selected)->first();
                $user_access = UsersSystemsNavSub::where('user_id',$user->id)
                                    ->where('system_nav_sub_id',$nav_selecteds->id)->first();
                $request->session()->put('user_access_level',$user_access->level_id);
            }
        }else{
            $user_access = '';
            $request->session()->put('user_access_level','');
        }
        $data = array(
            'system_selected' => $system_selected,
            'nav_selected' => $nav_selected,
            'user' => $user,
            'name' => $name,
            'profile_url' => $profile_url,
            'systems' => $systems,
            'systems_nav_array' => $systems_nav_array,
            'user_access' => $user_access
            );
        $request->session()->put('system_selected', $system_selected);
        if($system_selected=='USERS' && $nav_selected=='list'){
            return app('App\Http\Controllers\USERS\UserController')->user($data);
        }else{
            return app(str_replace('"',"",'App\Http\Controllers\"'.$system_selected.'\PageController'))->$nav_selected($data);
        }
        
    }
}

?>