<?php

namespace App\Console\Commands\HRIMS;

use App\Models\SystemsNavSub;
use App\Models\Users;
use App\Models\UsersSystems;
use App\Models\UsersSystemsNav;
use App\Models\UsersSystemsNavSub;
use Illuminate\Console\Command;

class NavigationUserAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:navigation-user-add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $nav_sub_ids = SystemsNavSub::where('system_nav_id','29')->pluck('id')->toArray();
        $check = Users::whereDoesntHave('nav_sub', function ($query) use ($nav_sub_ids) {
                foreach($nav_sub_ids as $nav_sub){
                    $query->where('system_nav_sub_id', $nav_sub);
                }
                
            })
            ->whereHas('user_role', function ($query) {
                $query->whereIn('role_id', [2,3]);
            })
            ->get();
        if($check->count()>0){
            foreach($check as $r){
               // dd($r->id);
                $role_id = $r->employee_default->role_id;
                $systemCheck = UsersSystems::where('system_id',6)
                    ->where('user_id',$r->id)
                    ->first();
                if($systemCheck==NULL){
                    $insert = new UsersSystems;
                    $insert->user_id = $r->id;
                    $insert->system_id = 6;
                    $insert->role_id = $role_id;
                    $insert->level_id = 6;
                    $insert->updated_by = 1;
                    $insert->save();
                }

                $systemNavCheck = UsersSystemsNav::where('system_nav_id',29)
                    ->where('user_id',$r->id)
                    ->first();
                if($systemNavCheck==NULL){
                    $insert = new UsersSystemsNav;
                    $insert->user_id = $r->id;
                    $insert->system_nav_id = 29;
                    $insert->role_id = $role_id;
                    $insert->level_id = 6;
                    $insert->updated_by = 1;
                    $insert->save();
                }
                foreach($nav_sub_ids as $nav_sub){
                    $systemNavSubCheck = UsersSystemsNavSub::where('system_nav_sub_id',$nav_sub)
                        ->where('user_id',$r->id)
                        ->first();
                    if($systemNavSubCheck==NULL){
                        $insert = new UsersSystemsNavSub;
                        $insert->user_id = $r->id;
                        $insert->system_nav_sub_id = $nav_sub;
                        $insert->role_id = $role_id;
                        $insert->level_id = 6;
                        $insert->updated_by = 1;
                        $insert->save();
                    }
                }
            }
        }
    }
}
