<?php

namespace App\Http\Controllers\USERS;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Users;
use App\Models\Status;
use App\Services\NameServices;
use App\Services\EncryptServices;
use Exception;

class StatusController extends Controller
{
    public function status(Request $request){
        $user = Auth::user();
        $user_level = $user->level_id;
        if($user_level==1){
            $encrypt = new EncryptServices;
            $decrypt_id = $encrypt->decrypt($request->id);
            $id = $request->id;
            $user = Users::where('id',$decrypt_id)->first();
            $statuses = Status::get();
            $data = array(
                'id' => $id,
                'statuses' => $statuses,
                'user' => $user
            );
            return view('users/modal_status',$data);
        }else{
            return '';
        }
    }
    public function update(Request $request){
        $user = Auth::user();
        $user_id = $user->id;
        $encrypt = new EncryptServices;
        $id = $encrypt->decrypt($request->id);
        $result = 'error';
        try{
            Users::where('id', $id)
                ->update(['status_id' => $request->status,
                          'user_id' => $user_id,
                          'updated_at' => date('Y-m-d H:i:s')]);
            $result = 'success';
        }catch(Exception $e) {
            
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
}