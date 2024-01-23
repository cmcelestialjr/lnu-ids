<?php
namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\Systems;
use App\Models\SystemsNav;
use App\Models\SystemsNavSub;
use App\Models\UsersSystemsNav;
use App\Models\UsersSystemsNavSub;

class ValidateAccessServices
{
    public function check($data,$level_ids){
        $user = Auth::user();
        $id = $user->id;
        $system_selected = $data['system_selected'];
        $nav_selected = $data['nav_selected'];
        $system = Systems::where('shorten',$system_selected)->first();
        $system_id = $system->id;
        $system_nav = SystemsNav::where('system_id',$system_id)->where('url',$nav_selected)->first();        
        if($system_nav!=NULL){
            $check = UsersSystemsNav::where('system_nav_id',$system_nav->id)
                        ->whereIn('level_id',$level_ids)
                        ->where('user_id',$id)->first();
            
        }else{
            $system_nav_ids = SystemsNav::where('system_id',$system_id)->pluck('id')->toArray();
            $system_nav_sub = SystemsNavSub::whereIn('system_nav_id',$system_nav_ids)->where('url',$nav_selected)->first();
            $check = UsersSystemsNavSub::where('system_nav_sub_id',$system_nav_sub->id)
                        ->whereIn('level_id',$level_ids)
                        ->where('user_id',$id)->first();
        }
        if($check!=NULL){
            $result = 'success';
        }else{
            $result = 'error';
        }
        return $result;
    }
}

?>