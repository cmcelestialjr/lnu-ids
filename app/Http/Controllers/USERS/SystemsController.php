<?php

namespace App\Http\Controllers\USERS;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Users;
use App\Models\Systems;
use App\Models\SystemsNav;
use App\Models\SystemsNavSub;
use Exception;

class SystemsController extends Controller
{
    public function table(Request $request){
        $query = Systems::get();
        $count = $query->count();
        $data = array();
        if($count>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r->name;
                $data_list['f3'] = $r->shorten;
                $data_list['f4'] = '<button class="btn btn-primary btn-primary-scan nav"
                                        data-id="'.$r->id.'">
                                        <span class="fa fa-edit"></span> Nav</button>';
                $data_list['f5'] = '<button class="btn btn-info btn-info-scan edit"
                                        data-id="'.$r->id.'">
                                        <span class="fa fa-edit"></span> Edit</button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    public function new(Request $request){
        $data = array(
            'id' => ''
        );
        return view('users/modal_system_new',$data);
    }
    public function edit(Request $request){
        $id = $request->id;
        $query = Systems::where('id',$id)->first();
        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('users/modal_system_edit',$data);
    }
    public function nav(Request $request){
        $id = $request->id;
        $query = Systems::where('id',$id)->first();
        $systems_nav = SystemsNav::where('system_id',$id)->orderBy('order')->get();
        $data = array(
            'id' => $id,
            'query' => $query,
            'systems_nav' => $systems_nav
        );
        return view('users/modal_system_nav',$data);
    }
    public function navView(Request $request){
        $id = $request->id;
        $systems_nav = SystemsNav::where('system_id',$id)->orderBy('order')->get();
        $data = array(
            'systems_nav' => $systems_nav
        );
        return view('users/modal_system_nav_view',$data);
    }
    public function navEdit(Request $request){
        $id = $request->id;
        $systems_nav = SystemsNav::with('system')->where('id',$id)->first();
        $data = array(
            'id' => $id,
            'systems_nav' => $systems_nav
        );
        return view('users/modal_system_nav_edit',$data);
    }
    public function navNew(Request $request){
        $id = $request->id;
        $query = Systems::where('id',$id)->first();
        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('users/modal_system_nav_new',$data);
    }
    public function navSubView(Request $request){
        $id = $request->id;
        $systems_nav_sub = SystemsNavSub::where('system_nav_id',$id)->orderBy('order')->get();
        $data = array(
            'systems_nav_sub' => $systems_nav_sub
        );
        return view('users/modal_system_nav_sub_view',$data);
    }
    public function navSub(Request $request){
        $id = $request->id;
        $query = SystemsNav::with('system')->where('id',$id)->first();
        $systems_nav_sub = SystemsNavSub::where('system_nav_id',$id)->orderBy('order')->get();
        $data = array(
            'id' => $id,
            'query' => $query,
            'systems_nav_sub' => $systems_nav_sub
        );
        return view('users/modal_system_nav_sub',$data);
    }
    public function navSubNew(Request $request){
        $id = $request->id;
        $query = SystemsNav::with('system')->where('id',$id)->first();
        $data = array(
            'id' => $id,
            'query' => $query
        );
        return view('users/modal_system_nav_sub_new',$data);
    }
    public function navSubEdit(Request $request){
        $id = $request->id;
        $systems_nav_sub = SystemsNavSub::with('system_nav.system')->where('id',$id)->first();
        $data = array(
            'id' => $id,
            'systems_nav_sub' => $systems_nav_sub
        );
        return view('users/modal_system_nav_sub_edit',$data);
    }
    public function navSubNewSubmit(Request $request){
        $user = Auth::user();
        $user_id = $user->id;
        $id = $request->id;
        $name = $request->name;
        $url = $request->url;
        $icon = $request->icon;
        $order = $request->order;
        $result = 'error';
        $systems_nav_id = SystemsNav::where('id',$id)->first();
        $check = SystemsNavSub::where('system_nav_id',$id)
                    ->where(function ($query) use ($name,$url,$order){
                        $query->where('name',$name)
                              ->orwhere('url',$url)
                              ->orWhere('order',$order);
                    })->first();
        $check1 = SystemsNav::where('system_id',$systems_nav_id->id)
                    ->where(function ($query) use ($url){
                        $query->where('url',$url);
                    })->first();
        if($check!=NULL || $check1!=NULL){
            $result = 'exists';
        }else{
            try{
                $insert = new SystemsNavSub;
                $insert->system_nav_id = $id;
                $insert->name = $name;
                $insert->url = mb_strtolower($url);
                $insert->icon = $icon;
                $insert->order = $order;
                $insert->user_id = $user_id;
                $insert->save();
                $result = 'success';
            }catch(Exception $e) {

            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    public function navSubEditSubmit(Request $request){
        $user = Auth::user();
        $user_id = $user->id;
        $id = $request->id;
        $name = $request->name;
        $url = $request->url;
        $icon = $request->icon;
        $order = $request->order;
        $systems_nav_sub = SystemsNavSub::with('system_nav')->where('id',$id)->first();
        $check = SystemsNavSub::where('id','!=',$id)
                    ->where('system_nav_id',$systems_nav_sub->system_nav_id)
                    ->where(function ($query) use ($name,$url,$order){
                        $query->where('name',$name)
                              ->orwhere('url',$url)
                              ->orWhere('order',$order);
                    })->first();
        $check1 = SystemsNav::where('system_id',$systems_nav_sub->system_nav->system_id)
                    ->where(function ($query) use ($url){
                        $query->where('url',$url);
                    })->first();
        $result = 'error';
        if($check!=NULL){
            $result = 'exists';
        }else{
            try{
                SystemsNavSub::where('id', $id)
                    ->update(['name' => $name,
                              'url' => mb_strtolower($url),
                              'icon' => $icon,
                              'order' => $order,
                              'user_id' => $user_id,
                              'updated_at' => date('Y-m-d H:i:s')]);
                $result = 'success';
            }catch(Exception $e) {

            }
        }
        $response = array('result' => $result,
                          'id' => $systems_nav_sub->system_nav_id);
        return response()->json($response);
    }
    public function navNewSubmit(Request $request){
        $user = Auth::user();
        $user_id = $user->id;
        $id = $request->id;
        $name = $request->name;
        $url = $request->url;
        $icon = $request->icon;
        $order = $request->order;
        $systems_nav_ids = SystemsNav::where('system_id',$id)->pluck('id')->toArray();
        $check = SystemsNav::where('system_id',$id)
                    ->where(function ($query) use ($name,$url,$order){
                        $query->where('name',$name)
                              ->orwhere('url',$url)
                              ->orWhere('order',$order);
                    })->first();
        $check1 = SystemsNavSub::whereIn('system_nav_id',$systems_nav_ids)
                    ->where(function ($query) use ($url){
                        $query->where('url',$url);
                    })->first();
        $result = 'error';
        if($check!=NULL || $check1!=NULL){
            $result = 'exists';
        }else{
            try{
                $insert = new SystemsNav;
                $insert->system_id = $id;
                $insert->name = $name;
                $insert->url = mb_strtolower($url);
                $insert->icon = $icon;
                $insert->order = $order;
                $insert->user_id = $user_id;
                $insert->save();
                $result = 'success';
            }catch(Exception $e) {

            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    public function navEditSubmit(Request $request){
        $user = Auth::user();
        $user_id = $user->id;
        $id = $request->id;
        $name = $request->name;
        $url = $request->url;
        $icon = $request->icon;
        $order = $request->order;
        $systems_nav = SystemsNav::with('system')->where('id',$id)->first();
        $systems_nav_ids = SystemsNav::where('system_id',$systems_nav->system->id)->pluck('id')->toArray();
        $check = SystemsNav::where('id','!=',$id)
                    ->where('system_id',$systems_nav->system->id)
                    ->where(function ($query) use ($name,$url,$order){
                        $query->where('name',$name)
                              ->orWhere('url',$url)
                              ->orWhere('order',$order);
                    })->first();
        $check1 = SystemsNavSub::whereIn('system_nav_id',$systems_nav_ids)
                    ->where(function ($query) use ($url){
                        $query->where('url',$url);
                    })->first();
        $result = 'error';
        if($check!=NULL || $check1!=NULL){
            $result = 'exists';
        }else{
            try{
                SystemsNav::where('id', $id)
                    ->update(['name' => $name,
                              'url' => mb_strtolower($url),
                              'icon' => $icon,
                              'order' => $order,
                              'user_id' => $user_id,
                              'updated_at' => date('Y-m-d H:i:s')]);
                $result = 'success';
            }catch(Exception $e) {

            }
        }
        $response = array('result' => $result,
                          'id' => $systems_nav->system->id);
        return response()->json($response);
    }
    public function editSubmit(Request $request){
        $user = Auth::user();
        $user_id = $user->id;
        $id = $request->id;
        $name = $request->name;
        $shorten = $request->shorten;
        $icon = $request->icon;
        $button = $request->button;
        $check = Systems::where('id','!=',$id)
                    ->where(function ($query) use ($name,$shorten){
                        $query->where('name',$name)
                              ->orWhere('shorten',$shorten);
                    })->first();
        $result = 'error';
        if($check!=NULL){
            $result = 'exists';
        }else{
            try{
                Systems::where('id', $id)
                    ->update(['name' => $name,
                              'shorten' => $shorten,
                              'icon' => $icon,
                              'button' => $button,
                              'user_id' => $user_id,
                              'updated_at' => date('Y-m-d H:i:s')]);
                $result = 'success';
            }catch(Exception $e) {

            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    public function newSubmit(Request $request){
        $user = Auth::user();
        $user_id = $user->id;
        $name = $request->name;
        $shorten = $request->shorten;
        $icon = $request->icon;
        $button = $request->button;
        $check = Systems::where(function ($query) use ($name,$shorten){
                        $query->where('name',$name)
                              ->orWhere('shorten',$shorten);
                    })->first();
        $result = 'error';
        if($check!=NULL){
            $result = 'exists';
        }else{
            try{
                $query = Systems::orderBy('order','DESC')->first();
                $insert = new Systems;
                $insert->name = $name;
                $insert->shorten = mb_strtoupper($shorten);
                $insert->icon = $icon;
                $insert->button = $button;
                $insert->order = $query->order+1;
                $insert->user_id = $user_id;
                $insert->save();
                $result = 'success';
            }catch(Exception $e) {

            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
}
