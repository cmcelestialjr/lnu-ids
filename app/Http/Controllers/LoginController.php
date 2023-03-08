<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class LoginController extends Controller
{
    public function check(Request $request){
        $result = 'error';
        $role = $request->role;
        $username = $request->username;
        $password = $request->password;
        $user = User::where('username',$username)->first();
        if($user==NULL){
            $result = 'none';
        }else{
            //$encrypt = Crypt::encryptString($token(4).hash('123').$token(4));

            $decrypt = Crypt::decryptString($user->password);
            $remove_first = substr($decrypt, 4);
            $hash_password = substr($remove_first, 0, -4);
            $isValid = Hash::check($password, $hash_password);
            if ($isValid) {
                if($user->status_id=='1'){
                    Auth::login($user);
                    $result = 'success';
                }elseif($user->status_id=='3'){
                    $result = 'On-hold';
                }else{
                    $result = 'Inactive';
                }
            } else {
                $result = 'wrong';
            }
        }
        $response = array('result' => $result);
        return response()->json($response);
    }
    public function logout()
    {
        Auth::logout();
        Session::flush();
        Session::flash('toastr', array('warning', 'You are now logout!.'));
        return redirect()->route('indexpage');
    }
}

?>